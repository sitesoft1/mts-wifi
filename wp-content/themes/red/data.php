<?php 

/*function is_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}*/

/*function json_error($description) {
	header("Content-type: application/json;charset=utf-8");
	die (json_encode(array('status' => 'error', 'description' => $description)));
}


function json_noerror($noerror,$desc,$arrs) {
	$recepient = "djpaoloboy@yandex.ru";
	$sitename = "AdMedia Agency";
	$pagetitle = "Письмо с сайта \"$sitename\"";

	$arrid = '';
	$massi = explode(',',$arrs);
	foreach($massi as $mi){
		$next = trim($_POST['field_' . $mi]);
		$arrid = $arrid . $next . ',';
	}

	header ("Content-type: application/json;charset=utf-8");
	die (json_encode(array('status' => $arrid, 'description' => $desc)));
}*/

if (isset($_GET['action']) and $_GET['action'] == 'addForm') {
		/*$response = array();
		$inputTrue = array();
		$inputFalse = array();

	$array_name_blocks = array();
	$complex_forms = trim($_POST['hidd_fields']);

	$response = array('status' => 'success', 'description' => $array_name_blocks);*/

	//if (!empty($inputFalse)) json_error($inputFalse);
	//else json_noerror($response, 'desc', $complex_forms);
	$file_place = $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/red/data/july_2020_21_07.xlsx';
	function excelFil($filename){
		// путь к библиотеки от корня сайта
		//require_once $_SERVER['DOCUMENT_ROOT'].'/PHPExcel/Classes/PHPExcel.php';
		require_once $_SERVER['DOCUMENT_ROOT'].'/wp-content/themes/red/data/PHPExcel/classes/PHPExcel.php';
		$result = array();
		// получаем тип файла (xls, xlsx), чтобы правильно его обработать
		$file_type = PHPExcel_IOFactory::identify( $filename );
		// создаем объект для чтения
		$objReader = PHPExcel_IOFactory::createReader( $file_type );
		$objPHPExcel = $objReader->load( $filename ); // загружаем данные файла
		$result = $objPHPExcel->getActiveSheet()->toArray(); // выгружаем данные

		return $result;
	}

	$co = excelFil($file_place);

	header ("Content-type: application/json;charset=utf-8");
	die (json_encode(array('code' => $co,)));
}