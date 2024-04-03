# Catalyst IT PHP Developer Assessment

## Introduction
This project implements an assessment provided by Catalyst IT and consists of 2 primary PHP scripts(user_upload.php and foobar.php), both of which utilize the MiniCli framework and adhere to it's recommended structure, including the PSR-12 coding standard.

## Requirements
- PHP 8.1 or higher
- Composer
- MySQL 5.7 or higher
- A database named 'catalyst' or a database of your choice, with the appropriate values provided either as the appropriate flags or in the .env file(see example.env for an example setup)
- Docker, if intending to use environment provided in the docker-compose.yml file.

## Installation
- Clone the repository to your local machine
- Run `composer install` to install dependencies

## Usage
- Run `php user_upload.php --help` to see the available commands and options
- Run `php foobar.php` to run the task 2 script

## Assumptions
- 'running script' Refers to a script that can be executed in a terminal, where the script then proceeds to execute the specified task(with the supplied parameters) without crashing and handling all errors and exceptions in a graceful manner.
- Errors and exceptions when caught, will be printed to the screen and the app will close gracefully or continue(where appropriate) instead of crashing with a stack trace.
- Special characters in the name and surname fields are considered acceptable, since the brief does not explicitly state they are not allowed.
- The --dry_run task checks individual lines within a given csv file on the basis of its fields alone, irrespective of other lines in the file or entries already existing in the users table.
- Emails should not contain special characters except the @ symbol, as many email providers do not support email addresses containing other special characters.

## Extras
- The -d flag can be used to specify the name of the database to use. The user upload script will fall back to the .env file if present, otherwise the default value of 'catalyst'.
