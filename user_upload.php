<?php

declare(strict_types=1);

/**
 * @file user_upload.php
 * This script implements task 1 of the assessment.
 */

require 'vendor/autoload.php';

use App\App;
use Minicli\Exception\CommandNotFoundException;
use RedBeanPHP\R as R;

// use custom App class that extends Minicli\App
$app = new App();

// get the commandline arguments
$short_options = "u:p:h:d:";
$long_options = ["file:", "create_table", "dry_run", "help"];
$options = getopt($short_options, $long_options);
$has_credentials = isset($options['u'], $options['p'], $options['h']);
// check if the required arguments are set
if ( ! $has_credentials && ! isset($options['help'])) {
    $app->error("Please provide the required args: -u (username), -p (password), -h (mysql host) or use --help for more information.");

    $app->info('Exiting...');
    exit(1);
} elseif($has_credentials) {
    // argument values for forming the database connection
    $username = $options['u'];
    $password = $options['p'];
    $host = $options['h'];
    $database = ! empty($options['d']) ? $options['d'] : (getenv('DB_NAME') ?: 'catalyst');
    $app->info("Connecting to database {$database} on {$host} as {$username}...");
    $toolbox = R::setup("mysql:host={$host};dbname={$database}", $username, $password);
    if (R::testConnection()) {
        $app->setToolBox($toolbox);
        $app->success("Connected to database.");
    } else {
        $app->error("Failed to connect to the specified database with the provided credentials.");
        exit(1);
    }
}

try {
    if (isset($options['help'])) {
        $app->runCommand(['', 'instructions']);
    } elseif (isset($options['create_table'])) {
        $app->runCommand(['', 'users', 'createtable']);
    } elseif (isset($options['dry_run'])) {
        $file = $options['file'] ?? './csv/users.csv';
        $app->runCommand(['', 'users', 'dryrun', "file={$file}"]);
    }
    // @todo run users import(default)
} catch (CommandNotFoundException $notFoundException) {
    $app->error("Command Not Found.");
    return 1;
} catch (Throwable $e) {
    $app->error('An error occurred: '.$e->getMessage());
    return 1;
}

return 0;
