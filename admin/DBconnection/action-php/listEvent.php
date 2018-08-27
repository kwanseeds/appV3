<?php
	error_reporting(E_ERROR);
	error_reporting(E_ALL);
	ini_set('display_errors','On');

	include_once '../MAINVAR.inc';

	// Get Requests
	header("Access-Control-Allow-Origin: *");
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
	$request = array('ACTION' => 'LISTEVENT');
	// Loop get request Data
	foreach($request as $key => $val){
		${$key} = $val;
	
		if($key == 'ACTION'){
			$ACTION = $val;
		}
	}
	
	$return_data = array();
	if($ACTION == 'LISTEVENT'){
		$SQL = "SELECT * FROM event INNER JOIN type_event ON (type_event.type_event_ID = event.type_event_ID) WHERE status = 'Y' and DATE(date_start) >= now();";
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'List Event';
		$return_data['DATA'] =  QureyDataArray($SQL); // Your object to return to client
		
	}else if($ACTION == 'LISTEVENTADMIN'){
		$SQL = "SELECT * FROM event INNER JOIN type_event ON (type_event.type_event_ID = event.type_event_ID) WHERE status = 'Y' ORDER BY event.event_id DESC";
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'List Event';
		$return_data['DATA'] =  QureyDataArray($SQL); // Your object to return to client
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