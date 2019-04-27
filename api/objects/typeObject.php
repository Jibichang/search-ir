<?php
class Type{
  private $connection;
  private $table_name = "type";

  public $type_id;
  public $type_name;


  public function __construct($connection){
    $this->connection = $connection;
  }

  function create(){

 // query to insert record
   $query = "INSERT INTO
             " . $this->table_name . "
         SET
             type_id = :type_id,
             type_name = :type_name";

 // prepare query
 $stmt = $this->connection->prepare($query);

 // sanitize
 $this->type_id=htmlspecialchars(strip_tags($this->type_id));
 $this->type_name=htmlspecialchars(strip_tags($this->type_name));

 // bind values
 $stmt->bindParam(":type_id", $this->type_id);
 $stmt->bindParam(":type_name", $this->type_name);
 // execute query
 if($stmt->execute()){
     return true;
 }

 return false;
}

  public function read(){
    $query = "SELECT * FROM " . $this->table_name;
    $stmt = $this->connection-> prepare($query);

    $stmt-> execute();
      return $stmt;
    }

    function update(){

              $query = "UPDATE
                            " . $this->table_name . "
                        SET
                        type_name = :type_name
                        WHERE
                            type_id = :id";

      $stmt = $this->connection->prepare($query);

      // sanitize
      $this->type_id=htmlspecialchars(strip_tags($this->type_id));
      $this->type_name=htmlspecialchars(strip_tags($this->type_name));

      // bind values
      $stmt->bindParam(":type_id", $this->type_id);
      $stmt->bindParam(":type_name", $this->type_name);


      if($stmt->execute()){
        return true;

      }
      return false;
    }

  }
?>
