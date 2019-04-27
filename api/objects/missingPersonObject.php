<?php
class MissingPersons{
  private $connection;
  private $table_name = "peoplelost";


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
  public $image;
  public $path_img;

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
  private $sim_result_return = array();

  private $feedback_array = array();
  private $name_array = array();
  private $city_array = array();

  public function __construct($connection){
    $this->connection = $connection;
  }

  function read(){
    $query = "SELECT * FROM $this->table_name WHERE status = '0' ORDER BY plost_id DESC";
    $stmt = $this->connection->prepare($query);
    $stmt->execute();

    // return $stmt;
    $missing_arr=array();
    $missing_arr["body"]=array();
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
        "shape"=> $this->textDetail($shape),
        "hairtype"=> $this->textDetail($hairtype),
        "haircolor"=> $haircolor,
        "skintone"=> $skintone,
        "upperwaist"=> $this->textDetail($upperwaist),
        "uppercolor"=> $uppercolor,
        "lowerwaist"=> $this->textDetail($lowerwaist),
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
    return $missing_arr;
  }

  function delete(){

    // delete query
    $query = "DELETE FROM " . $this->table_name . " WHERE plost_id = ?";

    // prepare query
    $stmt = $this->connection->prepare($query);

    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->plost_id));

    // bind id of record to delete
    $stmt->bindParam(1, $this->plost_id);

    // execute query
    if($stmt->execute()){
      return true;
    }
    return false;
  }

  function upload(){
    $query = "UPDATE $this->table_name
    SET path_img= :path_img
    WHERE plost_id = :plost_id";

    $stmt = $this->connection->prepare($query);
    $this->path_img=htmlspecialchars(strip_tags($this->path_img));
    $this->plost_id=htmlspecialchars(strip_tags($this->plost_id));

    $stmt->bindParam(":path_img", $this->path_img);
    $stmt->bindParam(":plost_id", $this->plost_id);

    if($stmt->execute()){
      return true;
    }
    return false;
  }

  function updateStatus(){
    $query = "UPDATE $this->table_name
    SET status = '1'
    WHERE plost_id = :plost_id";

    $stmt = $this->connection->prepare($query);
    $this->plost_id=htmlspecialchars(strip_tags($this->plost_id));

    $stmt->bindParam(":plost_id", $this->plost_id);

    if($stmt->execute()){
      return true;
    }
    return false;
  }

  function create(){

    $query = "INSERT INTO
    " . $this->table_name . "
    SET

    pname = :pname,
    fname = :fname,
    lname = :lname,
    gender = :gender,
    age = :age,
    place = :place,
    subdistrict = :subdistrict,
    district = :district,
    city = :city,
    height = :height,

    shape = :shape,
    hairtype = :hairtype,
    haircolor = :haircolor,
    skintone = :skintone,
    upperwaist = :upperwaist,
    uppercolor = :uppercolor,
    lowerwaist = :lowerwaist,
    lowercolor = :lowercolor,
    detail_etc = :detail_etc,
    special = :special,
    type_id = :type_id,
    guest_id = :guest_id,
    status = :status,
    reg_date = :reg_date";
    // plost_id = :plost_id,
    // weight = :weight,
    // prepare query
    $stmt = $this->connection->prepare($query);

    // sanitize
    // $this->plost_id=htmlspecialchars(strip_tags($this->plost_id));
    $this->pname=htmlspecialchars(strip_tags($this->pname));
    $this->fname=htmlspecialchars(strip_tags($this->fname));
    $this->lname=htmlspecialchars(strip_tags($this->lname));
    $this->gender=htmlspecialchars(strip_tags($this->gender));
    $this->age=htmlspecialchars(strip_tags($this->age));
    $this->place=htmlspecialchars(strip_tags($this->place));
    $this->subdistrict=htmlspecialchars(strip_tags($this->subdistrict));
    $this->district=htmlspecialchars(strip_tags($this->district));
    $this->city=htmlspecialchars(strip_tags($this->city));
    $this->height=htmlspecialchars(strip_tags($this->height));
    // $this->weight=htmlspecialchars(strip_tags($this->weight));
    $this->shape=htmlspecialchars(strip_tags($this->shape));
    $this->hairtype=htmlspecialchars(strip_tags($this->hairtype));
    $this->haircolor=htmlspecialchars(strip_tags($this->haircolor));
    $this->skintone=htmlspecialchars(strip_tags($this->skintone));
    $this->upperwaist=htmlspecialchars(strip_tags($this->upperwaist));
    $this->uppercolor=htmlspecialchars(strip_tags($this->uppercolor));
    $this->lowerwaist=htmlspecialchars(strip_tags($this->lowerwaist));
    $this->lowercolor=htmlspecialchars(strip_tags($this->lowercolor));
    $this->detail_etc=htmlspecialchars(strip_tags($this->detail_etc));
    $this->special=htmlspecialchars(strip_tags($this->special));
    $this->type_id=htmlspecialchars(strip_tags($this->type_id));
    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
    $this->status=htmlspecialchars(strip_tags($this->status));
    $this->reg_date=htmlspecialchars(strip_tags($this->reg_date));

    // bind values
    // $stmt->bindParam(":plost_id", $this->plost_id);
    $stmt->bindParam(":pname", $this->pname);
    $stmt->bindParam(":fname", $this->fname);
    $stmt->bindParam(":lname", $this->lname);
    $stmt->bindParam(":gender", $this->gender);
    $stmt->bindParam(":age", $this->age);
    $stmt->bindParam(":place", $this->place);
    $stmt->bindParam(":subdistrict", $this->subdistrict);
    $stmt->bindParam(":district", $this->district);
    $stmt->bindParam(":city", $this->city);
    $stmt->bindParam(":height", $this->height);
    // $stmt->bindParam(":weight", $this->weight);
    $stmt->bindParam(":shape", $this->shape);
    $stmt->bindParam(":hairtype", $this->hairtype);
    $stmt->bindParam(":haircolor", $this->haircolor);
    $stmt->bindParam(":skintone", $this->skintone);
    $stmt->bindParam(":upperwaist", $this->upperwaist);
    $stmt->bindParam(":uppercolor", $this->uppercolor);
    $stmt->bindParam(":lowerwaist", $this->lowerwaist);
    $stmt->bindParam(":lowercolor", $this->lowercolor);
    $stmt->bindParam(":detail_etc", $this->detail_etc);
    $stmt->bindParam(":special", $this->special);
    $stmt->bindParam(":type_id",$this->type_id);
    $stmt->bindParam(":guest_id",$this->guest_id);
    $stmt->bindParam(":status", $this->status);
    $stmt->bindParam(":reg_date",$this->reg_date);

    // execute query
    if($stmt->execute()){
      return true;
    }

    return false;
  }

  function getLastID(){
    $query = "SELECT plost_id FROM $this->table_name
    ORDER BY peoplelost.plost_id  DESC";
    $stmt = $this->connection->prepare($query);

    $stmt->execute();
    $num = $stmt->rowCount();

    if($num>0){
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      $this->plost_id = $row['plost_id'];
      return $this->plost_id;
    }
  }

  function emailExists(){

    $query = "SELECT guest_id, guest_name, guest_pass, guest_email
    FROM  $this->table_name
    WHERE guest_email = ?
    LIMIT 0,1";

    // prepare the query
    $stmt = $this->connection->prepare($query);
    $this->guest_email=htmlspecialchars(strip_tags($this->guest_email));
    $stmt->bindParam(1, $this->guest_email);

    $stmt->execute();
    $num = $stmt->rowCount();

    if($num>0){
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      $this->guest_id = $row['guest_id'];
      $this->guest_name = $row['guest_name'];
      $this->guest_email = $row['guest_email'];
      $this->guest_pass = $row['guest_pass'];
      return true;
    }
    return false;
  }

  function update(){

    $query = "UPDATE $this->table_name
    SET pname = :pname,
    fname = :fname,
    lname = :lname,
    gender = :gender,
    age = :age,
    place = :place,
    subdistrict = :subdistrict,
    district = :district,
    city = :city,
    height = :height,

    shape = :shape,
    hairtype = :hairtype,
    haircolor = :haircolor,
    skintone = :skintone,
    upperwaist = :upperwaist,
    uppercolor = :uppercolor,
    lowerwaist = :lowerwaist,
    lowercolor = :lowercolor,
    detail_etc = :detail_etc,
    special = :special,
    type_id = :type_id,
    guest_id = :guest_id,
    status = :status,
    reg_date = :reg_date
    WHERE
    plost_id = :plost_id";

    $stmt = $this->connection->prepare($query);

    // sanitize
    $this->plost_id=htmlspecialchars(strip_tags($this->plost_id));
    $this->pname=htmlspecialchars(strip_tags($this->pname));
    $this->fname=htmlspecialchars(strip_tags($this->fname));
    $this->lname=htmlspecialchars(strip_tags($this->lname));
    $this->gender=htmlspecialchars(strip_tags($this->gender));
    $this->age=htmlspecialchars(strip_tags($this->age));
    $this->place=htmlspecialchars(strip_tags($this->place));
    $this->subdistrict=htmlspecialchars(strip_tags($this->subdistrict));
    $this->district=htmlspecialchars(strip_tags($this->district));
    $this->city=htmlspecialchars(strip_tags($this->city));
    $this->height=htmlspecialchars(strip_tags($this->height));
    // $this->weight=htmlspecialchars(strip_tags($this->weight));
    $this->shape=htmlspecialchars(strip_tags($this->shape));
    $this->hairtype=htmlspecialchars(strip_tags($this->hairtype));
    $this->haircolor=htmlspecialchars(strip_tags($this->haircolor));
    $this->skintone=htmlspecialchars(strip_tags($this->skintone));
    $this->upperwaist=htmlspecialchars(strip_tags($this->upperwaist));
    $this->uppercolor=htmlspecialchars(strip_tags($this->uppercolor));
    $this->lowerwaist=htmlspecialchars(strip_tags($this->lowerwaist));
    $this->lowercolor=htmlspecialchars(strip_tags($this->lowercolor));
    $this->detail_etc=htmlspecialchars(strip_tags($this->detail_etc));
    $this->special=htmlspecialchars(strip_tags($this->special));
    $this->type_id=htmlspecialchars(strip_tags($this->type_id));
    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));
    $this->status=htmlspecialchars(strip_tags($this->status));
    $this->reg_date=htmlspecialchars(strip_tags($this->reg_date));

    // bind values
    $stmt->bindParam(":plost_id", $this->plost_id);
    $stmt->bindParam(":pname", $this->pname);
    $stmt->bindParam(":fname", $this->fname);
    $stmt->bindParam(":lname", $this->lname);
    $stmt->bindParam(":gender", $this->gender);
    $stmt->bindParam(":age", $this->age);
    $stmt->bindParam(":place", $this->place);
    $stmt->bindParam(":subdistrict", $this->subdistrict);
    $stmt->bindParam(":district", $this->district);
    $stmt->bindParam(":city", $this->city);
    $stmt->bindParam(":height", $this->height);
    // $stmt->bindParam(":weight", $this->weight);
    $stmt->bindParam(":shape", $this->shape);
    $stmt->bindParam(":hairtype", $this->hairtype);
    $stmt->bindParam(":haircolor", $this->haircolor);
    $stmt->bindParam(":skintone", $this->skintone);
    $stmt->bindParam(":upperwaist", $this->upperwaist);
    $stmt->bindParam(":uppercolor", $this->uppercolor);
    $stmt->bindParam(":lowerwaist", $this->lowerwaist);
    $stmt->bindParam(":lowercolor", $this->lowercolor);
    $stmt->bindParam(":detail_etc", $this->detail_etc);
    $stmt->bindParam(":special", $this->special);
    $stmt->bindParam(":type_id",$this->type_id);
    $stmt->bindParam(":guest_id",$this->guest_id);
    $stmt->bindParam(":status", $this->status);
    $stmt->bindParam(":reg_date",$this->reg_date);

    // execute query
    if($stmt->execute()){
      return true;
    }

    return false;
  }

  // guest_id
  function guest_id(){
    $query = "SELECT * FROM $this->table_name WHERE guest_id = :guest_id ORDER BY peoplelost . reg_date ASC";
    // prepare query statement
    $stmt = $this->connection->prepare($query);
    // sanitize
    $this->guest_id=htmlspecialchars(strip_tags($this->guest_id));

    $stmt->bindParam(":guest_id", $this->guest_id);
    $stmt->execute();
    return $stmt;
  }

  function textDetail($detail_id){
    $query = "SELECT detail_name FROM details WHERE detail_id = ?";
    // prepare query statement
    $stmt = $this->connection->prepare($query);
    // sanitize
    $detail_id = htmlspecialchars(strip_tags($detail_id));

    $stmt->bindParam(1, $detail_id);
    $stmt->execute();

    $num = $stmt->rowCount();

    if($num>0){
      $row = $stmt->fetch(PDO::FETCH_ASSOC);

      $detail_name = $row['detail_name'];
      return $detail_name;
    }
    return $detail_id;
  }

  function search($guest_id){
    $queryFeedback = "SELECT DISTINCT feedback.id FROM feedback WHERE feedback.guest_id = '$guest_id'";
    $stmtFeedback = $this->connection->prepare($queryFeedback);
    $stmtFeedback->execute();

    $arrayName = array();
    while ($rowFeedback = $stmtFeedback->fetch(PDO::FETCH_ASSOC))
    {
      $arrayName[$rowFeedback["id"]] = $rowFeedback["id"];
      // array_push($arrayName, $rowFeedback["id"] => 1);
    }
    // array_unique($arrayName);
    $this->feedback_array = $arrayName;

    $query = "SELECT * FROM $this->table_name
    WHERE IF(gender = :gender AND status = :status, 1, 0)";

    $query_name = "SELECT * FROM $this->table_name WHERE IF(fname LIKE :fname OR lname LIKE :lname, 1, 0)";
    $stmt_name = $this->connection->prepare($query_name);

    $query_city = "SELECT * FROM $this->table_name WHERE city = :city";
    $stmt_city = $this->connection->prepare($query_city);

    // prepare query statement

    //SELECT * FROM peoplelost WHERE IF(gender = 'F' AND status = "0" , IF(fname LIKE '' OR lname LIKE '' ,1,1) ,0)
    //SELECT * FROM peoplelost LEFT JOIN feedback ON peoplelost.plost_id = feedback.id WHERE feedback.guest_id = '1'
    $stmt = $this->connection->prepare($query);
    // sanitize
    $this->fname=htmlspecialchars(strip_tags($this->fname));
    $this->lname=htmlspecialchars(strip_tags($this->lname));
    $this->gender=htmlspecialchars(strip_tags($this->gender));
    $this->city=htmlspecialchars(strip_tags($this->city));
    $this->height=htmlspecialchars(strip_tags($this->height));
    $this->shape=htmlspecialchars(strip_tags($this->shape));
    $this->hairtype=htmlspecialchars(strip_tags($this->hairtype));
    $this->haircolor=htmlspecialchars(strip_tags($this->haircolor));
    $this->skintone=htmlspecialchars(strip_tags($this->skintone));
    $this->type_id=htmlspecialchars(strip_tags($this->type_id));
    $this->status=htmlspecialchars(strip_tags($this->status));

    if (!empty($this->fname)) {
      $this->fname = "%{$this->fname}%";
    }else {
      $this->fname = "-";
    }
    if (!empty($this->lname)) {
      $this->lname = "%{$this->lname}%";
    }else {
      $this->lname = "-";
    }

    $stmt_name->bindParam(":fname", $this->fname);
    $stmt_name->bindParam(":lname", $this->lname);
    $stmt_name->execute();

    $arrayName2 = array();
    while ($rowPlost = $stmt_name->fetch(PDO::FETCH_ASSOC))
    {
      $arrayName2[$rowPlost["plost_id"]] = $rowPlost["plost_id"];
    }
    $this->name_array = $arrayName2;

    $stmt_city->bindParam(":city", $this->city);
    $stmt_city->execute();

    $arrayName3 = array();
    while ($rowCity = $stmt_city->fetch(PDO::FETCH_ASSOC))
    {
      $arrayName3[$rowCity["plost_id"]] = $rowCity["plost_id"];
    }
    $this->city_array = $arrayName3;

    // $stmt->bindParam(":fname", $this->fname);
    // $stmt->bindParam(":lname", $this->lname);
    $stmt->bindParam(":gender", $this->gender);
    $stmt->bindParam(":status", $this->status);

    $stmt->execute();
    return $stmt;
  }

  public function searchIR($query, $stmt){
    $this->preProcess($stmt);
    $this->calIDF();
    $this->calQuery($query);
    // $this->calIR();
    return $this->calIR();
  }

  function preProcess($stmt){
    $segment = new Segment();

    $this->count_doc = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $temp = $row["plost_id"];
      // filter before calculate
      if (!isset($this->feedback_array[$temp])) {
        // feedback not show again [Filter]
        $this->feedback_array[$temp] = null;
        if ($this->feedback_array[$temp] != $temp) {
          $this->count_doc += 1; // N doc
          $str =
          $row["fname"]." ".$row["lname"]." ".
          $row["detail_etc"]." ".$row["special"]." ".$row["age"]." ".
          // place
          $row["city"]." ".
          $row["district"]." ".$row["subdistrict"]." ".$row["place"]." ".

          $row["height"]." ".$row["skintone"]." ".$row["shape"]." ".
          $row["hairtype"]." ".$row["haircolor"]." ".
          $row["upperwaist"]." ".$row["uppercolor"]." ".
          $row["lowerwaist"]." ".$row["lowercolor"];
          // if ($row["city"] != "-") {
          //   $str = $str. " #" .$row["city"]. "# ";
          //   if ($row["district"] != "-") {
          //     $str = $str. " #" .$row["district"]. "# ";
          //     if ($row["subdistrict"] != "-") {
          //       $str = $str. " #" .$row["subdistrict"]. "# ";
          //
          //     }
          //   }
          // }
          array_push($this->n_db, $row["plost_id"]);
          array_push($this->doc, $str); // detail (doc)
          $this->word_all = $segment-> get_segment_array($str); //word
        }
      }
    }
    $this->word_unique = array_keys(array_count_values($this->word_all)); // word unique
    // stop word
    $diff = array_diff($this->word_unique,["กก.","ซม.", "น้ำหนัก", "ประมาณ", "บริเวณ","ลักษณะ"]);
    $this->word_unique = array_values($diff);
    $this->count_word_all = count($this->word_unique); // count word unique

    return $this->doc;

  }


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
        //error_reporting(error_reporting() & ~E_NOTICE); // hide error nextime pls come to fix it
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
      return $count_n;
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
      $query = "SELECT * FROM invert";
      $stmt = $this->connection->prepare($query);
      $stmt-> execute();

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
              $sim_value += ($this->cut_query[$key]*0.077)*($weight/$lengh[$freq_key]);
            }
          } else {
            $this->cut_query[$key] = 0;
          }
        }
        $x = $this->n_db[$freq_key];
        $sim[$x] = $sim_value;
      }

      $missing_arr=array();
      $missing_arr["body"]=array();

      arsort($sim); // sort doc

      // from IR sort by similar
      foreach ($sim as $key => $value) {
        $key_plus = $key;
        $num = count($missing_arr["body"]);

        if ($value >= 0 && $num < 11) {
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
              "shape"=> $this->textDetail($shape),
              "hairtype"=> $this->textDetail($hairtype),
              "haircolor"=> $haircolor,
              "skintone"=> $skintone,
              "upperwaist"=> $this->textDetail($upperwaist),
              "uppercolor"=> $uppercolor,
              "lowerwaist"=> $this->textDetail($lowerwaist),
              "lowercolor"=> $lowercolor,
              "detail_etc"=> $detail_etc,
              "special"=> $special,
              "type_id"=> $type_id,
              "guest_id"=> $guest_id,
              "status"=> $status,
              "reg_date"=> $reg_date,
              "path_img"=> $path_img,
              "sim" => round($sim[$key],5)
            );
            array_push($missing_arr["body"], $missing_item);
            // array_push($sim_result, $row["detail_etc"]); // detail (doc)
          }
        }

      }

      // default value
      return $missing_arr;

      // doc number
      // return array_keys($sim);

      // sim only
      // return array_values($sim);

      // sim unsort
      // return $sim;

      // idf
      // return $this->idf;

      // tf per doc
      // return $this->freq_doc;

      // query
      // return $this->cut_query;
    }

  }

  ?>
