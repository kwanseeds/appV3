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
		        <h1><span class="glyphicon glyphicon-home"></span> Event.</h1> 
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
<div class="main" ng-controller="ReportEventController">
	<div class="container">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h2>
					<span class="glyphicon glyphicon-th-list"></span> Report Event
				</h2>
			</div>
			<div class="panel-title"></div>
			<div class="panel-body">
				<form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
					<!-- Customer Form -->
					<div class="form-group has-feedback">
						<p align="center"><font size="3" color="red">{{ myData }}</font></p><br>
						<label class="col-sm-2 control-label">Month</label>
						<div class="col-sm-3">
							<select name="month" ng-model="month" class="form-control">
								<option value='1'>Janaury</option>
								<option value='2'>February</option>
								<option value='3'>March</option>
								<option value='4'>April</option>
								<option value='5'>May</option>
								<option value='6'>June</option>
								<option value='7'>July</option>
								<option value='8'>August</option>
								<option value='9'>September</option>
								<option value='10'>October</option>
								<option value='11'>November</option>
								<option value='12'>December</option>
							</select>
						</div>
					</div>
					<div class="form-group has-feedback">
						<label class="col-sm-2 control-label">Year</label>
						<div class="col-sm-3">
							<select name="year" class="form-control" ng-model="year" required>
								<option ng-repeat="year in years">{{year}}</option>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-3">
							<input class="btn btn-success" type="submit" value="Save" name="btn-report"
								ng-controller="UpdateEventController"
								confirmed-click="reportEvent(month,year)"
								ng-confirm-click="Do you want to save ?">
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
	</body>
</html>

<?php 
if (isset ( $_POST ['btn-report'] )) {
	error_reporting(E_ERROR);
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	
	include_once ('ClassesExcel/EXPORTXLSFUNC.inc');
	include_once ('ClassesExcel/PHPExcel.php');
	include_once ('ClassesExcel/PHPExcel/IOFactory.php');
	include_once ('MAINVAR.inc');
	// Get Requests
	$MONTH = $_POST['month'];
	$YEAR = $_POST['year'];
	$ACTION = 'REPORT';
	
	$report_data = array();
	if($ACTION == 'REPORT'){
		$SQL = "SELECT * FROM event INNER JOIN income ON (event.event_ID = income.event_ID) WHERE MONTH(date_start) = $MONTH and YEAR(date_start) = $YEAR ORDER BY date";
		$report_data =  QureyDataArray($SQL);
		
		$styleBorderOutline = array(
				'borders' => array(
						'outline' => array(
								'style' => PHPExcel_Style_Border::BORDER_THIN,
								'color' => array('argb' => 'FF000000'),
						),
				),
		);
		$styleAlignment = array(
				'alignment' => array(
						'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
						'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
				)
				
		);
		$styleFontHead = array(
						'font'  => array(
						'bold'  => true,
						'size'  => 20,
				)
		);
		$styleFontTitle = array(
				'font'  => array(
						'bold'  => true,
						'size'  => 14,
				)
		);
		$styleFontBody = array(
				'font'  => array(
						'size'  => 14,
				)
		);
		
		
		
		$objPHPExcel = new PHPExcel();
		$objPHPExcel->setActiveSheetIndex(0);
		
		$head = date('F', mktime(0,0,0,$MONTH, 1, date('Y')))." ".$YEAR;
			
		//Head
		$objPHPExcel->getActiveSheet()->mergeCells('A1:G1')->getStyle('A1:G1')->applyFromArray($styleBorderOutline);
		$objPHPExcel->getActiveSheet()->SetCellValue('A1', "Event Manager Report ".$head);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleFontHead);
		$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleAlignment);
		
		//title
		$objPHPExcel->getActiveSheet()->SetCellValue('A2', "Date");
		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleFontTitle);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleAlignment);
		$objPHPExcel->getActiveSheet()->getStyle('A2')->applyFromArray($styleBorderOutline);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('B2', "List");
		$objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleFontTitle);
		$objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleAlignment);
		$objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleBorderOutline);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('C2', "revenue");
		$objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleFontTitle);
		$objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleAlignment);
		$objPHPExcel->getActiveSheet()->getStyle('C2')->applyFromArray($styleBorderOutline);
		
		$objPHPExcel->getActiveSheet()->SetCellValue('D2', "expenditure");
		$objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleFontTitle);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleAlignment);
		$objPHPExcel->getActiveSheet()->getStyle('D2')->applyFromArray($styleBorderOutline);
		
		$objPHPExcel = getDateData($report_data,$objPHPExcel,$styleBorderOutline);
		$objPHPExcel = getListData($report_data,$objPHPExcel,$styleBorderOutline);
		$objPHPExcel = getRevenueData($report_data,$objPHPExcel,$styleBorderOutline);
		$objPHPExcel = getExpenditureData($report_data,$objPHPExcel,$styleBorderOutline);
		
		$total = 3 + count($report_data) +1;
		$objPHPExcel->getActiveSheet()->SetCellValue('A'.$total, "Total");
		
	}
		$objPHPExcel->getActiveSheet()->setTitle(@date("YmdHi",time()));
		
		$pathSaveFile = dirname(__FILE__)."\\report\\";
		$fileName = $pathSaveFile.@date("YmdHi",time()).'_report_event.xls';
		//$fileName = @date("YmdHi",time()).'_report_event.xls';
		header ( 'Content-Type: application/vnd.ms-excel' );
		header ( 'Content-Disposition: attachment;filename='.$fileName );
		header ( 'Cache-Control: max-age=0' );
		$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		ob_end_clean();
		$objWriter->save($fileName);
		
		?> 
		<p align="center"><?php echo $fileName; ?></p>
		<?php 
		
		exit(0);
}	
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
		
	function getDateData($report_data,$objPHPExcel,$styleBorderOutline){

		$row = 3;
		$column = 'A';
		$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
		for ($i = 0; $i<count($report_data); $i++){
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$row, $report_data[$i]['date']);
			$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray($styleBorderOutline);
			$row++;
		}
		return $objPHPExcel;
	}
	
	function getListData($report_data,$objPHPExcel,$styleBorderOutline){
	
		$row = 3;
		$column = 'B';
		$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
		for ($i = 0; $i<count($report_data); $i++){
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$row, $report_data[$i]['list']);
			$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray($styleBorderOutline);
			$row++;
		}
		return $objPHPExcel;
	}
	
	function getRevenueData($report_data,$objPHPExcel,$styleBorderOutline){
	
		$row = 3;
		$column = 'C';
		$sum = 0;
		$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
		for ($i = 0; $i<count($report_data); $i++){
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$row, $report_data[$i]['revenue']);
			$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray($styleBorderOutline);
			$row++;
			$sum += $report_data[$i]['revenue'];
		}
		$row++;
		$objPHPExcel->getActiveSheet()->SetCellValue($column.$row, $sum);
		return $objPHPExcel;
	}
	
	function getExpenditureData($report_data,$objPHPExcel,$styleBorderOutline){
	
		$row = 3;
		$column = 'D';
		$sum = 0;
		$objPHPExcel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
		for ($i = 0; $i<count($report_data); $i++){
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$row, $report_data[$i]['expenditure']);
			$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray($styleBorderOutline);
			$row++;
			$sum += $report_data[$i]['expenditure'];
		}
		$row++;
		$objPHPExcel->getActiveSheet()->SetCellValue($column.$row, $sum);
		return $objPHPExcel;
	}
?>