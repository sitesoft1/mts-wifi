$(function(){
	
	var ts = new Date(2020, 9, 9),
		newYear = true;
	
	if((new Date()) > ts){
		// Задаем точку отсчета для примера. Пусть будет очередной Новый год или дата через 10 дней.
		// Обратите внимание на *1000 в конце - время должно задаваться в миллисекундах
		ts = (new Date()).getTime() + 10*24*60*60*1000;
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
