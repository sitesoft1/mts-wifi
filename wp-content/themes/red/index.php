<?php /* Template name: Home */

session_start();

if(isset($_SESSION['currentParam']) && !empty($_SESSION['currentParam'])){
    $old_session = $_SESSION['currentParam'];
    unset($_SESSION['currentParam']);
}
$config = array(
    'direct' => 'direct',
    'adwords' => 'adwords',
    'yandex' => 'yandex',
    'google' => 'google',
    '0' => 'my_site',
);

$currentParam = $config['0'];

$reffer = $_SERVER['HTTP_REFERER'];

if(strpos($reffer, "yandex")!= 0 && strpos($reffer, "mts-wifi.ru")== 0)
{
    if(!empty($_SESSION['currentParam']))
        $_SESSION['currentParam'] = '';


    if(!empty($_GET['yclid']))
    {
        $currentParam = $config['direct'];
    }
    elseif(!empty($_GET['utm_source']) && $_GET['utm_source'] == 'yandex')
    {
        $currentParam = $config['direct'];
    }
    else
    {
        $currentParam = $config['yandex'];
    }


}
elseif(strpos($reffer, "google")!= 0 && strpos($reffer, "mts-wifi.ru")== 0)
{

    if(!empty($_SESSION['currentParam']))
        $_SESSION['currentParam'] = '';


    if(!empty($_GET['gclid']))
    {
        $currentParam = $config['adwords'];
    }
    elseif(!empty($_GET['utm_source']) && $_GET['utm_source'] == 'google')
    {
        $currentParam = $config['adwords'];
    }
    else
    {
        $currentParam = $config['google'];
    }

}

if(empty($_SESSION['currentParam']) && $currentParam != 'my_site')
{
    $_SESSION['currentParam'] = $currentParam;
}

if(!isset($_SESSION['currentParam']) || empty($_SESSION['currentParam'])){
    $_SESSION['currentParam'] = $old_session;
}

if(!empty($_GET['utm_company_id']) && $_GET['utm_company_id'] == '102'){
    $_SESSION['currentParam'] = $config['direct'];
}elseif(!empty($_GET['utm_company_id']) && $_GET['utm_company_id'] == '120'){
    $_SESSION['currentParam'] = $config['adwords'];
}

