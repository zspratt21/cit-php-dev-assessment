<?php

declare(strict_types=1);

namespace App\Command\Users;

use Minicli\Command\CommandController;
use Minicli\Output\Helper\TableHelper;

class DryRunController extends CommandController
{
     public function handle(): void
    {
        $file = $this->getParam('file');
        if (file_exists($file) === false) {
            $this->error("File not found: $file");
            exit(1);
        }
        else {
            $file_handle = fopen($file, 'r');
            $this->info("Dry run mode enabled. Parsing file: $file");
            $first_line = true;
            $table = new TableHelper();
            $table->addHeader(['CSV Line', 'Valid SQL']);
            while (($line = fgetcsv($file_handle)) !== false) {
                if ($first_line) {
                    $first_line = false;
                    continue;
                }
                // @todo implement User Model and helper to print SQL if valid
                $table->addRow([rtrim(implode(',', $line)), 'SQL']);
            }
            $this->rawOutput($table->getFormattedTable());
            $this->newline();
        }
    }
}
