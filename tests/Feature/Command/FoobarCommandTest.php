<?php

declare(strict_types=1);

test('default command "foobar" is correctly loaded', function (): void {
    $app = getApp();
    $app->runCommand(['foobar', 'foobar']);
})->expectOutputRegex("/Foobar Value Table/");
