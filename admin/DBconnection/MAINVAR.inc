<?php
$DBHOST = "localhost";
$DBTYPE = "MYSQL";
$DBUSER = "root";
$DBPASS = "1234";
$DBNAME = "event_manage";


function Query2ArrayVarPack($SQL){
	global $DBNAME,$DBHOST,$DBUSER,$DBPASS,$DBTYPE;
	db_connect($DBHOST,$DBUSER,$DBPASS) or die(showException(db_error()));
	db_select_db($DBNAME) or die(showException(db_error(),"ON SELECT DB"));
	$result = db_query($SQL) or die (showException(db_error(),$SQL));
	$numfield=db_num_fields($result);
	for( $i=0;$i< $numfield;$i++) {
		$data = db_fetch_field($result);
		$arr_name[$i]=$data->name;
	}
	$j=0;
	while($rows=db_fetch_array($result)){
		for( $i=0;$i< $numfield;$i++) {
			if(($DBTYPE=="")||($DBTYPE=="MYSQL"))
			{
				$VARPACK[$arr_name[$i]][$j] = $rows[$arr_name[$i]];
			}
			if($DBTYPE=="MSSQL")
			{
				if($rows[$arr_name[$i]]==' '){$rows[$arr_name[$i]]='';}
				$VARPACK[$arr_name[$i]][$j] = stripslashes($rows[$arr_name[$i]]);
			}
		}
		$j++;
	}
	return($VARPACK);
}

function db_connect($DBHOST, $DBUSER, $DBPASS) {
	global $DBTYPE, $DBNAME;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		$link = mysql_pconnect ( $DBHOST, $DBUSER, $DBPASS );
		mysql_db_query ( $DBNAME, "SET NAMES tis620" );
		return $link;
	}
	if ($DBTYPE == 'MSSQL') {
		return mssql_connect ( $DBHOST, $DBUSER, $DBPASS );
	}
}
function db_close($link) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return ''; // mysql_close($link);
	}
	if ($DBTYPE == 'MSSQL') {
		return mssql_close ( $link );
	}
}
function db_error($id = '') {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return mysql_error ();
	}
	if ($DBTYPE == 'MSSQL') {
	} // Notvalid in MSSQL
}
function db_select_db($DBNAME) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		$tmp = mysql_select_db ( $DBNAME );
		return $tmp;
	}
	if ($DBTYPE == 'MSSQL') {
		return mssql_select_db ( $DBNAME );
	}
}
function db_db_query($DBNAME, $query) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		mysql_select_db ( $DBNAME );
		return mysql_query ( $query );
	}
	
	if ($DBTYPE == 'MSSQL') {
		mssql_select_db ( $DBNAME );
		return mssql_query ( $query );
	}
}
function db_query($query) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return mysql_query ( $query );
	}
	if ($DBTYPE == 'MSSQL') {
		// print "<hr>[$query]";
		$query = str_ireplace ( "NOW()", "getdate()", $query );
		$query = str_replace ( "\\" . "'", "''", $query );
		$query = str_replace ( '`', "", $query );
		$query = str_ireplace ( "UNIX_TIMESTAMP(", "datediff(ss,CAST('2000-01-01 00:0:00' AS datetime),", $query );
		
		// print "<br>[$query]";
		return mssql_query ( $query );
	}
}
function db_num_fields($result) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return mysql_num_fields ( $result );
	}
	if ($DBTYPE == 'MSSQL') {
		return mssql_num_fields ( $result );
	}
}
function db_num_rows($result) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return mysql_num_rows ( $result );
	}
	if ($DBTYPE == 'MSSQL') {
		return mssql_num_rows ( $result );
	}
}
function db_fetch_assoc($result) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return mysql_fetch_assoc ( $result );
	}
	if ($DBTYPE == 'MSSQL') {
		return mssql_fetch_array ( $result );
	}
}
function db_fetch_array($result) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return mysql_fetch_array ( $result );
	}
	if ($DBTYPE == 'MSSQL') {
		return mssql_fetch_array ( $result );
	}
}
function db_fetch_field($result, $i = "0") {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return ($i > 0 ? mysql_fetch_field ( $result, $i ) : mysql_fetch_field ( $result ));
	}
	if ($DBTYPE == 'MSSQL') {
		return (mssql_fetch_field ( $result ));
		/*
		 * $search_array = array('foreignInlandFreightChargeCurr' => 'foreignInlandFreightChargeCurrency',
		 * 'foreignInlandFreightChargeAmou' => 'foreignInlandFreightChargeAmountForeign');
		 * $pre = mssql_fetch_field($result);
		 *
		 * if (array_key_exists($pre->name, $search_array)) {
		 * //echo "The 'first' element is in the array";
		 * $pre->name=$search_array[$pre->name];
		 * }
		 * return $pre->name;
		 */
	}
}
function db_free_result($result) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return mysql_free_result ( $result );
	}
	if ($DBTYPE == 'MSSQL') {
		return mssql_free_result ( $result );
	}
}
function db_field_name($result, $i) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return mysql_field_name ( $result, $i );
	}
	if ($DBTYPE == 'MSSQL') {
		return mssql_field_name ( $result, $i );
	}
}
function db_field_flags($result, $i) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return mysql_field_flags ( $result, $i );
	}
	if ($DBTYPE == 'MSSQL') {
	} // Notvalid in MSSQL
}
function db_field_len($result, $i) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return mysql_field_len ( $result, $i );
	}
	if ($DBTYPE == 'MSSQL') {
		return mssql_field_length ( $result, $i );
	}
}
function db_field_type($result, $i) {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return mysql_field_type ( $result, $i );
	}
	if ($DBTYPE == 'MSSQL') {
		return mssql_field_type ( $result, $i );
	}
}
function db_insert_id() {
	global $DBTYPE;
	if (($DBTYPE == '') || ($DBTYPE == 'MYSQL')) {
		return mysql_insert_id ();
	}
	if ($DBTYPE == 'MSSQL') {
	} // Notvalid in MSSQL
}
?>
