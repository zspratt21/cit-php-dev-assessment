<?php

/**
 * @file foobar.php
 * This script implements task 2 of the assessment.
 */

$limit = 100;

for ($i = 1; $i <= $limit; $i++) {
    // first check determines if the value of $i is divisible by 3
    $check_a = $i % 3 == 0;
    // second check determines if the value of $i is divisible by 5
    $check_b = $i % 5 == 0;
    switch ($i) {
        // print foobar if both checks are true
        case ($check_a && $check_b):
            echo "foobar \n";
            break;
        // print foo if only the first check is true
        case ($check_a):
            echo "foo \n";
            break;
        // print bar if only the second check is true
        case ($check_b):
            echo "bar \n";
            break;
        // print the value of $i if neither check is true
        default:
            echo $i."\n";
            break;
    }
}
