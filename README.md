# Catalyst IT PHP Developer Assessment

## Introduction
This project implements an assessment provided by Catalyst IT and consists of 2 primary PHP scripts(user_upload.php and foobar.php), the first script utilizing the MiniCli framework and adhering to it's recommended structure, including the PSR-12 coding standard.

## Requirements
- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- A database named 'catalyst' or a database of your choice, with the appropriate values provided either as the appropriate flags or in the .env file(see example.env for an example setup)
- Docker, if intending to use environment provided in the docker-compose.yml file to execute the scripts.

## Installation
- Clone the repository to your local machine
- Run `composer install` to install dependencies
- Copy the example.env file to .env and adjust to your setup as needed

## Usage
- Run `php user_upload.php --help` to see the available commands and options
- Run `php foobar.php` to run the task 2 script

## Assumptions
- 'running script' Refers to a script that can be executed in a terminal, where the script then proceeds to execute the specified task(with the supplied parameters) without crashing and handling all errors and exceptions in a graceful manner.
- Errors and exceptions when caught, will be printed to the screen and the app will close gracefully or continue(where appropriate) instead of crashing with a stack trace.
- Special characters in the name and surname fields are considered acceptable, since the brief does not explicitly state they are not allowed.
- The --dry_run task checks individual lines within a given csv file on the basis of its fields alone, irrespective of other lines in the file or entries already existing in the users table.
- Task 1 may have its implementation split across multiple files, allowing the use of frameworks and libraries to handle functionality.

## Extras
- The -d flag can be used to specify the name of the database to use. The user upload script will fall back to the .env file if present, otherwise the default value of 'catalyst'.
- A second implementation of task 2 as a command registered in the MiniCli Based task 1 script.
- Unit tests for the task 1 implementation, using Pest.
- Support for Json files in the user upload script.
- The --export flag may be used to export users from the database to a (csv or json) file.
