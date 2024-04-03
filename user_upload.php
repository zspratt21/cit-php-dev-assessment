<?php

declare(strict_types=1);

/**
 * @file user_upload.php
 * This script implements task 1 of the assessment.
 */

require 'vendor/autoload.php';

use Minicli\App;
use Minicli\Exception\CommandNotFoundException;
use RedBeanPHP\R as R;

// use custom App class that extends Minicli\App
$app = new App();

try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    $app->info("WARNING: env file could not be loaded. Falling back to env values will not be possible.");
}

// get the commandline arguments
$short_options = "u:p:h:d:";
$long_options = ["file:", "create_table", "dry_run", "help", "foobar"];
$options = getopt($short_options, $long_options);
$has_credentials = isset($options['u'], $options['p'], $options['h']);
// check if the required arguments are set
if($has_credentials) {
    // argument values for forming the database connection
    $username = $options['u'];
    $password = $options['p'];
    $host = $options['h'];
    $database = ! empty($options['d']) ? $options['d'] : ($_ENV['DB_NAME'] ?: 'catalyst');
    $app->info("Connecting to database {$database} on {$host} as {$username}...");
    R::setup("mysql:host={$host};dbname={$database}", $username, $password);
    if (R::testConnection()) {
        $app->success("Connected to database.");
    } else {
        $app->error("Failed to connect to the specified database with the provided credentials.");
        exit(1);
    }
}

try {
    if (isset($options['help'])) {
        $app->runCommand(['', 'instructions']);
    } elseif (isset($options['foobar'])) {
        $app->runCommand(['', 'foobar']);
    } elseif (isset($options['create_table']) && $has_credentials) {
        $app->runCommand(['', 'users', 'createtable']);
    } elseif (isset($options['file']) && ($has_credentials || isset($options['dry_run'])) ) {
        $file = $options['file'];
        if (file_exists($file)) {
            if (isset($options['dry_run'])) {
                $app->runCommand(['', 'users', 'dryrun', "file={$file}"]);
            } else {
                $app->runCommand(['', 'users', 'default', "file={$file}"]);
            }
        } else {
            $app->error("File not found: {$file}");
        }
    } else {
        $app->error("Please provide adequate options to execute a valid command. Use --help for more information.");
    }
} catch (CommandNotFoundException $notFoundException) {
    $app->error("Command Not Found.");
    return 1;
} catch (Throwable $e) {
    $app->error("An error occurred: {$e->getMessage()}");
    return 1;
}

return 0;
