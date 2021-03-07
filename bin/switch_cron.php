<?php
require realpath(__DIR__ . '/../application') . '/include.php';

$growTimeHours = 12;

$datetime = new DateTime();
$dtHour = $datetime->format('H');
$dtMinute = $datetime->format('i');

writeEventData($datetime, 'cron', 'switch_cron', []);

$startGrowday = clone $datetime;
$startGrowday->setTime(8,0,0);

$endGrowday = clone $startGrowday;
$endGrowday->add(DateInterval::createFromDateString('+'.$growTimeHours.' hour'));

echo "NOW: " . $datetime->format('d.m.Y - H:i:s');
echo "\n";
echo "DAY: " . $startGrowday->format('d.m.Y - H:i:s');
echo "\n";
echo "NIGHT: " . $endGrowday->format('d.m.Y - H:i:s');
echo "\n";
echo "\n";

// 4=first; 17=second;
$pinNumberHumidity = PIN_HUMIDITY;
$pinNumberAirflow = PIN_AIRFLOW;

$stateOn = false;
$stateOff = true;
$stateHumidity = Commands::executeSwitchReadState($pinNumberHumidity);
$stateAirflow = Commands::executeSwitchReadState($pinNumberAirflow);

echo "Humidity: " . ($stateHumidity===$stateOn ? 'On' : 'Off');
echo "\n";
echo "Airflow: " . ($stateAirflow===$stateOn ? 'On' : 'Off');
echo "\n";
echo "\n";

if($datetime>=$startGrowday && $datetime<$endGrowday) {
    echo "DAY";
    echo "\n";
    echo "===";
    echo "\n";
    //Growday DAY
    echo "Humidity ";
    if($stateHumidity!==$stateOn) {
        Commands::executeSwitchChangeState($pinNumberHumidity, $stateOn);
        echo "switched ON";
        $eventData = [
            'pinNumber' => $pinNumberHumidity,
            'state' => 'on',
            'cron' => true
        ];
        $datetime = new DateTime();
        writeEventData($datetime, 'switch', 'change', $eventData);
    }
    else {
        echo "NO changes";
    }
    echo "\n";
    echo "Airflow ";
    if($stateAirflow!==$stateOn) {
        Commands::executeSwitchChangeState($pinNumberAirflow, $stateOn);
        echo "switched ON";
        $eventData = [
            'pinNumber' => $pinNumberAirflow,
            'state' => 'on',
            'cron' => true
        ];
        $datetime = new DateTime();
        writeEventData($datetime, 'switch', 'change', $eventData);
    }
    else {
        echo "NO changes";
    }
    echo "\n";
} elseif ($datetime>=$endGrowday) {
    //Growday NIGHT
    echo "NIGHT";
    echo "\n";
    echo "=====";
    echo "\n";
    echo "Humidity ";
    if($stateHumidity!==$stateOff) {
        Commands::executeSwitchChangeState($pinNumberHumidity, $stateOff);
        echo "switched OFF";
        $eventData = [
            'pinNumber' => $pinNumberHumidity,
            'state' => 'off',
            'cron' => true
        ];
        $datetime = new DateTime();
        writeEventData($datetime, 'switch', 'change', $eventData);
    }
    else {
        echo "NO changes";
    }

    echo "\n";
    echo "Airflow ";
    if($stateAirflow!==$stateOff) {
        Commands::executeSwitchChangeState($pinNumberAirflow, $stateOff);
        echo "switched OFF";
        $eventData = [
            'pinNumber' => $pinNumberAirflow,
            'state' => 'off',
            'cron' => true
        ];
        $datetime = new DateTime();
        writeEventData($datetime, 'switch', 'change', $eventData);
    }
    else {
        echo "NO changes";
    }
    echo "\n";
}
echo "\n";
echo "Done";
echo "\n";