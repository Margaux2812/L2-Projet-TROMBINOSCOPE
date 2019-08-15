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

include('functions.inc.php');

	if(!empty($_POST["connexion"])) {
	/* Regarder s'il y a tous les champs */
		foreach($_POST as $key=>$value) {
			if(empty($_POST[$key])) {
			$erreur = error_fields;
			break;
			}
		}
		
		if(dontmatch_ens(strtoupper($_POST['login']), $_POST['pass'])){
			$erreur = $lang['index_ens_error'];
		}else{
			if(!isset($_COOKIE['login_ens'])){
				setcookie('login_ens', htmlspecialchars($_POST['login']), time() + 200*24*3600);
			}
			/*On ouvre la session*/
			session_start();
			
			$infos = recuperer_ens(htmlspecialchars(strtoupper($_POST['login'])));
			$favorites = getFavorites(htmlspecialchars(strtoupper($_POST['login'])));
			
			$_SESSION['login'] = $infos['login'];
			$_SESSION['name'] = $infos['name'];
			$_SESSION['forname'] = $infos['forname'];
			$_SESSION['pass'] = $infos['pass'];
			$_SESSION['favorite'] = $favorites;
			$_SESSION['language'] = $langUsed;
			
			header('Location: accueil_ens.php');
		}
		
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
		<title><?php echo $lang['index_ens_title']; ?></title>
		<link rel="stylesheet" type="text/css" href="styles/index.css"/>
		<link rel="icon" href="../images/icon.png" sizes="16x16" type="image/png" />
		<link rel="icon" href="../images/iconbigger.png" sizes="32x32" type="image/png" />
</head>
<body>
	<div id='conteneur'>
	<a href="../index.php" id='return'><?php echo $lang['index_ens_retour']; ?></a>
		<div id='rectangle'>
			<form method="post" action='<?php echo $_SERVER['PHP_SELF']; ?>'>
						<table id="enstable">
							<tr>
								<td rowspan='2' id="enspic"><img src="images/teacher_icon.png" alt='enspic' /></td>
								<td></td>
								<td></td>
							</tr>
							<tr id="member_login">
								<td><?php echo $lang['index_ens']; ?></td>
								<td id="lockimg"><img src="../images/lock.png" alt='enslock' /></td>
							</tr>
							<tr class="filled">
								<td colspan='2'><input type="text" name="login" id="login" size="20" value="<?php
								if(isset($_COOKIE['login_ens'])){
									echo $_COOKIE['login_ens'];
								}?>"/></td>
								<td class="ens"> <label for="ens">Login</label> </td>
							</tr>
							<tr class="filled">
								<td colspan='2'><input type="password" name="pass" id="pass" size="20"/></td>
								<td class="ens"> <label for="pass"><?php echo $lang['index_ens_mdp']; ?></label> </td>
							</tr>
							<tr class="filled">
								<td colspan='2' id="button"><input type="submit" name="connexion" value="<?php echo $lang['index_ens_submit']; ?>"/></td>
								<td class="bottomright"></td>
							</tr>
						</table>
			</form>
			
			<?php if(!empty($erreur)) {?><div id="error"><p class="emoticon">&#9785;</p><p><?php echo $erreur; ?></p></div><?php }?>
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