<?php
/**
* Warunee
*/
class SelectThailand
{
  private $connection;
  private $table_name = "thailand";

  public $city;
  public $district;
  public $subdistrict;

  public $province;

  public function __construct($connection){
    $this->connection = $connection;
  }

  public function selectDistrict()
  {
    $query = "SELECT DISTINCT district FROM $this->table_name WHERE province = :district";
    $stmt = $this->connection->prepare($query);

    $this->district = htmlspecialchars(strip_tags($this->district));
    $stmt->bindParam(":district", $this->district);

    $stmt->execute();
    return $stmt;
  }

  public function selectSubDistrict()
  {
    $query = "SELECT DISTINCT subdistrict FROM $this->table_name WHERE district = :subdistrict";
    $stmt = $this->connection->prepare($query);

    $this->subdistrict = htmlspecialchars(strip_tags($this->subdistrict));
    $stmt->bindParam(":subdistrict", $this->subdistrict);

    $stmt->execute();
    return $stmt;
  }

}

?>
