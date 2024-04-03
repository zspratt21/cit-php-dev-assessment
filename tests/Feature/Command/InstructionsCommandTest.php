<?php

declare(strict_types=1);

test('default command "instructions" is correctly loaded', function (): void {
    $app = getApp();
    $app->runCommand(['user_upload', 'instructions']);
})->expectOutputRegex("/Usage guide for the Users Import Script/");
