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
                // @todo constants for success and error messages
                if (str_ends_with($outcome, 'imported successfully.')) {
                    $this->success($outcome);
                } else {
                    $this->error($outcome);
                }
            }
            $this->info('User Import completed.');
        } else {
            $this->error('The users table does not exist. Please run the script with the --create_table flag first.');
        }
    }
}
