<?php

declare(strict_types=1);

namespace App\Model;

class User
{
    private string $name;
    private string $surname;

    private string $email;

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
        // ensure email is in a valid format
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->email = mb_strtolower($email);
        } else {
            $this->email = '';
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
        if ($this->isValid()) {
            $name = addslashes($this->name);
            $surname = addslashes($this->surname);
            $email = addslashes($this->email);
            return "INSERT INTO users (id, name, surname, email) VALUES (NULL, '{$name}', '{$surname}', '{$email}')";
        }
        return 'Invalid user data. Cannot print SQL. Reason: '.$this->getInvalidFields();

    }

    public function store(): void
    {
        // @todo implement
    }
}
