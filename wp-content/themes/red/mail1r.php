<?php 

/*function is_email($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function json_error($description) {
	header("Content-type: application/json;charset=utf-8");
	die (json_encode(array('status' => 'error', 'description' => $description)));
}

function json_noerror($noerror,$desc) {

	//$recepient = "RamisNiyazov@yandex.ru";
	$recepient = "cardoffer@yandex.ru";
	$sitename = "MTS-WiFi";
	$pagetitle = "Заявка с главной";

	$request_data = date('d F Y');
	$request_time = date('H:i');

//$required = array('request__surname','request__middle_name','request__name','request__phone','request__address','request__entrance','request__floor','request__apart');
	$request__surname = trim($_POST['request__surname']);
	$request__middle_name = trim($_POST['request__middle_name']);
	$request__name = trim($_POST['request__name']);
	$request__phone = trim($_POST['request__phone']);
	$request__address = trim($_POST['request__address']);
	$request__entrance = trim($_POST['request__entrance']);
	$request__floor = trim($_POST['request__floor']);
	$request__apart = trim($_POST['request__apart']);
	$region__page = trim($_POST['region__page']);
	$tellink = preg_replace('/[^0-9]/', '', $request__phone);

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
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $region__page . '</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Фамилия:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">' . $request__surname . '</td>
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
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;"><a href="'.$tellink.'">' . $request__phone . '</td>
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

	mail($recepient, $pagetitle, $message, "Content-type: text/html; charset=\"utf-8\"\n From: $recepient");

	header ("Content-type: application/json;charset=utf-8");
	die (json_encode(array('status' => 'success', 'description' => $desc)));
}

function answerstepone($noerror){

	header ("Content-type: application/json;charset=utf-8");
	die (json_encode(array('status' => $noerror)));
}

//===== Отправление Email =====
if (isset($_GET['action']) and $_GET['action'] == 'mail1r') {

	$response = array();
	$inputTrue = array();
	$inputFalse = array();

	$check_request = array('check__apartment_name','check__office_name');

	//$required = array('request__surname','request__middle_name','request__name','request__phone','request__address','request__entrance','request__floor','request__apart');
	$required = array('request__phone');

	$checkapart = $_POST['check__apartment_name'];
	//if($checkapart == 'on'){
		foreach ($required as $field){
			if (isset($_POST[$field]) and strlen($_POST[$field]) > 1) array_push($inputTrue, $field);
			else array_push($inputFalse, $field); 
		}

		$response = array('status' => 'success', 'description' => 'next');

		if (!empty($inputFalse)) {json_error($inputFalse);}
		else { json_noerror($response, 'desc'); }
	//}
}*/

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
		$request__surname = trim($_POST['request__surname']);
		$request__middle_name = trim($_POST['request__middle_name']);
		$request__name = trim($_POST['request__name']);
		$request__phone = trim($_POST['request__phone']);
		$request__address = trim($_POST['request__address']);
		$request__entrance = trim($_POST['request__entrance']);
		$request__floor = trim($_POST['request__floor']);
		$request__apart = trim($_POST['request__apart']);

		$telout = preg_replace('/[^0-9]/', '', $request__phone);
		$telout = substr($telout,1); 
		$telout = '7'.$telout;
		$telout = intval($telout);

		$json_array = [
			'_dealer_token' => '7b02b0121a4f7ad',
			'phone' => $telout,
			'name' => $request__name.'тут 1',
			'fam' => $request__surname,
			'pname' => $request__middle_name,
			'comment' => $request__address.', подъезд '.$request__entrance.' этаж '.$request__floor.', кв.'.$request__apart,
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

		//DEBUG
		//echo "http_status=".$this->curl_error_message;
		//echo "http_status=".$this->http_status;
		//echo "<pre>"; var_dump( $this->api_response ); echo "</pre>";

		//return status
		$outarrs = array('response' => $response,'httpstatus' => $httpstatus,'out' => $outresponse,'status'=> $status,'json' => $post_data,'message' => $mess);
		return $outarrs;
}

function json_noerror($noerror,$desc) {

	//$recepient = "djpaoloboy@yandex.ru";
	$recepient = "cardoffer@yandex.ru";
	$sitename = "MTS-WiFi";
	$pagetitle = "Заявка с главной";

	$request_data = date('d F Y');
	$request_time = date('H:i');

//$required = array('request__surname','request__middle_name','request__name','request__phone','request__address','request__entrance','request__floor','request__apart');
	$request__surname = trim($_POST['request__surname']);
	$request__middle_name = trim($_POST['request__middle_name']);
	$request__name = trim($_POST['request__name']);
	$request__phone = trim($_POST['request__phone']);
	$request__address = trim($_POST['request__address']);
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

/*===== Отправление Email =====*/
if (isset($_GET['action']) and $_GET['action'] == 'mail1r') {

	$response = array();
	$inputTrue = array();
	$inputFalse = array();

	$check_request = array('check__apartment_name','check__office_name');

	//$required = array('request__surname','request__middle_name','request__name','request__phone','request__address','request__entrance','request__floor','request__apart');
	$required = array('request__phone','request__surname','request__name','request__middle_name');

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