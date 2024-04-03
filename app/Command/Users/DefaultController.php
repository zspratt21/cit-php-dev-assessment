<?php

declare(strict_types=1);

namespace App\Command\Users;

use App\Model\User;
use Minicli\Command\CommandController;
use RedBeanPHP\R as R;
use Exception;

class DefaultController extends CommandController
{
    public function handle(): void
    {
        // check and make sure the users table exists
        try {
            R::inspect('users');
        } catch (Exception $e) {
            $this->error('The users table does not exist. Please run the script with the --create_table flag first.');
            exit(1);
        }

        $file = $this->getParam('file');
        $file_handle = fopen($file, 'r');
        $this->info("User Import started. Parsing file: {$file}");
        $first_line = true;
        // loop through the csv and import the line as a new user if it contains valid data.
        while (($line = fgetcsv($file_handle)) !== false) {
            // ignore first line containing headers
            if ($first_line) {
                $first_line = false;
                continue;
            }
            $user = new User($line[0], $line[1], $line[2]);
            $outcome = $user->insert();
            if (str_ends_with($outcome, 'imported successfully.')) {
                $this->success($outcome);
            } else {
                $this->error($outcome);
            }
        }
    }
}
