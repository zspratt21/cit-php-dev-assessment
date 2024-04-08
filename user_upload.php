<?php

declare(strict_types=1);

/**
 * @file user_upload.php
 * This script implements task 1 of the assessment.
 */

require 'vendor/autoload.php';

use App\Helper\FileHelper;
use Dotenv\Dotenv;
use Minicli\App;
use Minicli\Exception\CommandNotFoundException;
use RedBeanPHP\R as R;

const DEFAULT_DB_NAME = 'catalyst';

$app = new App();

try {
    $dotenv = Dotenv::createImmutable(__DIR__);
    $dotenv->load();
} catch (Exception $e) {
    $app->info("WARNING: env file could not be loaded. Falling back to env values will not be possible.");
}

// get the commandline arguments
$short_options = "u:p:h:d:m:";
$long_options = ["file:", "create_table", "dry_run", "help", "foobar", "export"];
$options = getopt($short_options, $long_options);
$has_credentials = isset($options['u'], $options['p'], $options['h']);
$requested_dry_run = isset($options['dry_run']);
$requested_export = isset($options['export']) && $has_credentials;
$requested_file_operation = $requested_dry_run || $requested_export;
// check if the required arguments are set
if($has_credentials) {
    // argument values for forming the database connection
    $username = $options['u'];
    $password = $options['p'];
    $host = $options['h'];
    $database = ! empty($options['d']) ? $options['d'] : ($_ENV['DB_NAME'] ?: DEFAULT_DB_NAME);
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
    $model = match (true) {
        isset($options['m']) => match ($options['m']) {
            'project' => throw new Exception('Project model implementation coming soon!'),
            default => 'users',
        },
        default => 'users',
    };
    if (isset($options['help'])) {
        $app->runCommand(['', 'instructions']);
    } elseif (isset($options['foobar'])) {
        $app->runCommand(['', 'foobar']);
    } elseif (isset($options['create_table']) && $has_credentials) {
        $app->runCommand(['', $model, 'createtable']);
    } elseif (isset($options['file']) && ($has_credentials || $requested_file_operation)) {
        $file = $options['file'];
        $format = FileHelper::checkValidFile($file);
        if ($format) {
            $operation = match(true) {
                $requested_dry_run => 'dryrun',
                $requested_export => 'export',
                default => 'default',
            };
            $app->runCommand(['', $model, $operation, "file={$file}", "format={$format}"]);
        } else {
            $app->error("File {$file} not found or was an invalid format. See --help for valid file formats.");
        }
    } else {
        $app->error("Please provide adequate options to execute a valid command. Use --help for more information.");
    }
} catch (CommandNotFoundException $notFoundException) {
    $app->error("Command Not Found.");
    return 1;
} catch (Throwable $e) {
    $app->error($e->getMessage());
    return 1;
}

return 0;
