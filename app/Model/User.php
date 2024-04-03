<?php

declare(strict_types=1);

namespace App\Model;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use RedBeanPHP\R as R;

class User
{
    private string $name;
    private string $surname;

    private string|null $email;

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

        // ensure email is in a valid format
        $validator = new EmailValidator();
        if ($validator->isValid($email, new RFCValidation())) {
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
        $name = $this->name ? addslashes($this->name) : 'EMPTY_NAME';
        $surname = $this->surname ? addslashes($this->surname) : 'EMPTY_SURNAME';
        $email = $this->email ? addslashes($this->email) : 'EMPTY_EMAIL_OR_INVALID_FORMAT';
        if ($this->isValid()) {
            return "INSERT INTO users (id, name, surname, email) VALUES (NULL, '{$name}', '{$surname}', '{$email}')";
        }
        return "Invalid data for user {$name} {$surname} with email {$email}. Reason: {$this->getInvalidFields()}";

    }

    public function insert(): string
    {
        if ($this->isValid()) {
            $user_insert = R::dispense('users');
            $user_insert->name = $this->name;
            $user_insert->surname = $this->surname;
            $user_insert->email = $this->email;
            // check if user with specified email already exists
            $existing_user = R::findOne('users', 'email = ?', [$this->email]);
            if ($existing_user) {
                return 'User with email '.$this->email.' already exists.';
            }
            else {
                try {
                    R::store($user_insert);
                    return "User {$this->name} {$this->surname} with email {$this->email} imported successfully.";
                } catch (\Exception $e) {
                    return "Error importing user: {$e->getMessage()}";
                }
            }
        }
        $name = $this->name ?: 'EMPTY_NAME';
        $surname = $this->surname ?: 'EMPTY_SURNAME';
        $email = $this->email ?: 'EMPTY_EMAIL_OR_INVALID_FORMAT';
        return "Invalid data for user {$name} {$surname} with email {$email}. Reason: {$this->getInvalidFields()}";
    }
}
