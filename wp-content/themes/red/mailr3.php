<?php

//Функция логирования
function wpLog($filename, $data, $append=false)
{
    if(!$append){
        file_put_contents(__DIR__ . '/'. $filename . '.txt', var_export($data,true));
    }else{
        file_put_contents(__DIR__ . '/'. $filename . '.txt', var_export($data,true).PHP_EOL, FILE_APPEND);
    }
    
}
wpLog('post-mailr3', $_POST, false);

function is_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function json_error($description) {
	header("Content-type: application/json;charset=utf-8");
	die (json_encode(array('status' => 'error', 'description' => $description)));
}

function json_noerror($noerror,$desc) {

	$recepient = "cardoffer@yandex.ru";
	$sitename = "MTS-WiFi";
	$pagetitle = "Заявка на Консультацию";

	$request_data = date('d F Y');
	$request_time = date('H:i');

	$popup__req_connect = trim($_POST['popup__req_connect']);
	$popup__req_name = trim($_POST['popup__req_name']);
	$popup__req_phone = trim($_POST['popup__req_phone']);
	$popup__req_address = trim($_POST['popup__req_address']);
	$region__request = trim($_POST['region__request']);


	$message = '<body style="background:#f7f6f4; padding: 20px 10px;">
	<style>
		.letter_table a {text-decoration:none!important;color:white!important;}
	</style>
	<table class="letter_table">
		<tr style="margin-bottom:10px;background:linear-gradient(to right, #ebb864 0%, #DD82A6 100%);">
			<td colspan="2" style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>' . $pagetitle . '</b></td>
		</tr>
		<tr style="margin-bottom:10px;background:linear-gradient(to right, #ebb864 0%, #DD82A6 100%);">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Date and Time:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $request_data . '/' . $request_time . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:linear-gradient(to right, #ebb864 0%, #DD82A6 100%);">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Регион:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $region__request . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:linear-gradient(to right, #ebb864 0%, #DD82A6 100%);">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Имя:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__req_connect . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:linear-gradient(to right, #ebb864 0%, #DD82A6 100%);">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Отчество:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__req_name . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:linear-gradient(to right, #ebb864 0%, #DD82A6 100%);">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Телефон:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__req_phone . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:linear-gradient(to right, #ebb864 0%, #DD82A6 100%);">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Телефон:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__req_address . '</td>
		</tr>
	</table>
</body>';

	mail($recepient, $pagetitle, $message, "Content-type: text/html; charset=\"utf-8\"\n From: $recepient");

	header ("Content-type: application/json;charset=utf-8");
	die (json_encode(array('status' => $noerror, 'description' => $desc)));
}

function answerstepone($noerror){

	header ("Content-type: application/json;charset=utf-8");
	die (json_encode(array('status' => $noerror)));
}

/*===== Отправление Email =====*/
if (isset($_GET['action']) and $_GET['action'] == 'mail3r') {

		$response = array();
		$inputTrue = array();
		$inputFalse = array();

	$check_request = array('popup__cons_apart','popup__cons_office','popup__cons_other');
	//$check_apart = $_POST['popup__cons_apart'];

	$required = array('popup__req_connect','popup__req_name','popup__req_phone','popup__req_address');

	$checkapart = $_POST['request__apart'];
	//if($checkapart == 'on'){
		foreach ($required as $field){
			if (isset($_POST[$field]) and strlen($_POST[$field]) > 1) array_push($inputTrue, $field);
			else array_push($inputFalse, $field); 
		}

		$response = array('status' => 'success', 'description' => 'next');

		//header ("Content-type: application/json;charset=utf-8");
		//die (json_encode(array('status' => 'Test', 'description' => 'text')));

		 if(!empty($inputFalse)) {json_error($inputFalse);}
		else { json_noerror($response, 'desc'); }
	//}
}