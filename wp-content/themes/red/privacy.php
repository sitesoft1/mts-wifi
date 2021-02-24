<?php /* Template name: Privacy */ ?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<link rel="icon" href="<?php bloginfo('template_url'); ?>/favicon.png" type="image/png">
	<title><?php the_title(); ?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<!--<meta name="yandex-verification" content="f81136422d83c1c6" />-->
	<link rel="stylesheet" href="<?php bloginfo('template_url'); ?>/css/app.min.css">
<?php
	$client  = @$_SERVER['HTTP_CLIENT_IP'];
	$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
	$remote  = @$_SERVER['REMOTE_ADDR'];
	 
	if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
	elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
	else $ip = $remote;
	$utmcity = $_GET['utm_city'];
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
						<img src="<?php echo get_field('red_logo',8); ?>" alt="Logo">
					</a>
				</div>
			</div>
		</div>
	</div>
	<div id="privacy__content">
		<?php echo the_content(); ?>
	</div>
</div>
</body>
</html>