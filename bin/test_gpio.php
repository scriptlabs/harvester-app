<?php

$output = shell_exec('bash ./gpio_readall.sh');
if(!empty($output)) {
    file_put_contents('./gpio_readall.txt', $output);
}
