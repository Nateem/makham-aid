<?php 
require_once "./MAIN.php";
$Connect_Status = MAIN::CONNECT();
//error_reporting(0);
 $DB = $Connect_Status["DB"];

$_POST = json_decode(file_get_contents('php://input'), true);

$TYPES = isset($_POST["TYPES"]) ? $_POST["TYPES"] : "";

$arr["ERROR"]=true;
if($TYPES=="SELECT_report_where_customer"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$DateStart = $FORM_DATA["DateStart"];
	$DateEnd = $FORM_DATA["DateEnd"];

	$title_ID = isset($FORM_DATA["title_ID"]) ? $FORM_DATA["title_ID"] : "";	
	$AUMNO = isset($FORM_DATA["AUMNO"]) ? $FORM_DATA["AUMNO"] : "";	
	
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
				"SELECT customer_value.MASCODE,SUM(customer_value.VALUE) AS SUM_TOTAL_VALUE ,customer_value.company_ID,customer_value.store_type_ID,customer_value.title_ID,customer.AUMNO,CONCAT(COALESCE(customer.STNA,''),COALESCE(customer.NAME,''),' ',COALESCE(customer.SURNAME,'')) AS customer_NAME
				FROM customer_value
				LEFT JOIN customer
				ON customer_value.MASCODE=customer.MASCODE
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
				AND customer.AUMNO='{$AUMNO}' 
				$AND
				GROUP BY customer_value.MASCODE
				ORDER BY customer.MASCODE ASC
				");

			$DATA1 = array();
			while($Fetch=mysqli_fetch_assoc($Query)){
				$DATA1[] = $Fetch;			
			}
			


			//-------------------------------start sum col-----------------------------------------------

			$Query5 = mysqli_query($DB,
				"SELECT customer_value.*,SUM(customer_value.VALUE) AS SUM_VALUE
				FROM customer_value
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
				$AND
				GROUP BY customer_value.MASCODE,customer_value.company_ID,customer_value.store_type_ID,customer_value.title_ID
				");
			$DATA5 = [];
			
			while($Fetch5=mysqli_fetch_assoc($Query5)){
				$DATA5[]=$Fetch5;
					
			}
			//-------------------------------end sum col-----------------------------------------------

			//-------------------------------start sum row-----------------------------------------------
			$Query6 = mysqli_query($DB,
				"SELECT DISTINCT customer_value.company_ID,company.NAME_SHORT
				FROM customer_value
				LEFT JOIN company
				ON customer_value.company_ID=company.ID
				LEFT JOIN customer
				ON customer_value.MASCODE=customer.MASCODE
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
				AND customer.AUMNO='{$AUMNO}' 
				$AND
				ORDER BY company.NAME_SHORT ASC
				");
			$DATA6 = [];
			$n6 = 0;
			$SUM_TOTAL = 0;
			while($Fetch6=mysqli_fetch_assoc($Query6)){
				$DATA6[]=$Fetch6;
				$company_ID = $Fetch6["company_ID"];
				$Query7 = mysqli_query($DB,
					"SELECT DISTINCT customer_value.store_type_ID,store_type.NAME AS ST_NAME,store_type.COLOR AS ST_COLOR,SUM(customer_value.VALUE) AS SUM_ROW
					FROM customer_value
					LEFT JOIN store_type
					ON customer_value.store_type_ID=store_type.ID
					LEFT JOIN customer
					ON customer_value.MASCODE=customer.MASCODE
					WHERE customer_value.company_ID='{$company_ID}'
					AND (customer_value.CURDATE 
					BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
					AND customer.AUMNO='{$AUMNO}' 
					$AND
					GROUP BY customer_value.store_type_ID
					ORDER BY customer_value.store_type_ID ASC
					");
				$DATA7[$n6] = [];
				while($Fetch7=mysqli_fetch_assoc($Query7)) {
					$DATA7[$n6][]=$Fetch7;
					$SUM_TOTAL+=$Fetch7["SUM_ROW"];	
				}
				$DATA6[$n6]+= [
					"store_type"=>$DATA7[$n6]
				];
			$n6++;
			}
			//-------------------------------end sum row-----------------------------------------------
			//-------------------------start sum store_type-----------------------------------------------
			$Query8 = mysqli_query($DB,
				"SELECT DISTINCT customer_value.store_type_ID,store_type.NAME,SUM(customer_value.VALUE) AS SUM_STORE_TYPE
				FROM customer_value
				LEFT JOIN store_type
				ON customer_value.store_type_ID=store_type.ID
				LEFT JOIN customer
				ON customer_value.MASCODE=customer.MASCODE
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
				AND customer.AUMNO='{$AUMNO}' 
				$AND
				GROUP BY customer_value.store_type_ID
				");
			$DATA8 = [];
			while ($Fetch8=mysqli_fetch_assoc($Query8)) {
				$DATA8[]=$Fetch8;
			}
			//--------------------------end sum store_type-----------------------------------------------
			//---------------------start sum store_type col-----------------------------------------------

			$Query9 = mysqli_query($DB,
				"SELECT customer_value.*,SUM(customer_value.VALUE) AS SUM_VALUE
				FROM customer_value
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
				$AND
				GROUP BY customer_value.MASCODE,customer_value.store_type_ID,customer_value.title_ID
				");
			$DATA9 = [];
			
			while($Fetch9=mysqli_fetch_assoc($Query9)){
				$DATA9[]=$Fetch9;
					
			}
			//---------------------end sum store_type col-----------------------------------------------
			$DATA = [];
			$DATA += [
				"AUMNO"=>$AUMNO,
				"DATA"=>$DATA1,
				"DATA_STORE_TYPE"=>$DATA8,
				"DATA_STORE_TYPE_SUM_COL"=>$DATA9,
				"SUM_COL"=>$DATA5,
				"SUM_ROW"=>$DATA6,
				"SUM_TOTAL"=>$SUM_TOTAL
			];


		
		
		$arr["ERROR"] = false;
		$arr["MSG"] = "Get Data success";
		$arr["TYPE"] = "success";
		$arr["DATA"] = $DATA;
		$arr["title_NAME"] = $title_NAME;
	}
	else{
		$arr["ERROR"] = true;
		$arr["MSG"] = "ข้อมูลไม่ครบถ้วน !";
		$arr["TYPE"] = "warning";
	}
	echo json_encode($arr);
}
else if($TYPES=="SELECT_company_loop"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];

	
	$arr["ERROR"] = false;
	$arr["MSG"] = "Get Data success";
	$arr["TYPE"] = "success";
	$arr["DATA"] = $DATA6;
	echo json_encode($arr);
}
else if($TYPES=="sumData"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];

	
	$arr["ERROR"] = false;
	$arr["MSG"] = "Get Data success";
	$arr["TYPE"] = "success";
	$arr["DATA"] = $DATA;
	echo json_encode($arr);
}
else if($TYPES=="SELECT_aumno"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$user_ID = $CURRENT_DATA["ID"];

	try{
		$Query = mysqli_query($DB,
			"SELECT DISTINCT customer.AUMNO
			FROM customer
			ORDER BY AUMNO ASC
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
else if($TYPES=="SELECT_TABLE_2_HTML"){
	$CURRENT_DATA = isset($_POST["CURRENT_DATA"]) ? $_POST["CURRENT_DATA"] : "";
	$FORM_DATA = isset($_POST["FORM_DATA"]) ? $_POST["FORM_DATA"] : "";	
	$user_ID = $CURRENT_DATA["ID"];

	$DateStart = $FORM_DATA["DateStart"];
	$DateEnd = $FORM_DATA["DateEnd"];

	$title_ID = isset($FORM_DATA["title_ID"]) ? $FORM_DATA["title_ID"] : "";	
	$AUMNO = isset($FORM_DATA["AUMNO"]) ? $FORM_DATA["AUMNO"] : "";	

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
				"SELECT customer_value.MASCODE,SUM(customer_value.VALUE) AS SUM_TOTAL_VALUE ,customer_value.company_ID,customer_value.store_type_ID,customer_value.title_ID,customer.AUMNO,CONCAT(COALESCE(customer.STNA,''),COALESCE(customer.NAME,''),' ',COALESCE(customer.SURNAME,'')) AS customer_NAME
				FROM customer_value
				LEFT JOIN customer
				ON customer_value.MASCODE=customer.MASCODE
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
				AND customer.AUMNO='{$AUMNO}' 
				$AND
				GROUP BY customer_value.MASCODE
				ORDER BY customer.MASCODE ASC
				");

			$DATA1 = array();
			while($Fetch=mysqli_fetch_assoc($Query)){
				$DATA1[] = $Fetch;			
			}
			


			//-------------------------------start sum col-----------------------------------------------

			$Query5 = mysqli_query($DB,
				"SELECT customer_value.*,SUM(customer_value.VALUE) AS SUM_VALUE
				FROM customer_value
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
				$AND
				GROUP BY customer_value.MASCODE,customer_value.company_ID,customer_value.store_type_ID,customer_value.title_ID
				");
			$DATA5 = [];
			
			while($Fetch5=mysqli_fetch_assoc($Query5)){
				$DATA5[]=$Fetch5;
					
			}
			//-------------------------------end sum col-----------------------------------------------

			//-------------------------------start sum row-----------------------------------------------
			$Query6 = mysqli_query($DB,
				"SELECT DISTINCT customer_value.company_ID,company.NAME_SHORT
				FROM customer_value
				LEFT JOIN company
				ON customer_value.company_ID=company.ID
				LEFT JOIN customer
				ON customer_value.MASCODE=customer.MASCODE
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
				AND customer.AUMNO='{$AUMNO}' 
				$AND
				ORDER BY company.NAME_SHORT ASC
				");
			$DATA6 = [];
			$n6 = 0;
			$SUM_TOTAL = 0;
			while($Fetch6=mysqli_fetch_assoc($Query6)){
				$DATA6[]=$Fetch6;
				$company_ID = $Fetch6["company_ID"];
				$Query7 = mysqli_query($DB,
					"SELECT DISTINCT customer_value.store_type_ID,store_type.NAME AS ST_NAME,store_type.COLOR AS ST_COLOR,SUM(customer_value.VALUE) AS SUM_ROW
					FROM customer_value
					LEFT JOIN store_type
					ON customer_value.store_type_ID=store_type.ID
					LEFT JOIN customer
					ON customer_value.MASCODE=customer.MASCODE
					WHERE customer_value.company_ID='{$company_ID}'
					AND (customer_value.CURDATE 
					BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
					AND customer.AUMNO='{$AUMNO}' 
					$AND
					GROUP BY customer_value.store_type_ID
					ORDER BY customer_value.store_type_ID ASC
					");
				$DATA7[$n6] = [];
				while($Fetch7=mysqli_fetch_assoc($Query7)) {
					$DATA7[$n6][]=$Fetch7;
					$SUM_TOTAL+=$Fetch7["SUM_ROW"];	
				}
				$DATA6[$n6]+= [
					"store_type"=>$DATA7[$n6]
				];
			$n6++;
			}
			//-------------------------------end sum row-----------------------------------------------
			//-------------------------start sum store_type-----------------------------------------------
			$Query8 = mysqli_query($DB,
				"SELECT DISTINCT customer_value.store_type_ID,store_type.NAME,SUM(customer_value.VALUE) AS SUM_STORE_TYPE
				FROM customer_value
				LEFT JOIN store_type
				ON customer_value.store_type_ID=store_type.ID
				LEFT JOIN customer
				ON customer_value.MASCODE=customer.MASCODE
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
				AND customer.AUMNO='{$AUMNO}' 
				$AND
				GROUP BY customer_value.store_type_ID
				");
			$DATA8 = [];
			while ($Fetch8=mysqli_fetch_assoc($Query8)) {
				$DATA8[]=$Fetch8;
			}
			//--------------------------end sum store_type-----------------------------------------------
			//---------------------start sum store_type col-----------------------------------------------

			$Query9 = mysqli_query($DB,
				"SELECT customer_value.*,SUM(customer_value.VALUE) AS SUM_VALUE
				FROM customer_value
				WHERE (customer_value.CURDATE 
				BETWEEN DATE('{$DateStart}') AND DATE('{$DateEnd}'))
				$AND
				GROUP BY customer_value.MASCODE,customer_value.store_type_ID,customer_value.title_ID
				");
			$DATA9 = [];
			
			while($Fetch9=mysqli_fetch_assoc($Query9)){
				$DATA9[]=$Fetch9;
					
			}
			//---------------------end sum store_type col-----------------------------------------------
			$DATA = [];
			$DATA += [
				"AUMNO"=>$AUMNO,
				"DATA"=>$DATA1,
				"DATA_STORE_TYPE"=>$DATA8,
				"DATA_STORE_TYPE_SUM_COL"=>$DATA9,
				"SUM_COL"=>$DATA5,
				"SUM_ROW"=>$DATA6,
				"SUM_TOTAL"=>$SUM_TOTAL
			];

	}
	?>
	<table  border="1" cellspacing="0" cellpadding="8">
    <caption>
        <p style="margin: 1px;">รายงาน รายละเอียด</p>
        <p style="margin: 1px;">หัวข้อ : <?php echo $title_NAME;?></p>
        <p style="margin: 1px;">กลุ่มที่ : <?php echo $AUMNO; ?></p>
    </caption>
    <thead>
        <tr>
            <th rowspan="2">ลำดับ</th>
            <th rowspan="2">ชื่อ</th>
			<?php 
			foreach ($DATA['SUM_ROW'] as $key => $value) {
			?>
				<th colspan="<?php echo count($value['store_type']); ?>">
                	<?php echo $value["NAME_SHORT"]; ?>
            	</th>
			<?php
			}
			foreach ($DATA['DATA_STORE_TYPE'] as $key => $value) {
			?>
				<th rowspan="2" >
	                <?php echo $value["NAME"]; ?>
	            </th>
			<?php
			}
			 ?>     
            <th rowspan="2">ยอดรวม</th>
            <th rowspan="2">ว/ด/ป ที่รับสินค้า</th>
            <th rowspan="2">หมายเหตุ</th>
        </tr>
        <tr>
        	<?php 
        	foreach ($DATA['SUM_ROW'] as $key => $value) {
        		foreach ($value['store_type'] as $key2 => $value2) {
        			?>
					<th style="background-color:<?php echo $value2["ST_COLOR"];  ?>;">
		                <?php echo $value2['ST_NAME']; ?>
		            </th>
        			<?php
        		}
        	}
        	 ?>
            
        </tr>
    </thead>
     <tbody>
<?php
	
	foreach ($DATA["DATA"] as $key => $value) {
?>
		<tr>
            <td><?php echo $key+1; ?></td>
            <td><?php echo $value['MASCODE']; ?> : <?php echo $value['customer_NAME']; ?></td>
            <?php 
            foreach ($DATA["SUM_ROW"] as $key1 => $value1) {
            	foreach ($value1['store_type'] as $key2 => $value2) {?>
            		<td >
            		<?php
            		foreach ($DATA["SUM_COL"] as $key3 => $value3) {   
	            			if($value['MASCODE']==$value3["MASCODE"] && $value1['company_ID']==$value3['company_ID'] && $value2["store_type_ID"]==$value3['store_type_ID']){
	          					echo number_format($value3["SUM_VALUE"],2);
	            			}            			
            		}
            		?>
            		</td>
            		<?php
            	}
            }
            foreach ($DATA["DATA_STORE_TYPE"] as $key1 => $value1) { ?>
            	<td >
            	<?php 
            		foreach ($DATA["DATA_STORE_TYPE_SUM_COL"] as $key2 => $value2) { 
            			if($value["MASCODE"]==$value2["MASCODE"] && $value1["store_type_ID"]==$value2["store_type_ID"]){
            				echo number_format($value2["SUM_VALUE"],2);

            			}
            		}
            	 ?>
	                
            	</td>
            <?php
            }
             ?>
            
            <td>
            	<?php echo number_format($value["SUM_TOTAL_VALUE"],2); ?>
            </td>
            <td></td>
            <td></td>
        </tr>

			<?php		
			}
	?>
 </tbody>
    <tfoot>
        <tr>
            <th colspan="2">รวม</th>
            <?php 
			foreach ($DATA['SUM_ROW'] as $key => $value) {
        		foreach ($value['store_type'] as $key2 => $value2) {
        			?>
					<th>
		                <?php echo number_format($value2['SUM_ROW'],2); ?>
		            </th>
        			<?php
        		}
        	}
			foreach ($DATA['DATA_STORE_TYPE'] as $key => $value) {
			?>
				<th>
	                <?php echo number_format($value["SUM_STORE_TYPE"],2); ?>
	            </th>
			<?php
			}
			 ?>             
            <th>
            	<?php echo number_format($DATA["SUM_TOTAL"]); ?>
            </th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
</table>
	<?php

}
else{
	$arr["ERROR"] = true;
	$arr["MSG"] = "Cannot Find to database!";
	$arr["TYPE"] = "error";
	echo json_encode($arr);
}
?>
