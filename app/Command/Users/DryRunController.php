<?php

/**
 * Dry Run Controller.
 *
 * Parses a CSV file and prints SQL queries for each valid data line.
 */

declare(strict_types=1);

namespace App\Command\Users;

use App\Model\User;
use Minicli\Command\CommandController;
use Minicli\Output\Helper\TableHelper;

class DryRunController extends CommandController
{
    public function handle(): void
    {
        $file = $this->getParam('file');
        $file_handle = fopen($file, 'r');
        $this->info("Dry run mode enabled. Parsing file: {$file}");
        $first_line = true;
        $table = new TableHelper();
        $table->addHeader(['CSV Line', 'SQL Query']);
        // loop through the csv and print the SQL query that would be executed if the line contains valid data.
        while (($line = fgetcsv($file_handle)) !== false) {
            // ignore first line containing headers
            if ($first_line) {
                $first_line = false;
                continue;
            }
            $user = new User($line[0], $line[1], $line[2]);
            $csv_line = rtrim(implode(',', $line));
            $sql_query = $user->dryPrint();
            $table->addRow([$csv_line, $sql_query]);
        }
        $this->rawOutput($table->getFormattedTable());
        $this->newline();
        $this->success('Dry run complete. No data was inserted.');
    }
}
