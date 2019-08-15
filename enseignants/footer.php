<?php
/*-------------LANGUES ALTERNATIVES-------------*/
	
	/*Création d'un array des langues disponibles*/
	$langues = array(
	'fr' => 'Français',
	'en' => 'English',
	);
	if(isSet($_GET['lang']) && array_key_exists($_GET['lang'], $langues)){
		$langUsed = $_GET['lang'];
		setcookie('lang', $langUsed, time() + (3600*24*365)); 
	}
	elseif(isSet($_COOKIE['lang'])){
		$langUsed = $_COOKIE['lang'];
	}
	else{
		$langUsed = 'fr';
	}

	switch ($langUsed) {
	  case 'en':
	  $lang_file = 'lang.en.php';
	  break;

	  case 'fr':
	  $lang_file = 'lang.fr.php';
	  break;

	  default:
	  $lang_file = 'lang.fr.php';
	}

	include_once '../lang/'.$lang_file;


echo '<footer>
		<aside><a href="#">'.$lang['ens_footer'].'</a></aside>
		<p>
			<a href=\'?lang=fr\'>fr</a>
			<a href=\'?lang=en\'>en</a>
		<br>Copyright &copy; FERNANDES & VAILLANT - 2018</p>
	</footer>';
?>