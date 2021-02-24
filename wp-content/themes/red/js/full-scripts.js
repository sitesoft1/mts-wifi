
var coordin = ''; 
navigator.geolocation.getCurrentPosition(position => {

	var lat = position.coords.latitude;
	var long = position.coords.longitude;

	function isEmpty(str){ return(!str||0===str.length); }

	function parseCities(text,tab){
		var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
		var data = {
			action: 'action_name',
			geo:text,
			geoTab: tab,
			_ajax_nonce: "<?php echo wp_create_nonce( 'my_ajax_nonce' ); ?>"
		};
		$.ajax({
			method: "POST",
			url: ajaxurl,
			data: data,
			dataType: "json",
			success: function(data) { 
				//console.log(data);
				if(data.status != 'notfound' ){
					$('.tariff__big_wrap').html(' ');
					$('.tariff__big_wrap').html(data.status);
					$('.header__menu_ul').html('');
					$('.header__menu_ul').html(data.ismenu);
				}
				
			}
		});
	}
	function addCities(){
		$('body').on('click','.location__add_cities li a',function(){
			var textCity = $(this).html();
			var parseCity = $(this).attr('tab');
			$('.hidden__location').fadeOut();
			$('.header__info__location__title').html('');
			$('.header__info__location__title').html(textCity);
			parseCities(textCity,parseCity);
		});
	}
	function addContent(geotag){

		var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
		var data = {
			action: 'action_name',
			geo:geotag,
			_ajax_nonce: "<?php echo wp_create_nonce( 'my_ajax_nonce' ); ?>"
		};

		$.ajax({
			method: "POST",
			url: ajaxurl,
			data: data,
			dataType: "json",
			success: function(data) { 
				if(data.status == 'notfound' ){
					$('.hidden__location').fadeIn();
					addCities();
					$('.location__close').click(function(){
						$('.hidden__location').fadeOut();
					});
				}
				else {
					$('.tariff__big_wrap').html(' ');
					$('.tariff__big_wrap').html(data.status);
					$('.header__menu_ul').html(' ');
					$('.header__menu_ul').html(data.ismenu);

					$('.header__info__location__title').html(' ');
					$('.header__info__location__title').html(data.city);
				}
				
			}
		});
	}

	function listCity(nameCity,acti){

		if(acti == 'starting') { cityaction = 'starting'; }
		else if(acti == 'update'){  cityaction = 'update';  }
		else { cityaction = 'starting'; }
		var ww = window.outerWidth;

		var ajaxurl = "";
		var data = {
			action: 'action_naming',
			name:nameCity,
			cityaction: cityaction,
			width:ww,
			find:'',
			_ajax_nonce: ""
		};

		$.ajax({
			method: "POST",
			url: ajaxurl,
			data: data,
			dataType: "json",
			success: function(data) { 

				$('.location__grid').html(' ');
				$('.location__grid').html(data.status);

				$('.header__menu_ul').html(' ');$('.header__menu_ul').html(data.ismenu);

				$('.tariff__big_wrap').html(' ');
					$('.tariff__big_wrap').html(data.status);
				console.log('Start TEST NO LOC');

				//$('.header__info__location__title').html(' ');
				//$('.header__info__location__title').html(data.cityname);
				
			}
		});
	}
	



	function finalAdd(yand,geoip){
		
		/*if(geoip == yand || geoip !== undefined || yand !== undefined) {
			document.querySelector('.header__info__location__title').innerHTML = geoip;
			addContent(geoip); console.log('Final - 1');
		}
		else {
			if(isEmpty(geoip) === true) { document.querySelector('.header__info__location__title').innerHTML = yand; addContent(yand); console.log('Final - 2'); }
			else { document.querySelector('.header__info__location__title').innerHTML = geoip; addContent(geoip);console.log('Final - 3'); }
		}*/
		//console.log('FINAL'); 
		if(isEmpty(isGetLocation) === true) {
			console.log('Метка не обнаружена');
			if(geoip == yand && (typeof geoip != 'undefined' || typeof yand != 'undefined')) {
			
				addContent(yand); 
			}
			else {
				console.log('Геолокация не совпадает');
				if(isEmpty(geoip) === true) { 
					document.querySelector('.header__info__location__title').innerHTML = yand; 
					addContent(yand); 
					console.log('Геолокация от PHP пуста');
				}
				else { 
					document.querySelector('.header__info__location__title').innerHTML = geoip; 
					addContent(geoip);
					console.log('Геолокация от PHP ЗАПОЛНЕНА');
				}
			}
		}
		else {
			console.log('Метка не пустая');
				document.querySelector('.header__info__location__title').innerHTML = geoip; 
				addContent(isGetLocation);
				var z=$('.header__info__location__title').attr('tab-section');
				if(isEmpty(z) === false){
					var valTab;
					if(z=='mobinttv'){valTab = '#items__three';}
					if(z=='inttv'){valTab = '#items__two';}
					if(z=='int'){valTab = '#items__one';}
					if(z=='tv'){valTab = '#items__onetwo';}
					$('html, body').animate({ scrollTop: $(valTab).offset().top }, { duration: 10, easing: "linear" });
				}
			
		}
	}
	function isE(str){return(!str||0===str.length);}

	var isGetLocation = document.querySelectorAll('.header__info_location .header__info__location__title')[0].getAttribute('tab-city');
	listCity(isGetLocation,'starting'); 
	ymaps.ready(init);

	if(isE(isGetLocation) === true){ 
		$.magnificPopup.open({
			items: { src: '#popup__select_city' },
			type: 'inline'
		},0); 
	}
	//else { listCity(isGetLocation,'starting'); }

	
	function init() {
		var geoIpCity;
		var yandexCity;

	    var myGeocoder = ymaps.geocode([lat,long]);
		myGeocoder.then(
		    function (res) {
		        // Выведем в консоль данные, полученные в результате геокодирования объекта.
				var objs = res.geoObjects.toArray();
				geoIpCity = objs[0].properties.getAll().metaDataProperty.GeocoderMetaData.Address.Components[3].name;
				//var isLoca = document.querySelectorAll('.header__info_location .header__info__location__title')[0].getAttribute('tab-city');
				//if(isE(isLoca) === true){ console.log('IS TEST'); }
		    },
		    function (err) {
		        // Обработка ошибки.
		    }
		);

		var geolocation = ymaps.geolocation;
		geolocation.get({
		    provider: 'yandex',
		    mapStateAutoApply: true
		}).then(function (result) {
			//console.log('One - 2');
		    yandexCity = result.geoObjects.get(0).properties._data.name;
		    finalAdd(yandexCity,geoIpCity);
		});
		
	}
});
	function canUseWebp() {
		// Создаем элемент canvas
		let elem = document.createElement('canvas');
		// Приводим элемент к булеву типу
		if (!!(elem.getContext && elem.getContext('2d'))) {
		// Создаем изображение в формате webp, возвращаем индекс искомого элемента и сразу же проверяем его
		return elem.toDataURL('image/webp').indexOf('data:image/webp') == 0;
		}
		// Иначе Webp не используем
		return false;
	}
	window.onload = function () {
	    // Получаем все элементы с дата-атрибутом data-bg
	    let images = document.querySelectorAll('[data-bg]');
	    // Проходимся по каждому
	    for (let i = 0; i < images.length; i++) {
	        // Получаем значение каждого дата-атрибута
	        let image = images[i].getAttribute('data-bg');
	        // Каждому найденному элементу задаем свойство background-image с изображение формата jpg
	        images[i].style.backgroundImage = 'url(' + image + ')';
	    }

	    // Проверяем, является ли браузер посетителя сайта Firefox и получаем его версию
	    let isitFirefox = window.navigator.userAgent.match(/Firefox\/([0-9]+)\./);
	    let firefoxVer = isitFirefox ? parseInt(isitFirefox[1]) : 0;

	    // Если есть поддержка Webp или браузер Firefox версии больше или равно 65
	    if (canUseWebp() || firefoxVer >= 65) {
	        // Делаем все то же самое что и для jpg, но уже для изображений формата Webp
	        let imagesWebp = document.querySelectorAll('[data-bg-webp]');
	        for (let i = 0; i < imagesWebp.length; i++) {
	            let imageWebp = imagesWebp[i].getAttribute('data-bg-webp');
	            imagesWebp[i].style.backgroundImage = 'url(' + imageWebp + ')';
	        }
	    }
	};

	$(document).ready(function(){
		//alert('test 1.8.6'); 
		
		$('.all__ok').css('display','flex').hide();

		$('.check__wrap').click(function(){
			if($(this).attr('class').indexOf('active') == -1) {
				$('.check__wrap').removeClass('active');
				$('.check__wrap input').removeAttr('checked');

				var checkVal = $(this).children('input').attr('name');
				if(checkVal == 'check__apartment_name'){ $('.hidden__office').fadeIn(100); $('.show__office').fadeOut(100); }
				if(checkVal == 'check__office_name'){ $('.hidden__office').fadeOut(100); $('.show__office').fadeIn(100); }

				$(this).find('input').attr('checked','checked');
				$(this).addClass('active');
			}
		});

		$('.check__request').click(function(){
			if($(this).attr('class').indexOf('active') == -1) {
				$('.check__request').removeClass('active');
				$('.check__request input').removeAttr('checked');

				var checkVal = $(this).children('input').attr('name');
				if(checkVal == 'request__apart'){ $('.hidden__popup_apart').fadeIn(100); $('.hidden__popup_office').fadeOut(100); }
				if(checkVal == 'request__office'){ $('.hidden__popup_apart').fadeOut(100); $('.hidden__popup_office').fadeIn(100); }

				$(this).find('input').attr('checked','checked');
				$(this).addClass('active');
			}
		});

		$('.request__label_cons').click(function(){
			if($(this).attr('class').indexOf('active') == -1) {
				$('.request__label_cons').removeClass('active');
				$('.request__label_cons input').removeAttr('checked');

				var checkVal = $(this).children('input').attr('name');
				if(checkVal == 'popup__cons_apart'){ 
					$('.hidden__consul_apart').fadeOut(100);  
					$('.hidden__consul_office').fadeIn(100);  
					$('.hidden__consul_other').fadeIn(100);  
				}
				if(checkVal == 'popup__cons_office' || checkVal == 'popup__cons_other'){ 
					$('.hidden__consul_apart').fadeIn(100);  
					$('.hidden__consul_office').fadeOut(100);  
					$('.hidden__consul_other').fadeOut(100);  
				}
				/*if(checkVal == 'popup__cons_other') { 
					$('.hidden__consul_apart').fadeIn(100);  
					$('.hidden__consul_office').fadeOut(100);  
					$('.hidden__consul_other').fadeIn(100); 
				}*/

				$(this).find('input').attr('checked','checked');
				$(this).addClass('active');
			}
		});
		$('.tmr__button').click(function(){
			$('html, body').animate({ scrollTop: $('.tariff__zero_element').offset().top }, { duration: 10, easing: "linear" });
		});

		$('body').on('click','.location__grid_row',function(){
			if($(this).attr('class').indexOf('active') == -1){
				$('.location__grid_row').removeClass('active');
				$(this).addClass('active');
			}
			else { $(this).removeClass('active'); }
		});

		$('.hidden__location').css('display','flex').hide();
		$('.header__info_location').click(function(){
			$('.hidden__location').fadeIn();
			$('.location__close').click(function(){
				$('.hidden__location').fadeOut();
			});
		});
		//$('.tariff__wrap').css('display','flex').hide();
		//$('#items__three').show();

		$('body').on('click','.header__menu_ul li a',function(e){
			e.preventDefault();
			$('.header__menu_ul li a').removeClass('active');
			var valTab = $(this).attr('tab');
			$('.tariff__wrap').removeClass('active');
			$(valTab).addClass('active');
			$(this).addClass('active');
			$('html, body').animate({ scrollTop: $(valTab).offset().top }, { duration: 10, easing: "linear" });
		});

		$('body').on('click','.tariff__add_check',function(){
			if($(this).attr('class').indexOf('active') == -1){ $(this).addClass('active'); $(this).parent().next('.tariff__price_with_wifi').css('display','flex'); }
			else { $(this).removeClass('active'); $(this).parent().next('.tariff__price_with_wifi').css('display','none'); }
		});
		
		$('.button__request').magnificPopup();

		$('body').on('click','.tariff__connect',function(e){
			e.preventDefault();
			var hrefPopup = $(this).attr('href');
			$.magnificPopup.open({
				items: { src: hrefPopup },
				type: 'inline'
			},0);
		});

		$('.first__call').click(function(e){
			e.preventDefault();
			$.magnificPopup.open({
				items: { src: '#popup__call_wrap' },
				type: 'inline'
			},0);
			$('.popup__call_button').click(function(){
				$.magnificPopup.close();
				setTimeout(function(){
					$.magnificPopup.open({
						items: { src: $('#popup__consult_wrap') },
						type: 'inline'
					});
				},10);	
			});
		});


		$('body').on('click','.tariff__more',function(e){
				e.preventDefault();
				var dir = $(this).attr('href');
				$.magnificPopup.open({
					items: { src: dir },
					type: 'inline'
				},0);
				$('#popup__features_button_connect').click(function(){
					$.magnificPopup.close();
					setTimeout(function(){
						$.magnificPopup.open({
							items: { src: $('#popup__request_wrap') },
							type: 'inline'
						});
					},10);	
				});
		});

		/*$('.tariff__more').click(function(){
			
		});*/
		$('.header__info_support,.button__expert').click(function(){
			$.magnificPopup.open({
				items: { src: '#popup__interesting_wrap' },
				type: 'inline'
			},0);

			$('.popup__inter_apart_button').click(function(){
				$.magnificPopup.close();
				setTimeout(function(){
					$.magnificPopup.open({
						items: { src: '#popup__call_wrap' },
						type: 'inline'
					},0);
					$('.popup__call_button').click(function(){
						$.magnificPopup.close();
						setTimeout(function(){
							$.magnificPopup.open({
								items: { src: $('#popup__consult_wrap') },
								type: 'inline'
							});
						},10);	
					});
				},10);	
			});
			$('.popup__inter__other_button').click(function(){
				$.magnificPopup.close();
				setTimeout(function(){
					$.magnificPopup.open({
						items: { src: $('#popup__phone__wrap') },
						type: 'inline'
					});
				},10);	
			});
		});

		$(function(){
			<?php $stDate = get_field('timer_date'); $exDate = explode('.',$stDate); ?>
			
			var ts = new Date(<?php echo $exDate[0]; ?>, <?php $outEx = (int)$exDate[1]; $outEx = $outEx-1;echo $outEx; ?>,<?php echo $exDate[2]; ?>);
			if((new Date()) > ts){
				
				// Задаем точку отсчета для примера. Пусть будет очередной Новый год или дата через 10 дней.
				// Обратите внимание на *1000 в конце - время должно задаваться в миллисекундах
				//ts = (new Date(2020, 8,20)).getTime() + 10*24*60*60*1000;
				ts = (new Date(2020, 7,20)).getTime() + 10*24*60*60*1000;
				newYear = false;
			}
				
			$('.tmr__main_wrap').countdown({
				timestamp	: ts,
				callback	: function(days, hours, minutes, seconds){
					var strDay = ''+days;
					var strHour = ''+hours;
					var strMin = ''+minutes;
					var strSec = ''+seconds;
					if(strDay.length == 1){ strDay = '0'+strDay; }
					if(strHour.length == 1){ strHour = '0'+strHour; }
					if(strMin.length == 1){ strMin = '0'+strMin; }
					if(strSec.length == 1){ strSec = '0'+strSec; }

					$('.tmr__seconds_wrap .tmr__numbers span').eq(0).html(strSec.charAt(0));
					$('.tmr__seconds_wrap .tmr__numbers span').eq(1).html(strSec.charAt(1));

					$('.tmr__minutes_wrap .tmr__numbers span').eq(0).html(strMin.charAt(0));
					$('.tmr__minutes_wrap .tmr__numbers span').eq(1).html(strMin.charAt(1));

					$('.tmr__hour_wrap .tmr__numbers span').eq(0).html(strHour.charAt(0));
					$('.tmr__hour_wrap .tmr__numbers span').eq(1).html(strHour.charAt(1));

					$('.tmr__day_wrap .tmr__numbers span').eq(0).html(strDay.charAt(0));
					$('.tmr__day_wrap .tmr__numbers span').eq(1).html(strDay.charAt(1));
				}
			});
			
		});

		$("#request_phone,#popup__consultphone,#popup__reqphone").mask("8(999) 999-99-99");

		function keyInput(keyVal){
			var valfindcity = $('#input_find').attr('value');
			var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
			var wwidth = window.outerWidth;
			var data = {
				action: 'action_naming',
				name:'',
				cityaction: keyVal,
				width:wwidth,
				find:valfindcity,
				_ajax_nonce: '<?php echo wp_create_nonce( 'my_ajax_nonce' ); ?>'
			};

			$.ajax({
				method: "POST",
				url: ajaxurl,
				data: data,
				dataType: "json",
				success: function(data) { 
					console.log(data);
					if(data.keycode == null) {
						$('.location__grid').html(' ');
						if(keyVal == 'keyup'){ $('.location__grid').html(data.parent);  }
						else { $('.location__grid').html(data.parent); }
					}
					else {
						$('.location__grid').html(' ');
						if(keyVal == 'keyup'){ $('.location__grid').html(data.keycode);  }
						else { $('.location__grid').html(data.parent); }
					}
					
				}
			});
		}

		$('body').on('keyup touchend','#input_find',function(e) {
			if(e.keyCode === 13) { e.preventDefault(); keyInput('find'); }
			keyInput('keyup');
		});
		$('body').on('click','.find__icon',function(){ keyInput('find'); });

		var ww = $(window).outerWidth();
		if(ww < 1199){
			$('.header__menu_wrap').css('display','flex').hide();
			$('.header__mobile_menu').click(function(){
				if($('.header__menu_ul li').length == 1){
					$('.header__info_support__mobile').css('top','37vw');
					$('.header__menu_wrap').addClass('isone');
				}
				if($('.header__menu_ul li').length == 2){
					$('.header__info_support__mobile').css('top','50vw');
					$('.header__menu_wrap').addClass('istwo');
				}
				if($('.header__menu_ul li').length == 3){
					$('.header__info_support__mobile').css('top','60vw');
					$('.header__menu_wrap').addClass('isthree');
				}
				if($('.header__menu_ul li').length == 4){
					$('.header__info_support__mobile').css('top','72.8vw');
					$('.header__menu_wrap').addClass('isfour');
				}


				$('.header__menu_wrap').show();
				$('.header__menu_mobile_close').click(function(){ $('.header__menu_wrap').fadeOut(); 
					$('.header__menu_wrap').removeClass('isfour isone istwo isthree');
 				});
				$('.header__menu_ul li a').click(function(e){
					e.preventDefault();
					$('.header__menu_ul li a').removeClass('active');
					var valTab = $(this).attr('tab');
					$('.tariff__wrap').removeClass('active');
					$(valTab).addClass('active');
					$(this).addClass('active');
					$('.header__menu_wrap').fadeOut();
					$('html, body').animate({ scrollTop: $(valTab).offset().top }, { duration: 10, easing: "linear" });
				});
				$('.header__info_support__mobile').click(function(){
					$('.header__menu_wrap').fadeOut();
					$.magnificPopup.open({
						items: { src: '#popup__interesting_wrap' },
						type: 'inline'
					},0);

					$('.popup__inter_apart_button').click(function(){
						$.magnificPopup.close();
						setTimeout(function(){
							$.magnificPopup.open({
								items: { src: '#popup__call_wrap' },
								type: 'inline'
							},0);
							$('.popup__call_button').click(function(){
								$.magnificPopup.close();
								setTimeout(function(){
									$.magnificPopup.open({
										items: { src: $('#popup__consult_wrap') },
										type: 'inline'
									});
								},10);	
							});
						},10);	
					});
					$('.popup__inter__other_button').click(function(){
						$.magnificPopup.close();
						setTimeout(function(){
							$.magnificPopup.open({
								items: { src: $('#popup__phone__wrap') },
								type: 'inline'
							});
						},10);	
					});
				});
			});

			var htmlAll = '';

			$('.location__grid_column').each(function(){
				var newHtml = $(this).html();
				htmlAll = htmlAll+newHtml;
			});
			$('.location__grid_column').remove();
			$('.location__grid').append(htmlAll);

			$('.location__grid_row').click(function(){
				if($(this).attr('class').indexOf('active') == -1){
					$('.location__grid_row').removeClass('active');
					$(this).addClass('active');
				}
				else { $(this).removeClass('active'); }
			});

			$('#popup__consuladdress,#popup__reqaddress').attr('placeholder','Адрес');
		}


		$('#request__submit').click(function(){
			var lru = document.location.protocol+'//'+document.location.host;
			var regionIn = $('.header__info__location__title').html();
			$('#region_page').attr('value',regionIn);
			var onedata = $('#form1r').serialize(); console.log(onedata);
			$.ajax({
				method: "POST",
				url: lru+'/wp-content/themes/red/mail1r.php?action=mail1r',
				data: onedata,
				dataType: "json",
				success: function(data) { console.log(data);
					if(data.status == 'error') {
						var array_names = data.description;
						$('#form1r input[type=text]').removeClass('error_input');
						array_names.forEach(function(item, i, array_names){
							$('[name='+item+']').parent('label').addClass('error_input');
						});
					}
					if(data.status == 'success'){
						$('input[type=text]').attr('value','');
						$('.req__ok').show();
						setTimeout(function(){ $('.req__ok').fadeOut(); },2000);
					}
				}
			});
		});

		$('#popup__consult_submit').click(function(){
			var lru = document.location.protocol+'//'+document.location.host;
			var regionIn = $('.header__info__location__title').html();
			$('#region_consult').attr('value',regionIn);
			var onedata = $('#form2r').serialize(); console.log(onedata);
			$.ajax({
				method: "POST",
				url: lru+'/wp-content/themes/red/mail2r.php?action=mail2r',
				data: onedata,
				dataType: "json",
				success: function(data) {
					console.log(data);
					if(data.status == 'error') {
						var array_names = data.description;
						$('input[type=text]').parent('label').removeClass('error_input');
						array_names.forEach(function(item, i, array_names){
							$('[name='+item+']').parent('label').addClass('error_input');
						});
					}
					if(data.status == 'success'){
						$('input[type=text]').attr('value','');
						$('.consult__ok').show();
						setTimeout(function(){ $('.consult__ok').fadeOut(); },2000);
					}
				}
			});
		});

		$('body').on('click','.tariff__connect',function(){
			var tarifftitle = $(this).parent('.tariff__grid_column').children('.tariff__grid_title').html();
			$('#popup__reqconnect').attr('value',tarifftitle);
		});

		$('#popup__request_submit').click(function(){
			var lru = document.location.protocol+'//'+document.location.host;
			var regionIn = $('.header__info__location__title').html();
			$('#region_request').attr('value',regionIn);
			var threedata = $('#form3r').serialize(); console.log(threedata);
			$.ajax({
				method: "POST",
				url: lru+'/wp-content/themes/red/mail3r.php?action=mail3r',
				data: threedata,
				dataType: "json",
				success: function(data) {
					console.log(data);
					if(data.status == 'error') {
						var array_names = data.description;
						$('input[type=text]').parent('label').removeClass('error_input');
						array_names.forEach(function(item, i, array_names){
							$('[name='+item+']').parent('label').addClass('error_input');
						});
					}
					if(data.status == 'success'){
						$('input[type=text]').attr('value','');
						$('.popup_req__ok').show();
						setTimeout(function(){ $('.popup_req__ok').fadeOut(); },2000);
					}
				}
			});
		});
		
	});
