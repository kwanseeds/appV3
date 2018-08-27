<?php
	error_reporting(E_ERROR);
	error_reporting(E_ALL);
	ini_set('display_errors','On');

	include_once '../MAINVAR.inc';	
	// Get Requests
	header("Access-Control-Allow-Origin: *");
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
	//$request = array('ACTION' => 'ADDCONTACT','EVENT_NAME' => 'Home Electric Euro Super Sale');
	// Loop get request Data
	foreach($request as $key => $val){
		${$key} = $val;
	
		if($key == 'ACTION'){
			$ACTION = $val;
		}else if($key == 'NAME_CUSTOMER'){
			$NAME_CUSTOMER = $val;
		}else if($key == 'DATE_CONTACT'){
			$DATE_CONTACT = $val;
		}else if($key == 'TIME'){
			$TIME = $val;
		}else if($key == 'PHONE'){
			$PHONE = $val;
		}
	}
	
	$return_data = array();
	if($ACTION == 'ADDCONTACT'){
		
		$SQL = "INSERT INTO contact (name_customer, date_contact, time, phone) VALUES ('$NAME_CUSTOMER', '$DATE_CONTACT', '$TIME', '$PHONE')";
		$return_data['DATA'] = exec_query($SQL,'Can not Query');
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'ADD contact $NAME_EVENT success.';

	}else if ($ACTION == 'LISTCONTACT'){
		$SQL = "SELECT * FROM event";
		$return_data['DATA'] = QureyDataArray($SQL);
		$return_data['STATUS'] = 'OK';
	}
	
	echo json_encode($return_data);
	exit(0);

	//Function Qurey DB
	function QureyDataArray($SQL){
		global $DBHOST, $DBUSER, $DBPASS, $DBNAME;
		$conn = new mysqli($DBHOST, $DBUSER, $DBPASS, $DBNAME);
		$data = array();
		mysqli_set_charset($conn, "utf8");
		$result = mysqli_query($conn, $SQL);
		if(mysqli_num_rows($result) > 0){
			while($row = mysqli_fetch_assoc($result)){
				$data[] = $row;
			}
		}
		mysqli_close($conn);
		return $data;
	}

	function exec_query($SQL,$error_code){
		global $DBHOST, $DBUSER, $DBPASS, $DBNAME;
		$conn = new mysqli($DBHOST, $DBUSER, $DBPASS, $DBNAME);
		mysqli_set_charset($conn, "utf8");
		$result = mysqli_query($conn, $SQL);
		if(! $result ){
			 $data = $error_code;
		}else{
			$data = 'Query OK';
		}
		mysqli_close($conn);
		return $data;
	}
?>