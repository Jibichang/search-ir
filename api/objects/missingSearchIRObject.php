<?php
include('../libs/THSplitLib/THSplitLib/segment.php');
// include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'api/libs/THSplitLib/THSplitLib/segment.php');
class MissingPersonsIR{
  public $plost_id;
  public $pname;
  public $fname;
  public $lname;
  public $gender;
  public $age;
  public $place;
  public $subdistrict;
  public $district;
  public $city;
  public $height;
  public $weight;
  public $shape;
  public $hairtype;
  public $haircolor;
  public $skintone;
  public $upperwaist;
  public $uppercolor;
  public $lowerwaist;
  public $lowercolor;
  public $detail_etc;
  public $special;
  public $type_id;
  public $guest_id;
  public $status;
  public $reg_date;

  private $connection;
  private $table_name = "peoplelost";
  private $count_doc = 0;
  private $word_all = array();
  private $count_word_all = 0;
  private $word_per_doc = array();
  private $count_word_per_doc = array();
  private $cut_query = array();
  private $doc = array();
  private $word_unique = array();
  private $idf_value = array();
  private $idf =array();

  private $freq_doc = array();
  private $n_db =array();

  public function __construct($connection){
    $this->connection = $connection;
  }

  public function searchIR($doc, $query){
    $this->preProcess($doc);
    $this->calIDF();
    $this->calQuery($query);
    // $this->calIR();
    return $this->calIR();
  }

  function read(){
    $query = "SELECT * FROM $this->table_name
    WHERE IF(gender = 'M' AND status = '0' ,
      IF(fname LIKE '' OR lname LIKE '' ,1,1) ,0)";
    $stmt = $this->connection->prepare($query);
    $stmt-> execute();
    // $count_doc = $stmt->rowCount();
    return $stmt;
  }

  function cutWord($detail)
  {
    $segment = new Segment();
    $result = $segment->get_segment_array($detail);

    return $result;
  }

  function preProcess($stmt){
    $segment = new Segment();

    // $query = "SELECT * FROM " . $this->table_name;
    // $stmt2 = $this->connection->prepare($query);
    // $stmt2-> execute();

    $this->count_doc = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $this->count_doc += 1; // N doc
      $str =  $row["fname"]." ".$row["lname"]." ".
              $row["detail_etc"]." ".$row["special"]." ".$row["age"]." ".
              $row["city"]." ".$row["district"]." ".$row["subdistrict"]." ".$row["place"]." ".
              $row["height"]." ".$row["skintone"]." ".$row["shape"]." ".
              $row["hairtype"]." H".$row["haircolor"]." ".
              $row["upperwaist"]." U".$row["uppercolor"]." ".
              $row["lowerwaist"]." L".$row["lowercolor"];
      array_push($this->n_db, $row["plost_id"]);
      array_push($this->doc, $str); // detail (doc)
      $this->word_all = $segment-> get_segment_array($str); //word
    }
    $this->word_unique = array_keys(array_count_values($this->word_all)); // word unique
    // stop word
    $diff = array_diff($this->word_unique,["กก.","ซม.", "น้ำหนัก", "ประมาณ", "บริเวณ","ลักษณะ"]);
    $this->word_unique = array_values($diff);
    $this->count_word_all = count($this->word_unique); // count word unique

