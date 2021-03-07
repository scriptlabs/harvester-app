<?php

namespace App;

final class Helpers {

    public static function formatFilesize($bytes, $precision = 2) {
        if(empty($bytes)) {
            $bytes = 0;
        }
        if(is_string($bytes)) {
            $filepath = $bytes;
            $bytes = 0;
            if(file_exists($filepath) && is_readable($filepath)) {
                $bytes = filesize($filepath);
            }
        }
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision) . ' ' . $units[$i];
    }

    public static function cleanConsoleOutput($cleanOutput) {
        if(!empty($cleanOutput)) {
            $cleanOutput = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $cleanOutput);
            $cleanOutput = str_replace('[1m', '', $cleanOutput);
            $cleanOutput = str_replace('[0m', '', $cleanOutput);
        }
        return is_array($cleanOutput) ? array_filter($cleanOutput) : $cleanOutput;
    }
}