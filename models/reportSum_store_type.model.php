<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"]=true;
if($TYPES=="SELECT_reportSum_store_type"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$DateStart = $FORM_DATA["DateStart"];
	$DateEnd = $FORM_DATA["DateEnd"];

	$title_ID = isset($FORM_DATA["title_ID"]) ? $FORM_DATA["title_ID"] : "";	
	
	$AND = '';
	$title_NAME='';
	if($title_ID){
		$AND .= " AND customer_value.title_ID='{$title_ID}' ";
		$QueryTitle = mysqli_query($DB,
			"SELECT title.NAME AS title_NAME
			FROM title
			WHERE title.ID='{$title_ID}'
			");
		$FetchTitle = mysqli_fetch_assoc($QueryTitle);
		$title_NAME = $FetchTitle["title_NAME"];
	}

	if($DateStart && $DateEnd){
		
			$Query = mysqli_query($DB,
				"SELECT DISTINCT customer_value.store_type_ID,SUM(customer_value.VALUE) AS SUM_VALUE,store_type.NAME
				FROM customer_value
				LEFT JOIN store_type
				ON customer_value.store_type_ID=store_type.ID
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))				
				$AND
				GROUP BY customer_value.store_type_ID
				ORDER BY store_type.NAME ASC
				");
			$DATA = array();
			$SUM_TOTAL = 0;
			while($Fetch=mysqli_fetch_assoc($Query)){
				$DATA[] = $Fetch;
				$SUM_TOTAL+=$Fetch["SUM_VALUE"];
			}
			$arr["ERROR"] = false;
			$arr["MSG"] = "Get Data success";
			$arr["TYPE"] = "success";
			$arr["DATA"] = $DATA;
			$arr["SUM_TOTAL"] = $SUM_TOTAL;
			$arr["title_NAME"] = $title_NAME;
		
	}
	else{
		$arr["ERROR"] = true;
		$arr["MSG"] = "ข้อมูลไม่ครบถ้วน !";
		$arr["TYPE"] = "warning";
	}
	echo json_encode($arr);
}
else{
	$arr["ERROR"] = true;
	$arr["MSG"] = "Cannot Find to database!";
	$arr["TYPE"] = "error";
}
?>
