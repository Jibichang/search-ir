<?php

	$con = mysqli_connect('localhost' , 'root' , '','missing_person');
		if (mysqli_connect_errno()){
			echo "1 :Connect failed";
			exit();
		}

    /*if( isset($_POST["json"]) ) {
         $data = json_decode($_POST["json"]);
         $data->msg = strrev($data->msg);
         echo hhhhh;
         //echo json_encode($data);

    }*/

	mysqli_set_charset($con,"utf8");

	$data = json_decode(file_get_contents("php://input"), TRUE);
//	print_r($data);

	$strCountry = "" ;
	// $strSQL = "SELECT * FROM customer WHERE 1 AND CountryCode like '%".$strCountry."%' ";
	$strSQL = "SELECT * FROM plost";
	$objQuery = mysqli_query($con,$strSQL) or die (mysqli_error());

	$intNumField = mysqli_num_fields($objQuery);
	//echo $intNumField;

	$resultArray = array();
	while($obResult = mysqli_fetch_array($objQuery))
	{

		$arrCol = array();

		/*$arrCol["username"] = $obResult["username"];
		$arrCol["score"] = $obResult["score"];
		$arrCol["sumworduse"] = $obResult["sumworduse"];*/

		for($i=0;$i<$intNumField;$i++)
		{

			$arrCol[mysqli_field_name($objQuery,$i)] = $obResult[$i];
//print_r($arrCol);

		}

		/*if($arraysAreEqual = ($data=== $arrCol)){
			print_r("ffff");
		}*/
		array_push($resultArray,$arrCol);

	}
		function mysqli_field_name($result, $field_offset)
	{
	$properties = mysqli_fetch_field_direct($result, $field_offset);
	return is_object($properties) ? $properties->name : null;
}

foreach ($data as $key => $value){

				if(strlen($value)  != 0){

					for($i=0;$i<$intNumField;$i++){
						//if($resultArray[$i][$key] == $value){
							//echo($resultArray[0]["id"]);
							//เข้าแล้วตรงแล้วจ้าาาา 5555
							print_r($resultArray[$i][$key]);

						//}
					}
					print "<br>";

				}

}


echo($key);
  //$a = json_encode($resultArray, JSON_UNESCAPED_UNICODE );
	//print_r($a);
?>
