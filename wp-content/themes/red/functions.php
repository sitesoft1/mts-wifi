<?php

if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Файл',
		'menu_title'	=> 'Файл',
		'menu_slug' 	=> 'file-tariff',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
		'icon_url'      => 'dashicons-text-page'
	));

	/*acf_add_options_page(array(
		'page_title' 	=> 'Данные из файла',
		'menu_title'	=> 'Данные',
		'menu_slug' 	=> 'file-date',
		'capability'	=> 'edit_posts',
		'redirect'		=> false,
		'icon_url'      => 'dashicons-networking',
		'post_id'       => 'data_from_file'
	));*/
	
}

function tariffs_posts(){
  $labels = array(
	'name' => 'ТАРИФЫ', // Основное название типа записи
	'singular_name' => 'ТАРИФ', // отдельное название записи типа Book
	'add_new' => 'Добавить тариф',
	'add_new_item' => 'Добавить новый тариф',
	'edit_item' => 'Редактировать тариф',
	'new_item' => 'Новый тариф',
	'view_item' => 'Посмотреть тариф',
	'search_items' => 'Найти тариф',
	'not_found' =>  'Тариф не найден',
	'not_found_in_trash' => 'В корзине тариф не найден',
	'parent_item_colon' => '',
	'menu_name' => 'ТАРИФЫ'
  );
  $args = array(
	'labels' => $labels, 
	'public' => false,
	'publicly_queryable' => true,
	'show_ui' => true,
	'show_in_menu' => true,
	'query_var' => true,
	'rewrite' => false,
	'capability_type' => 'post',
	'has_archive' => true,
	'hierarchical' => false,
	'menu_icon' => 'dashicons-table-row-after',
	'supports' => array('title'),
	'menu_position' => 28
  );
  register_post_type('tariffs', $args);
}
add_action('init', 'tariffs_posts');


function add_new_area() { 
/* создаем функцию с произвольным именем и вставляем 
в неё register_taxonomy() */  
  register_taxonomy('area',
    array('tariffs'),
    array(
      'hierarchical' => true,
      /* true - по типу рубрик, false - по типу меток, 
      по умолчанию - false */
      'labels' => array(
        /* ярлыки, нужные при создании UI, можете
        не писать ничего, тогда будут использованы
        ярлыки по умолчанию */
        'name' => 'Регионы',
        'singular_name' => 'Регион',
        'search_items' =>  'Найти регион',
        'popular_items' => 'Популярные регионы',
        'all_items' => 'Все регионы',
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => 'Редактировать регион', 
        'update_item' => 'Обновить регион',
        'add_new_item' => 'Добавить новый регион',
        'new_item_name' => 'Название нового региона',
        'separate_items_with_commas' => 'Разделяйте регионы',
        'add_or_remove_items' => 'Добавить или удалить регион',
        'choose_from_most_used' => 'Выбрать из наиболее часто используемых регионов',
        'menu_name' => 'Регионы'
      ),
      'public' => false, 
      /* каждый может использовать таксономию, либо
      только администраторы, по умолчанию - true */
      'show_in_nav_menus' => true,
      /* добавить на страницу создания меню */
      'show_ui' => true,
      /* добавить интерфейс создания и редактирования */
      'show_tagcloud' => true,
      /* нужно ли разрешить облако тегов для этой таксономии */
      'update_count_callback' => '_update_post_term_count',
      /* callback-функция для обновления счетчика $object_type */
      'query_var' => true,
      /* разрешено ли использование query_var, также можно 
      указать строку, которая будет использоваться в качестве 
      него, по умолчанию - имя таксономии */
      'rewrite' => false,
    )
  );
}
add_action( 'init', 'add_new_area', 10 );

add_filter( 'manage_'.'tariffs'.'_posts_columns', 'add_views_column', 4 );
function add_views_column( $columns ){
	$num = 2; // после какой по счету колонки вставлять новые

	$new_columns = array(
		'regions' => 'Регион',
	);

	return array_slice( $columns, 0, $num ) + $new_columns + array_slice( $columns, $num );
}

// заполняем колонку данными
// wp-admin/includes/class-wp-posts-list-table.php
add_action('manage_'.'tariffs'.'_posts_custom_column', 'fill_views_column', 5, 2 );
function fill_views_column( $colname, $post_id ){
	if( $colname === 'regions' ){
		$nameterms = get_the_terms($post_id,'area'); 
		$int=1;
		foreach($nameterms as $nt){
			if($nt->parent == 0){
				if($int != 1){ echo ', '.$nt->name; }
				else {  echo $nt->name; }
				$int++;
			}
			
		}
	}
}
add_filter( 'manage_'.'tariffs'.'_posts_columns', 'add_views_column_city', 4 );
function add_views_column_city( $columns ){
	$num = 3; // после какой по счету колонки вставлять новые

	$new_columns = array(
		'cities' => 'Город',
	);

	return array_slice( $columns, 0, $num ) + $new_columns + array_slice( $columns, $num );
}

// заполняем колонку данными
// wp-admin/includes/class-wp-posts-list-table.php
add_action('manage_'.'tariffs'.'_posts_custom_column', 'fill_views_column_city', 5, 2 );
function fill_views_column_city( $colname, $post_id ){
	if( $colname === 'cities' ){
		$nameterms = get_the_terms($post_id,'area'); 
		$int=1;
		foreach($nameterms as $nt){
			if($nt->parent != 0){
				if($int != 1){ echo ', '.$nt->name; }
				else {  echo $nt->name; }
				$int++;
			}
			
		}
	}
}

include __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

