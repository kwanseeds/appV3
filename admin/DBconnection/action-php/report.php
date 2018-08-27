<?php 

	error_reporting(E_ERROR);
	error_reporting(E_ALL);
	ini_set('display_errors','On');
	
	include_once ('../ClassesExcel/EXPORTXLSFUNC.inc');
	include_once ('../ClassesExcel/PHPExcel.php');
	include_once ('../ClassesExcel/PHPExcel/IOFactory.php');
	include_once '../MAINVAR.inc';
	// Get Requests
	header("Access-Control-Allow-Origin: *");
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	
// 	$request = array('ACTION' => 'REPORT',
// 			'YEAR'=> '2017', 
// 			'MONTH' => '05'
// 	);
	// Loop get request Data
	foreach($request as $key => $val){
		${$key} = $val;
		
		if($key == 'REPORT'){
			$ACTION = $val;
		}else if($key == 'YEAR'){
			$YEAR = $val;
		}else if($key == 'MONTH'){
			$MONTH = $val;
		}
	}
	if($YEAR == '' || $MONTH == ''){
		exit(0);
	}
	
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
	
		$pathSaveFile = dirname(__FILE__)."\\report\\";
		$fileName = $pathSaveFile.@date("YmdHi",time()).'_report_event.xls';
	
		$objPHPExcel->getActiveSheet()->setTitle(@date("YmdHi",time()));
		$objWriter = PHPExcel_IOFactory::createWriter ( $objPHPExcel, 'Excel5' );
		ob_end_clean();
		$objWriter->save($fileName);
		
		$return_data['DATA'] = $fileName;
		$return_data['STATUS'] = 'OK';
		$return_data['MSG'] = 'OK';
		echo json_encode($return_data);
		
		exit(0);
	
	//Function Qurey DB
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
	
	function genMonth($objPHPExcel,$styleBorderOutline,$objPHPExcel,$styleFontBody,$styleAlignment){
		$row = 2;
		$column = 'C';
		for($i = 1; $i <= 12; $i++){
			$month = date('F', mktime(0,0,0,$i, 1, date('Y')));
			$tmp = $column;
			$tmp++;
			$objPHPExcel->getActiveSheet()->mergeCells($column.$row.':'.$tmp.$row)->getStyle($column.$row.':'.$tmp.$row)->applyFromArray($styleBorderOutline);
			$date = $row;
			$date++;
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$date, 'Date')->getStyle($column.$date)->applyFromArray($styleBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle($column.$date)->applyFromArray($styleFontBody);
			$objPHPExcel->getActiveSheet()->getStyle($column.$date)->applyFromArray($styleAlignment);
			$objPHPExcel->getActiveSheet()->SetCellValue($tmp.$date, 'Bath')->getStyle($tmp.$date)->applyFromArray($styleBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle($tmp.$date)->applyFromArray($styleFontBody);
			$objPHPExcel->getActiveSheet()->getStyle($tmp.$date)->applyFromArray($styleAlignment);
			$objPHPExcel->getActiveSheet()->SetCellValue($column.$row, $month);
			$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray($styleFontBody);
			$objPHPExcel->getActiveSheet()->getStyle($column.$row)->applyFromArray($styleAlignment);
			$column++;$column++;
		}
		return $objPHPExcel;
	}
?>