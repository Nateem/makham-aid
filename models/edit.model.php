<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"]=true;
if($TYPES=="SELECT_customer_value"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];
	$Query = mysqli_query($DB,
		"SELECT customer_value.ID,customer_value.CURDATE,customer_value.MASCODE,customer_value.VALUE,customer_value.user_ID,customer_value.CREATED,title.NAME AS title_NAME,company.NAME_FULL AS company_NAME,store_type.NAME AS store_type_NAME,CONCAT(COALESCE(customer.STNA,''),COALESCE(customer.NAME,''),' ',COALESCE(customer.SURNAME,'')) AS customer_NAME,CONCAT(COALESCE(user.FNAME,''),' ',COALESCE(user.LNAME,'')) AS user_NAME
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
else if($TYPES=="SELECT_customer_value_where"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$customer_value_ID = isset($_POST["customer_value_ID"]) ? $_POST["customer_value_ID"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$Query = mysqli_query($DB,
		"SELECT customer_value.*,CONCAT(COALESCE(customer.STNA,''),COALESCE(customer.NAME,''),' ',COALESCE(customer.SURNAME,'')) AS customer_NAME
		FROM customer_value			
		LEFT JOIN customer
		ON customer_value.MASCODE=customer.MASCODE
		WHERE customer_value.ID = '{$customer_value_ID}'
		");
	
	$Fetch = mysqli_fetch_assoc($Query);
	$DATA = $Fetch;
	
	$arr["ERROR"] = false;
	$arr["MSG"] = "Get Data success";
	$arr["TYPE"] = "success";
	$arr["DATA"] = $DATA;

	echo json_encode($arr);
}
else if($TYPES=="SELECT_customer_code"){
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
else if($TYPES=="UPDATE_customer_value"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$ID = $FORM_DATA["ID"];
	$MASCODE = $FORM_DATA["CUS_CODE"];	
	$title_ID = $FORM_DATA["title_ID"];	
	$company_ID = $FORM_DATA["company_ID"];	
	$store_type_ID = $FORM_DATA["store_type_ID"];	
	$CURDATE = $FORM_DATA["CURDATE"];	
	$VALUE = $FORM_DATA["VALUE"];	

	if($MASCODE && $title_ID && $company_ID && $store_type_ID && $CURDATE && $ID){
		$Query = mysqli_query($DB,
			"SELECT customer.MASCODE
			FROM customer
			WHERE customer.MASCODE = '{$MASCODE}'
			");		
		$Fetch = mysqli_fetch_assoc($Query);
		if($Fetch){
			$Query2 = mysqli_query($DB,
				"SELECT customer_value.ID
				FROM customer_value
				WHERE customer_value.ID='{$ID}'
				");
			$Fetch2=mysqli_fetch_assoc($Query2);
			if($Fetch2){
				try {
					mysqli_query($DB,
						"UPDATE customer_value SET MASCODE='{$MASCODE}',CURDATE='{$CURDATE}',title_ID='{$title_ID}',company_ID='{$company_ID}',store_type_ID='{$store_type_ID}',VALUE='{$VALUE}',user_ID='{$user_ID}'
						WHERE ID = '{$ID}'
						");
					$arr["ERROR"] = false;
					$arr["MSG"] = "แก้ไขข้อมูลสำเร็จ..";
					$arr["TYPE"] = "success";
				} catch (Exception $e) {
					$arr["ERROR"] = true;
					$arr["MSG"] = "Mysql Update Error !";
					$arr["TYPE"] = "error";
				}
				
			}
			else{
				$arr["ERROR"] = true;
				$arr["MSG"] = "ข้อมูลไม่ครบถ้วน !";
				$arr["TYPE"] = "warning";
			}			
		}
		else{
			$arr["ERROR"] = true;
			$arr["MSG"] = "ไม่พบเลขสมาชิกนี้";
			$arr["TYPE"] = "error";
		}

	}
	else{
		$arr["ERROR"] = true;
		$arr["MSG"] = "กรอกข้อมูลให้ครบถ้วน!";
		$arr["TYPE"] = "error";
	}
	echo json_encode($arr);
}
else if($TYPES=="DELETE_customer_value"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];
	$ID = isset($FORM_DATA["ID"]) ? $FORM_DATA["ID"] : null;
	if($ID){
		$Query = mysqli_query($DB,
				"SELECT MASCODE
				FROM customer_value
				WHERE ID='{$ID}'
				");
			$Fetch=mysqli_fetch_assoc($Query);
			if($Fetch){
				try {
					mysqli_query($DB,
						"DELETE FROM customer_value
						WHERE ID = '{$ID}'
						");
					$arr["ERROR"] = false;
					$arr["MSG"] = "ลบข้อมูลสำเร็จ..";
					$arr["TYPE"] = "success";
				} catch (Exception $e) {
					$arr["ERROR"] = true;
					$arr["MSG"] = "Mysql Delete Error !";
					$arr["TYPE"] = "error";
				}
				
			}
			else{
				$arr["ERROR"] = true;
				$arr["MSG"] = "ไม่พบข้อมูลที่ต้องการลบ !";
				$arr["TYPE"] = "warning";
			}	
	}
	else{
		$arr["ERROR"] = true;
		$arr["MSG"] = "Error not data!";
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