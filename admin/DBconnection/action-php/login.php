<?php
	error_reporting(E_ERROR);
	error_reporting(E_ALL);
	ini_set('display_errors','On');

	include_once '../MAINVAR.inc';

	// Get Requests
	header("Access-Control-Allow-Origin: *");
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
	//$request = array('ACTION' => 'LOGINEVENT');
	// Loop get request Data
	foreach($request as $key => $val){
		${$key} = $val;
	
		if($key == 'ACTION'){
			$ACTION = $val;
		}
		if($key == 'USERNAME'){
			$USERNAME = $val;
		}
		if($key == 'PASSWORD'){
			$PASSWORD = $val;
		}
	}
	
	$return_data = array();
	$RS = array();
	if($ACTION == 'LOGINEVENT'){
		$SQL = "SELECT * FROM login WHERE username = '$USERNAME' AND password = '$PASSWORD'";
		$return_data['MSG'] = 'Login Event';
		$RS =  QureyDataArray($SQL); // Your object to return to client
		if($RS != null){
			$return_data['STATUS'] = 'OK';
		}
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


?>