    return $this->doc;

  }

  // function clearIDF(){
  //   ///delete data in table idf
  //   $queryDel = "DELETE FROM invert";
  //   $stmtDel = $this->connection->prepare($queryDel);
  //   if($stmtDel->execute()){}
  //
  //     //INSERT to phpmyadmin///
  //     for ($i=0; $i < $this->count_word_all; $i++) {
  //       $term = $this->word_unique[$i];
  //       $idf = $this->idf_value[$i];
  //       $queryTerm = "INSERT INTO `invert`(`term`,`idf`) VALUES ('$term','$idf')";
  //       $stmtTerm = $this->connection->prepare($queryTerm);
  //       $stmtTerm->bindParam(":term", $term);
  //       $stmtTerm->bindParam(":term", $idf);
  //       if($stmtTerm->execute()){}
  //       }
  //     }


      function calIDF(){
        $segment = new Segment();

        $result = array();$result_freq = array();
        $freq = array(); $count_n = array();
        foreach ($this->doc as $doc_key => $doc_value) { // N doc
          $word_doc = array(); $freq_temp = array();
          $cut_doc = array();
          $word_doc = $segment-> get_segment_array($this->doc[$doc_key]); //cut term per doc
          $diff = array_diff($word_doc,["กก.","ซม.", "น้ำหนัก", "ประมาณ", "บริเวณ","ลักษณะ"]);

          $sm = new Segment();
          $cut_doc = $sm-> get_segment_array($this->doc[$doc_key]); //cut term per doc
          $cut_doc = array_diff($cut_doc,["กก.","ซม.", "น้ำหนัก", "ประมาณ", "บริเวณ","ลักษณะ"]);

          // have term in doc?
          $temp = array_intersect($word_doc, $this->word_unique);
          $freq_temp = array_intersect($cut_doc, $this->word_unique);
          $this->freq_doc[$doc_key] = array_count_values(
            array_intersect($cut_doc, $this->word_unique));
            // query and cut doc
            // $this->freq_doc[$doc_key] = array_intersect($cut_doc, array_keys($this->cut_query));

            $freq[$doc_key] = array_unique($freq_temp);
            $result_freq[$doc_key] = array_count_values($freq[$doc_key]);
            // error_reporting(error_reporting() & ~E_NOTICE);
            foreach ($result_freq[$doc_key] as $key_x => $value_x) {
              if (empty($count_n[$key_x])) {
                $count_n[$key_x] = 0;
              }
              $count_n[$key_x] += $value_x; // valid value tf [true]
            }
            // $freq[$doc_key] = array_count_values($cut_doc);
            // $temp_freq = array_intersect($freq[$doc_key], $this->word_unique);
            // $this->freq_doc[$doc_key] = $cut_doc;
            // number of term in doc // term => frequency
            $result = array_count_values($temp);
            $this->idf_value = array_values($count_n);
            foreach ($this->idf_value as $key => $value) { // calculate idf
              $this->idf_value[$key] = round((log10(($this->count_doc)/($this->idf_value[$key]))),4);
            }
            // same value term unique before compare $countx = count($result);
          }
          // ///delete data in table idf
          // $queryDel = "DELETE FROM invert";
          // $stmtDel = $this->connection->prepare($queryDel);
          // if($stmtDel->execute()){}
          //
          // //INSERT to phpmyadmin///
          // for ($i=0; $i < $this->count_word_all; $i++) {
          //   $term = $this->word_unique[$i];
          //   $idf = $this->idf_value[$i];
          //   $queryTerm = "INSERT INTO `invert`(`term`,`idf`) VALUES ('$term','$idf')";
          //   $stmtTerm = $this->connection->prepare($queryTerm);
          //   $stmtTerm->bindParam(":term", $term);
          //   $stmtTerm->bindParam(":term", $idf);
          //   if($stmtTerm->execute()){}
          // }
          return array_keys($count_n);
        }

        function calQuery($input_detail){
          $segment = new Segment();
          $cutInput = array();

          $data = $input_detail;
          $cutInput = $segment -> get_segment_array($data);
          $diff = array_diff($cutInput,["กก.","ซม.", "น้ำหนัก", "ประมาณ", "บริเวณ","ลักษณะ"]);
          $this->cut_query = array_count_values($diff);
          // $this->cut_query = array_values($diff);
          return $this->cut_query;
        }

        function calIR(){
          $segment = new Segment();
          // $query = "SELECT * FROM invert";
          // $stmt = $this->connection->prepare($query);
          // $stmt-> execute();

          $lengh = array();

          for ($i=0; $i < $this->count_word_all; $i++) {
            $term = $this->word_unique[$i];
            $idf = $this->idf_value[$i];
            $this->idf[$term] = $idf;
          }
          $sim = array();
          $weight = 0;
          foreach ($this->freq_doc as $freq_key => $freq_value) {
            $sum = 0;
            foreach ($freq_value as $key => $value) {
              // weight = idf * tf
              $weight = $this->idf[$key]*$value;
              $sum += $weight; // length
            }
            $lengh[$freq_key] = $sum;
            $sim_value = 0;
            foreach ($freq_value as $key => $value) {
              // if ($key == $freq_value[$key]) { ///mark error if wrong value pls hide this line
              if(!empty($this->cut_query[$key])){
                if ($this->cut_query[$key] > 0) {
                  // weight
                  $weight = $this->idf[$key]*$value;
                  // sim = frequency query* ( weight / lenght)
                  $sim_value += $this->cut_query[$key]*($weight/$lengh[$freq_key]);
                }
              } else {
                $this->cut_query[$key] = 0;
              }

              // } ///and this line
            }
            $x = $this->n_db[$freq_key];
            $sim[$x] = $sim_value;
          }
          arsort($sim); // sort doc
          $sim_result = array();

          // return $sim;
          $missing_arr=array();
          $missing_arr["body"]=array();
          foreach ($sim as $key => $value) {
            $key_plus = $key;
            $query = "SELECT * FROM $this->table_name WHERE plost_id = '$key_plus'";
            $stmt = $this->connection->prepare($query);
            $stmt-> execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
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
                    "reg_date"=> $reg_date
              );
              array_push($missing_arr["records"], $missing_item);
              // array_push($sim_result, $row["detail_etc"]); // detail (doc)
            }

          }
          return json_encode($missing_arr,JSON_UNESCAPED_UNICODE);
          return $sim_result;
        }

        function search(){
          $query = "SELECT * FROM $this->table_name
          WHERE IF(gender = :gender AND status = :status ,
            IF(fname LIKE :fname OR lname LIKE :lname ,1,1) ,0)";
            // prepare query statement
            $stmt = $this->connection->prepare($query);

            $this->fname = "%{$this->fname}%";
            $this->lname = "%{$this->lname}%";
            // bind
            $stmt->bindParam(":fname", $this->fname);
            $stmt->bindParam(":lname", $this->lname);
            $stmt->bindParam(":gender", $this->gender);
            $stmt->bindParam(":status", $this->status);
            $stmt->execute();

            return $stmt;
          }

        }
        ?>
