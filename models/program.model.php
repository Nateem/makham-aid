<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"]=true;
if($TYPES=="UPDATE_program"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];
	$DATERANG = $FORM_DATA["DATERANG"];
	$ID = $FORM_DATA["ID"];
	$TITLE = $FORM_DATA["TITLE"];
	$DETAIL = $FORM_DATA["DETAIL"];

	$DATE_START = $DATERANG["startDate"];
	$DATE_END = $DATERANG["endDate"];

	mysqli_query($DB,
		"UPDATE program
		SET TITLE='{$TITLE}',DETAIL='{$DETAIL}',DATE_START='{$DATE_START}',DATE_END='{$DATE_END}',user_ID_EDIT='{$user_ID}',EDITED=NOW()
		WHERE ID='{$ID}'
		");

	$arr["ERROR"] = false;
	$arr["MSG"] = "แก้ไขกำหนดการสำเร็จ";
	$arr["TYPE"] = "success";
	echo json_encode($arr);
}
else if($TYPES=="INSERT_program"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];

	$DATERANG = $FORM_DATA["DATERANG"];	
	$TITLE = $FORM_DATA["TITLE"];
	$DETAIL = $FORM_DATA["DETAIL"];

	$DATE_START = $DATERANG["startDate"];
	$DATE_END = $DATERANG["endDate"];

	mysqli_query($DB,
		"INSERT INTO program(TITLE,DETAIL,DATE_START,DATE_END,user_ID_CREATE,CREATED)
		VALUES ('{$TITLE}','{$DETAIL}','{$DATE_START}','{$DATE_END}','{$user_ID}',NOW())
		");

	$arr["ERROR"] = false;
	$arr["MSG"] = "เพิ่มกำหนดการสำเร็จ";
	$arr["TYPE"] = "success";
	echo json_encode($arr);
}
else if($TYPES=="SELECT_program"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];	

	$Query = mysqli_query($DB,
		"SELECT program.*
		FROM program
		ORDER BY program.DATE_END DESC		
		");
	$DATA = [];
	while ($Fetch=mysqli_fetch_assoc($Query)) {
		$DATA[]=$Fetch;
	}

	$arr["ERROR"] = false;
	$arr["MSG"] = "select success";
	$arr["TYPE"] = "success";
	$arr['DATA'] = $DATA;
	echo json_encode($arr);
}
else if($TYPES=="SELECT_program_WHERE"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";	
	$ID = isset($_POST["ID"]) ? $_POST["ID"] : "";	
	$user_ID = $CURRENT_DATA["ID"];	

	$Query = mysqli_query($DB,
		"SELECT program.*
		FROM program
		WHERE program.ID='{$ID}'	
		");
	
	$Fetch=mysqli_fetch_assoc($Query);

	$arr["ERROR"] = false;
	$arr["MSG"] = "select success";
	$arr["TYPE"] = "success";
	$arr['DATA'] = $Fetch;
	echo json_encode($arr);
}
else if($TYPES=="DELETE_program"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";	
	$ID = isset($_POST["ID"]) ? $_POST["ID"] : "";	
	$user_ID = $CURRENT_DATA["ID"];	
	if($ID && $user_ID){
		$Query = mysqli_query($DB,
			"SELECT program.*
			FROM program
			WHERE program.ID='{$ID}'	
			");
		
		$Fetch=mysqli_fetch_assoc($Query);
		if($Fetch){
			mysqli_query($DB,
				"DELETE FROM program
				WHERE ID='{$ID}'	
				");
			$arr["ERROR"] = false;
			$arr["MSG"] = "ลบกำหนดการสำเร็จ";
			$arr["TYPE"] = "success";
		}
		else{
			$arr["ERROR"] = true;
			$arr["MSG"] = "ไม่พบรายการที่เลือก";
			$arr["TYPE"] = "warning";
		}
		
	}
	else{
		$arr["ERROR"] = true;
		$arr["MSG"] = "Delete Error";
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