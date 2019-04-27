<?php
class Feedback{
  private $connection;
  private $table_name = "feedback";

  public $feedback_id;
  public $guest_id;
  public $id;

  public $id_fb = array();

  public function __construct($connection){
    $this->connection = $connection;
  }


  function create(){
    // query to insert record
    $query = "INSERT INTO
    " . $this->table_name . "
    SET
    guest_id = :guest_id,
    id = :id";

    // prepare query
    $stmt = $this->connection->prepare($query);

    // sanitize
    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
    $this->id=htmlspecialchars(strip_tags($this->id));

    // bind values
    $stmt->bindParam(":guest_id", $this->guest_id);
    $stmt->bindParam(":id", $this->id);
    // execute query
    if($stmt->execute()){
      return true;
    }

    return false;
  }

  public function read(){
    $query = "SELECT id FROM $this->table_name
              WHERE guest_id = :guest_id
              ORDER BY feedback.feedback_id DESC";
    $stmt = $this->connection->prepare($query);

    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
    $stmt->bindParam(":guest_id", $this->guest_id);
    $stmt->execute();

    $arrayName = array();
    while ($rowFeedback = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $arrayName[$rowFeedback["id"]] = $rowFeedback["id"];
    }
    $this->id_fb = $arrayName;

    $missing_arr = array();
    $missing_arr["body"]=array();
    $missing_arr["count"] = count($this->id_fb);

    foreach ($this->id_fb as $key => $value) {
      $query2 = "SELECT * FROM peoplelost WHERE plost_id = '$key'";
      $stmt2 = $this->connection->prepare($query2);
      $stmt2-> execute();

      while ($row = $stmt2->fetch(PDO::FETCH_ASSOC))
      {
        extract($row);
        $missing_item = array(
          "id"=> $plost_id,
          "pname"=> $pname,
          "fname"=> $fname,
          "lname"=> $lname,
          "gender"=> $gender,
          "age"=> $age,
          "place"=> $place,
          "subdistrict"=> $subdistrict,
          "district"=> $district,
          "city"=> $city,
          "height"=> $height,
          "weight"=> $weight,
          "shape"=> $shape,
          "hairtype"=> $hairtype,
          "haircolor"=> $haircolor,
          "skintone"=> $skintone,
          "upperwaist"=> $upperwaist,
          "uppercolor"=> $uppercolor,
          "lowerwaist"=> $lowerwaist,
          "lowercolor"=> $lowercolor,
          "detail_etc"=> $detail_etc,
          "special"=> $special,
          "type_id"=> $type_id,
          "guest_id"=> $guest_id,
          "status"=> $status,
          "reg_date"=> $reg_date,
          "path_img"=> $path_img
        );
        array_push($missing_arr["body"], $missing_item);
        // array_push($sim_result, $row["detail_etc"]); // detail (doc)
      }

    }
    return $missing_arr;
  }

  public function delete(){
    $query = "DELETE FROM $this->table_name
              WHERE id = :id AND guest_id = :guest_id";
    $stmt = $this->connection->prepare($query);

    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
    $this->id=htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(":guest_id", $this->guest_id);
    $stmt->bindParam(":id", $this->id);

    // execute the query
    if($stmt->execute()){
      return true;
    }
    return false;
  }

  public function deleteAll(){
    $query = "DELETE FROM $this->table_name
              WHERE guest_id = :guest_id";
    $stmt = $this->connection->prepare($query);

    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));

    $stmt->bindParam(":guest_id", $this->guest_id);

    // execute the query
    if($stmt->execute()){
      return true;
    }
    return false;
  }

  function update(){

    $query = "UPDATE $this->table_name
    SET guest_id = :guest_id,
    id = :id,
    WHERE
    feedback_id = :feedback_id";


    $stmt = $this->connection->prepare($query);

    $this->feedback_id=htmlspecialchars(strip_tags($this->feedback_id));
    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
    $this->id=htmlspecialchars(strip_tags($this->id));

    $stmt->bindParam(":feedback_id", $this->feedback_id);
    $stmt->bindParam(":guest_id", $this->guest_id);
    $stmt->bindParam(":id", $this->id);

    // execute the query
    if($stmt->execute()){
      return true;
    }

    return false;
  }

}
?>
