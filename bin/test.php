<?php
$scriptPath = __DIR__ . DIRECTORY_SEPARATOR . 'bme280.py';
$output = shell_exec('python ' . $scriptPath);
$json = json_decode($output, true);

echo "\n";
echo $scriptPath;
echo "\n";
var_dump($json);
//echo $output;
echo "\n";
echo "END";
echo "\n";
