<?php

declare(strict_types=1);

namespace App\Model;

use RedBeanPHP\R as R;
use Exception;

class User
{
    protected string $name;
    protected string $surname;
    protected string|null $email;

    public const EMPTY_NAME = 'EMPTY_NAME';
    public const EMPTY_SURNAME = 'EMPTY_SURNAME';
    public const EMPTY_EMAIL = 'EMPTY_EMAIL_OR_INVALID_FORMAT';
    public const INVALID_DATA_MSG = "Invalid data for user %s %s with email %s. Reason: %s";

    public function __construct(string $name, string $surname, string $email)
    {
        $this->setName($name);
        $this->setSurname($surname);
        $this->setEmail($email);
    }

    public function setName(string $name): void
    {
        // trim the name and capitalize first letter, all other letters must be lowercase
        $this->name = ucfirst(mb_strtolower(trim($name)));
    }

    public function setSurname(string $surname): void
    {
        // follow the same format as name
        $this->surname = ucfirst(mb_strtolower(trim($surname)));
    }

    public function setEmail(string $email): void
    {
        // trim the email before validating it
        $email = trim($email);

        // @correction validate email with builtin function
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = mb_strtolower($email);
        } else {
            $this->email = null;
        }
    }

    public function isValid(): bool
    {
        return ! empty($this->name) && ! empty($this->surname) && ! empty($this->email);
    }

    public function getInvalidFields(): string
    {
        $invalid_fields = [];
        if (empty($this->name)) {
            $invalid_fields[] = 'Name';
        }
        if (empty($this->surname)) {
            $invalid_fields[] = 'Surname';
        }
        if (empty($this->email)) {
            $invalid_fields[] = 'Email';
        }
        return implode(', ', $invalid_fields);
    }

    public function dryPrint(): string
    {
        $name = $this->name ? addslashes($this->name) : self::EMPTY_NAME;
        $surname = $this->surname ? addslashes($this->surname) : self::EMPTY_SURNAME;
        $email = $this->email ? addslashes($this->email) : self::EMPTY_EMAIL;
        if ($this->isValid()) {
            return "INSERT INTO users (id, name, surname, email) VALUES (NULL, '{$name}', '{$surname}', '{$email}')";
        }
        return sprintf(self::INVALID_DATA_MSG, $name, $surname, $email, $this->getInvalidFields());
    }

    /**
     * @return array{message: string, success: bool}
     */
    public function insert(): array
    {
        $name = $this->name ?: self::EMPTY_NAME;
        $surname = $this->surname ?: self::EMPTY_SURNAME;
        $email = $this->email ?: self::EMPTY_EMAIL;
        $message = sprintf(self::INVALID_DATA_MSG, $name, $surname, $email, $this->getInvalidFields());
        $success = false;
        if ($this->isValid()) {
            $user_insert = R::dispense('users');
            $user_insert->name = $name;
            $user_insert->surname = $surname;
            $user_insert->email = $email;
            // check if user with specified email already exists
            $existing_user = R::findOne('users', 'email = ?', [$email]);
            if ($existing_user) {
                $message = "User {$name} {$surname} with email {$email} already exists.";
            } else {
                try {
                    R::store($user_insert);
                    $message = "User {$name} {$surname} with email {$email} successfully inserted.";
                    $success = true;
                } catch (Exception $e) {
                    return [
                        'message' => "Error importing user: {$e->getMessage()}",
                        'success' => false,
                    ];
                }
            }
        }
        return [
            'message' => $message,
            'success' => $success,
        ];
    }
}
