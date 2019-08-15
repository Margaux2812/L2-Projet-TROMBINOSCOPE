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
	
session_start();

if(!isset($_SESSION['login'])){
	header('Location: ../admin_log.php');
}

include('deconnexion_admin.php');
include('functions.inc.php');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
		<title><?php echo $lang['admin_accueil']; ?></title>
		<link rel="stylesheet" type="text/css" href="styles/style.css"/>
		<link rel="icon" href="../images/iconadmin.png" sizes="16x16" type="image/png" />
		<link rel="icon" href="../images/iconadminbigger.png" sizes="32x32" type="image/png" />
</head>
<body>
	<header>
		<nav>
			<ul>
				<li id='en-cours'><a href="admin.php"><strong><?php echo $lang['admin_menu_home']; ?></strong></a></li>
				<li><a href="fil.php"><?php echo $lang['admin_menu_fil']; ?></a></li>
				<li><a href="td.php"><?php echo $lang['admin_menu_td']; ?></a></li>
				<li><a href="ens.php" class='two_lines'><?php echo $lang['admin_menu_ens']; ?></a></li>
				<li><?php include('deconnexion_form.php'); ?></li>
			</ul>
		</nav>
	</header>
	<div id='contenu'>
		<section id='graphs'>
			<figure>
			<?php
			$imgpath = '../enseignants/charts/global_fil_chart.'.$langUsed.'.php';
			?>
				<img src='<?php echo $imgpath; ?>' alt='Les filieres'>
			</figure>

			<figure>
			<?php
			$imgpath2 = '../enseignants/charts/barre_graph_fil.'.$langUsed.'.php';
			?>
				<img src='<?php echo $imgpath2; ?>' alt='Les Groupes de td'>
			</figure>
		</section>
		<footer>
			<p>
			<a href='?lang=fr'>fr</a>
			<a href='?lang=en'>en</a>
			</p>
		</footer>
	</div>
</body>
</html>