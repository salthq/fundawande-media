<?php

namespace App\Helpers;

use App\Resource;

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

    // Get the percentage of total storage space used
    public static function getTotalStorage()
    {
        $total_storage = 2000000000000;
        $resource_sizes = Resource::all()->pluck('size')->toArray();

        $storage_used = array_reduce($resource_sizes, function ($sum, $item) {
            $sum += $item;
            return $sum;
        });
        $storage_percentage = round($storage_used * 100 / $total_storage);

        return $storage_percentage;
    }
}