if($_SESSION['currentParam'] == 'direct'){
    $campaign = 102;//102 - Yandex
}elseif($_SESSION['currentParam'] == 'adwords'){
    $campaign = 120;//120 - google
}else{
    $campaign = 102;//102 - Yandex
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<link rel="icon" href="<?php bloginfo('template_url'); ?>/favicon.png" type="image/png">
	<title><?php the_title(); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="yandex-verification" content="f81136422d83c1c6" />


	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?php the_title(); ?>" />
	<meta property="og:url" content="<?php home_url(); ?>" />
	<meta property="og:site_name" content="<?php the_title(); ?>" />

	<?php $key__fields = get_field('key__fields',get_the_ID());
	if($key__fields):?>
		<meta name="keywords" content="<?php echo $key__fields; ?>">
	<?php endif; ?>
	<?php $desc__fields = get_field('desc__fields',get_the_ID());
	if($desc__fields):?>
		<meta name="description" content="<?php echo $desc__fields; ?>">
	<?php endif; ?>
	
	
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/app.min.css">
<?php

	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = @$_SERVER['REMOTE_ADDR'];
	
	if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
	elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
	else $ip = $remote;
	$get_result='';

	function isTerm($termid){
		$retterm = false;
		if(!empty($termid)){
			$gettermis = get_terms(['taxonomy' => 'area','hide_empty' => false,'meta_key' => 'region_google_id', 'meta_value' => $termid]);
			if($gettermis){ $retterm=true; }
		}
		return $retterm;
	}


	if(!empty($_GET['utm_city'])){ $get_result = $_GET['utm_city']; }
	else if(isTerm($_GET['utm_city_interest']) === true) {
		$get_result = $_GET['utm_city_interest'];
	}
	else if(isTerm($_GET['utm_city_interest']) === false && isTerm($_GET['utm_city_physical']) === true) {
		$get_result = $_GET['utm_city_physical'];
	}
	
	$utmcity = $get_result;
	$utmsection = $_GET['section'];
	?>
</head>
<body>
<div id="main">

	<?php $termstxt = get_terms(array('taxonomy' => 'area','hide_empty' => false,'parent' => 0));
		$txterms = '';
		foreach($termstxt as $txt) {
			if(!empty($txt)) {
				$txterms = $txterms.$txt->name.' - ['.$txt->slug.']</br>';
				$terms = get_terms(array('taxonomy' => 'area','hide_empty' => false,'parent' => $txt->term_id));
				$finalCity = $txt->term_id;
				foreach($terms as $trm){
					$txterms =  $txterms.'------'.$trm->name.' - ['.$trm->slug.']</br>';
				}
				
			}
		}

	?>
	<div id="header">
		<div class="content__fix">
			<div class="header__info_menu">
				<div class="header__info">
					<a href="/" class="header__info_logo">
						<img src="<?php echo get_field('red_logo'); ?>" alt="Logo">
					</a>
					<div class="header__info_line">
						<a class="header__info_support">
							<span class="header__info__support__icon"></span>
							<span class="header__info__support__title">Служба поддержки</span>
						</a>
						<a class="header__info_location">
							<span class="header__info__location__icon"></span>
							<?php $field_term_id = get_term_by('slug', $utmcity, 'area');  ?>
							<span class="header__info__location__title" tab="<?php echo $ip; ?>" tab-city="<?php echo $utmcity; ?>" tab-region="<?php print_r($field_term_id->parent); ?>" tab-section="<?php echo $utmsection = $_GET['section']; ?>"></span>
						</a>
						<?php
                        
                            if($_SESSION['currentParam'] == 'direct'){
                                $is_phone = get_field('is_phone', get_the_ID());
                            }elseif($_SESSION['currentParam'] == 'adwords'){
                                $is_phone = get_field('is_phone_google', get_the_ID());
                            }else{
                                $is_phone = get_field('is_phone', get_the_ID());
                            }
                            
                            $nunumb_phone= preg_replace('/[^0-9]/', '', $is_phone);
						?>
						<a href="tel: <?php echo $nunumb_phone; ?>" class="header__info_connect" onclick="return gtag_report_conversion('tel:<?php echo $nunumb_phone; ?>');">Бесплатный звонок</a>
						<a id="header__info_lk"></a>
					</div>
				</div>
				<div class="header__questions_wrap">
					<div class="header__location_svg"></div>
					<div class="header__questions_locaion__text">Вы находитесь в Городе <span></span></div>
					<div class="header__questions_buttons">
						<a class="header__quest_yes">Да</a>
						<a class="header__quest_change">Изменить город</a>
					</div>
				</div>
				<div class="header__menu">
					<a class="header__mobile_menu"><span></span></a>
					<div class="header__menu_wrap">
						<a class="header__menu_mobile_logo"><img src="<?php echo get_field('red_logo'); ?>" alt="Logo"></a>
						<a class="header__menu_mobile_close"></a>
						<ul class="header__menu_ul height">
							<li><a id="all_tariff" class="active">Все тарифы</a></li>
							<li><a tab="#items__three">Мобильная связь + Интернет + ТВ</a></li>
							<li><a tab="#items__two">Интернет + ТВ</a></li>
							<li><a tab="#items__one">Интернет</a></li>
							<li><a tab="#items__onetwo">Цифровое ТВ</a></li>
							<li><a tab="#items__twoduo">Мобильная связь + ТВ</a></li>
							<li><a tab="#items__twothird">Интернет + Мобильная связь</a></li>
						</ul>
						<a class="header__info_support__mobile">
							<span class="header__info__support__icon__mobile"></span>
							<span class="header__info__support__title__mobile">Служба поддержки</span>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php 
		$img_banner_jpeg = get_field('sales_banner',get_the_ID());
		$img_banner_webp = get_field('sales_banner_webp',get_the_ID());
		$img_banner_jpeg_mobile = get_field('sales_banner_mobile',get_the_ID());
		$img_banner_webp_mobile = get_field('sales_banner_mobile_webp',get_the_ID());
		$array_area = get_field('select_area',get_the_ID()); //print_r($array_area);
		$arrays_finish=''; $af=1;
		foreach($array_area as $area){
			if($af==1){ $arrays_finish = $area->term_id; }
			else { $arrays_finish = $arrays_finish.','.$area->term_id; }
			$af++;
		}

		if(!empty($img_banner_jpeg)){
			$string_webp = '';
			$string_webp2 = '';
			$allcheckit = get_field('all_area_check',get_the_ID());
			$stylestring='';
			if($allcheckit[0] == 'all'){ $stylestring=' style="display:flex;" all-tab="true"'; }
			else { $stylestring=' all-tab="false"'; }
			if(!empty($img_banner_webp)){ $string_webp = '<source type="image/webp" srcset="'.$img_banner_webp.'">'; }
			if(!empty($img_banner_webp_mobile)){ $string_webp2 = '<source type="image/webp" srcset="'.$img_banner_webp_mobile.'">'; }
			echo '<div id="temporary_banner" regions="'.$arrays_finish.'"'.$stylestring.'>
					<div class="content__fix">';
					echo '<picture id="pic_banner_desc">
							'.$string_webp.'
							<source type="image/jpeg" srcset="'.$img_banner_jpeg.'">
							<img src="'.$img_banner_jpeg.'">
						</picture>';
					echo '<picture id="pic_banner_mobile">
							'.$string_webp2.'
							<source type="image/jpeg" srcset="'.$img_banner_jpeg_mobile.'">
							<img src="'.$img_banner_jpeg_mobile.'">
						</picture>';
			echo '</div></div>';
		}

	?>
	<div id="sldrbnr" style="background:url(<?php echo get_field('banner_img'); ?>) no-repeat;background-size: cover;background-position:center center;">
		<div class="content__fix">
			<div class="sldrbnr__line_wrap">
				<h2 class="sldrbnr__title">
					<?php echo get_field('banner_title_small_text'); ?>
					<strong><?php echo get_field('banner_title_big_text'); ?></strong>
				</h2>
				<a href="#popup__call_wrap" class="sldrbnr__button first__call">Подключение</a>
			</div>
		</div>
	</div>
	<div id="home__content">
		<div class="content__fix home__content__fix">
			<div class="tmr__wrap">
				<h2 class="tmr__title"><?php echo get_field('timer_title'); ?></h2>
				<p class="tmr__desc"><?php echo get_field('timer_desc'); ?></p>
				<div class="tmr__main_wrap">
					<div class="tmr__day_wrap">
						<div class="tmr__numbers">
							<span>2</span>
							<span>3</span>
						</div>
						<div class="tmr__label">Дней</div>
					</div>
					<div class="tmr__hour_wrap">
						<div class="tmr__numbers">
							<span>1</span>
							<span>2</span>
						</div>
						<div class="tmr__label">Часов</div>
					</div>
					<div class="tmr__minutes_wrap">
						<div class="tmr__numbers">
							<span>1</span>
							<span>2</span>
						</div>
						<div class="tmr__label">Минут</div>
					</div>
					<div class="tmr__seconds_wrap">
						<div class="tmr__numbers">
							<span>2</span>
							<span>5</span>
						</div>
						<div class="tmr__label">Секунд</div>
					</div>
				</div>
				<a class="tmr__button">Тарифы</a>
			</div>
			<div class="steps__wrap">
				<div class="steps__tariffs__wrap">
					<div class="steps__icons">
						<div class="steps__icon_text">
							<span class="steps__icon message" style="background:url(<?php echo get_field('connect_icon_one'); ?>) no-repeat;background-size:100% auto;"></span>
							<span class="steps__text"><?php echo get_field('connect_desc_icon_one'); ?></span>
						</div>
						<div class="steps__arrows"></div>
						<div class="steps__icon_text">
							<span class="steps__icon chatmessage" style="background:url(<?php echo get_field('connect_icon_two'); ?>) no-repeat;background-size:100% auto;"></span>
							<span class="steps__text"><?php echo get_field('connect_desc_icon_two'); ?></span>
						</div>
						<div class="steps__arrows"></div>
						<div class="steps__icon_text">
							<span class="steps__icon global" style="background:url(<?php echo get_field('connect_icon_three'); ?>) no-repeat;background-size:100% auto;"></span>
							<span class="steps__text"><?php echo get_field('connect_desc_icon_three'); ?></span>
						</div>
						<div class="steps__arrows"></div>
					</div>
					<a href="#popup__call_wrap" class="steps__button first__call">Подключиться</a>
                    <?
                    if($_GET['test']){
                        echo '<pre>';
                        echo getenv("HTTP_REFERER");
                        print_r($_SESSION);
                        echo '</pre>';
                    }
                    ?>
				</div>
			</div>
			<div class="tariff__zero_element"></div>
			<div class="tariff__big_wrap">
				<div class="tariff__not_founding">
					<div class="not__found_text">К сожалению, в вашем городе тарифы не найдены</div>
					<a class="not__found_button">Изменить город</a>
				</div>
			</div>
			<div class="request__wrap">
				<div class="request__form">
					<h3 class="request__title"><?php echo get_field('page_request_title'); ?></h3>
					<p class="request__desc"><?php echo get_field('page_request_desc'); ?></p>
					<form id="form1r">
						<div class="request__checkbox">
							<label for="check__apartment" class="request__label_chek">
								<span class="check__wrap active">
									<input type="checkbox" name="check__apartment_name" id="check__apartment" class="check__box" checked="checked">
									<span class="check__title">В квартиру</span>
								</span>
							</label>
							<label for="check__office" class="request__label_chek">
								<span class="check__wrap">
									<input type="checkbox" name="check__office_name" id="check__office" class="check__box">
									<span class="check__title">В офис/в частный дом</span>
								</span>
							</label>
						</div>
						<div id="request__apart">
							<input type="hidden" name="region__page" id="region_page">
                            <input type="hidden" name="region__id" class="region_id">
                            <input type="hidden" name="campaign__id" value="<?php echo $campaign; ?>" class="campaign_id">
                            <input type="hidden" name="region__name" class="region_name">
                            <input type="hidden" name="region__city" class="region_city">
							<div class="request__row_third hidden__office">
								<label for="request__surnamename" id="label__surname" class="all__label">
									<input type="text" name="request__surname" id="request_surname" class="request__input" placeholder="Фамилия">
								</label>
								<label for="request__name" id="label__name" class="all__label">
									<input type="text" name="request__name" id="request_name" class="request__input" placeholder="Имя">
								</label>
								<label for="request__middlename" id="label__middlename" class="all__label">
									<input type="text" name="request__middle_name" id="request__middlename" class="request__input" placeholder="Отчество">
								</label>
							</div>
							<div class="request__row_first hidden__office">
								<label for="request_phone" class="all__label" id="label__telephone">
									<input type="text" name="request__phone" id="request_phone" class="request__input" placeholder="Ваш телефон*">
								</label>
							</div>
							<h3 class="request__address_title hidden__office">Ваш адрес</h3>
							<div class="request__row_four hidden__office">
								
                                <!--<label for="request_address" class="all__label" id="label__address"><input type="text" name="request__address" id="request_address" class="request__input request_address" placeholder="Введите ваш адрес"></label>-->
								<label for="request_address" class="all__label__dadata" id="label__address">
                                    <input type="text" name="request__address" id="request_address" class="request__input request_address" placeholder="Введите ваш адрес">
                                    <input type="hidden" name="request__dadata__address" class="request_dadata_address">
                                </label>
								
                                
                                <label for="request_entrance" class="all__label" id="label__entrance">
									<input type="text" name="request__entrance" id="request_entrance" class="request__input" placeholder="Подъезд">
								</label>
								<label for="request_floor" class="all__label" id="label__floor">
									<input type="text" name="request__floor" id="request_floor" class="request__input" placeholder="Этаж">
								</label>
								<label for="request_apart" class="all__label" id="label__apart">
									<input type="text" name="request__apart" id="request_apart" class="request__input" placeholder="Квартира">
								</label>
							</div>
							<span class="request__description hidden__office"><?php echo get_field('page_request_warning_fields'); ?></span>
							<a href="<?php echo get_privacy_policy_url(); ?>" target="_blank" class="request__privacy hidden__office"><?php echo get_field('page_request_privacy'); ?></a>
							<div class="request__text_office show__office"><?php echo get_field('page_request__ooffice'); ?></div>
							<?php
                            
                            if($_SESSION['currentParam'] == 'direct'){
                                $ourFull = get_field('is_phone');
                            }elseif($_SESSION['currentParam'] == 'adwords'){
                                $ourFull = get_field('is_phone_google');
                            }else{
                                $ourFull = get_field('is_phone');
                            }
                                    $strOur = preg_replace('/[^0-9]/', '', $ourFull);
                                    $nOur = substr($strOur, 1);
                                    
                                    $mtsFull = get_field('phone_is_mts');
                                    $strMts = preg_replace('/[^0-9]/', '', $mtsFull);
                                    $nMts = substr($strMts, 1);
							?>
							<a href="tel:<?php echo $strMts; ?>" onclick="return gtag_report_conversion('tel:<?php echo $strMts; ?>');" class="request__office_tel show__office"><?php echo $mtsFull; ?></a>
						</div>
					</form>
					<!--<button id="request__submit" class="hidden__office" onclick="return gtag_report_conversion('Form');">Отправить</button>-->
					<button id="request__submit" class="hidden__office">Отправить</button>
					<div class="req__ok all__ok"><?php echo get_field('sent_ok__page_form'); ?></div>
				</div>
			</div>
			<div class="red__questions">
				<h3 class="red__questionds__title"><?php echo get_field('questions_title'); ?></h3>
				<p class="red__questions__desc"><?php echo get_field('questions_desc'); ?></p>
				<div class="red__questions__buttons">
					<a class="button__expert questions__button">Связаться со специалистом</a>
					<a href="#popup__consult_wrap" class="button__request questions__button">Обратный звонок</a>
				</div>
			</div>
		</div>
	</div>
	<div id="footer">
		<div class="content__fix">
			<div class="footer__content">
				<h6 class="footer__title"><?php echo get_field('privacy_title'); ?></h6>
				<p class="footer_desc"><?php echo get_field('privacy_desc'); ?></p>
			</div>
		</div>
	</div>
 
	<div class="hidden__location">
		<div class="location__wrap">
			<div class="find__close_row">
				<form id="rowfind">
					<label for="" class="find__label">
						<input type="text" name="input__find" id="input_find" placeholder="Поиск по городу">
						<span class="find__icon"></span>
					</label>
				</form>
				<a class="location__close"></a>
			</div>
			<div class="location__grid">
				
				<ul>
				</ul>
			</div>
		</div>
	</div>
 
</div>

<div class="hidden__popup">
	<div id="popup__call_wrap">
		<p class="popup__call_text"><?php echo get_field('popup__callphone_text_up'); ?></p>
		<a href="tel:<?php echo $strOur; ?>" onclick="return gtag_report_conversion('tel:<?php echo $strOur; ?>');"  class="request__call_phone"><?php echo $ourFull; ?></a>
		<!--<p class="popup__call_desc"><?php echo get_field('popup__callphone_text_down'); ?></p>-->
		<a class="popup__call_button">Заказать обратный звонок</a>
	</div>
</div>
<div class="hidden__popup">
	<div id="popup__interesting_wrap">
		<h4 class="popup__interesting_title"><?php echo get_field('popup__interesting_title'); ?></h4>
		<a class="popup__inter_apart_button">Подключение в квартиру</a>
		<a class="popup__inter__other_button">Другие вопросы</a>
	</div>
</div>
<div class="hidden__popup">
	<div id="popup__phone__wrap">
		<p class="popup__phone__desc"><?php echo get_field('popup__call_text'); ?></p>
		<a href="tel:<?php echo $strMts; ?>" onclick="return gtag_report_conversion('tel:<?php echo $strMts; ?>');" class="popup__phone_tel"><?php echo $mtsFull; ?></a>
	</div>
</div>
<div class="hidden__popup">
	<div id="popup__request_wrap">
		<h4 class="popup__req__title"><?php echo get_field('popup__request_title');  ?></h4>
		<p class="popup__req_desc">Оставляйте заявку на подключение интересующих Вас услуг онлайн</p>
		<form id="form3r">
			<div class="request__checkbox">
				<input type="hidden" name="region__request" id="region_request">
                <input type="hidden" name="region__id" class="region_id">
                <input type="hidden" name="campaign__id" value="<?php echo $campaign; ?>" class="campaign_id">
                <input type="hidden" name="region__name" class="region_name">
                <input type="hidden" name="region__city" class="region_city">
				<label for="request_apart" class="request__label_chek">
					<span class="check__request active">
						<input type="checkbox" name="request__apart" id="request_apart" class="check__box" checked="checked">
						<span class="check__title">В квартиру</span>
					</span>
				</label>
				<label for="request_office" class="request__label_chek">
					<span class="check__request">
						<input type="checkbox" name="request__office" id="request_office" class="check__box">
						<span class="check__title">В офис/в частный дом</span>
					</span>
				</label>
			</div>
			<div class="popup__request_inputs">
				<label for="popup__reqconnect" id="label__reqconnect" class="all__label hidden__popup_apart">
					<input type="text" name="popup__req_connect" id="popup__reqconnect" value="" readonly>
				</label>
				<label for="popup__req_surname" id="label__reqname" class="all__label hidden__popup_apart">
					<input type="text" name="popup__req__surname" id="popup__req_surname" placeholder="Ваша фамилия">
				</label>
				<label for="popup__reqname" id="label__reqname" class="all__label hidden__popup_apart">
					<input type="text" name="popup__req_name" id="popup__reqname" placeholder="Ваше имя">
				</label>
				<label for="popup__req_middlename" id="label__reqname" class="all__label hidden__popup_apart">
					<input type="text" name="popup__req_middle_name" id="popup__req_middlename" placeholder="Ваша фамилия">
				</label>

                <label for="popup__reqaddress" id="label__reqaddress" class="all__label__dadata hidden__popup_apart">
                    <input type="text" name="popup__req_address" id="popup__reqaddress" class="request_address" placeholder="Адрес, по которому необходимо произвести подключение">
                    <input type="hidden" name="request__dadata__address" class="request_dadata_address">
                </label>
                
				<label for="popup__reqphone" id="label__reqphone" class="all__label hidden__popup_apart">
					<input type="text" name="popup__req_phone" id="popup__reqphone" placeholder="Ваш телефон*">
				</label>
				
				<p class="popup__request_label hidden__popup_apart"><?php echo get_field('popup__request__warning_text'); ?></p>
				<a href="<?php echo get_privacy_policy_url(); ?>" target="_blank" class="popup__request_privacy hidden__popup_apart"><?php echo get_field('popup__request__privacy_text'); ?></a>
				<p class="popup__request_start_desc hidden__popup_office"><?php echo get_field('page_request_office'); ?></p>
				<a href="tel:<?php echo $strMts; ?>" onclick="return gtag_report_conversion('tel:<?php echo $strMts; ?>');" class="popup__request_telephone hidden__popup_office"><?php echo $mtsFull; ?></a>
			</div>
		</form>
		<button id="popup__request_submit" class="hidden__popup_apart" onclick="return gtag_report_conversion('Request');">Отправить</button>
		<div class="popup_req__ok all__ok"><?php echo get_field('sent_ok__popreq'); ?></div>
	</div>
</div>
<div class="hidden__popup">
	<div id="popup__features_wrap">
		<h4 class="popup__features_title"><?php echo get_field('tariff_title'); ?></h4>
		<p class="popup__features_desc">Пакет включает мобильную связь, Домашний интернет, и ТВ, а также 150 ТВ каналов в мобильном приложении МТС ТВ 2.0(бесплатно для подключения), 5 мультискринов. Возможность деления пакетом минут и трафиком в рамках услухи. Общий пакет — 30 Гб раздачи уже включено в тариф. Подключение мультирум на льготных условия — 10 руб/мес. за вторую и последующие приставки. Единый счет и оплата с побильного номера телефона.</p>
		<button id="popup__features_button_connect">Подключение</button>
	</div>
</div>
<div class="hidden__popup">
	<div id="popup__select_city">
		<h4 class="popup__features_title"><?php echo get_field('popup_select_city'); ?></h4>
	</div>
</div>
<div class="hidden__popup">
	<div id="popup__consult_wrap">
		<h4 class="popup__consult_title"><?php echo get_field('popup_consultation'); ?></h4>
		<form id="form2r">
			<div class="request__checkbox">
				<input type="hidden" name="region__consult" id="region_consult">
                <input type="hidden" name="region__id" class="region_id">
                <input type="hidden" name="campaign__id" value="<?php echo $campaign; ?>" class="campaign_id">
                <input type="hidden" name="region__name" class="region_name">
                <input type="hidden" name="region__city" class="region_city">
				<label for="popup__consapart">
					<span class="request__label_cons active">
						<input type="checkbox" name="popup__cons_apart" id="popup__consapart" class="check__box" checked="checked">
						<span class="check__title">В квартиру</span>
					</span>
				</label>
				<label for="popup__consoffice">
					<span class="request__label_cons">
						<input type="checkbox" name="popup__cons_office" id="popup__consoffice" class="check__box">
						<span class="check__title">В офис/в частный дом</span>
					</span>
				</label>
				<label for="popup__consother">
					<span class="request__label_cons">
						<input type="checkbox" name="popup__cons_other" id="popup__consother" class="check__box">
						<span class="check__title">Другие вопросы</span>
					</span>
				</label>
			</div>
			<div class="popup__consult_inputs">
				<label for="popup__consultsurname" class="all__label hidden__consul_office">
					<input type="text" name="popup__consult_surname" id="popup__consultsurname" class="" placeholder="Ваша фамилия">
				</label>
				<label for="popup__consultname" class="all__label hidden__consul_office">
					<input type="text" name="popup__consult_name" id="popup__consultname" class="" placeholder="Ваше имя">
				</label>
				<label for="popup__consultmiddlename" class="all__label hidden__consul_office">
					<input type="text" name="popup__consult_middle_name" id="popup__consultmiddlename" class="" placeholder="Ваше отчество">
				</label>

                <label for="popup__consuladdress" class="all__label__dadata hidden__consul_other">
                    <input type="text" name="popup__consultaddress" id="popup__consuladdress" class="request_address" placeholder="Адрес, по которому необходимо произвести подключение">
                    <input type="hidden" name="request__dadata__address" class="request_dadata_address">
                </label>
                
				<label for="popup__consultphone" class="all__label hidden__consul_office">
					<input type="text" name="popup__consult_phone" id="popup__consultphone" class="" placeholder="Ваш телефон*">
				</label>
				
			</div>
			<p class="popup__consult_label hidden__consul_office"><?php echo get_field('popup__request__warning_text');  ?></p>
			<a href="<?php echo get_privacy_policy_url(); ?>" target="_blank" class="popup__consult_privacy hidden__consul_office"><?php echo get_field('popup__request__privacy_text');  ?></a>
			<p class="popup__consult_start_desc hidden__consul_apart"><?php echo get_field('page_request_office');  ?></p>
			<a href="tel:<?php echo $strMts; ?>" onclick="return gtag_report_conversion('tel:<?php echo $strMts; ?>');" class="popup__consult_telephone hidden__consul_apart"><?php echo $mtsFull; ?></a>
		</form>
		<!--<button id="popup__consult_submit" class="hidden__consul_office" onclick="return gtag_report_conversion('Consultation');">Отправить</button>-->
		<button id="popup__consult_submit" class="hidden__consul_office">Отправить</button>
		<div class="consult__ok all__ok"><?php echo get_field('sent_ok__consult'); ?></div>
	</div>
</div>

<div class="hidden__popup">
	<div id="lk__popup">
		<a href="<?php echo get_field('link__lk',get_the_id()); ?>" id="lk__link" target="_blank"><?php echo get_field('title__link_lk',get_the_id()); ?></a>
	</div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/css/suggestions.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/suggestions-jquery@20.3.0/dist/js/jquery.suggestions.min.js"></script>
<script>
    var dadata_token = "7225ec19a2a62bfc433f02c0fb6867ff800848a3";
    function suggest(query) {
        var serviceUrl = "https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address";
        var request = {
            "query": query
        };
        var params = {
            type: "POST",
            contentType: "application/json",
            headers: {
                "Authorization": "Token " + dadata_token
            },
            async: false,
            data: JSON.stringify(request)
        }
        return $.ajax(serviceUrl, params);
    }
    
    $(".request_address").suggestions({
        token: dadata_token,
        type: "ADDRESS",
        /* Вызывается, когда пользователь выбирает одну из подсказок */
        onSelect: function(suggestion) {
            console.log(suggestion);
            var json_suggestion = JSON.stringify(suggestion);
            $(".request_dadata_address").val(json_suggestion);
        },
        /* Вызывается, если пользователь не выбрал ни одной подсказки */
        /*
        onSelectNothing: function() {
            var input_addres = $(".request_address").val();
            var promise = suggest(input_addres);
            promise.done(function(response) {
                    var json_all_suggestions = JSON.stringify(response.suggestions[0]);
                    $(".request_dadata_address").val(json_all_suggestions);
                });
        }
        */
    });
</script>

<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/libs/magnific/magnific-popup.css">
<script type="text/javascript" src="<?php bloginfo('template_url'); ?>/js/appfull.min.js"></script>

<script src="https://api-maps.yandex.ru/2.1/?apikey=252ddaca-a6db-4d30-94ae-460a80828cc8&lang=ru_RU" type="text/javascript"></script>
<script type="text/javascript">

    //console.log('start js!!!');

function isEmpty(str){ return(!str||0===str.length);}
 
	$('body').on('click','.location__add_cities li a',function(){
		var textCity = $(this).html();
		var parseCity = $(this).attr('tab');
		
        $('.region_city').val(textCity);
        var tarifnik_id = $(this).data('tarifnik_id');
        $('.region_id').val(tarifnik_id);
        var region_name = $('.location__grid_row > [data-tarifnik_id="'+tarifnik_id+'"]').text();
        $('.region_name').val(region_name);
		
		$('.hidden__location').hide();
		$('.header__info__location__title').html('');
		$('.header__info__location__title').html(textCity);
  
		$('.header__questions_wrap').hide();
	    $('.header__info_menu').removeClass('questions');
		parseCities(textCity, parseCity);
	});
 

//Выводит список регионов и городов по умолчанию
function listCity(nameCity, acti){
	if(acti == 'starting') { cityaction = 'starting'; }
	else if(acti == 'update'){  cityaction = 'update';  }
	var ww = window.outerWidth;
	//console.log(nameCity);
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	var data = {
		action: 'action_naming',
		name:nameCity,
		cityaction: cityaction,
		width:ww,
		find:'',
		_ajax_nonce: '<?php echo wp_create_nonce( 'my_ajax_nonce' ); ?>'
	};
	//console.log(ajaxurl);

	$.ajax({
		method: "POST",
		url: ajaxurl,
		data: data,
		dataType: "json",
		success: function(data) { 
			//console.log(data);
			$('.location__grid').html(' ');
			$('.location__grid').html(data.status);
			$('.header__info__location__title').html(' ');
			$('.header__info__location__title').html(data.cityname);
			//console.log(data);

            if(nameCity){
                $('.region_city').val(nameCity);
                var tarifnik_id = $('[title="'+nameCity+'"]').data('tarifnik_id');
                $('.region_id').val(tarifnik_id);
                var region_name = $('.location__grid_row > [data-tarifnik_id="'+tarifnik_id+'"]').text();
                $('.region_name').val(region_name);
            }
            
			
		}
	});
}


function addContent(geotag){
    
    //console.log(geotag);
    
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	var data = {
		action: 'action_name',
		geo:geotag,
		_ajax_nonce: '<?php echo wp_create_nonce( 'my_ajax_nonce' ); ?>'
	};

	$.ajax({
		method: "POST",
		url: ajaxurl,
		data: data,
		dataType: "json",
		success: function(data) {
		    
		    //console.log('success: ');
			//console.log(data);
			
			var regions = $('#temporary_banner').attr('regions');
			var alltab = $('#temporary_banner').attr('all-tab');
			if(isEmpty(regions) === false){
				var cut_reg = regions.split(',');
				var checkReg=false;
				
				cut_reg.forEach(function(element){
					if(element == data.slug_city){ checkReg=true; }
				});
				if(checkReg === true){ $('#temporary_banner').show();  }
				else { 
					if(alltab == 'true'){ $('#temporary_banner').show();  }
					else { $('#temporary_banner').hide();  }
				}
				
			}
			if(data.status == 'notfound' ){
				
				var outCodes = '<div class="tariff__not_founding"><div class="not__found_text">К сожалению, в вашем городе тарифы не найдены</div><a class="not__found_button">Изменить город</a></div>';
				$('.tariff__big_wrap').html(' ');
				$('.tariff__big_wrap').html(outCodes);
				$('.location__close').click(function(){
					$('.hidden__location').fadeOut();
				});
			}
			else {
				//console.log(data.ismenu);
				$('.tariff__big_wrap').html(' ');
				$('.tariff__big_wrap').html(data.status);
				$('.header__menu_ul').html(' ');
				$('.header__menu_ul').html(data.ismenu);
			}
			
		}
	});
	
	
}



function parseCities(text, tab){
	var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	var data = {
		action: 'action_name',
		geo:text,
		geoTab: tab,
		_ajax_nonce: '<?php echo wp_create_nonce( 'my_ajax_nonce' ); ?>'
	};
	$.ajax({
		method: "POST",
		url: ajaxurl,
		data: data,
		dataType: "json",
		success: function(data) { 
			var regions = $('#temporary_banner').attr('regions');
			var alltab = $('#temporary_banner').attr('all-tab');
			if(isEmpty(regions) === false){
				var cut_reg = regions.split(',');
				var checkReg=false;
				
				cut_reg.forEach(function(element){
					if(element == data.slug_city){ checkReg=true; }
				});
				if(checkReg === true){ $('#temporary_banner').show();  }
				else { 
					if(alltab == 'true'){ $('#temporary_banner').show();  }
					else { $('#temporary_banner').hide();  }
				}
				
			}

			if(data.status != 'notfound' ){
				$('.tariff__big_wrap').html(' ');
				$('.tariff__big_wrap').html(data.status);
				$('.header__menu_ul').html('');
				$('.header__menu_ul').html(data.ismenu);

			}
			
		}
	});
}

function questionsShow(){
	//$('.header__questions_wrap').css('display','flex');
	var isTown = $('.header__info__location__title').html();
	$('.header__questions_locaion__text span').html(isTown);
	//$('.header__info_menu').addClass('questions');
}

function initGeolocation(){
	var coordin='';
	ymaps.ready(init);
	 
	function init() {
		var geoIpCity;
		var yandexCity;
	    
	    navigator.geolocation.getCurrentPosition(position => {
			var lat = position.coords.latitude;
			var long = position.coords.longitude;

            //ZAKOMENTIT
			//var lat = 64.564239;//second
            //var long = 39.831502;//first
			//console.log('lat:'+lat+' long:'+long);
			
			var myGeocoder = ymaps.geocode([lat,long]);
			myGeocoder.then(
			    function (res) {
			        // Выведем в консоль данные, полученные в результате геокодирования объекта.
					var objs = res.geoObjects.toArray();
					geoIpCity = objs[0].properties.getAll().metaDataProperty.GeocoderMetaData.Address.Components[4].name;
                    //console.log(geoIpCity);

                    var geoProvince = objs[0].properties.getAll().metaDataProperty.GeocoderMetaData.Address.Components[2].name;
                    //console.log(geoProvince);
					
					var geoTest = objs[0].properties.getAll().metaDataProperty.GeocoderMetaData.Address.Components;
                    //console.log(geoTest);
                    
					addContent(geoIpCity);
					listCity(geoIpCity,'starting');
					setTimeout(function(){ questionsShow(); },1000);
			    },
			    function (err) {
			        // Обработка ошибки.
			    }
			);

		},function(error){
			var geolocation = ymaps.geolocation;
			geolocation.get({
			    provider: 'yandex',
			    mapStateAutoApply: true
			}).then(function (result) {
			    yandexCity = result.geoObjects.get(0).properties._data.name;
			    
			    addContent(yandexCity);
			    listCity(yandexCity,'starting');
			    setTimeout(function(){ questionsShow(); },1000);
			    //$(".hidden__location").fadeIn();
			});
		});
		
	}
}

function canUseWebp(){var e=document.createElement("canvas");return!(!e.getContext||!e.getContext("2d"))&&0==e.toDataURL("image/webp").indexOf("data:image/webp")}

window.onload = function(){
	var isGetLocation = document.querySelectorAll('.header__info_location .header__info__location__title')[0].getAttribute('tab-city');
	//console.log(isGetLocation);
	if(isEmpty(isGetLocation) === false) {
		addContent(isGetLocation);
		listCity(isGetLocation,'starting');
		
		setTimeout(function(){
			var z=$('.header__info__location__title').attr('tab-section'); 
			if(isEmpty(z) === false){ //console.log('test');
			//console.log('prohsel');
				var valTab;
				if(z=='mobinttv'){valTab = '#items__three'; }
				if(z=='inttv'){valTab = '#items__two'; }
				if(z=='int'){valTab = '#items__one'; }
				if(z=='tv'){valTab = '#items__onetwo'; }
				$('.header__menu_ul li a').removeClass('active');
				$('.tariff__wrap').removeClass('active');
				$(valTab).addClass('active');
				$('html, body').animate({ scrollTop: $(valTab).offset().top }, { duration: 100, easing: "linear" });
			}
		},1000);
		setTimeout(function(){ questionsShow(); },1000);
	}
	else {
		initGeolocation();
	}

	$('body').on('click','.header__quest_yes',function(){
		$('.header__questions_wrap').hide();
	    $('.header__info_menu').removeClass('questions');
	});
	$('body').on('click','.header__quest_change',function(){
		$(".hidden__location").fadeIn();
	});
	$('body').on('click','.not__found_button',function(){
		$(".hidden__location").fadeIn();
	});

	$('body').on('click','.not__found_button',function(){
		$(".hidden__location").fadeIn();
	});

	<?php 
		$is_check = get_field('all_area_check',get_the_ID());
		if($is_check[0] != 'all'): ?>

		$('#temporary_banner').css('display','flex').hide();
		var regions = $('#temporary_banner').attr('regions');
		if(isEmpty(regions) === false){
			var cut_reg = regions.split(',');
			var checkReg=false;
			var tabRegion = $('.header__info__location__title').attr('tab-region');
			cut_reg.forEach(function(element){
				if(element == tabRegion){ checkReg=true; }
			});
			if(checkReg === true){ $('#temporary_banner').show();  }
			else { $('#temporary_banner').hide();  }
			
		}
	<?php endif; ?>
    
   // console.log('test js2!!!');

for(var e=document.querySelectorAll("[data-bg]"),t=0;t<e.length;t++){
    var a=e[t].getAttribute("data-bg");
    e[t].style.backgroundImage="url("+a+")"}var r=window.navigator.userAgent.match(/Firefox\/([0-9]+)\./),n=r?parseInt(r[1]):0;
    if(canUseWebp()||65<=n)for(var o=document.querySelectorAll("[data-bg-webp]"),t=0;t<o.length;t++){var g=o[t].getAttribute("data-bg-webp");
        o[t].style.backgroundImage="url("+g+")"}};

    $(document).ready(function(){
        $(".all__ok").css("display","flex").hide(),
        $(".check__wrap").click(function(){
            var e;-1==$(this).attr("class").indexOf("active")&&($(".check__wrap").removeClass("active"),
            $(".check__wrap input").removeAttr("checked"),"check__apartment_name"==(e=$(this).children("input").attr("name"))&&($(".hidden__office").fadeIn(100),
                $(".show__office").fadeOut(100)),
            "check__office_name"==e&&($(".hidden__office").fadeOut(100),$(".show__office").fadeIn(100)),
            $(this).find("input").attr("checked","checked"),
            $(this).addClass("active"))});
    $(".check__request").click(function(){
        var e;
        -1==$(this).attr("class").indexOf("active")&&($(".check__request").removeClass("active"),
        $(".check__request input").removeAttr("checked"),
        "request__apart"==(e=$(this).children("input").attr("name"))&&($(".hidden__popup_apart").fadeIn(100),
        $(".hidden__popup_office").fadeOut(100)),"request__office"==e&&($(".hidden__popup_apart").fadeOut(100),
        $(".hidden__popup_office").fadeIn(100)),$(this).find("input").attr("checked","checked"),
        $(this).addClass("active"))});
    $(".request__label_cons").click(function(){
        var e;
        -1==$(this).attr("class").indexOf("active")&&($(".request__label_cons").removeClass("active"),
        $(".request__label_cons input").removeAttr("checked"),
        "popup__cons_apart"==(e=$(this).children("input").attr("name"))&&($(".hidden__consul_apart").fadeOut(100),
        $(".hidden__consul_office").fadeIn(100),
        $(".hidden__consul_other").fadeIn(100)),
        "popup__cons_office"!=e&&"popup__cons_other"!=e||($(".hidden__consul_apart").fadeIn(100),
        $(".hidden__consul_office").fadeOut(100),
            $(".hidden__consul_other").fadeOut(100)),
        $(this).find("input").attr("checked","checked"),
        $(this).addClass("active"))}),
        $(".tmr__button").click(function(){
            $("html, body").animate({
                scrollTop:$(".tariff__zero_element").offset().top},
                {duration:10,easing:"linear"})});
        
    $("body").on("click",".location__grid_row",function(){
        -1==$(this).attr("class").indexOf("active")?($(".location__grid_row").removeClass("active"),
            $(this).addClass("active")):$(this).removeClass("active")}),
            $(".hidden__location").css("display","flex").hide(),
            
            $(".header__info_location").click(function(){
                $(".hidden__location").fadeIn(),
                $(".location__close").click(function(){
                    $(".hidden__location").fadeOut()
                })
            });
        $("body").on("click",".header__menu_ul li a",function(i){
            i.preventDefault(),
            $(".header__menu_ul li a").removeClass("active");
            var t=$(this).attr("tab");
            $(".tariff__wrap").removeClass("active"),
                $(t).addClass("active"),
                $(this).addClass("active"),
                $("html, body").animate({scrollTop:$(t).offset().top},
                    {duration:10,easing:"linear"
                    })
        }),
            $("body").on("click",".tariff__add_check",function(){
                -1==$(this).attr("class").indexOf("active")?($(this).addClass("active"),
                $(this).parent().next(".tariff__price_with_wifi").css("display","flex")):($(this).removeClass("active"),
                $(this).parent().next(".tariff__price_with_wifi").css("display","none"))}),
            $(".button__request").magnificPopup(),
            $("body").on("click",".tariff__connect",function(i){
                i.preventDefault();
                var t=$(this).attr("href");
            $.magnificPopup.open({items:{src:t},type:"inline"},0)}),
            $(".first__call").click(function(i){i.preventDefault(),
                $.magnificPopup.open({items:{src:"#popup__call_wrap"},type:"inline"},0),
                $(".popup__call_button").click(function(){$.magnificPopup.close(),
                    setTimeout(function(){$.magnificPopup.open({items:{src:$("#popup__consult_wrap")},
                        type:"inline"})},10)})}),
            $("body").on("click",".tariff__more",function(i){
                i.preventDefault();
                var t=$(this).attr("href");
            $.magnificPopup.open({items:{src:t},type:"inline"},0),
                $("#popup__features_button_connect").click(function(){
                    $.magnificPopup.close(),
                        setTimeout(function(){
                            $.magnificPopup.open({items:{src:$("#popup__request_wrap")},type:"inline"})},10)})});
        $(".header__info_support,.button__expert").click(function(){
            $.magnificPopup.open({items:{src:"#popup__interesting_wrap"},type:"inline"},0),
            $(".popup__inter_apart_button").click(function(){
                $.magnificPopup.close(),
                    setTimeout(function(){$.magnificPopup.open({items:{src:"#popup__call_wrap"},type:"inline"},0),$(".popup__call_button").click(function(){
                        $.magnificPopup.close(),
                            setTimeout(function(){$.magnificPopup.open({items:{src:$("#popup__consult_wrap")},type:"inline"})},10)})},10)}),
                $(".popup__inter__other_button").click(function(){
                    $.magnificPopup.close(),setTimeout(function(){
                        $.magnificPopup.open({items:{src:$("#popup__phone__wrap")},type:"inline"})},10)})});
        $(function(){
                <?php $stDate=get_field('timer_date');
                $exDate=explode('.',$stDate);?>
            var ts=new Date(<?php echo$exDate[0];?>,
                <?php $outEx=(int)$exDate[1];$outEx=$outEx-1;echo$outEx;?>,
                <?php echo $exDate[2];?>);new Date>ts&&(ts=new Date(2020,7,20).getTime()+864e6,newYear=!1),$(".tmr__main_wrap").countdown({timestamp:ts,callback:function(t,r,_,m){var a=""+t,e=""+r,n=""+_,s=""+m;1==a.length&&(a="0"+a),1==e.length&&(e="0"+e),1==n.length&&(n="0"+n),1==s.length&&(s="0"+s),$(".tmr__seconds_wrap .tmr__numbers span").eq(0).html(s.charAt(0)),$(".tmr__seconds_wrap .tmr__numbers span").eq(1).html(s.charAt(1)),$(".tmr__minutes_wrap .tmr__numbers span").eq(0).html(n.charAt(0)),$(".tmr__minutes_wrap .tmr__numbers span").eq(1).html(n.charAt(1)),$(".tmr__hour_wrap .tmr__numbers span").eq(0).html(e.charAt(0)),$(".tmr__hour_wrap .tmr__numbers span").eq(1).html(e.charAt(1)),$(".tmr__day_wrap .tmr__numbers span").eq(0).html(a.charAt(0)),$(".tmr__day_wrap .tmr__numbers span").eq(1).html(a.charAt(1))}})});$.mask.definitions['9'] = false;$.mask.definitions['9'] = '[9]';
        $.mask.definitions['5'] = "[0-9]";
        $("#request_phone,#popup__consultphone,#popup__reqphone").mask("+7(955) 555-55-55");

        
        
        //Выводит список регионов и городов при заполнении строки поиска
        function keyInput(n){
            var t=$("#input_find").attr("value"),
                i=window.outerWidth,o={
                action:"action_naming",
                    name:"",
                    cityaction:n,
                    width:i,
                    find:t,
                    _ajax_nonce:'<?php echo wp_create_nonce('my_ajax_nonce');?>'
            };
        $.ajax({
            method:"POST",
            url:'<?php echo admin_url('admin-ajax.php');?>',
            data:o,dataType:"json",
            success:function(t){
                null==t.keycode?($(".location__grid").html(" "),
                    $(".location__grid").html(t.parent)):(
                        $(".location__grid").html(" "),
                    "keyup"==n?$(".location__grid").html(t.keycode):$(".location__grid").html(t.parent)
                )
            }
        });
        
        }
        
        
        
        
                    $("body").on("keyup touchend","#input_find",function(e){
                        13===e.keyCode&&(e.preventDefault(),
                            keyInput("find")),
                            keyInput("keyup")}),
                        $("body").on("click",".find__icon",
                            function(){
                            keyInput("find")}
                            );
        
        var htmlAll,ww=$(window).outerWidth();
        ww<1199&&($(".header__menu_wrap").css("display","flex").hide(),
            $(".header__mobile_menu").click(function(){
                1==$(".header__menu_ul li").length&&($(".header__info_support__mobile").css("top","37vw"),
                    $(".header__menu_wrap").addClass("isone")),
                5==$(".header__menu_ul li").length&&($(".header__info_support__mobile").css("top","80vw"),
                    $(".header__menu_wrap").addClass("isfive")),
                6==$(".header__menu_ul li").length&&($(".header__info_support__mobile").css("top","93vw"),
                    $(".header__menu_wrap").addClass("issix")),
                2==$(".header__menu_ul li").length&&($(".header__info_support__mobile").css("top","50vw"),
                    $(".header__menu_wrap").addClass("istwo")),
                3==$(".header__menu_ul li").length&&($(".header__info_support__mobile").css("top","60vw"),
                    $(".header__menu_wrap").addClass("isthree")),
                4==$(".header__menu_ul li").length&&($(".header__info_support__mobile").css("top","72.8vw"),
                    $(".header__menu_wrap").addClass("isfour")),$(".header__menu_wrap").show(),$(".header__menu_mobile_close").click(function(){$(".header__menu_wrap").fadeOut(),$(".header__menu_wrap").removeClass("isfour isone istwo isthree isfive issix")}),$(".header__menu_ul li a").click(function(e){e.preventDefault(),$(".header__menu_ul li a").removeClass("active");var _=$(this).attr("tab");$(".tariff__wrap").removeClass("active"),$(_).addClass("active"),$(this).addClass("active"),$(".header__menu_wrap").fadeOut(),$("html, body").animate({scrollTop:$(_).offset().top},{duration:10,easing:"linear"})}),$(".header__info_support__mobile").click(function(){$(".header__menu_wrap").fadeOut(),$.magnificPopup.open({items:{src:"#popup__interesting_wrap"},type:"inline"},0),$(".popup__inter_apart_button").click(function(){$.magnificPopup.close(),setTimeout(function(){$.magnificPopup.open({items:{src:"#popup__call_wrap"},type:"inline"},0),$(".popup__call_button").click(function(){$.magnificPopup.close(),setTimeout(function(){$.magnificPopup.open({items:{src:$("#popup__consult_wrap")},type:"inline"})},10)})},10)}),$(".popup__inter__other_button").click(function(){$.magnificPopup.close(),setTimeout(function(){$.magnificPopup.open({items:{src:$("#popup__phone__wrap")},type:"inline"})},10)})})}),htmlAll="",
            $(".location__grid_column").each(function(){
                var e=$(this).html();
                htmlAll+=e
            }),
            $(".location__grid_column").remove(),
            $(".location__grid").append(htmlAll),
            $(".location__grid_row").click(function(){
                -1==$(this).attr("class").indexOf("active")?($(".location__grid_row").removeClass("active"),
                $(this).addClass("active")):$(this).removeClass("active")}),
            $("#popup__consuladdress,#popup__reqaddress").attr("placeholder","Адрес"));
        
        $("#request__submit").click(function(){
            
            var dadata_address = $("#form1r .request_dadata_address").val();
            console.log(dadata_address.length);
            
            if(dadata_address.length == 0){
                var input_addres = $("#form1r .request_address").val();
                console.log(input_addres);
                //if(input_addres.length >= 1){
                    var promise = suggest(input_addres);
                    console.log();
                    promise.done(function(response) {
                        console.log(response.suggestions[0]);
                        var json_suggestions = JSON.stringify(response.suggestions[0]);
                        console.log(json_suggestions);
                        $("#form1r .request_dadata_address").val(json_suggestions);
                    });
                //}
            }
            
            $('.request__form').addClass('active');
            var t=document.location.protocol+"//"+document.location.host,
            e=$(".header__info__location__title").html();
            $("#region_page").attr("value",e);
            
        var o=$("#form1r").serialize();
        /*console.log(o),*/
            $.ajax({
                method:"POST",
                url:t+"/wp-content/themes/red/mail1r.php?action=mail1r",
                data:o,
                dataType:"json",
                async: false,
                success:function(t){
                    $('.request__form').removeClass('active');
                    var e;
                    /*console.log(t),*/
                    "error"==t.status&&(e=t.description,
                        $("#form1r input[type=text]").removeClass("error_input"),
                        e.forEach(function(t,e,o){
                            $("[name="+t+"]").parent("label").addClass("error_input")})),
                            "success"==t.status&&($("input[type=text]").attr("value",""),
                        $(".req__ok").show(),
                        setTimeout(function(){$(".req__ok").fadeOut()},2e3))
                }
            })
        }),
            
            $("#popup__consult_submit").click(function(){

               // console.log('popup__consult_submit click!!!');
                
                $('#popup__consult_wrap').addClass('active');
                var t=document.location.protocol+"//"+document.location.host,
                    e=$(".header__info__location__title").html();
                $("#region_consult").attr("value",e);
                var o=$("#form2r").serialize();
                /*console.log(o),*/
                $.ajax({
                    method:"POST",
                    url:t+"/wp-content/themes/red/mail2r.php?action=mail2r",
                    data:o,dataType:"json",
                    success:function(t){

                        //console.log('popup__consult_submit success!!!');
                        
                        $('#popup__consult_wrap').removeClass('active');
                        var e;
                        /*console.log(t),*/
                        "error"==t.status&&(e=t.description,$("input[type=text]").parent("label").removeClass("error_input"),
                            e.forEach(function(t,e,o){$("[name="+t+"]").parent("label").addClass("error_input")})),"success"==t.status&&($("input[type=text]").attr("value",""),
                            $(".consult__ok").show(),
                            setTimeout(function(){
                                $(".consult__ok").fadeOut()},2e3))
                    }
                })
            }),
            
            $("body").on("click",".tariff__connect",function(){
                var t=$(this).parent(".tariff__grid_column").children(".tariff__grid_title").html();
            $("#popup__reqconnect").attr("value",t)}),$("#popup__request_submit").click(function(){
                $('#popup__request_wrap').addClass('active');
                var t=document.location.protocol+"//"+document.location.host,
                    e=$(".header__info__location__title").html();
                $("#region_request").attr("value",e);
                var o=$("#form3r").serialize();
                /*console.log(o),*/
            $.ajax({method:"POST",url:t+"/wp-content/themes/red/mail3r.php?action=mail3r",
                data:o,
                dataType:"json",
                success:function(t){
                $('#popup__request_wrap').removeClass('active');
                var e;
                /*console.log(t),*/
                    "error"==t.status&&(e=t.description,$("input[type=text]").parent("label").removeClass("error_input"),
                        e.forEach(function(t,e,o){$("[name="+t+"]").parent("label").addClass("error_input")})),
                    "success"==t.status&&($("input[type=text]").attr("value",""),
                        $(".popup_req__ok").show(),
                        setTimeout(function(){$(".popup_req__ok").fadeOut()},2e3))}})});
});
	var isget=document.querySelectorAll(".header__info_location .header__info__location__title")[0].getAttribute("tab-city");
	listCity(isget,'starting');
	if(isEmpty(isget) === false){ addContent(isget); }
	
	$('body').on('click','.tariff__add_value label',function(){
			var klass = $(this).children('.tariff__add_check').attr('class');
			var visib = $(this).children('.tariff__add_check');
			var isprise = $(this).siblings('.tariff__price_with_wifi').children('.pricetut').html();
			isprise = Number.parseInt(isprise);
			var priceMain = $(this).parent().parent().parent('.tariff__options__add').siblings('.tariff__price_wrap').children('.tariff__price_main').html();
			var outprice = 0;
			priceMain = Number.parseInt(priceMain);
			if(klass.indexOf('active') > -1){ outprice = priceMain+isprise; }
			else { outprice = priceMain-isprise; }
			$(this).parent().parent().parent('.tariff__options__add').siblings('.tariff__price_wrap').children('.tariff__price_main').html(outprice)
			
	});
	
	$('body').on('click','#all_tariff',function(){
		setTimeout(function(){
			$(".tariff__wrap").addClass('active');
		},1000);
 
	});

	<?php if($_GET['action'] == 'lk'): ?>
		$.magnificPopup.open({
			type:'inline',
			items: { src:'#lk__popup' }
		});
	<?php endif; ?>

	$('#header__info_lk').magnificPopup({type:'inline',items: { src:'#lk__popup' } });

	if($(window).outerWidth() > 1200){
		$('body').on('click','.location__grid_row',function(){
			setTimeout(function(){
				var hei = $('.header__menu_ul').eq(0).height();
				//console.log(hei);
				if(hei == 60){
					$('.header__menu_ul').removeClass('height');
				}
				else {
					$('.header__menu_ul').addClass('height');
				}
			},300);
		});
	}
</script>

<?php echo get_field('code_yandex'); ?>
<?php echo get_field('code_tag'); ?>
<?php echo get_field('code_jivosite'); ?>

</body>
</html>