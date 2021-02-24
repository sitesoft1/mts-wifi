<?php 

function is_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function json_error($description) {
	header("Content-type: application/json;charset=utf-8");
	die (json_encode(array('status' => 'error', 'description' => $description)));
}

function sendApi(){
		$status = true;
		$url = 'https://api.tarifnik.ru/make_handle';
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
		$popup__req_connect = trim($_POST['popup__req_connect']);
		$popup__req__surname = trim($_POST['popup__req__surname']);
		$popup__req_name = trim($_POST['popup__req_name']);
		$popup__req_middle_name = trim($_POST['popup__req_middle_name']);
		$popup__req_phone = trim($_POST['popup__req_phone']);
		$popup__req_address = trim($_POST['popup__req_address']);
		$region__request = trim($_POST['region__request']);
		$tellink = preg_replace('/[^0-9]/', '', $popup__req_phone);
		$telout = substr($tellink,1); 
		$telout = '7'.$telout;
		$telout = intval($telout);

		$json_array = [
			'_dealer_token' => '7b02b0121a4f7ad',
			'phone' => $telout,
			'name' => $popup__req_name.'тут 3',
			'fam' => $popup__req__surname,
			'pname' => $popup__req_middle_name,
			'comment' => $popup__req_connect.' / '.$popup__req_address,
			'suggestion' => [
				'value' => '',
				'unrestricted_value' => '',
				'data' => [ 'postal_code' => '', 'etc' => '...' ]
			]
		];

		$post_data = json_encode($json_array);

		$headers = array(
			'Accept: application/json',
			'Content-Type: application/json',
			'Content-Length: ' . strlen( $post_data ),
			'access_token: 7b02b0121a4f7ad'
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
			$ptitle = "Ошибка при отправке партнеру заявки по тарифу";

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
	$pagetitle = "Заявка по тарифу";

	$request_data = date('d F Y');
	$request_time = date('H:i');

	$popup__req_connect = trim($_POST['popup__req_connect']);
	$popup__req__surname = trim($_POST['popup__req__surname']);
	$popup__req_name = trim($_POST['popup__req_name']);
	$popup__req_middle_name = trim($_POST['popup__req_middle_name']);
	$popup__req_phone = trim($_POST['popup__req_phone']);
	$popup__req_address = trim($_POST['popup__req_address']);
	$region__request = trim($_POST['region__request']);
	$tellink = preg_replace('/[^0-9]/', '', $popup__req_phone);


	$message = '<body style="background:#f7f6f4; padding: 20px 10px;">
	<style>
		.letter_table a {text-decoration:none!important;color:white!important;}
	</style>
	<table class="letter_table">
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td colspan="2" style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>' . $pagetitle . '</b></td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Date and Time:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $request_data . '/' . $request_time . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Регион:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $region__request . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Тариф:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__req_connect . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Фамилия:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__req__surname . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Имя:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__req_name . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Отчество:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__req_middle_name . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Телефон:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;"><a href="tel:'.$tellink.'">' . $popup__req_phone . '</a></td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Адресс:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $popup__req_address . '</td>
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
if (isset($_GET['action']) and $_GET['action'] == 'mail3r') {

		$response = array();
		$inputTrue = array();
		$inputFalse = array();

	$check_request = array('popup__cons_apart','popup__cons_office','popup__cons_other');
	//$check_apart = $_POST['popup__cons_apart'];

	$required = array('popup__req_phone','popup__req_name');

	$checkapart = $_POST['request__apart'];
	//if($checkapart == 'on'){
		foreach ($required as $field){
			if (isset($_POST[$field]) and strlen($_POST[$field]) > 1) array_push($inputTrue, $field);
			else array_push($inputFalse, $field); 
		}

		$response = array('status' => 'success', 'description' => 'next');

		//header ("Content-type: application/json;charset=utf-8");
		//die (json_encode(array('status' => 'Test', 'description' => 'text')));

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