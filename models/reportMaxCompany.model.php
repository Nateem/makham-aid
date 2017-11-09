<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"]=true;
if($TYPES=="SELECT_reportMaxCompany"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$DateStart = $FORM_DATA["DateStart"];
	$DateEnd = $FORM_DATA["DateEnd"];

	$title_ID = isset($FORM_DATA["title_ID"]) ? $FORM_DATA["title_ID"] : "";	
	$TOP_NUM = isset($FORM_DATA["TOP_NUM"]) ? $FORM_DATA["TOP_NUM"] : "";	
	
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
			//----------------------start sum ---------------------------
			$Query = mysqli_query($DB,
				"SELECT DISTINCT customer_value.store_type_ID,store_type.NAME
				FROM customer_value
				LEFT JOIN store_type
				ON customer_value.store_type_ID=store_type.ID
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))				
				$AND				
				ORDER BY store_type.NAME ASC
				");
			$DATA = array();
			$n=0;
			while($Fetch=mysqli_fetch_assoc($Query)){
				$DATA[] = $Fetch;
				$store_type_ID = $Fetch["store_type_ID"];
				$Query2 = mysqli_query($DB,
					"SELECT SUM(customer_value.VALUE) AS MAX_SUM_VALUE,company.NAME_FULL AS company_NAME
					FROM customer_value
					LEFT JOIN company
					ON customer_value.company_ID=company.ID
					WHERE (customer_value.CURDATE 
					BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))	
					AND customer_value.store_type_ID='{$store_type_ID}'			
					$AND
					GROUP BY customer_value.company_ID,customer_value.store_type_ID
					ORDER BY MAX_SUM_VALUE DESC
					LIMIT {$TOP_NUM}
					");
				$DATA1[$n]=[];
				while ($Fetch2=mysqli_fetch_assoc($Query2)) {
					$DATA1[$n][]=$Fetch2;
				}
				$DATA[$n]+=[
					"DATA"=>$DATA1[$n]
				];
			$n++;
			}
			
			//---------------------end sum----------------------------
			$Query3 = mysqli_query($DB,
				"SELECT SUM(customer_value.VALUE) AS MAX_SUM_VALUE,company.NAME_FULL AS company_NAME
				FROM customer_value
				LEFT JOIN company
				ON customer_value.company_ID=company.ID
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))		
				$AND
				GROUP BY customer_value.company_ID
				ORDER BY MAX_SUM_VALUE DESC
				LIMIT {$TOP_NUM}
				");
			$DATA2=[];
			while ($Fetch3=mysqli_fetch_assoc($Query3)) {
				$DATA2[]=$Fetch3;
			}
			$arr["ERROR"] = false;
			$arr["MSG"] = "Get Data success";
			$arr["TYPE"] = "success";
			$arr["DATA"] = $DATA;
			$arr["DATA2"] = $DATA2;
			$arr["TOP_NUM"] = $TOP_NUM;
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
