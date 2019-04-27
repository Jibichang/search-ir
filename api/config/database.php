<?php
class Database{
  // specify your own database credentials
  private $host = "localhost";
  private $db_name = "missing_person";
  private $username = "root";
  private $password = "";
  public $connection;

  // get the database connection
  public function getConnection(){
    $this->connection = null;
    try{
      $this->connection = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
      $this->connection->exec("set names utf8");
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }catch(PDOException $exception){
      echo "Connection error: " . $exception->getMessage();
    }
    return $this->connection;
  }
}
?>
