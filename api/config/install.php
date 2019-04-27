<?php
function cons()
{
  include_once './database.php';
  try {
    $database = new Database();
    $connection = $database-> getConnection();
    $sql = file_get_contents("data/database.sql");
    $connection->exec($sql);
    
    echo "Database and tables created successfully!";
  } catch(PDOException $e){
    echo $e->getMessage();
  }
}
cons();
?>
