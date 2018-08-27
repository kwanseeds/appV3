<?php
	error_reporting(E_ERROR);
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	
	include_once '../MAINVAR.inc';
	
	// Get Requests
	header("Access-Control-Allow-Origin: *");
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
	$request = array('ACTION' => 'CALENDAR');
	// Loop get request Data
	foreach($request as $key => $val){
		${$key} = $val;
	
		if($key == 'CALENDAR'){
			$ACTION = $val;
		}
	}
	
	$return_data = array();
	if($ACTION == 'CALENDAR'){
		$SQL = "SELECT event_id, event_name, owner_name, place, budget, date_start, (date_end+INTERVAL 1 DAY) AS date_end, time_start, time_end, detaill, soundName, staff, manageName, event.type_event_ID, status 
				FROM event INNER JOIN type_event ON (type_event.type_event_ID = event.type_event_ID) 
				WHERE status = 'Y'";
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'Calendar Event';
		$return_data['DATA'] =  QureyDataArray($SQL);
		
		$SQL = "SELECT * FROM contact";
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'Calendar Event';
		$return_data['CONCACT'] =  QureyDataArray($SQL);
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