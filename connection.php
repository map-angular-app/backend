<?php
require_once 'Database/MySqlConnection.php';

use Database\MySqlConnection;

$env = parse_ini_file('.env');

$connection = new MySqlConnection([
    'dbname' => $env["DB_DATABASE"],
    'hostname' => $env["DB_HOST"],
    'username' => $env["DB_USERNAME"],
    'password' => $env["DB_PASSWORD"],
    'port' => $env["DB_PORT"],
]);
