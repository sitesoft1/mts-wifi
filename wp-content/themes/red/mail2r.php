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
wpLog('post-mail2r', $_POST, false);

function is_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function json_error($description) {
	header("Content-type: application/json;charset=utf-8");
	die (json_encode(array('status' => 'error', 'description' => $description)));
}

function sendApi(){
		$status = true;
		$url = 'https://api.tarifnik.ru/v1.2/make_handle';
		/*array = {
  "_dealer_token": "0e68e48facb4d56",
  "phone": 79119589595,
  "name": "Ержан",
  "fam": "Калмыков",
  "pname": "Витальевич",
  "comment": "string",
  "suggestion": {
    "value": "г Самара, ул Ленина, д 1",
    "unrestricted_value": "443000, Самарская обл, г Самара, ул Ленина, д 1",
    "data": {
      "postal_code": "443000",
      "etc": "..."
    }
  }
}*/
		$popup__consult_name = trim($_POST['popup__consult_name']);
		$popup__consult_phone = trim($_POST['popup__consult_phone']);
		$popup__consultaddress = trim($_POST['popup__consultaddress']);
		$popup__consult_surname = trim($_POST['popup__consult_surname']);
		$popup__consult_middle_name = trim($_POST['popup__consult_middle_name']);

		$tellink = preg_replace('/[^0-9]/', '', $popup__consult_phone);
		$telout = substr($tellink,1); 
		$telout = '7'.$telout;
		$telout = intval($telout);

		$json_array = [
			'_personal_token' => '56ad24efca4f548cf1624cbbd40a0965',
			'campaign' => 120,
			'phone' => $telout,
			'name' => $popup__consult_name,
			'fam' => $popup__consult_surname,
			'pname' => $popup__consult_middle_name,
			'comment' => $popup__consultaddress,
			'suggestion' => [
				'value' => $popup__consultaddress,
				'unrestricted_value' => $popup__consultaddress,
				'data' => [ 'postal_code' => '', 'etc' => '...' ]
			]
		];

		$post_data = json_encode($json_array);

		$headers = array(
			'Accept: application/json',
			'Content-Type: application/json',
			'Content-Length: ' . strlen( $post_data )
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
		$response = curl_exec( $ch );
		//when curl failed: get curl error number and message
		//if( curl_errno( $ch ) ){
			
		//}

		//get the http return code
		$httpstatus = curl_getinfo( $ch, CURLINFO_HTTP_CODE);

		//close curl
		curl_close ( $ch );
		$mess='';
		//extract the results to array (param = true for array, false for object)
		$outresponse = json_decode( $response, true );
		
		if($httpstatus != 201){ $status = false;
			$rec = "cardoffer@yandex.ru";
			$sitename = "MTS-WiFi";
			$ptitle = "Ошибка при отправке партнеру заявки на консультацию";

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

			 mail($rec, $ptitle, $mssg, "Content-type: text/html; charset=\"utf-8\"\n From: $rec"); }
		if($httpstatus == 422){ $mess='phone'; }

		//DEBUG
		//echo "http_status=".$this->curl_error_message;
		//echo "http_status=".$this->http_status;
		//echo "<pre>"; var_dump( $this->api_response ); echo "</pre>";

		//return status
		$outarrs = array('response' => $response,'httpstatus' => $httpstatus,'out' => $outresponse,'status'=> $status,'json' => $post_data,'message' => $mess);
		return $outarrs;
}

function json_noerror($noerror,$desc) {

	$recepient = "cardoffer@yandex.ru";
	$sitename = "MTS-WiFi";
	$pagetitle = "Заявка На консультацию";

	$request_data = date('d F Y');
	$request_time = date('H:i');


	$popup__consult_name = trim($_POST['popup__consult_name']);
	$popup__consult_surname = trim($_POST['popup__consult_surname']);
	$popup__consult_middle_name = trim($_POST['popup__consult_middle_name']);
	$popup__consult_phone = trim($_POST['popup__consult_phone']);
	$popup__consultaddress = trim($_POST['popup__consultaddress']);
	$region__consult = trim($_POST['region__consult']);
	$tellink = preg_replace('/[^0-9]/', '', $popup__consult_phone);


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
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Регион:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $region__consult . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Фамилия:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__consult_surname . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Имя:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__consult_name . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Отчество:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__consult_middle_name . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Телефон:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;"><a href="tel:'.$tellink.'">' . $popup__consult_phone . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Адрес:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__consultaddress . '</td>
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

/*===== Отправление Email =====*/
if (isset($_GET['action']) and $_GET['action'] == 'mail2r') {

	$response = array();
	$inputTrue = array();
	$inputFalse = array();

	$check_request = array('popup__cons_apart','popup__cons_office','popup__cons_other');

	$required = array('popup__consult_phone','popup__consult_name','popup__consult_surname','popup__consult_middle_name');

	$checkapart = $_POST['check__apartment_name'];
	//if($checkapart == 'on'){
		foreach ($required as $field){
			if (isset($_POST[$field]) and strlen($_POST[$field]) > 1) array_push($inputTrue, $field);
			else array_push($inputFalse, $field); 
		}

		$response = array('status' => 'success', 'description' => 'next');

		if (!empty($inputFalse)) {json_error($inputFalse);}
		else { 
			$yesjson = json_noerror($response, 'desc'); 
			$apii = sendApi();

			if($yesjson === true && $apii['status'] === true) { $outstat = 'success'; }
			else { $outstat = 'error'; if(!empty($apii['message']) == 'phone') { array_push($inputFalse,'request__phone'); }   }

			header ("Content-type: application/json;charset=utf-8");
			die (json_encode(array('status' => $outstat, 'array' => $apii,'description' => $inputFalse)));
		}
	//}
}