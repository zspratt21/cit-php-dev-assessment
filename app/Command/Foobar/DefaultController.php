<?php

/**
 * Default Controller for the Foobar command namespace.
 *
 * Implements task 2 of the assessment as a command within the structure of minicli framework.
 */

declare(strict_types=1);

namespace App\Command\Foobar;

use Minicli\Command\CommandController;
use Minicli\Output\Helper\TableHelper;

class DefaultController extends CommandController
{
    public function handle(): void
    {
        $limit = 100;
        $this->display('Foobar Value Table');
        $table = new TableHelper();
        $table->addHeader(['Value', 'Output']);
        for ($i = 1; $i <= $limit; $i++) {
            // first check determines if the value of $i is divisible by 3
            $check_a = 0 === $i % 3;
            // second check determines if the value of $i is divisible by 5
            $check_b = 0 === $i % 5;
            $table->addRow([(string)$i, match (true) {
                // print foobar if both checks are true
                $check_a && $check_b => "foobar",
                // print foo if only the first check is true
                $check_a => "foo",
                // print bar if only the second check is true
                $check_b => "bar",
                // print the value of $i if none of the checks are true
                default => (string)$i,
            }]);
        }
        $this->rawOutput($table->getFormattedTable());
        $this->newline();
    }
}
