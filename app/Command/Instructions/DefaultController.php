<?php

declare(strict_types=1);

namespace App\Command\Instructions;

use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
     public function handle(): void
    {
        $infoContent = "--file [csv file name] – this is the name of the CSV to be parsed\n";
        $infoContent .= "--create_table – this will cause the MySQL users table to be built (and no further action will be taken)\n";
        $infoContent .= "--dry_run – this will be used with the --file directive to run the script, but not insert into the DB. All other functions will be executed, but the database won't be altered\n";
        $infoContent .= "-u – MySQL username\n";
        $infoContent .= "-p – MySQL password\n";
        $infoContent .= "-h – MySQL host\n";
        $infoContent .= "-d – MySQL database name, falls back to DB_NAME environment variable if not provided and 'catalyst' if DB_NAME is not set in the .env file";

        $this->info($infoContent);
    }
}