function excel__start(){
ignore_user_abort();

    // Get newly saved values.
    //$values = get_fields( $post_id );
	$arrsmb = new WP_Query(array('post_type' => 'tariffs','posts_per_page'=>-1)); 

	while ( $arrsmb->have_posts() ) {
			$arrsmb->the_post();

			wp_delete_post( get_the_ID(),true);
	}
	wp_reset_postdata();
	

	function addlog($ell){
		$log = date('Y-m-d H:i:s') . ' ';
		$log .= str_replace(array('	', PHP_EOL), '', print_r($ell, true));
		file_put_contents(__DIR__ . '/log.txt', $log . PHP_EOL, FILE_APPEND);
	}

    // Check the new value of a specific field.
    $hero_file = get_field('download_file', 'options');

    if( $hero_file ) {
        
		global $docFile;
		$inputFileName = $hero_file['url'];
		$lFile = strlen($inputFileName);
		$url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'];
		$countStr = strlen($url);
		$urlNoMain = substr($inputFileName,$countStr,$lFile);
		$urlNoMain = $urlNoMain;
		$pathMain = $_SERVER['DOCUMENT_ROOT'].$urlNoMain;
		$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		$reader->setLoadSheetsOnly('Area Tariff');
		$docFile = $reader->load($pathMain);
		
		$i_start = 2; //Строка с которой начинается проходка по файлу
		$iterms = 2;
		global $cellArea;
		global $cellCity;
		global $startArea;
		global $startCity;
		global $fullArray; $fullArray = [];

		$cellArea = $docFile->getActiveSheet()->getCellByColumnAndRow(25, $i_start)->getValue(); //Стартовая область 
		$cellCity = $docFile->getActiveSheet()->getCellByColumnAndRow(26, $i_start)->getValue(); //Стартовый город 

		$startArea = $docFile->getActiveSheet()->getCellByColumnAndRow(25, $iterms)->getValue(); //Стартовая область 
		$startCity = $docFile->getActiveSheet()->getCellByColumnAndRow(26, $iterms)->getValue(); //Стартовый город 

		function transliterate($input){
		$gost = array(
			"а"=>"a","б"=>"b","в"=>"v","г"=>"g","д"=>"d",
			"е"=>"e","ё"=>"yo","ж"=>"j","з"=>"z","и"=>"i",
			"й"=>"i","к"=>"k","л"=>"l", "м"=>"m","н"=>"n",
			"о"=>"o","п"=>"p","р"=>"r","с"=>"s","т"=>"t",
			"у"=>"y","ф"=>"f","х"=>"h","ц"=>"c","ч"=>"ch",
			"ш"=>"sh","щ"=>"sh","ы"=>"i","э"=>"e","ю"=>"u",
			"я"=>"ya",
			"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G","Д"=>"D",
			"Е"=>"E","Ё"=>"Yo","Ж"=>"J","З"=>"Z","И"=>"I",
			"Й"=>"I","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
			"О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
			"У"=>"Y","Ф"=>"F","Х"=>"H","Ц"=>"C","Ч"=>"Ch",
			"Ш"=>"Sh","Щ"=>"Sh","Ы"=>"I","Э"=>"E","Ю"=>"U",
			"Я"=>"Ya",
			"ь"=>"","Ь"=>"","ъ"=>"","Ъ"=>"",
			"ї"=>"j","і"=>"i","ґ"=>"g","є"=>"ye",
			"Ї"=>"J","І"=>"I","Ґ"=>"G","Є"=>"YE"
		);
		return strtr($input, $gost);
		}

		$terms = get_terms(['taxonomy' => 'area', 'hide_empty' => false,]);
		
		while( !empty($startArea) ) {
					
			$isarea = term_exists($startArea,'area');
			$iscity = term_exists($startCity,'area');
			
			if(empty($iscity)){
				if(empty($isarea)){
					$transliArea = transliterate($startArea); 
					$transliCity = transliterate($startCity); 
					$id_term = wp_insert_term($startArea,'area',['slug' => $transliArea,'parent' => 0]);
					$id_city = wp_insert_term($startCity,'area',['slug' => $transliCity,'parent' => $id_term]);
				}
				else {
					$transliArea = transliterate($startArea); 
					$transliCity = transliterate($startCity); 
					$id_city = wp_insert_term($startCity,'area',['slug' => $transliCity,'parent' => $isarea['term_id']]);
				}
			}

			$iterms++;
			$startArea = $docFile->getActiveSheet()->getCellByColumnAndRow(25, $iterms)->getValue();
			$startCity = $docFile->getActiveSheet()->getCellByColumnAndRow(26, $iterms)->getValue();

		}

		//while( $i_start < 10 ) {
		while( !empty($cellArea) ) {

			$sheetStyle = $docFile->getActiveSheet();
			$cellCity = $sheetStyle->getCellByColumnAndRow(26, $i_start)->getValue(); //Город
			$cellTitle = $sheetStyle->getCellByColumnAndRow(3, $i_start)->getValue(); //Наименование тарифа
			$cellPrice = $sheetStyle->getCellByColumnAndRow(6, $i_start)->getValue(); //Базовая
			$cellAfterPrice = $sheetStyle->getCellByColumnAndRow(7, $i_start)->getValue(); //АП Промо
			$cellSpeed = $sheetStyle->getCellByColumnAndRow(9, $i_start)->getValue(); //Скорость
			$cellMinutes = $sheetStyle->getCellByColumnAndRow(18, $i_start)->getValue(); //Кол-во минут
			$cellSms = $sheetStyle->getCellByColumnAndRow(19, $i_start)->getValue(); //Кол-во смс
			$cellGb = $sheetStyle->getCellByColumnAndRow(20, $i_start)->getValue(); //Кол-во ГБ
			$cellTvChan = $sheetStyle->getCellByColumnAndRow(10, $i_start)->getValue(); //Кол-во ТВ каналов
			$cellHDChan = $sheetStyle->getCellByColumnAndRow(11, $i_start)->getValue(); //Кол-во HD каналов
			$cellWiFi = $sheetStyle->getCellByColumnAndRow(21, $i_start)->getValue(); //Ст-ть Wi-Fi
			$cellTvStat = $sheetStyle->getCellByColumnAndRow(22, $i_start)->getValue(); //ТВ приставка
			$cellMore = $sheetStyle->getCellByColumnAndRow(1, $i_start)->getValue(); //Подробнее о тарифе
			$cellHDStatPrice = $sheetStyle->getCellByColumnAndRow(23, $i_start)->getValue(); //Цена HD приставки
			//$cellIsSale = $sheetStyle->getCellByColumnAndRow(16, $i_start)->getValue(); //Наличие акции
			$cellType = $sheetStyle->getCellByColumnAndRow(5, $i_start)->getValue(); //Тип тарифа
			global $clacSpeed;

			if($cellGb == 99999 || $cellGb == '99999'){ $cellGb = 'Безлимитный интернет'; }

			addlog($cellType);

			$array_type = [
				'ШПД' => 'Internet',
				'ТВ' => 'TV',
				'ШПД+ТВ' => 'InternetAndTV',
				'ШПД+ТВ+МОБ' => 'InternetAndTVandMOB',
				'ТВ+МОБ' => 'TVandMOB',
				'ШПД+МОБ' => 'InternetAndMOB'
			];
			
			//if(!empty($cellAfterPrice)){ update_field('tariff_issales','1',$tariff_id); }
			if($cellType == 'ШПД' || $cellType == 'ТВ' || $cellType == 'ШПД+ТВ' || $cellType == 'ШПД+ТВ+МОБ' || $cellType == 'ТВ+МОБ' || $cellType == 'ШПД+МОБ') {
				addlog('ДОШЛИ-------------'.$cellTitle);
				$tariff_id = wp_insert_post(array('post_title' => $cellTitle,'post_type' => 'tariffs','post_status' => 'publish')); //Добавляем заголовок и
				if(!empty($cellSpeed)){ $cellSpeed = (int)$cellSpeed;$clacSpeed = $cellSpeed; }

				if(!empty($cellType)){       update_field('filter_tariff',$array_type[$cellType],$tariff_id); 	} //Добавляем тип тарифа
				if(!empty($cellPrice)){      update_field('tariff_price',$cellPrice,$tariff_id);              	} //Добавляем тип тарифа
				if(!empty($cellAfterPrice)){ update_field('tariff_afterprice',$cellAfterPrice,$tariff_id);    	} //Добавляем тип тарифа
				if(!empty($clacSpeed)){      update_field('tariff_speed',$clacSpeed,$tariff_id);              	} //Добавляем скорость 
				if(!empty($cellMinutes)){    update_field('tariff_minutes',$cellMinutes,$tariff_id);          	} //Добавляем Минуты 
				if(!empty($cellSms)){        update_field('tariff_sms',$cellSms,$tariff_id);                  	} //Добавляем СМС 
				if(!empty($cellGb)){         update_field('tariff_gb',$cellGb,$tariff_id);                    	} //Добавляем Минуты 
				if(!empty($cellTvChan)){     update_field('tariff_tvchannels',$cellTvChan,$tariff_id);        	} //Добавляем Минуты 
				if(!empty($cellHDChan)){     update_field('tariff_hdchannels',$cellHDChan,$tariff_id);        	} //Добавляем Минуты 
				if(!empty($cellWiFi)){       update_field('tariff_wifi',$cellWiFi,$tariff_id);                	} //Добавляем Минуты 
				if(!empty($cellTvStat) || $cellTvStat == 0 || $cellTvStat == '0'){ update_field('tariff_tvstation',$cellTvStat,$tariff_id); } //Добавляем Минуты 
				if(!empty($cellHDStatPrice) || $cellHDStatPrice == 0 || $cellHDStatPrice == '0'){ update_field('tariff_hdstation',$cellHDStatPrice,$tariff_id); } //Добавляем Минуты 
				if(!empty($cellIsSale)){  } //Добавляем Минуты 
				if(!empty($cellMore)){       update_field('more_about_tarif',$cellMore,$tariff_id);             } //Добавляем Минуты 

				$postArea = term_exists($cellArea,'area');
				$postCity = term_exists($cellCity,'area');

				wp_set_post_terms($tariff_id,array($postArea['term_id'],$postCity['term_id']),'area',true);
			}			

			$i_start++;
			$cellArea = $docFile->getActiveSheet()->getCellByColumnAndRow(25, $i_start)->getValue();

		}

		
    }

}

