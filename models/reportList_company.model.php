<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"]=true;
if($TYPES=="SELECT_reportList_company"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$DateStart = $FORM_DATA["DateStart"];
	$DateEnd = $FORM_DATA["DateEnd"];
	$company_ID = $FORM_DATA["company_ID"];

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
	if($company_ID){		
		$Querycompany = mysqli_query($DB,
			"SELECT company.NAME_FULL AS company_NAME
			FROM company
			WHERE company.ID='{$company_ID}'
			");
		$Fetchcompany = mysqli_fetch_assoc($Querycompany);
		$company_NAME = $Fetchcompany["company_NAME"];
	}

	if($DateStart && $DateEnd && $company_ID){
		$Query2 = mysqli_query($DB,
			"SELECT customer_value.*,CONCAT(COALESCE(customer.MASCODE,''),'  ',COALESCE(customer.STNA,''),COALESCE(customer.NAME,''),' ',COALESCE(customer.SURNAME,'')) AS customer_NAME,store_type.NAME AS store_type_NAME,user.FNAME AS user_NAME
			FROM customer_value
			LEFT JOIN customer
			ON customer_value.MASCODE=customer.MASCODE
			LEFT JOIN store_type
			ON customer_value.store_type_ID=store_type.ID
			LEFT JOIN user
			ON customer_value.user_ID=user.ID
			WHERE (customer_value.CURDATE 
			BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
			AND customer_value.company_ID='{$company_ID}'
			$AND
			ORDER BY customer_value.MASCODE ASC
			");
			$DATA = [];
			$SUM_TOTAL = 0;
			while ($Fetch2 = mysqli_fetch_assoc($Query2)) {
				$DATA[]=$Fetch2;
				$SUM_TOTAL+=$Fetch2["VALUE"];
			}
			$arr["ERROR"] = false;
			$arr["MSG"] = $company_NAME;
			$arr["TYPE"] = "success";
			$arr["DATA"] = $DATA;
			$arr["SUM_TOTAL"] = $SUM_TOTAL;
			$arr["title_NAME"] = $title_NAME;
			$arr["company_NAME"] = $company_NAME;		
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
