<?php

declare(strict_types=1);

test('dryrun command is correctly loaded', function (): void {
    $app = getApp();
    $app->runCommand(['user_upload', 'users', 'dryrun', "file=./provided/users.csv", 'format=csv']);
})->expectOutputRegex("/Dry run mode enabled. Parsing file: .\/provided\/users.csv/");
