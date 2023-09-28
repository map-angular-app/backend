<?php

require_once 'Database/Migrations/CreateTables.php';
require_once 'connection.php';
require_once 'Database/Migrations/Tables/hospitals_table.php';
require_once 'Database/Migrations/Tables/citys_table.php';

use Database\Migrations\CreateTables;

$db = new CreateTables($connection);

foreach ($statements as $key => $statement) {
    $db->up($statement);
    echo "create table $tables[$key] if not exist success\n";
}
