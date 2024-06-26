<?php

/**
 * Default Controller for the Instructions command namespace.
 *
 * Prints out command details.
 */

declare(strict_types=1);

namespace App\Command\Instructions;

use Minicli\Command\CommandController;

class DefaultController extends CommandController
{
    public function handle(): void
    {
        $this->info("Usage guide for the Users Import Script");
        $this->info("Flags are listed in the order they will be prioritised(if multiple flags trigger opposing operations)");

        $infoContent = "--create_table - requires database credentials – this will cause the MySQL users table to be built (and no further action will be taken)\n";
        $infoContent .= "--dry_run – this will be used with the --file directive to run the script, but not insert into the DB. All other functions will be executed, but the database won't be altered\n";
        $infoContent .= "--export - requires database credentials – this will be used with the --file directive to export users in the database to the specified file\n";
        $infoContent .= "--file [file name] - requires database credentials(for default import operation) – this is the name of the file to be parsed. Csv and Json files are supported.\n";
        $infoContent .= "-u – MySQL username\n";
        $infoContent .= "-p – MySQL password\n";
        $infoContent .= "-h – MySQL host\n";
        $infoContent .= "-d – MySQL database name, falls back to DB_NAME environment variable if not provided and 'catalyst' if DB_NAME is not set in the .env file or the .env file is not present";

        $this->info($infoContent);
    }
}
