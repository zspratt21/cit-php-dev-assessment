<?php

declare(strict_types=1);

namespace App\Handler\File\Model\User;

use App\Handler\Data\Model\UserDataHandler;
use App\Handler\File\FileHandler;
use App\Model\User;
use RedBeanPHP\R as R;

class UserCsvFileHandler extends FileHandler implements UserDataHandler
{
    public function importUsers(): array
    {
        $users = [];
        $first_line = true;
        $content = $this->read();
        $lines = explode(PHP_EOL, trim($content));
        foreach ($lines as $line) {
            $data = str_getcsv($line);
            if ($first_line) {
                $first_line = false;
                continue;
            }
            $name = $data[0] ?? '';
            $surname = $data[1] ?? '';
            $email = $data[2] ?? '';
            $users[] = new User($name, $surname, $email);
        }
        return $users;
    }

    public function exportUsers(): bool
    {
        $users = R::findAll('users');
        if (count($users) > 0) {
            $data = 'name,surname,email'.PHP_EOL;
            foreach ($users as $user) {
                $data .= "{$user->name},{$user->surname},{$user->email}".PHP_EOL;
            }
            $this->write($data);
            return true;
        }
        return false;
    }
}
