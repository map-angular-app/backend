<?php

namespace Database;

use PDO;

class MySqlConnection
{
  // PDO Object
  protected $pdo;

  // create a new connection instance.
  public function __construct(array $config)
  {
    $this->open($config);
  }

  // destroy an exists connection instance.
  public function __destruct()
  {
    $this->close();
  }

  // open connection
  private function open(array $config)
  {
    $this->pdo = new PDO(
      $this->getConnectionString($config),
      $config['username'],
      $config['password']
    );
  }

  // execute a sql query.
  public function execute($sql)
  {
    return $this->pdo->exec($sql);
  }

  // send a sql query to get results.
  public  function query($sql, array $params = [])
  {
    if (!empty($params)) {
      $sth = $this->pdo->prepare($sql);
      $sth->execute($params);
      return $sth;
    }

    return $this->pdo->query($sql);
  }

  // close connection.
  private function close()
  {
    $this->pdo = null;
  }

  // get the connection string with format 'mysql:dbname=...;host=...;'
  protected function getConnectionString(array $config)
  {
    $port = isset($config['port']) ? $config['port'] : 3306;
    return 'mysql:dbname=' . $config['dbname'] . ';host=' . $config['hostname'] . ';port=' . $port;
  }
}
