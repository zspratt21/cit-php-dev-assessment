<?php

declare(strict_types=1);

namespace App\Command\Users;

use App\Helper\Model\UserHelper;
use Minicli\Command\CommandController;

class ExportController extends CommandController
{
    public function handle(): void
    {
        $file = $this->getParam('file');
        $this->info("Exporting data to {$file}");
        $handler = UserHelper::getDataHandler($file, $this->getParam('format'));
        if (UserHelper::checkTableExists()) {
            if ($handler->exportUsers()) {
                $this->success('Export complete.');
            } else {
                $this->error('Export failed.');
            }
        } else {
            $this->error('The users table does not exist. Please run the script with the --create_table flag first.');
        }
    }
}
