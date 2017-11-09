<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"]=true;
if($TYPES=="SELECT_user_where"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];

	$Query = mysqli_query($DB,
		"SELECT user.PIC,CONCAT(COALESCE(prefix.PREFIX,''),COALESCE(user.FNAME,''),' ',COALESCE(user.LNAME,'')) AS user_NAME,user.OFFICE,user.TEL,user.EDITED,user.CREATED
		FROM user		
		LEFT JOIN prefix
		ON user.PREFIX_ID=prefix.PREFIX_ID			
		WHERE user.ID = '{$user_ID}'
		");
	
	$Fetch = mysqli_fetch_assoc($Query);
	$DATA = $Fetch;
	
	$arr["ERROR"] = false;
	$arr["MSG"] = "Get Data success";
	$arr["TYPE"] = "success";
	$arr["DATA"] = $DATA;

	echo json_encode($arr);
}
else{
	$arr["ERROR"] = true;
	$arr["MSG"] = "Cannot Show Database!";
	$arr["TYPE"] = "error";
	echo json_encode($arr);
}

?>