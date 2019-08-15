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

if(!empty($_POST["admin_co"])) {
	/* Regarder s'il y a tous les champs */
		foreach($_POST as $key=>$value) {
			if(empty($_POST[$key])) {
			$error = $lang['error_fields'];
			break;
			}
		}
		
		try{	
			/*On parcourt le fichier .csv pour trouver l'identifiant et stockr le mdp trouvé*/
			$row = 1;
			$mdp_trouve='';
			if (($handle = fopen("admin/admin.csv", "a+")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					$row++;
					if($data[0]==$_POST["admin"]){
						$mdp_trouve = $data[1];
						break;
					}
				}
				fclose($handle);
			}
			
			/*password_verify nous dira si les mots de passe hachés correspondent
			donc comme la fonction s'appelle dontmatch, on envoie la negation*/
			$isPasswordCorrect = password_verify($_POST["pass"], $mdp_trouve);
			
				if($isPasswordCorrect == TRUE){
					/*On ouvre la session*/
					session_start();
					
					$_SESSION['login'] = 'admin';
					$_SESSION['language'] = $langUsed;
					
					header('Location: admin/admin.php');
					
				}else{
					$erreur = $lang['admin_log_error2'];
				}
		}
		catch(Exception $e){
			$erreur = "Erreur";
		}		
}
?>
<!DOCTYPE html>
<html>
<head>
		<meta charset="UTF-8">
		<title><?php $lang['admin_log_title']; ?></title>
		<link rel="stylesheet" type="text/css" href="style.css"/>
		<link rel="icon" href="images/icon.png" sizes="16x16" type="image/png" />
		<link rel="icon" href="images/iconbigger.png" sizes="32x32" type="image/png" />
</head>
<body>
	<div id='conteneur'>
		<a href="index.php" id='return'><?php echo $lang['admin_log_return']; ?></a>
		<div id="rectangle_2">
			
			<?php if(!empty($error)) { ?>	
									
				<div class="error"><?php if(isset($error)) echo $error; ?></div>
				
			<?php } ?>
			
			<form method="post" action='<?php echo $_SERVER['PHP_SELF']; ?>'>
				<table id="admintable">
					<tr>
						<td rowspan='2' id="adminpic"><img src="images/admin_profile_pic.png" alt='adminpic' /></td>
						<td></td>
						<td></td>
					</tr>
					<tr id="member_login">
						<td><?php echo $lang['admin_log_id']; ?></td>
						<td id="lockimg"><img src="images/lock.png" alt='adminlock' /></td>
					</tr>
					<tr class="filled">
						<td colspan='2'><input type="text" name="admin" id="admin" size="20"/></td>
						<td class="admin"> <label for="admin">Login</label> </td>
					</tr>
					<tr class="filled">
						<td colspan='2'><input type="password" name="pass" id="pass" size="20"/></td>
						<td class="admin"> <label for="pass"><?php echo $lang['admin_log_mdp']; ?></label> </td>
					</tr>
					<tr class="filled">
						<td colspan='2' id="button"><input type="submit" name="admin_co" value="<?php echo $lang['admin_log_submit']; ?>"/></td>
						<td class="bottomright"></td>
					</tr>
					
					<tr class="erreur">
						<td><?php if(!empty($erreur)) { echo $erreur; }?></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</form>
		</div>
		<footer>
			<p>
			<a href='?lang=fr'>fr</a>
			<a href='?lang=en'>en</a>
			</p>
		</footer>
	</div>
</body>
</html>