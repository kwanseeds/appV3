<?php
	error_reporting(E_ERROR);
	error_reporting(E_ALL);
	ini_set('display_errors','On');

	include_once '../MAINVAR.inc';

	// Get Requests
	header("Access-Control-Allow-Origin: *");
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
	//$request = array('ACTION' => 'GRAPH', 'YEAR' => '2017');
	// Loop get request Data
	foreach($request as $key => $val){
		${$key} = $val;
	
		if($key == 'ACTION'){
			$ACTION = $val;
		}else if($key == 'YEAR'){
			$YEAR = $val;
		}
		else if($key == 'NAME_EVENT'){
			$NAME_EVENT = $val;
		}
	}
	
	$return_data = array();
	if($ACTION == 'GRAPH'){
		$SQL = "SELECT event_name, SUM(expenditure) AS expenditure FROM event INNER JOIN income ON (event.event_ID = income.event_ID) WHERE YEAR(date) = '$YEAR' GROUP BY event_name";
		// $SQL = "SELECT name AS type_name, COUNT(event_name) AS event FROM event INNER JOIN type_event ON (type_event.type_event_ID = event.type_event_ID) WHERE YEAR(date_start) = '$YEAR' GROUP BY name ORDER BY event DESC;";
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'List Event';
		$return_data['DATA'] =  QureyDataArray($SQL);

		$SQL = "SELECT date, event_name, SUM(expenditure) AS expenditure, list FROM event INNER JOIN income ON (event.event_ID = income.event_ID) WHERE YEAR(date) = '$YEAR' GROUP BY event_name, date, list";
		//$SQL = "SELECT event_name, date_start, name AS type_name FROM event INNER JOIN type_event ON (type_event.type_event_ID = event.type_event_ID) WHERE YEAR(date_start) = '$YEAR'";
		$return_data['TABLE'] =  QureyDataArray($SQL);
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