<?php
if (isset ( $_POST ['btn-upload'] )) {
	$pic = rand ( 1000, 100000 ) . "-" . $_FILES ['pic'] ['name'];
	$pic_loc = $_FILES ['pic'] ['tmp_name'];
	$folder = "images/";
	if (move_uploaded_file ( $pic_loc, $folder . $pic )) {
		
		$SQL = "INSERT INTO image (path_file, event_ID) VALUES ('$folder/$pic', '9');";
		exec_query($SQL,'Can not Query');
		
		?><script>alert('successfully uploaded');</script><?php
	} else {
		?><script>alert('error while uploading file');</script><?php
	}
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