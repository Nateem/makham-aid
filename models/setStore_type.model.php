<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"]=true;
if($TYPES=="SELECT_store_type"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];
	$Query = mysqli_query($DB,
		"SELECT store_type.*
		FROM store_type
		ORDER BY store_type.NAME ASC
		");
	$DATA = array();
	$n=0;
	while($Fetch=mysqli_fetch_assoc($Query)){
		$DATA[] = $Fetch;		
		$COLOR = $Fetch["COLOR"];
		$DATA[$n]+=[
			"COLOR_TXT"=> "<i class=\"fa fa-square\" aria-hidden=\"true\" style=\"color:{$COLOR}\"></i> ".$COLOR
		];
	$n++;
	}
	$arr["ERROR"] = false;
	$arr["MSG"] = "Get Data success";
	$arr["TYPE"] = "success";
	$arr["DATA"] = $DATA;
	echo json_encode($arr);
}
else if($TYPES=="SELECT_store_type_where"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$store_type_ID = isset($_POST["store_type_ID"]) ? $_POST["store_type_ID"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$Query = mysqli_query($DB,
		"SELECT store_type.*
		FROM store_type					
		WHERE store_type.ID = '{$store_type_ID}'
		");
	
	$Fetch = mysqli_fetch_assoc($Query);
	$DATA = $Fetch;
	
	$arr["ERROR"] = false;
	$arr["MSG"] = "Get Data success";
	$arr["TYPE"] = "success";
	$arr["DATA"] = $DATA;

	echo json_encode($arr);
}
else if($TYPES=="INSERT_store_type"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];


	$NAME = $FORM_DATA["NAME"];	
	$COLOR = isset($FORM_DATA["COLOR"]) ? $FORM_DATA["COLOR"] : "";	

	if($NAME){
		try {
			mysqli_query($DB,
				"INSERT INTO store_type(NAME,COLOR)
				VALUES ('{$NAME}','{$COLOR}')
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
else if($TYPES=="UPDATE_store_type"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$ID = $FORM_DATA["ID"];
	$NAME = $FORM_DATA["NAME"];	
	$COLOR = isset($FORM_DATA["COLOR"]) ? $FORM_DATA["COLOR"] : "";		
	

	if($NAME && $ID){

		$Query2 = mysqli_query($DB,
			"SELECT store_type.ID
			FROM store_type
			WHERE store_type.ID='{$ID}'
			");
		$Fetch2=mysqli_fetch_assoc($Query2);
		if($Fetch2){
			try {
				mysqli_query($DB,
					"UPDATE store_type SET NAME='{$NAME}',COLOR='{$COLOR}'
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
else if($TYPES=="DELETE_store_type"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];
	$ID = isset($FORM_DATA["ID"]) ? $FORM_DATA["ID"] : null;
	if($ID){
		$Query = mysqli_query($DB,
				"SELECT NAME
				FROM store_type
				WHERE ID='{$ID}'
				");
			$Fetch=mysqli_fetch_assoc($Query);
			if($Fetch){
				try {
					mysqli_query($DB,
						"DELETE FROM store_type
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