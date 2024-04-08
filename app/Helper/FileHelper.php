<?php

declare(strict_types=1);

namespace App\Helper;

class FileHelper
{
    public static function checkValidFile(string $filePath): string|null
    {
        if(file_exists($filePath)) {
            return match (true) {
                'csv' === pathinfo($filePath, PATHINFO_EXTENSION) => 'csv',
                'json' === pathinfo($filePath, PATHINFO_EXTENSION) => 'json',
                default => null,
            };
        }
        return null;
    }
}
