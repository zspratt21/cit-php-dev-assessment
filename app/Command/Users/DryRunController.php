<?php

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
        if (false === file_exists($file)) {
            $this->error("File not found: {$file}");
            exit(1);
        } else {
            $file_handle = fopen($file, 'r');
            $this->info("Dry run mode enabled. Parsing file: {$file}");
            $first_line = true;
            $table = new TableHelper();
            $table->addHeader(['CSV Line', 'SQL Query']);
            while (($line = fgetcsv($file_handle)) !== false) {
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
}
