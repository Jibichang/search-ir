<?php
class Guest {
  private $connection;
  private $table_name = "guest";

  public $id;

  public $guest_id;
  public $guest_name;
  public $guest_email;
  public $guest_pass;
  public $guest_place;
  public $guest_phone;

  public function __construct($connection){
    $this->connection = $connection;
  }

  function create(){
    $query = "INSERT INTO $this->table_name SET
    guest_name = :name,
    guest_pass = :password,
    guest_place = :place,
    guest_phone = :phone,
    guest_email = :email";

    // prepare the query
    $stmt = $this->connection->prepare($query);

    // sanitize
    $this->guest_name=htmlspecialchars(strip_tags($this->guest_name));
    $this->guest_email=htmlspecialchars(strip_tags($this->guest_email));
    $this->guest_pass=htmlspecialchars(strip_tags($this->guest_pass));
    $this->guest_place=htmlspecialchars(strip_tags($this->guest_place));
    $this->guest_phone=htmlspecialchars(strip_tags($this->guest_phone));


    // bind the values
    $stmt->bindParam(':name', $this->guest_name);
    $stmt->bindParam(':email', $this->guest_email);


    // hash the password before saving to database
    $password_hash = password_hash($this->guest_pass, PASSWORD_BCRYPT);
    $stmt->bindParam(':password', $password_hash);
    $stmt->bindParam(':phone', $this->guest_phone);
    $stmt->bindParam(':place', $this->guest_place);

    // execute the query, also check if query was successful
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

  public function contact(){
    $query = "SELECT guest_email, guest_name, guest_phone
              FROM $this->table_name WHERE guest_id = :guest_id
              LIMIT 0,1";
    $stmt = $this->connection-> prepare($query);

    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
    $stmt->bindParam(':guest_id', $this->guest_id);


    $stmt->execute();
    $num = $stmt->rowCount();
    if($num>0){
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      // $this->guest_id = $row['guest_id'];
      $this->guest_name = $row['guest_name'];
      $this->guest_email = $row['guest_email'];
      // $this->guest_pass = $row['guest_pass'];
      // $this->guest_place = $row['guest_place'];
      $this->guest_phone = $row['guest_phone'];
      return true;
    }
    return false;
  }

  function emailExists(){
    $query = "SELECT *
    FROM  $this->table_name
    WHERE guest_email = ?
    LIMIT 0,1";

    // prepare the query
    $stmt = $this->connection->prepare( $query );
    $this->guest_email=htmlspecialchars(strip_tags($this->guest_email));
    $stmt->bindParam(1, $this->guest_email);

    $stmt->execute();
    $num = $stmt->rowCount();

    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      $this->guest_id = $row['guest_id'];
      $this->guest_name = $row['guest_name'];
      $this->guest_email = $row['guest_email'];
      $this->guest_pass = $row['guest_pass'];
      $this->guest_place = $row['guest_place'];
      $this->guest_phone = $row['guest_phone'];
      return true;
    }
    return false;
  }

  function update(){
    $password_set = !empty($this->guest_pass) ? "guest_pass = :password" : "";

    $query = "UPDATE $this->table_name
    SET
    guest_name = :name,
    guest_place = :place,
    guest_phone = :phone,
    {$password_set}
    WHERE
    guest_id = :guest_id";

    $stmt = $this->connection->prepare($query);

    $this->guest_name=htmlspecialchars(strip_tags($this->guest_name));
    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
    $this->guest_place=htmlspecialchars(strip_tags($this->guest_place));
    $this->guest_phone=htmlspecialchars(strip_tags($this->guest_phone));

    $stmt->bindParam(':name', $this->guest_name);
    $stmt->bindParam(':guest_id', $this->guest_id);
    $stmt->bindParam(':phone', $this->guest_phone);
    $stmt->bindParam(':place', $this->guest_place);

    // hash the password before saving to database
    if(!empty($this->guest_pass)){
      $this->guest_pass=htmlspecialchars(strip_tags($this->guest_pass));
      $password_hash = password_hash($this->guest_pass, PASSWORD_BCRYPT);
      $stmt->bindParam(':password', $password_hash);
    }
    // $stmt->bindParam(':id', $this->guest_id);

    if($stmt->execute()){
      return true;
    }
    return false;
  }

  function validPass($input_pass) {
    $query = "SELECT guest_pass
    FROM  $this->table_name
    WHERE guest_id = ?
    LIMIT 0,1";

    $stmt = $this->connection->prepare( $query );
    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
    $stmt->bindParam(1, $this->guest_id);

    $stmt->execute();
    $num = $stmt->rowCount();

    if($num>0){
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      $this->guest_pass = $row['guest_pass'];

      if (password_verify($input_pass, $this->guest_pass)) {
        return true;
      }
    }
    return false;
  }
}
