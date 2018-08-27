<?php

	include_once '../MAINVAR.inc';
	
	// Get Requests
	header("Access-Control-Allow-Origin: *");
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
	//$request = array('ACTION' => 'VIEWEVENT');
	// Loop get request Data
	foreach($request as $key => $val){
		${$key} = $val;
	
		if($key == 'ACTION'){
			$ACTION = $val;
		}
		if($key == 'EID'){
			$EID = $val;
		}
	}
	
	$return_data = array();
	if($ACTION == 'VIEWEVENT'){
		$SQL = "SELECT * FROM event INNER JOIN type_event ON (type_event.type_event_ID = event.type_event_ID) WHERE event_id = $EID";
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'List Event';
		$return_data['DATA'] =  QureyDataArray($SQL); // Your object to return to client

		$SQL = "SELECT * FROM type_event";
		$return_data['TYPE'] = QureyDataArray($SQL);
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