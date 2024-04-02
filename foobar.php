<?php

declare(strict_types=1);

/**
 * @file foobar.php
 * This script implements task 2 of the assessment.
 */

require 'vendor/autoload.php';

use Minicli\App;
use Minicli\Exception\CommandNotFoundException;

$app = new App();

try {
    $app->runCommand(['', 'foobar']);
} catch (CommandNotFoundException $notFoundException) {
    $app->error("Command Not Found.");
    return 1;
} catch (Throwable $e) {
    if ($e instanceof Exception) {
        if ($app->config->debug) {
            $app->error("An error occurred:");
            $app->error($e->getMessage());
        }
    } else {
        $app->info('An error occurred: '.$e->getMessage());
    }
    return 1;
}

return 0;
