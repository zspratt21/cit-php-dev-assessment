<?php

/**
 * Create Table Controller.
 *
 * Implements function to create the users table in the database.
 */

declare(strict_types=1);

namespace App\Command\Users;

use App\Helper\Model\UserHelper;
use Minicli\Command\CommandController;
use PDOException;
use RedBeanPHP\R;

class CreateTableController extends CommandController
{
    public function handle(): void
    {
        if (UserHelper::checkTableExists()) {
            $this->info('The users table already exists.');
            exit(0);
        } else {
            $this->info('Creating users table...');
            $create_table_sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            surname VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE
            )";
            $pdo = R::getToolBox()->getDatabaseAdapter()->getDatabase()->getPDO();
            try {
                $pdo->exec($create_table_sql);
                $this->success('Users table created.');
            } catch (PDOException $e) {
                $this->error("Failed to create users table. Reason: {$e->getMessage()}.");
                exit(1);
            }
        }
    }
}
