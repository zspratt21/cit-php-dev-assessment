<?php

declare(strict_types=1);

namespace App\Handler\File\Model\User;

use App\Handler\Data\Model\UserDataHandler;
use App\Handler\File\FileHandler;
use App\Model\User;
use Exception;
use RedBeanPHP\R as R;

class UserJsonFileHandler extends FileHandler implements UserDataHandler
{
    /**
     * @throws Exception
     */
    public function importUsers(): array
    {
        $users = [];
        $json = json_decode($this->read(), true);
        if ( ! is_array($json)) {
            throw new Exception('Decoded JSON is not an array');
        }
        if ($json) {
            foreach ($json as $user) {
                $name = $user['name'] ?? '';
                $surname = $user['surname'] ?? '';
                $email = $user['email'] ?? '';
                $users[] = new User($name, $surname, $email);
            }
        }
        return $users;
    }

    public function exportUsers(): bool
    {
        $users = R::findAll('users');
        if (count($users) > 0) {
            $data = [];
            foreach ($users as $user) {
                $data[] = [
                    'name' => $user->name,
                    'surname' => $user->surname,
                    'email' => $user->email,
                ];
            }
            $this->write(json_encode($data, JSON_PRETTY_PRINT));
            return true;
        }
        return false;
    }
}
