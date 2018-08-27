<?php
	error_reporting(E_ERROR);
	error_reporting(E_ALL);
	ini_set('display_errors','On');

	include_once '../MAINVAR.inc';

	// Get Requests
	header("Access-Control-Allow-Origin: *");
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
	//$request = array('ACTION' => 'IMAGE');
	// Loop get request Data
	foreach($request as $key => $val){
		${$key} = $val;

		if($key == 'ACTION'){
			$ACTION = $val;
		}else if($key == 'IMAGE_ID'){
			$IMAGE_ID = $val;
		}else if($key == 'PATH_FILE'){
			$PATH_FILE = $val;
		}else if($key == 'EVENTID'){
			$EVENTID = $val;
		}
	}
	
	$return_data = array();
	if($ACTION == 'IMAGE'){
		$SQL = "SELECT * FROM event INNER JOIN image ON (event.event_ID=image.event_ID)";
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'List Image';
		$return_data['DATA'] =  QureyDataArray($SQL);
		
	}else if($ACTION == 'DELETE'){
		unlink('../'.$PATH_FILE);
		$SQL = "DELETE FROM image WHERE image_ID='$IMAGE_ID';";
		$return_data['DATA'] = exec_query($SQL,$error_code = 'Can not Query');
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'Delete Image';
		
	}else if($ACTION == 'IMAGEUSER'){
		$SQL = "SELECT  event_name, path_file, event.event_id FROM event event INNER JOIN image ON (event.event_ID=image.event_ID) GROUP BY(event_name);";
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'List Image';
		$return_data['DATA'] =  QureyDataArray($SQL);
		
	}else if($ACTION == 'IMAGEALBUM'){
		$SQL = "SELECT  event_name, path_file, event.event_id FROM event event INNER JOIN image ON (event.event_ID=image.event_ID) WHERE event.event_id = $EVENTID;";
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'List Image';
		$return_data['DATA'] =  QureyDataArray($SQL);
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