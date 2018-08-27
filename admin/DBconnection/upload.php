<?php
error_reporting(E_ERROR);
error_reporting(E_ALL);
ini_set('display_errors','On');
include_once 'MAINVAR.inc';

if (isset ( $_POST ['btn-upload'] )) {
	$pic = rand ( 1000, 100000 ) . "-" . $_FILES ['pic'] ['name'];
	$event = $_POST['event'];
	$pic_loc = $_FILES ['pic'] ['tmp_name'];
	$folder = "images/";
	//echo $event;
	if (move_uploaded_file ( $pic_loc, $folder . $pic )) {
		?><script>alert('successfully uploaded');</script><?php
	} else {
		?><script>alert('error while uploading file');</script><?php
	}
	$return_data = array();
	$SQL = "INSERT INTO image (path_file, event_ID) VALUES ('$folder$pic', $event)";
	$return_data['DATA'] = exec_query($SQL,'Can not Query');
	
	
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
<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="utf-8">

		<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
		Remove this if you use the .htaccess -->
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title></title>
		<meta name="description" content="">
		<link rel="shortcut icon" href="favicon.ico"/>
		<meta name="viewport" content="initial-scale=1, maximum-scale=1, user-scalable=no, width=device-width">
		
		<!-- include js & css -->
		<link rel="stylesheet" href="bootstrap/admincss/bootstrap/css/bootstrap.min.css">
		
		<script type="text/javascript" src="js/bower_components/angular/angular.min.js"></script>
		<script type="text/javascript" src="js/bower_components/angular-route/angular-route.min.js"></script>

		<!-- Modules -->
		<script src="js/action-js/app.js"></script>
		<script src="js/action-js/app_controller.js"></script>
		<script src="js/action-js/app_route.js"></script>
		
		<!-- calendar -->
		<!-- <link href="http://fonts.googleapis.com/css?family=Noto+Sans+Thai:400,300,600,700&subset=all" rel="stylesheet" type="text/css"> -->
	   	<link href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.min.css" rel="stylesheet" type="text/css" />
	 	<link rel='stylesheet' href='http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.css' />
	 	<script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
	 	<script src="http://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>  
	 	<script src='http://cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment.min.js'></script> 
	 	<script src='http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/fullcalendar.min.js'></script>
	 	<script src='http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.3.1/lang-all.js'></script>
	  	<link rel="stylesheet" href="bootstrap/calendar.css">
	  	<!-- End calendar -->

		<!-- include end -->
	
	</head>

	<body ng-app="myApp">
		<div class="header">
	     	<div class="container">
		        <h1><span class="glyphicon glyphicon-home"></span> Event Manager</h1> 
	     	</div>
	    </div>
	    <div class="main">
	      	<div class="container">
	      		<div>
				<a class="btn btn-default" href="admin.html">
				   	<span class="glyphicon glyphicon-log-out"></span> Back</a>
				</div>
	      	</div>
	      	
	    </div>
	    <br>
<div class="main" ng-controller="UserListEventController">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h2>
					<span class="glyphicon glyphicon-th-list"></span> Upload Image
				</h2>
			</div>
			<div class="panel-title"></div>
			<div class="panel-body">
			<?php 
			$return_data = array();
			$SQL = "SELECT * FROM event INNER JOIN type_event ON (type_event.type_event_ID = event.type_event_ID) WHERE status = 'Y'";
			$return_data['DATA'] = QureyDataArray($SQL);
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
				<form action="" method="post" enctype="multipart/form-data">
					<div class="form-group">
                    <label class="col-sm-2 control-label">Event Name </label>
                    <div class="col-sm-3">
						<select ng-model="status" class="form-control" name="event">
						<?php for ($i = 0; $i< count($return_data['DATA']); $i++){ ?>
							<option value='<?php echo "".$return_data['DATA'][$i]['event_ID']; ?>'><?php echo "".$return_data['DATA'][$i]['event_name']; ?></option>
						<?php } ?>
						</select>
					</div></div>
					<p align="center"><input class="btn btn-default" type="file" name="pic" /></p>
					<p align="center"><button class="btn btn-default" type="submit" name="btn-upload">Upload</button></p>
				</form>
<!-- 				<img src="images/28052-t0509042016.jpg"/> -->
		</div>
		<center>
			<!-- next page -->
		</center>
	</div>
</div>
	    
	</body>
</html>
