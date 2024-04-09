<?php

declare(strict_types=1);

namespace App\Helper\Model;

use App\Handler\Data\Model\UserDataHandler;
use App\Handler\File\User\UserCsvFileHandler;
use App\Handler\File\User\UserJsonFileHandler;
use App\Helper\ModelHelper;
use Exception;
use RedBeanPHP\R as R;

class UserHelper implements ModelHelper
{
    public static function checkTableExists(): bool
    {
        // check and make sure the users table exists
        try {
            R::inspect('users');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function getDataHandler(string $filePath, string $format): UserDataHandler
    {
        return match ($format) {
            'csv' => new UserCsvFileHandler($filePath),
            'json' => new UserJsonFileHandler($filePath),
        };
    }
}
