<?php

declare(strict_types=1);

namespace App\Command\Users;

use Minicli\Command\CommandController;

class DryRunController extends CommandController
{
     public function handle(): void
    {
        // @todo implement
        $this->info('This controller will handle the dry run operation.');
    }
}
