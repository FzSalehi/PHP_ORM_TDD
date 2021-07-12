<?php

namespace App\Helpers;

use App\Exceptions\ConfigFileNotFoundExeption;

class Config
{
    public static function getFileContents(string $filename)
    {
        $filepath = realpath(__DIR__."/../Configs/{$filename}.php");

        if(!$filepath) {
            throw new ConfigFileNotFoundExeption();
        }

        return require $filepath;
    }

    public static function get(string $filename,$key = null)
    {
        $filecontents = self::getFileContents($filename);

        if(is_null($key)) return $filecontents;

        return $filecontents[$key] ?? null;

    }
}

