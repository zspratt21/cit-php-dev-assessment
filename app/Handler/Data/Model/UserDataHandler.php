<?php

namespace App\Handler\Data\Model;

use App\Handler\Data\DataHandler;
use App\Model\User;

interface UserDataHandler extends DataHandler
{
    /**
     * @return User[]
     */
    public function importUsers(): array;
    public function exportUsers(): bool;
}
