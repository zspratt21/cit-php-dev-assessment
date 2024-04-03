<?php

declare(strict_types=1);

/**
 * @file foobar.php
 * This script implements task 2 of the assessment as a single file as per the brief requirements.
 */

$limit = 100;
echo "Foobar Value Table\n";
echo "Value\tOutput\n";
for ($i = 1; $i <= $limit; $i++) {
    // first check determines if the value of $i is divisible by 3
    $check_a = 0 === $i % 3;
    // second check determines if the value of $i is divisible by 5
    $check_b = 0 === $i % 5;
    echo $i . "\t";
    echo match (true) {
        // print foobar if both checks are true
        $check_a && $check_b => "foobar\n",
        // print foo if only the first check is true
        $check_a => "foo\n",
        // print bar if only the second check is true
        $check_b => "bar\n",
        // print the value of $i if none of the checks are true
        default => $i . "\n",
    };
}
