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

		if($key == 'FINANCE'){
			$ACTION = $val;
			
		}else if ($key == 'EXPENDITURE'){
			$EXPENDITURE = $val;
			
		}else if ($key == 'LIST'){
			$LIST = $val;
			
		}else if ($key == 'DATE'){
			$DATE = $val;
			
		}else if ($key == 'EVENTID'){
			$EVENTID = $val;
		}
	}
	
	$return_data = array();
	if($ACTION == 'FINANCE'){
	
		$SQL = "INSERT INTO income (expenditure, list, date, event_ID) VALUES ($EXPENDITURE, '$LIST', '$DATE', $EVENTID);";
		//echo "<pre><br>$SQL<br>";
		$return_data['DATA'] = exec_query($SQL,'Can not Query');
		$return_data['STATUS'] = 'OK';
	
	}else if ($ACTION == 'LISTFINANCE'){
		$SQL = "SELECT * FROM event;";
		//echo "$SQL";
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