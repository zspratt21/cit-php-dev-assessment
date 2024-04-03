<?php

declare(strict_types=1);

namespace App\Command\Users;

use App\Model\User;
use Minicli\Command\CommandController;
use Minicli\Output\Helper\TableHelper;
use RedBeanPHP\R as R;

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
        // check and make sure the users table exists
        try {
            R::inspect('users');
        }
        catch (\Exception $e) {
            $this->info('WARNING: The users table does not exist. You will need to run the script with the --create_table flag before you can import users into the database!');
        }
        $this->success('Dry run complete. No data was inserted.');
    }
}
