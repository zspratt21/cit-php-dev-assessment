<?php

declare(strict_types=1);

namespace App\Model;

use RedBeanPHP\SimpleModel;

class User extends SimpleModel
{
    public string $name;
    public string $surname;
    public string $email;


}
