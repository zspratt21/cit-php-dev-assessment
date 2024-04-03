<?php

declare(strict_types=1);

namespace App\Command\Users;

use App\Model\User;
use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
     public function handle(): void
    {
        $file = $this->getParam('file');
        if (false === file_exists($file)) {
            $this->error("File not found: {$file}");
            exit(1);
        } else {
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
}
