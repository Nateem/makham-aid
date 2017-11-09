<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"]=true;
if($TYPES=="SELECT_reportList_customer_value"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$DateStart = $FORM_DATA["DateStart"];
	$DateEnd = $FORM_DATA["DateEnd"];
	$CODE = $FORM_DATA["CODE"];

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

	if($DateStart && $DateEnd && $CODE){
		$Query2 = mysqli_query($DB,
			"SELECT CONCAT(COALESCE(customer.MASCODE,''),'  ',COALESCE(customer.STNA,''),COALESCE(customer.NAME,''),' ',COALESCE(customer.SURNAME,'')) AS customer_NAME
			FROM customer
			WHERE customer.MASCODE='{$CODE}'
			");
		$Fetch2 = mysqli_fetch_assoc($Query2);
		if($Fetch2){
			$customer_NAME=$Fetch2["customer_NAME"];

			$Query = mysqli_query($DB,
				"SELECT customer_value.CREATED,customer_value.VALUE,company.NAME_FULL AS company_NAME,store_type.NAME AS store_type_NAME,CONCAT(COALESCE(user.FNAME,''),' ',COALESCE(user.LNAME,'')) AS user_NAME
				FROM customer_value				
				LEFT JOIN company
				ON customer_value.company_ID=company.ID
				LEFT JOIN store_type
				ON customer_value.store_type_ID=store_type.ID
				LEFT JOIN user
				ON customer_value.user_ID=user.ID
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
				AND customer_value.MASCODE='{$CODE}'				
				$AND				
				ORDER BY customer_value.CREATED ASC
				");
			$DATA = array();
			$SUM_TOTAL = 0;
			while($Fetch=mysqli_fetch_assoc($Query)){
				$DATA[] = $Fetch;
				$SUM_TOTAL+=$Fetch["VALUE"];
			}
			

			$arr["ERROR"] = false;
			$arr["MSG"] = $customer_NAME;
			$arr["TYPE"] = "success";
			$arr["DATA"] = $DATA;
			$arr["SUM_TOTAL"] = $SUM_TOTAL;
			$arr["title_NAME"] = $title_NAME;
			$arr["customer_NAME"] = $customer_NAME;
		}
		else{
			$arr["ERROR"] = true;
			$arr["MSG"] = $CODE." : ไม่พบรหัสสมาชิกนี้ !";
			$arr["TYPE"] = "error";
		}
		
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