add_action( 'excel_hook', 'excel__start' );
add_action('acf/save_post', 'my_acf_save_post');
function my_acf_save_post( $post_id ) {
	wp_schedule_single_event( time()+60,'excel_hook');
}

function json_content($status,$mailtype) {

	header ("Content-type: application/json;charset=utf-8");
	die (json_encode(array('status' => $status)));
}

//если вы хотите принимать запрос только от авторизованных пользователей, тогда добавьте этот хук
add_action('wp_ajax_action_naming', 'my_AJAX_processing_function_two');
//если вы хотите получить запрос от неавторизованных пользователей, тогда добавьте этот хук
add_action('wp_ajax_nopriv_action_naming', 'my_AJAX_processing_function_two');
//Если хотите, чтобы оба вариант работали, тогда оставьте оба хука

function my_AJAX_processing_function_two(){


	$getcity = $_POST['name'];
	$cityaction = $_POST['cityaction'];
	$widthing = $_POST['width'];
	$widthing=(int)$widthing;
	global $exterm;
	global $findcity;

	$findcity = trim($_POST['find']);
	global $exitCode;
	global $parentis;
	global $isparent;
	global $findterm;
	global $parentis;
	global $isparent;
	global $exitCode;
	$exitCode = '';
	global $outcity;
	$outcity = '';

	if(!empty($getcity)) {
		$gcityterm = term_exists($getcity, 'area');
		
		if(!empty($gcityterm)) {
		    $arrterm = get_term_by('id', $gcityterm['term_id'], 'area');
		    $outcity = $arrterm->name;
		}
		else { 

			$termarray = get_terms(['taxonomy' => 'area','meta_key' => 'region_google_id', 'meta_value' => $getcity]); 
			if($termarray){
				$outcity =$termarray[0]->name;
			}
		}
		if(empty($outcity)){ $outcity = $getcity; }
	}

	if(!empty($findcity)){
		//$findterm = term_exists($findcity,'area');
		$exists_term = term_exists($findcity,'area');
		if(!empty($exists_term)){ 
			$parentis = get_term_by('id',$exists_term['term_id'],'area');
			
			$isparent = get_term_by('id',$parentis->parent,'area');
            $tarifnik_id = get_field('tarifnik_id', 'area_'.$isparent->term_id);
			$exitCode = $exitCode.'<div class="location__grid_column">';
			$exitCode = $exitCode.'<div class="location__grid_row">
				<a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$isparent->slug.'">'.$isparent->name.'</a><ul class="location__add_cities">';
			$exitCode = $exitCode.'<li><a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$parentis->slug.'">'.$parentis->name.'</a></li>';
			$exitCode = $exitCode.'</ul></div>';
			
		}
	}

	function formulaOne($num,$colum){ $outNum = ($num+$colum)%4; return $outNum;  } 

	if($cityaction == 'keyup'){

		$findterms = get_terms(['taxonomy' => 'area','hide_empty' => false]);
		if(!empty($findterms) && !empty($findcity)){
			
			global $keyColOne;
			global $keyColTwo;
			global $keyColThree;
			global $keyColFour;
			global $keyCode;
			$icolumnis=1;

			$keyCode = $keyCode.'<div class="location__grid_column">';
			$isFindArray = [];
			foreach($findterms as $fndtrms){
				$onete = mb_strtolower($fndtrms->name);
				$twote = mb_strtolower($findcity);
				$strtag = strpos($onete,$twote);
				if($strtag !== false){
					if($fndtrms->parent != 0){ 
						if(array_key_exists($fndtrms->parent,$isFindArray)){
							$itemCount = count($isFindArray[$fndtrms->parent]);
							$isFindArray[$fndtrms->parent][$itemCount]=$fndtrms->term_id;
						}
						else {
							$isFindArray[$fndtrms->parent] =[$fndtrms->term_id];
						}
						
					}
				} 
			}
			$column_1r = 1;
			foreach($isFindArray as $keyFind => $valueFind){
				
				if($widthing > 1199){ 
					//$keyCode = $valueFind;
					if(formulaOne($column_1r,3) == 0) {
						$parentLocal = get_term($keyFind,'area');
                        $tarifnik_id = get_field('tarifnik_id', 'area_'.$parentLocal->term_id);
						$keyColOne = $keyColOne.'<div class="location__grid_row active">
							<a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$parentLocal->slug.'">'.$parentLocal->name.'</a><ul class="location__add_cities">';
							foreach($valueFind as $palo){
								$ister = get_term($palo,'area');
								$keyColOne = $keyColOne.'<li><a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$ister->slug.'">'.$ister->name.'</a></li>';
							}
						$keyColOne = $keyColOne.'</ul></div>';
					}
					if(formulaOne($column_1r,2) == 0) {
						$parentLocal = get_term($keyFind,'area');
                        $tarifnik_id = get_field('tarifnik_id', 'area_'.$parentLocal->term_id);
						$keyColTwo = $keyColTwo.'<div class="location__grid_row active">
							<a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$parentLocal->slug.'">'.$parentLocal->name.'</a><ul class="location__add_cities">';
							foreach($valueFind as $palo){
								$ister = get_term($palo,'area');
								$keyColTwo = $keyColTwo.'<li><a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$ister->slug.'">'.$ister->name.'</a></li>';
							}
						$keyColTwo = $keyColTwo.'</ul></div>';
					}
					if(formulaOne($column_1r,1) == 0) {
						$parentLocal = get_term($keyFind,'area');
                        $tarifnik_id = get_field('tarifnik_id', 'area_'.$parentLocal->term_id);
						$keyColThree = $keyColThree.'<div class="location__grid_row active">
							<a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$parentLocal->slug.'">'.$parentLocal->name.'</a><ul class="location__add_cities">';
							foreach($valueFind as $palo){
								$ister = get_term($palo,'area');
								$keyColThree = $keyColThree.'<li><a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$ister->slug.'">'.$ister->name.'</a></li>';
							}
						$keyColThree = $keyColThree.'</ul></div>';
					}
					if(formulaOne($column_1r,0) == 0) {
						$parentLocal = get_term($keyFind,'area');
                        $tarifnik_id = get_field('tarifnik_id', 'area_'.$parentLocal->term_id);
						$keyColFour = $keyColFour.'<div class="location__grid_row active">
							<a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$parentLocal->slug.'">'.$parentLocal->name.'</a><ul class="location__add_cities">';
							foreach($valueFind as $palo){
								$ister = get_term($palo,'area');
								$keyColFour = $keyColFour.'<li><a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$ister->slug.'">'.$ister->name.'</a></li>';
							}
						$keyColFour = $keyColFour.'</ul></div>';
					}
					$column_1r++;
				}
				if($widthing < 1200){
					$parentLocal = get_term($keyFind,'area');
                    $tarifnik_id = get_field('tarifnik_id', 'area_'.$parentLocal->term_id);
					$keyCode = $keyCode.'<div class="location__grid_row active">
						<a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$parentLocal->slug.'">'.$parentLocal->name.'</a><ul class="location__add_cities">';
						foreach($valueFind as $palo){
							$ister = get_term($palo,'area');
							$keyCode = $keyCode.'<li><a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$ister->slug.'">'.$ister->name.'</a></li>';
						}
					$keyCode = $keyCode.'</ul></div>';
				}
			}
			if($widthing > 1199){ 
				$keyCode = $keyCode.$keyColOne.'</div>
				<div class="location__grid_column">';
				$keyCode = $keyCode.$keyColTwo.'</div>
				<div class="location__grid_column">';
				$keyCode = $keyCode.$keyColThree.'</div>
				<div class="location__grid_column">';
				$keyCode = $keyCode.$keyColFour.'</div>';
			}
			if($widthing < 1200){ 
				$keyCode = $keyCode.'</div>';
			}
		}

	}
 
	
	//Загрузка списка регионов по умолчанию без каких либо действий пользователя
	if($cityaction == 'starting' || empty($findcity)){ 
		$termis = term_exists($getcity, 'area');
		if(!empty($termis)) {
			$exterm = get_term_by('id', $termis['term_id'], 'area');
		}

		$listerming = get_terms([ 'taxonomy' => 'area', 'hide_empty' => false]);
		
		if(!empty($listerming)){

			if($widthing > 1199){ 
				
			 $list_terms = get_terms(array('taxonomy' => 'area', 'hide_empty' => false, 'parent' => 0));
				$exitCode = $exitCode.'<div class="location__grid_column">';
						$column_1r = 1;
						$exitCodeOne=''; $exitCodeTwo=''; $exitCodeThree=''; $exitCodeFour='';
						foreach($list_terms as $lite) {


								if(formulaOne($column_1r,3) == 0) {
								$parentLocal = get_terms(array('taxonomy' => 'area','hide_empty' => false,'parent' => $lite->term_id));
								$tarifnik_id = get_field('tarifnik_id', 'area_'.$lite->term_id);
									$exitCodeOne = $exitCodeOne.'<div class="location__grid_row"><a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$lite->slug.'">'.$lite->name.'</a><ul class="location__add_cities">';
											foreach($parentLocal as $palo){
												$exitCodeOne = $exitCodeOne.'<li><a title="'.$palo->name.'" data-tarifnik_id="'.$tarifnik_id.'" tab="'.$palo->slug.'">'.$palo->name.'</a></li>';
											}
									$exitCodeOne = $exitCodeOne.'</ul></div>';
								}
								if(formulaOne($column_1r,2) == 0) {
								$parentLocal = get_terms(array('taxonomy' => 'area','hide_empty' => false,'parent' => $lite->term_id));
                                $tarifnik_id = get_field('tarifnik_id', 'area_'.$lite->term_id);
								$exitCodeTwo = $exitCodeTwo.'<div class="location__grid_row"><a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$lite->slug.'">'.$lite->name.'</a><ul class="location__add_cities">';
											foreach($parentLocal as $palo){
												$exitCodeTwo = $exitCodeTwo.'<li><a title="'.$palo->name.'" data-tarifnik_id="'.$tarifnik_id.'" tab="'.$palo->slug.'">'.$palo->name.'</a></li>';
											}
									$exitCodeTwo = $exitCodeTwo.'</ul></div>';
								}
								if(formulaOne($column_1r,1) == 0) {
								$parentLocal = get_terms(array('taxonomy' => 'area','hide_empty' => false,'parent' => $lite->term_id));
                                $tarifnik_id = get_field('tarifnik_id', 'area_'.$lite->term_id);
									$exitCodeThree = $exitCodeThree.'<div class="location__grid_row">
										<a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$lite->slug.'">'.$lite->name.'</a><ul class="location__add_cities">';
											foreach($parentLocal as $palo){
												$exitCodeThree = $exitCodeThree.'<li><a title="'.$palo->name.'" data-tarifnik_id="'.$tarifnik_id.'" tab="'.$palo->slug.'">'.$palo->name.'</a></li>';
											}
									$exitCodeThree = $exitCodeThree.'</ul></div>';
								}
								if(formulaOne($column_1r,0) == 0) {
								$parentLocal = get_terms(array('taxonomy' => 'area','hide_empty' => false,'parent' => $lite->term_id));
                                $tarifnik_id = get_field('tarifnik_id', 'area_'.$lite->term_id);
									$exitCodeFour = $exitCodeFour.'<div class="location__grid_row">
										<a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$lite->slug.'">'.$lite->name.'</a><ul class="location__add_cities">';
											foreach($parentLocal as $palo){
												$exitCodeFour = $exitCodeFour.'<li><a title="'.$palo->name.'" data-tarifnik_id="'.$tarifnik_id.'" tab="'.$palo->slug.'">'.$palo->name.'</a></li>';
											}
									$exitCodeFour = $exitCodeFour.'</ul></div>';
								}
								$column_1r++;

						} 
				$exitCode = $exitCode.$exitCodeOne.'</div>
				<div class="location__grid_column">';
				$exitCode = $exitCode.$exitCodeTwo.'</div>
				<div class="location__grid_column">';
				$exitCode = $exitCode.$exitCodeThree.'</div>
				<div class="location__grid_column">';
				$exitCode = $exitCode.$exitCodeFour.'</div>';
						
			}
			if($widthing < 1200){ 
				$termarray = get_terms(['taxonomy' => 'area','hide_empty' => false,'parent' => 0]);
				if(!empty($termarray)){
					foreach($termarray as $trmrr){
						$parentLocal = get_terms(array('taxonomy' => 'area','hide_empty' => false,'parent' => $trmrr->term_id));
                        $tarifnik_id = get_field('tarifnik_id', 'area_'.$lite->term_id);
						$exitCode = $exitCode.'<div class="location__grid_row">
							<a data-tarifnik_id="'.$tarifnik_id.'" tab="'.$trmrr->slug.'">'.$trmrr->name.'</a><ul class="location__add_cities">';
							foreach($parentLocal as $palo){
								$exitCode = $exitCode.'<li><a title="'.$palo->name.'" data-tarifnik_id="'.$tarifnik_id.'" tab="'.$palo->slug.'">'.$palo->name.'</a></li>';
							}
						$exitCode = $exitCode.'</div>';
					}
				}
			}

		}

	}
	
	

	header("Content-type: application/json;charset=utf-8");
	wp_send_json(array('status' => $exitCode,'cityname' => $outcity,'width' => $widthing,'parent' => $exitCode,'keycode' => $keyCode));
	wp_die();
}


