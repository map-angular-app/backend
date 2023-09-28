<?php

namespace Database\Migrations;

class CreateTables
{
    protected $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function up($statement)
    {
        if (method_exists($this->connection, 'execute')) {
            $this->connection->execute($statement);
        } else $this->connection->exec($statement);
    }
}
