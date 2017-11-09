<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"]=true;
if($TYPES=="INSERT_customer_value"){
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];
	
	$MASCODE = $FORM_DATA["CODE"];	
	$title_ID = $FORM_DATA["title_ID"];	
	$company_ID = $FORM_DATA["company_ID"];	
	$store_type_ID = $FORM_DATA["store_type_ID"];	
	$CURDATE = $FORM_DATA["CURDATE"];	
	$VALUE = $FORM_DATA["VALUE"];	
	

	if($MASCODE && $user_ID  && $title_ID && $company_ID && $store_type_ID && $VALUE){
		$Query = mysqli_query($DB,
			"SELECT MASCODE,STNA,NAME,SURNAME
			FROM customer
			WHERE MASCODE = '{$MASCODE}'
			");
		$Fetch = mysqli_fetch_assoc($Query);
		if($Fetch){

			mysqli_query($DB,
			"INSERT INTO customer_value(CURDATE,title_ID,company_ID,store_type_ID,MASCODE,VALUE,user_ID,CREATED)
			VALUES ('{$CURDATE}','{$title_ID}','{$company_ID}','{$store_type_ID}','{$MASCODE}','{$VALUE}','{$user_ID}',NOW())				
			");
			$arr["ERROR"] = false;
			$arr["MSG"] = "รับข้อมูล ".$Fetch["STNA"].$Fetch['NAME'].' '.$Fetch["SURNAME"]." สำเร็จ";
			$arr["TYPE"] = "success";
			
		}
		else{
			$arr["ERROR"] = true;
			$arr["MSG"] = "ไม่พบเลขทะเบียนสมาชิก";
			$arr["TYPE"] = "error";
		}
		
	}
	else{
		$arr["ERROR"] = true;
		$arr["MSG"] = "กรุณากรอกข้อมูลให้ครบถ้วน";
		$arr["TYPE"] = "warning";
	}

	echo json_encode($arr);
}
else if($TYPES=="SELECT_customer_value"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];
	$Query = mysqli_query($DB,
		"SELECT customer_value.ID,customer_value.CURDATE,customer_value.MASCODE,customer_value.VALUE,customer_value.user_ID,customer_value.CREATED,title.NAME AS title_NAME,company.NAME_SHORT AS company_NAME,store_type.NAME AS store_type_NAME,CONCAT(COALESCE(customer.STNA,''),COALESCE(customer.NAME,''),' ',COALESCE(customer.SURNAME,'')) AS customer_NAME,CONCAT(COALESCE(user.FNAME,''),' ',COALESCE(user.LNAME,'')) AS user_NAME
		FROM customer_value
		LEFT JOIN customer
		ON customer_value.MASCODE=customer.MASCODE
		LEFT JOIN title
		ON customer_value.title_ID=title.ID
		LEFT JOIN company
		ON customer_value.company_ID=company.ID
		LEFT JOIN store_type
		ON customer_value.store_type_ID=store_type.ID
		LEFT JOIN user
		ON customer_value.user_ID=user.ID
		ORDER BY customer_value.CREATED DESC
		LIMIT 10
		");
	$DATA = array();
	$n=0;
	while($Fetch=mysqli_fetch_assoc($Query)){
		$DATA[] = $Fetch;		
	$n++;
	}
	$arr["ERROR"] = false;
	$arr["MSG"] = "Get Data success";
	$arr["TYPE"] = "success";
	$arr["DATA"] = $DATA;
	echo json_encode($arr);
}
else if($TYPES=="DELETE_customer_value"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];
	$customer_value_ID = isset($_POST["customer_value_ID"]) ? $_POST["customer_value_ID"] : "";
	$Query = mysqli_query($DB,
		"SELECT customer_value.MASCODE
		FROM customer_value
		WHERE customer_value.ID='{$customer_value_ID}'
		");
	$Fetch=mysqli_fetch_assoc($Query);
	if($Fetch){
		mysqli_query($DB,
			"DELETE FROM customer_value
			WHERE customer_value.ID='{$customer_value_ID}'
		");
		$arr["ERROR"] = false;
		$arr["MSG"] = "ลบรายการสำเร็จ";
		$arr["TYPE"] = "success";
	}
	else{
		$arr["ERROR"] = true;
		$arr["MSG"] = "ไม่พบรายการที่เลือกหรืออาจถูกลบไปแล้ว";
		$arr["TYPE"] = "error";
	}
	echo json_encode($arr);
}
else if($TYPES=="SELECT_customer"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$CUS_CODE = isset($_POST["CUS_CODE"]) ? $_POST["CUS_CODE"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$Query = mysqli_query($DB,
		"SELECT customer.STNA,customer.NAME AS CUS_FNAME,customer.SURNAME AS CUS_LNAME,customer.GENCODE,customer.AUMNO
		FROM customer
		WHERE customer.MASCODE = '{$CUS_CODE}'
		");
	
	$Fetch = mysqli_fetch_assoc($Query);
	if($Fetch){
		$DATA = $Fetch;
	
		$arr["ERROR"] = false;
		$arr["MSG"] = "Get Data success";
		$arr["TYPE"] = "success";
		$arr["DATA"] = $DATA;
	}
	else{
		$arr["ERROR"] = true;
		$arr["MSG"] = "ไม่พบเลขสมาชิกนี้";
		$arr["TYPE"] = "error";
	}
	

	echo json_encode($arr);
}
else if($TYPES=="SELECT_company"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];

	$Query = mysqli_query($DB,
		"SELECT company.*
		FROM company
		ORDER BY company.ID ASC
		");
	$DATA = [];
	while($Fetch=mysqli_fetch_assoc($Query)){
		$DATA[]=$Fetch;
	}
	$arr["ERROR"] = false;
	$arr["MSG"] = "Get Data success";
	$arr["TYPE"] = "success";
	$arr["DATA"] = $DATA;
	echo json_encode($arr);
}
else if($TYPES=="SELECT_store_type"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];

	$Query = mysqli_query($DB,
		"SELECT store_type.*
		FROM store_type
		ORDER BY store_type.ID ASC
		");
	$DATA = [];
	while($Fetch=mysqli_fetch_assoc($Query)){
		$DATA[]=$Fetch;
	}
	$arr["ERROR"] = false;
	$arr["MSG"] = "Get Data success";
	$arr["TYPE"] = "success";
	$arr["DATA"] = $DATA;
	echo json_encode($arr);
}
else if($TYPES=="SELECT_title"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];

	try{
		$Query = mysqli_query($DB,
			"SELECT title.*
			FROM title
			ORDER BY ID DESC
			");
		$DATA = [];
		while($Fetch = mysqli_fetch_assoc($Query)){
			$DATA[] = $Fetch;
		}	
	
		$arr["ERROR"] = false;
		$arr["MSG"] = "Get Data success";
		$arr["TYPE"] = "success";
		$arr["DATA"] = $DATA;
	}
	catch(Exception $ex){
		$arr["ERROR"] = true;
		$arr["MSG"] = "ไม่พบหัวข้อนี้";
		$arr["TYPE"] = "error";
	}
	echo json_encode($arr);
}
else{
	$arr["ERROR"] = true;
	$arr["MSG"] = "Cannot Show Database!";
	$arr["TYPE"] = "error";
	echo json_encode($arr);
}

 ?>