//если вы хотите принимать запрос только от авторизованных пользователей, тогда добавьте этот хук
add_action('wp_ajax_action_name', 'my_AJAX_processing_function');
//если вы хотите получить запрос от неавторизованных пользователей, тогда добавьте этот хук
add_action('wp_ajax_nopriv_action_name', 'my_AJAX_processing_function');
//Если хотите, чтобы оба вариант работали, тогда оставьте оба хука

function my_AJAX_processing_function(){

	$outData = $_POST['geo'];
	$outCode = '';
	$namecity = '';
	$termarr ='';

	$iscity = term_exists($outData,'area');

	if(empty($iscity)){ $termarr = get_terms(['taxonomy' => 'area','meta_key' => 'region_google_id', 'meta_value' => $outData]); }


	if(empty($iscity) && empty($termarr)){
		$outCode = 'notfound';
	}
	else {

		$wrap_one = '';
		$wrap_two = '';
		$wrap_three = '';
		$wrap_four = '';
		$wrap_five = '';
		$wrap_six = '';

		global $grid_one;
		global $grid_two;
		global $grid_three;
		global $grid_four;
		global $grid_five;
		global $grid_six;
		global $isMenuOne;
		global $isMenuTwo;
		global $isMenuThree;
		global $isMenuFour;
		global $isMenuFive;
		global $isMenuSix;
		global $popupOne;
		global $popupTwo;
		global $popupThree;
		global $popupFour;
		global $popupFive;
		global $popupSix;
		$isMenuOne = false;
		$isMenuTwo = false;
		$isMenuThree = false;
		$isMenuFour = false;
		$isMenuFive = false;
		$isMenuSix = false;
		global $alltypes;

		global $termising;
		global $termparent;
		$termising=[];
		$termparent='';


		if(empty($iscity)){
			$countarrs = count($termarr);
			if($termarr){
				foreach($termarr as $ta){
					array_push($termising,$ta->term_id);
					//array_push($termparent,$ta->parent);
				}
			}
		}
		else { $termising=$iscity['term_id']; 
		//$byfield = get_term_by('id',$iscity['term_id'],'cat');
		//$termparent=$byfield;
		}

		$array_query = new WP_Query(array('posts_per_page' => -1,'post_type' => 'tariffs','post_status' => 'publish','tax_query' => [['taxonomy' => 'area','field' => 'term_id','terms' => $termising]]));
		while ( $array_query->have_posts() ) {
			$array_query->the_post();
			$after='';
			$sales=''; $classsales = '';
			$speed='';
			$kolich = '';
			$tvhdchan = '';
			$wifiis='';
			$tvstat='';
			$hdstat='';

			$newprice = get_field('tariff_afterprice',get_the_ID());
			$pprice = get_field('tariff_price',get_the_ID());
			if(!empty($newprice) && $newprice != $pprice){ $after = '<span class="tariff__price_old">'.$pprice.'</span>'; }
			$salesfield = get_field('tariff_issales',get_the_ID());
			if(!empty($newprice)){ $sales = '<span class="tariff__sale">Акция</span>'; $classsales = 'isSales';}

			$speedfield = get_field('tariff_speed',get_the_ID());
			if(!empty($speedfield)){ $speed = '<div class="tariff__options_row">
									<span class="tariff__options_row__icon union"></span>
									<span class="tariff__options_row__values">
										<span class="tariff__options_row__title">'.$speedfield.' Мбит/с</span>
									</span>
								</div>'; }

			$kolvo = get_field('tariff_minutes',get_the_ID());
			$kolvogb = get_field('tariff_gb',get_the_ID());
			if(!empty($kolvo) || !empty($kolvogb)){
					$onek = ''; $twok = '';
					if(!empty($kolvogb)) { 
						if(strpos($kolvogb,'езлимит') === false){ $onek='<span class="tariff__options_row__title">'.$kolvogb.' ГБ</span>'; }
						else {$onek='<span class="tariff__options_row__title">'.$kolvogb.'</span>';}
					}
					//if(!empty($kolvogb)) { $onek='<span class="tariff__options_row__title">'.$kolvogb.'</span>'; }
					if(!empty($kolvo)) { $twok='<span class="tariff__options_row__title">'.$kolvo.' минут</span>'; }
				
					$kolich = '<div class="tariff__options_row">
						<span class="tariff__options_row__icon phone"></span>
						<span class="tariff__options_row__values">
						'.$onek.''.$twok.'
						</span>
					</div>';
			}

			$tvtv = get_field('tariff_tvchannels',get_the_ID());
			$tvhd = get_field('tariff_hdchannels',get_the_ID());
			if(!empty($tvtv) || !empty($tvhd)){

				$tvstr = '';
				$hdstr = '';
				if(!empty($tvtv)){ $tvstr= $tvtv.' каналов'; }
				if(!empty($tvtv) && !empty($tvhd)){ $hdstr=$tvhd.' HD'; }
				if(empty($tvtv) && !empty($tvhd)){ $hdstr=$tvhd.' HD каналов'; }

				$tvhdchan = '<div class="tariff__options_row">
						<span class="tariff__options_row__icon desctop"></span>
						<span class="tariff__options_row__values">
							<span class="tariff__options_row__title">'.$tvstr.' '.$hdstr.'</span>
						</span>
					</div>';
			}

			$iswifi = get_field('tariff_wifi',get_the_ID());
			if(!empty($iswifi)){
				$wifiis = '<div class="tariff__add_row">
					<div class="tariff__add_title">Wi-Fi роутер</div>
					<div class="tariff__add_value">
						<label for="">
							<span class="tariff__add_check">
								<input type="checkbox" name="check__wifi" class="checkwifi">
							</span>
						</label>
						<span class="tariff__price_with_wifi"><span class="pricetut">'.$iswifi.' </span><span class="iconruble"></span>/мес.</span>
					</div>
				</div>';
			}

			$tvstation = get_field('tariff_tvstation',get_the_ID());
			if(!empty($tvstation) || $tvstation === 0 || $tvstation === '0'){
				/*$tvstat ='<div class="tariff__add_row">
						<div class="tariff__add_title">ТВ приставка/САМ-модуль</div>
						<div class="tariff__add_value">в комплекте</div>
					</div>';*/
				$tvstat = '<div class="tariff__add_row">
					<div class="tariff__add_title">САМ-модуль</div>
					<div class="tariff__add_value">
						<label for="">
							<span class="tariff__add_check">
								<input type="checkbox" name="check__tvprst" class="checkwifi">
							</span>
						</label>
						<span class="tariff__price_with_wifi"><span class="pricetut">'.$tvstation.' </span><span class="iconruble"></span>/мес.</span>
					</div>
				</div>';
			}
			$hdstation = get_field('tariff_hdstation',get_the_ID());
			if(!empty($hdstation) || $hdstation === 0 || $hdstation === '0'){
				/*$tvstat ='<div class="tariff__add_row">
						<div class="tariff__add_title">ТВ приставка/САМ-модуль</div>
						<div class="tariff__add_value">в комплекте</div>
					</div>';*/
				$hdstat = '<div class="tariff__add_row">
					<div class="tariff__add_title">HD приставка</div>
					<div class="tariff__add_value">
						<label for="">
							<span class="tariff__add_check">
								<input type="checkbox" name="check__hdprst" class="checkwifi">
							</span>
						</label>
						<span class="tariff__price_with_wifi"><span class="pricetut">'.$hdstation.' </span><span class="iconruble"></span>/мес.</span>
					</div>
				</div>';
			}

			$typeTariff = get_field('filter_tariff',get_the_ID());
			$salesis='';
			$field_mobinttv = get_field('sales_field_mobinttv',8);
			$field_inttv = get_field('sales_field_inttv',8);
			$field_int = get_field('sales_field_int',8);
			$field_tv = get_field('sales_field_tv',8);
			$field_tvmob = get_field('sales_field_tvmob',8);
			$field_intmob = get_field('sales_field_intmob',8);

	if(!empty($field_mobinttv) && $typeTariff == 'InternetAndTVandMOB'){$salesis = '<span class="textsales">'.$field_mobinttv.'</span>'; }
	if(!empty($field_inttv) && $typeTariff == 'InternetAndTV'){$salesis = '<span class="textsales">'.$field_inttv.'</span>';}
	if(!empty($field_int) && $typeTariff == 'Internet'){$salesis = '<span class="textsales">'.$field_int.'</span>';}
	if(!empty($field_tv) && $typeTariff == 'TV'){$salesis = '<span class="textsales">'.$field_tv.'</span>';}
	if(!empty($field_tvmob) && $typeTariff == 'TVandMOB'){$salesis = '<span class="textsales">'.$field_tvmob.'</span>';}
	if(!empty($field_intmob) && $typeTariff == 'InternetAndMOB'){$salesis = '<span class="textsales">'.$field_intmob.'</span>';}
			$finalpri='';
			if(empty($newprice)){ $finalpri=$pprice; }
			else { $finalpri=$newprice; }
			$returnGrid = '
						<div class="tariff__grid_column '.$classsales.'">
							'.$sales.'
							<h4 class="tariff__grid_title">'.get_the_title(get_the_ID()).'</h4>
							<div class="tariff__price_wrap">
								<span class="tariff__price_main">'.$finalpri.'</span>
								'.$after.'
								<span class="tariff__period">в месяц</span>
							</div>
							<span class="tariff__hr"></span>
							<div class="tariff__list_options">
								'.$speed.'
								'.$kolich.'
								'.$tvhdchan.'
							</div>
							<span class="tariff__hr"></span>
							<div class="tariff__options__add">
								<div class="tariff__add_row">
									<div class="tariff__add_title">Стоимость подключения</div>
									<div class="tariff__add_value">бесплатно</div>
								</div>
								'.$wifiis.'
								'.$tvstat.'
								'.$hdstat.'
							</div>
							<a href="#popup__features_wrap_'.get_the_ID().'" class="tariff__more">Подробнее о тарифе...</a>'.$salesis.'
							<a href="#popup__request_wrap" class="tariff__connect">Подключиться</a>
						</div>';
			$returnPopup = '<div class="hidden__popup">
				<div id="popup__features_wrap_'.get_the_ID().'" class="popup__features_wrap">
					<h4 class="popup__features_title">'.get_field('tariff_title',get_the_ID()).'</h4>
					<p class="popup__features_desc">'.get_field('more_about_tarif',get_the_ID()).'</p>
					<button id="popup__features_button_connect" class="popup_features_button_connect">Подключение</button>
				</div>
			</div>';
			
			

			if($typeTariff == 'InternetAndTVandMOB') { $alltypes = $alltypes.'InternetAndTVandMOB/'; $isMenuOne=true; $wrap_one = $wrap_one.$returnGrid; $popupOne=$popupOne.$returnPopup;}
			if($typeTariff == 'InternetAndTV') {$alltypes = $alltypes.'InternetAndTV/';$isMenuTwo=true; $wrap_two = $wrap_two.$returnGrid;  $popupTwo=$popupTwo.$returnPopup;}
			if($typeTariff == 'Internet') {$alltypes = $alltypes.'Internet/';$isMenuThree=true; $wrap_three = $wrap_three.$returnGrid;  $popupThree=$popupThree.$returnPopup;}
			if($typeTariff == 'TV') {$alltypes = $alltypes.'TV/';$isMenuFour=true; $wrap_four = $wrap_four.$returnGrid;  $popupFour=$popupFour.$returnPopup;}
			if($typeTariff == 'TVandMOB') {$alltypes = $alltypes.'TVandMOB/';$isMenuFive=true; $wrap_five = $wrap_five.$returnGrid;  $popupFive=$popupFive.$returnPopup;}
			if($typeTariff == 'InternetAndMOB') {$alltypes = $alltypes.'InternetAndMOB/';$isMenuSix=true; $wrap_six = $wrap_six.$returnGrid;  $popupSix=$popupSix.$returnPopup;}

			 
				
		}
		$allMenu = '';
		$allMenu = $allMenu.'<li><a id="all_tariff" class="active" tab="#items__three">Все тарифы</a></li>';
		if($isMenuOne===true){ $allMenu = $allMenu.'<li><a tab="#items__three">Мобильная связь + Интернет + ТВ</a></li>'; }
		if($isMenuTwo===true){ $allMenu = $allMenu.'<li><a tab="#items__two">Интернет + ТВ</a></li>'; }
		if($isMenuThree===true){ $allMenu = $allMenu.'<li><a tab="#items__one">Интернет</a></li>'; }
		if($isMenuFour===true){ $allMenu = $allMenu.'<li><a tab="#items__onetwo">Цифровое ТВ</a></li>'; }
		if($isMenuFive===true){ $allMenu = $allMenu.'<li><a tab="#items__twoduo">Мобильная связь + ТВ</a></li>'; }
		if($isMenuSix===true){ $allMenu = $allMenu.'<li><a tab="#items__twothird">Интернет + Мобильная связь</a></li>'; }

		
		if(!empty($wrap_one)){ $outCode = $outCode.'<div class="tariff__wrap active" id="items__three">
				<h2 class="tariff__title">Мобильная связь + Домашний интернет + Цифровое ТВ</h2>
				<div class="tariff__grid">'.$wrap_one.$popupOne.'</div></div>'; }
		if(!empty($wrap_two)){ $outCode = $outCode.'<div class="tariff__wrap active" id="items__two">
					<h2 class="tariff__title">Домашний интернет + Цифровое ТВ</h2>
					<div class="tariff__grid">'.$wrap_two.$popupTwo.'</div></div>'; }
		if(!empty($wrap_three)){$outCode = $outCode.'<div class="tariff__wrap active" id="items__one">
					<h2 class="tariff__title">Домашний интернет</h2>
					<div class="tariff__grid">'.$wrap_three.$popupThree.'</div></div>'; }
		if(!empty($wrap_four)){$outCode = $outCode.'<div class="tariff__wrap active" id="items__onetwo">
					<h2 class="tariff__title">Цифровое ТВ</h2>
					<div class="tariff__grid">'.$wrap_four.$popupFour.'</div></div>'; }
		if(!empty($wrap_five)){$outCode = $outCode.'<div class="tariff__wrap active" id="items__twoduo">
					<h2 class="tariff__title">Мобильная связь + ТВ</h2>
					<div class="tariff__grid">'.$wrap_five.$popupFour.'</div></div>'; }
		if(!empty($wrap_six)){$outCode = $outCode.'<div class="tariff__wrap active" id="items__twothird">
					<h2 class="tariff__title">Интернет + Мобильная связь</h2>
					<div class="tariff__grid">'.$wrap_six.$popupFour.'</div></div>'; }
		//$outCode = $wrap_one.$wrap_two.$wrap_three.$wrap_four;
		if(!empty($iscity)) {
			$namecity = get_term_field('name',$iscity['term_id'],'area');
			$termparent = get_term_field('parent',$iscity['term_id'],'area');
		}else {
			if(!empty($termarr[0]->name)){
				$namecity=$termarr[0]->name;
			}
		}
		//$namecity = $namecity->name;

	}

	

	header("Content-type: application/json;charset=utf-8");
	wp_send_json(array('status' => $outCode,'ismenu' => $allMenu,'row' => $wrap_three,'types' => $alltypes,'city' => $namecity,'arrayterm' => $termarr,'slug_city' => $termparent));
	wp_die();
}