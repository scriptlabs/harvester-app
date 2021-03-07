<?php
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require realpath(__DIR__ . '/../application') . '/include.php';

$datetime = new DateTime();

writeEventData($datetime, 'cron', 'sensor_cron', []);

$sensorData = readSensorData();
$timestamp = isset($sensorData['timestamp']) ? $sensorData['timestamp'] : $datetime->getTimestamp();
$temperature = isset($sensorData['temperature']) ? $sensorData['temperature'] : 0;
$humidity = isset($sensorData['humidity']) ? $sensorData['humidity'] : 0;
$pressure = isset($sensorData['pressure']) ? $sensorData['pressure'] : 0;

writeSensorData(
    $datetime->setTimestamp($timestamp),
    round($temperature*100),
    round($humidity*100),
    round($pressure*100)
);