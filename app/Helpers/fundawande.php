<?php

namespace App\Helpers;

class Fundawande
{
    public static function bytesToHuman($bytes)
    {
        $units = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 0) . ' ' . $units[$i];
    }
}
