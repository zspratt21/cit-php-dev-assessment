<?php

/**
 * Default Controller for the Users command namespace.
 *
 * Implements the logic to import users from a CSV file.
 */

declare(strict_types=1);

namespace App\Command\Users;

use App\Helper\Model\UserHelper;
use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle(): void
    {
        $file = $this->getParam('file');
        $this->info("User Import started. Parsing file: {$file}");
        $handler = UserHelper::getDataHandler($file, $this->getParam('format'));
        if (UserHelper::checkTableExists()) {
            $users = $handler->importUsers();
            // loop through the users and insert them into the database if their fields contain valid data.
            foreach ($users as $user) {
                $outcome = $user->insert();
                $outcome['success'] ? $this->success($outcome['message']) : $this->error($outcome['message']);
            }
            $this->info('User Import completed.');
        } else {
            $this->error(UserHelper::getMissingTableMessage());
        }
    }
}
