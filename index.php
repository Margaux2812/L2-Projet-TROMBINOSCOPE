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

	include_once 'lang/'.$lang_file;
	

if(!empty($_POST["set_status"])) {
	
	/* Regarder si on a bien coché une option */
	if(!isset($_POST["status"])) {
		$error = "Tous les champs sont requis";
	}else{
		if($_POST["status"]=='enseignant'){
			header('Location: enseignants/index.php');
		}else{
			header('Location: etudiants/index.php');
		}
	}
}
	
?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="UTF-8">
		<title><?php echo $lang['title']; ?></title>
		<link rel="stylesheet" type="text/css" href="style.css"/>
		<link rel="icon" href="images/icon.png" sizes="16x16" type="image/png" />
		<link rel="icon" href="images/iconbigger.png" sizes="32x32" type="image/png" />
</head>
<body>
	<div id='conteneur'>
		<figure>
			<a href="admin_log.php"><img src="images/key_admin.png" alt="admin_key" /></a>
		</figure>
		<div id="rectangle">
			<h1><?php echo $lang['h1']; ?></h1>
			<p><?php echo $lang['title_text']; ?></p>
		</div>
		
			<?php if(!empty($error)) { ?>	
									
				<div class="error"><?php if(isset($error)) echo $error; ?></div>
				
			<?php } ?>
			
			<form method="post" action="index.php">
				<table id="formulaire">	
				<tr id="legend">
					<td colspan='2'><?php echo $lang['status_top']; ?></td>
				</tr>
				<tr>
					<td>
						<label for="enseignant"><?php echo $lang['ens_status']; ?><input type="radio" name="status" value="enseignant" id="enseignant" /><span class="checkmark"></span></label>
					</td>
					<td>
						<label for="etudiant"><?php echo $lang['stu_status']; ?><input type="radio" name="status" value="etudiant" id="etudiant" /><span class="checkmark"></span></label>
					</td>
				</tr>
				<tr>
					<td colspan='2'><input type="submit" name="set_status" value="<?php echo $lang['submit_status']; ?>" /></td>
				</tr>
				</table>
			</form>
		<footer>
			<p>
			<a href='?lang=fr'>fr</a>
			<a href='?lang=en'>en</a>
			</p>
		</footer>
	</div>
</body>
</html>