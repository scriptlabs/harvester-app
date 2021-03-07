<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require realpath(__DIR__ . '/../application') . '/include.php';

require realpath(__DIR__ . '/../application') . '/CronJobs.php';
require realpath(__DIR__ . '/../application') . '/Cron.php';

$datetime = new DateTime();

$growEnabled = false;
$eventData = ['growing' => $growEnabled];

if($growEnabled) {
    //$result = Cron::run();
    //var_dump($result);
    //echo "\n";
    $captureHistoryFile = DATA_PATH . DIRECTORY_SEPARATOR . 'webcam.txt';
    $alertHistoryFile = DATA_PATH . DIRECTORY_SEPARATOR . 'alert.txt';

    $smsEnabled = false;

    $maxAlerts = 5;

    $growTimeHours = 12;

    $startGrowday = clone $datetime;
    $startGrowday->setTime(8,0,0);

    $endGrowday = clone $startGrowday;
    $endGrowday->add(DateInterval::createFromDateString('+'.$growTimeHours.' hour'));

    $sensorData = readSensorData();
    $temperature = isset($sensorData['temperature']) ? round($sensorData['temperature'],2) : 0.0;
    $humidity = isset($sensorData['humidity']) ? round($sensorData['humidity'],2) : 0.0;
    $pressure = isset($sensorData['pressure']) ? round($sensorData['pressure'],2) : 0.0;

    $timestamp = isset($sensorData['timestamp']) ? $sensorData['timestamp'] : $datetime->getTimestamp();
    $sensorDt = new DateTime();
    // write sensor data to db
    writeSensorData(
        $sensorDt->setTimestamp($timestamp),
        round($temperature*100),
        round($humidity*100),
        round($pressure*100)
    );

    $stateFan = !Commands::executeSwitchReadState(PIN_FAN);
    $stateAirflow = !Commands::executeSwitchReadState(PIN_AIRFLOW);
    $stateHumidity = !Commands::executeSwitchReadState(PIN_HUMIDITY);

    $webcamCapture = false;

    if($datetime>=$startGrowday && $datetime<$endGrowday) {
        //LIGHTS ON (GROW DAY)
        echo "Daytime: DAY" . "\n";
        $eventData['state'] = 'DAY';

        $stateFanRequired = true; //on
        $stateAirflowRequired = true; //on
        $stateHumidityRequired = true; //on

        $temperatureMax = 28.0;
        $temperatureMin = 20.0;

        $humidityMax = 43.0;
        $humidityMin = 38.0;

        // capture webcam image
        $nextCaptureDt = new DateTime();
        $lastCaptureTimestamp = null;
        if(@file_exists($captureHistoryFile)) {
            $lastCaptureTimestamp = @file_get_contents($captureHistoryFile);
            $nextCaptureDt->setTimestamp($lastCaptureTimestamp);
            $nextCaptureDt->add(DateInterval::createFromDateString('+30 minute'));
        }
        if($lastCaptureTimestamp===null || $datetime>=$nextCaptureDt) {
            $sensorDataEncoded =
                round($temperature*100) .
                '+' .
                round($humidity*100);
            $result = captureImage('-' . $sensorDataEncoded);

            if(!empty($result) && strpos($result, 'Timed out')===false && strpos($result, 'error')===false) {
                // write webcam history file
                @file_put_contents($captureHistoryFile, $datetime->getTimestamp());
                $webcamCapture = true;
            }
        }
    }
    elseif ($datetime>$endGrowday || $datetime<$startGrowday) {
        //LIGHTS OFF (GROW NIGHT)
        echo "Daytime: NIGHT" . "\n";
        $eventData['state'] = 'NIGHT';

        $stateFanRequired = false; //off
        $stateAirflowRequired = false; //off
        $stateHumidityRequired = false; //off

        $temperatureMax = 23.0;
        $temperatureMin = 18.0;

        $humidityMax = 48.0;
        $humidityMin = 36.0;
    } else {
        die("ENDE");
    }
    echo "\n";
    echo "Temperature: " . $temperature . 'C' . "\n";
    echo "Humidity: " . $humidity . "%" . "\n";
    echo "Pressure: " . $pressure . "P" . "\n";
    echo "\n";
    echo "Webcam: " . ($webcamCapture ? 'IMAGE SAVED' : 'NO IMAGE') . "\n";
    echo "\n";
    echo "Fan: " . ($stateFan ? 'On' : 'Off') . "\n";
    echo "Airflow: " . ($stateAirflow ? 'On' : 'Off') . "\n";
    echo "Humidity: " . ($stateHumidity ? 'On' : 'Off') . "\n";
    echo "\n";

    // alert check
    $messages = [];
    if($humidity<$humidityMin) {
        $messages[] = 'HUMIDITY LOW: ('.$humidity.'%)';

        $stateHumidityRequired = true; //on
    }
    if($humidity>$humidityMax) {
        $messages[] = 'HUMIDITY HIGH: ('.$humidity.'%)';

        $stateHumidityRequired = false; //off
        $stateAirflowRequired = true; //on
        $stateFanRequired = true; //on
    }
    if($temperature<$temperatureMin) {
        $messages[] = 'TEMP LOW: ('.$temperature.'C)';

        $stateFanRequired = false; //off
        $stateAirflowRequired = false; //off
        $stateHumidityRequired = false; //off
    }
    if($temperature>$temperatureMax) {
        $messages[] = 'TEMP HIGH: ('.$temperature.'C)';

        $stateFanRequired = true; //on
        $stateAirflowRequired = true; //on
    }
    // check switch states
    if($stateAirflow!==$stateAirflowRequired) {
        echo "Change Airflow" . "\n";
        $stateAirflow = !Commands::executeSwitchChangeState(PIN_AIRFLOW, !$stateAirflowRequired);
        echo "Airflow: " . ($stateAirflow ? 'On' : 'Off') . "\n";
        $eventData['airflow'] = $stateAirflow;
    }
    if($stateHumidity!==$stateHumidityRequired) {
        echo "Change Humidity" . "\n";
        $stateHumidity = !Commands::executeSwitchChangeState(PIN_HUMIDITY, !$stateHumidityRequired);
        echo "Humidity: " . ($stateHumidity ? 'On' : 'Off') . "\n";
        $eventData['humidity'] = $stateHumidity;
    }
    if($stateFan!==$stateFanRequired) {
        echo "Change Fan" . "\n";
        $stateFan = !Commands::executeSwitchChangeState(PIN_FAN, !$stateFanRequired);
        echo "Fan: " . ($stateFan ? 'On' : 'Off') . "\n";
    }
    // send alert SMS
    $alertCount = 0;
    if(@file_exists($alertHistoryFile)) {
        $alertCount = @file_get_contents($alertHistoryFile);
    }
    if(count($messages)) {
        $alertCount++;
        $message = implode(', ', $messages);
        echo "Alert: " . $message . "\n";

        if($alertCount>$maxAlerts) {
            $alertCount = 0;
            if($smsEnabled) {
                $result = SmsAlert::sendSms(SMS_ALERT_NUMBER, $message);
                $eventData['sms'] = $result;
                echo "SMS: " . $result . "\n";
            }
        }
        @file_put_contents($alertHistoryFile, $alertCount);
    } else {
        // reset if normal
        @file_put_contents($alertHistoryFile, 1);
    }
    $eventData['alert'] = [];
    $eventData['alert']['count'] = $alertCount;
    $eventData['alert']['messages'] = $messages;

    $eventData['fan'] = $stateFan;
    $eventData['humidity'] = $stateHumidity;
    $eventData['airflow'] = $stateAirflow;

    $eventData['webcam'] = $webcamCapture;
}

writeEventData($datetime, 'cron', 'cron', $eventData);
