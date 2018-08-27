<?php
	error_reporting(E_ERROR);
	error_reporting(E_ALL);
	ini_set('display_errors','On');

	include_once '../MAINVAR.inc';	
	// Get Requests
	header("Access-Control-Allow-Origin: *");
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
	//$request = array('ACTION' => 'ADDEVENT','ID' => '4');
	// Loop get request Data
	foreach($request as $key => $val){
		${$key} = $val;
	
		if($key == 'ACTION'){
			$ACTION = $val;
		}
		if($key == 'EVENT_ID'){
			$EVENT_ID = $val;
		}
		if($key == 'NAME_EVENT'){
			$NAME_EVENT = $val;
		}
		if($key == 'OWNER_NAME'){
			$OWNER_NAME = $val;
		}
		if($key == 'PLACE'){
			$PLACE = $val;
		}
		if($key == 'BUDGET'){
			$BUDGET = $val;
		}
		if($key == 'DATE_START'){
			$DATE_START = $val;
		}
		if($key == 'DATE_END'){
			$DATE_END = $val;
		}
		if($key == 'TIME_START'){
			$TIME_START = $val;
		}
		if($key == 'TIME_END'){
			$TIME_END = $val;
		}
		if($key == 'DETAIL'){
			$DETAIL = $val;
		}
		if($key == 'SOUNDNAME'){
			$SOUNDNAME = $val;
		}
		if($key == 'STAFF'){
			$STAFF = $val;
		}
		if($key == 'MANAGENAME'){
			$MANAGENAME = $val;
		}
		if($key == 'TYPE_EVENT_ID'){
			$TYPE_EVENT_ID = $val;
		}
		if($key == 'STATUS'){
			$STATUS = $val;
		}
	}
	
	$return_data = array();
	$MAXIDSTAFF = array();
	if($ACTION == 'UPDATEEVENT'){
		
		$SQL = "UPDATE event SET event_name = '$NAME_EVENT',  owner_name = '$OWNER_NAME',  place = '$PLACE',  budget = '$BUDGET',  date_start = '$DATE_START', date_end = '$DATE_END', time_start = '$TIME_START', time_end = '$TIME_END', detaill = '$DETAIL', soundName = '$SOUNDNAME', staff = '$STAFF', manageName = '$MANAGENAME', type_event_ID = $TYPE_EVENT_ID, status = '$STATUS'  WHERE  event_ID = $EVENT_ID";
		//echo $SQL;
		$return_data['DATA'] = exec_query($SQL,'Can not Query');
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'ADD event $NAME_EVENT success.';

	}else if($ACTION == 'LISTTYPE'){

		$SQL = "SELECT * FROM type_event";
		$return_data['DATA'] = QureyDataArray($SQL);
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'LISTTYPE';
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