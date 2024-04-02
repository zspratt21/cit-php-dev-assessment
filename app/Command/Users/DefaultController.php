<?php

declare(strict_types=1);

namespace App\Command\Users;

use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
     public function handle(): void
    {
        // @todo implement
        $this->info('This controller will handle the importing of users from the csv file.');
    }
}
