<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"]=true;
if($TYPES=="SELECT_company"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];
	$Query = mysqli_query($DB,
		"SELECT company.*
		FROM company
		ORDER BY company.NAME_FULL ASC
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
else if($TYPES=="SELECT_company_where"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$company_ID = isset($_POST["company_ID"]) ? $_POST["company_ID"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$Query = mysqli_query($DB,
		"SELECT company.*
		FROM company					
		WHERE company.ID = '{$company_ID}'
		");
	
	$Fetch = mysqli_fetch_assoc($Query);
	$DATA = $Fetch;
	
	$arr["ERROR"] = false;
	$arr["MSG"] = "Get Data success";
	$arr["TYPE"] = "success";
	$arr["DATA"] = $DATA;

	echo json_encode($arr);
}
else if($TYPES=="INSERT_company"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];


	$NAME_FULL = $FORM_DATA["NAME_FULL"];	
	$NAME_SHORT = $FORM_DATA["NAME_SHORT"];	
	$ADDSS = $FORM_DATA["ADDSS"];	
	$TEL = $FORM_DATA["TEL"];		

	if($NAME_FULL && $NAME_SHORT && $ADDSS && $TEL){
		try {
			mysqli_query($DB,
				"INSERT INTO company(NAME_FULL,NAME_SHORT,ADDSS,TEL)
				VALUES ('{$NAME_FULL}','{$NAME_SHORT}','{$ADDSS}','{$TEL}')
				");
			$arr["ERROR"] = false;
			$arr["MSG"] = "เพิ่มข้อมูลสำเร็จ..";
			$arr["TYPE"] = "success";
		} catch (Exception $e) {
			$arr["ERROR"] = true;
			$arr["MSG"] = "Mysql Error !";
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
else if($TYPES=="UPDATE_company"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$ID = $FORM_DATA["ID"];
	$NAME_FULL = $FORM_DATA["NAME_FULL"];	
	$NAME_SHORT = $FORM_DATA["NAME_SHORT"];	
	$ADDSS = $FORM_DATA["ADDSS"];	
	$TEL = $FORM_DATA["TEL"];		

	if($NAME_FULL && $NAME_SHORT && $ADDSS && $TEL){

		$Query2 = mysqli_query($DB,
			"SELECT company.ID
			FROM company
			WHERE company.ID='{$ID}'
			");
		$Fetch2=mysqli_fetch_assoc($Query2);
		if($Fetch2){
			try {
				mysqli_query($DB,
					"UPDATE company SET NAME_FULL='{$NAME_FULL}',NAME_SHORT='{$NAME_SHORT}',ADDSS='{$ADDSS}',TEL='{$TEL}'
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
		$arr["MSG"] = "กรอกข้อมูลให้ครบถ้วน!";
		$arr["TYPE"] = "error";
	}
	echo json_encode($arr);
}
else if($TYPES=="DELETE_company"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];
	$ID = isset($FORM_DATA["ID"]) ? $FORM_DATA["ID"] : null;
	if($ID){
		$Query = mysqli_query($DB,
				"SELECT NAME_FULL
				FROM company
				WHERE ID='{$ID}'
				");
			$Fetch=mysqli_fetch_assoc($Query);
			if($Fetch){
				try {
					mysqli_query($DB,
						"DELETE FROM company
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