<?php

declare(strict_types=1);

namespace App\Command\Users;

use Minicli\Command\CommandController;
use PDOException;

class CreateTableController extends CommandController
{
    public function handle(): void
    {
        $this->info('Creating users table...');
        $create_table_sql = "CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            surname VARCHAR(100) NOT NULL,
            email VARCHAR(100) NOT NULL UNIQUE
        )";
        $pdo = $this->getApp()->getToolBox()->getDatabaseAdapter()->getDatabase()->getPDO();
        try {
            $pdo->exec($create_table_sql);
            $this->success('Users table created!');
        } catch (PDOException $e) {
            error_log($e->getMessage());
        }
    }
}
