<?php

/**
 * Dry Run Controller.
 *
 * Parses a provided file and prints SQL queries for each valid data entry within the provided file.
 */

declare(strict_types=1);

namespace App\Command\Users;

use App\Helper\Model\UserHelper;
use Minicli\Command\CommandController;
use Minicli\Output\Helper\TableHelper;

class DryRunController extends CommandController
{
    public function handle(): void
    {
        $file = $this->getParam('file');
        $this->info("Dry run mode enabled. Parsing file: {$file}");
        $table = new TableHelper();
        $table->addHeader(['#', 'SQL Query']);
        $count = 0;
        $handler = UserHelper::getDataHandler($file, $this->getParam('format'));
        $users = $handler->importUsers();
        // loop through the users and print the SQL query that would be executed if the line contains valid data.
        foreach ($users as $user) {
            $count++;
            $table->addRow([(string)$count, $user->dryPrint()]);
        }
        $this->rawOutput($table->getFormattedTable());
        $this->newline();
        $this->success('Dry run complete. No data was inserted.');
    }
}
