<?php

namespace App\Helper;

use App\Handler\Data\DataHandler;

interface ModelHelper
{
    public static function checkTableExists(): bool;
    public static function getMissingTableMessage(): string;
    public static function getDataHandler(string $filePath, string $format): DataHandler;
}
