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

/*******************************************************************Form connexion*/
if(!empty($_POST["connexion"])) {
	/* Regarder s'il y a tous les champs */
		foreach($_POST as $key=>$value) {
			if(empty($_POST[$key])) {
			$erreur = $lang['error_fields'];
			break;
			}
		}
	
	if(!isset($erreur)){
		if(dontmatch($_POST['login'], $_POST['pass'])){
			$erreur = $lang['etu_index_form_error2'];
		}else{
			/*On ouvre la session*/
			session_start();
			
			$infos = recuperer(htmlspecialchars($_POST['login']));
			
			$_SESSION['login'] = $infos['login'];
			$_SESSION['name'] = str_replace('_', ' ', $infos['name']);
			$_SESSION['forname'] = $infos['forname'];
			$_SESSION['email'] = $infos['email'];
			$_SESSION['filiere'] = $infos['filiere'];
			$_SESSION['TD'] = $infos['td'];
			$_SESSION['profile_pic'] = $infos['profile_pic'];
			$_SESSION['mdp'] = $infos['mdp'];
			
			header('Location: page_etu.php');
		}
	}	
}

/*******************************************************************Form Inscription*/
if(!empty($_POST["inscription"])) {
	
	/* Regarder s'il y a tous les champs */
		foreach($_POST as $key=>$value) {
			if(empty($_POST[$key])) {
			$error = $lang['error_fields'];
			break;
			}
		}
	/* Si les mots de passes correspondent */
		if($_POST['mdp'] != $_POST['conf_mdp']){ 
			$error = $lang['etu_page_mdp_error_match']; 
		}
		
	/*Si le numéro étudiant a 8 chiffres*/
	if(!isset($error)){
		if((strlen($_POST['num_etu']) != 8) || is_int($_POST['num_etu'])){
			$error = $lang['etu_index_numetu_error']; 
		}
	}
	/* Email Validation */
	if(!isset($error)){
		if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$error = $lang['etu_index_mail_error'];
		}
	}
	
	/*Les filieres*/
	if(!isset($error)){
		if(!isset($_POST["fil"])) {
			$error = $lang['error_fields'];
		}
	}
	
	/*Securite mot de passe*/
	
	if(!isset($error)){
		if(strlen($_POST['mdp'])<5) {
			$error = $lang['etu_page_mdp_error_security'];
		}
		elseif(!preg_match("#[A-Z]#", $_POST['mdp']) || !preg_match("#[0-9]#", $_POST['mdp'])){
			$error = $lang['etu_page_mdp_error_security2'];
		}
	}
	
	
	/*On cherche notre numero etudiant dans le fichier csv*/
	
	if(!isset($error)){
		if(already_exists($_POST['num_etu']) == 1){
			$error = $lang['etu_index_insform_exists'];
		}
		else{ /*L'étudiant est nouveau*/
		
			/*On crypte le mot de passe */
			$mdp_hash = password_hash(htmlspecialchars($_POST['mdp']), PASSWORD_DEFAULT);
			$img_path = '';
			$td='';
		
			$donnees=array( array(
				htmlspecialchars($_POST['num_etu']),
				htmlspecialchars(strtoupper(str_replace(' ', '_', $_POST['name']))),
				htmlspecialchars(strtoupper(str_replace(' ', '_',$_POST['forname']))),
				htmlspecialchars($_POST['email']),
				$mdp_hash,
				$img_path,
				htmlspecialchars($_POST['fil']),
				$td
				
				)
			);
			
			$fp = fopen('donnees/etudiants.csv', 'a+');
			foreach($donnees as $fields){
				fputcsv($fp, $fields);
			}
			
			fclose($fp);
		
			$error = "";
			$compte_validé = $lang['etu_index_insform_success'];	
			unset($_POST);
		}
	}
	
	
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
		<title><?php echo $lang['etu_index_title']; ?></title>
		<link rel="stylesheet" type="text/css" href="styles/accueil.css"/>
		<link rel="icon" href="../images/icon.png" sizes="16x16" type="image/png" />
		<link rel="icon" href="../images/iconbigger.png" sizes="32x32" type="image/png" />
</head>
<body>
	<header>
	<a href="../index.php" id='return'><?php echo $lang['index_ens_retour']; ?></a>
		<h1><?php echo $lang['etu_index_h1']; ?></h1>
		<div id='conteneur_form'>
			<form method="post" action='index.php' id="login_form">
					<table>
						<tr>
							<td class='labels'> <label for="login">Login</label><img src="images/icon_help.png" alt="help" title="<?php echo $lang['etu_index_form_img']; ?>"></td>
							<td class='labels'> <label for="pass"><?php echo $lang['etu_index_form_mdp']; ?></label> </td>
							<td></td>
						</tr>
						<tr>
							<td><input type="text" name="login" id="login" size="15"/></td>
							<td><input type="password" name="pass" id="pass" size="15"/></td>
							<td class='button'><input type="submit" name="connexion" value="<?php echo $lang['etu_index_form_submit']; ?>"/></td>
						</tr>
						<tr class="erreur">
							<td><?php if(!empty($erreur)) { echo $erreur; }?></td>
							<td><a href='mdpoublie.php'><?php echo $lang['etu_index_form_forgot']; ?></a></td>
							<td></td>
						</tr>
					</table>
			</form>
		</div>
	</header>
	
	<div id="conteneur">
		<div id="img_presentation">
		<figure>
			<img src="images/dragon.png" alt="dragon" />
		</figure>
		</div>
		
		<div id="inscription">
			<h2><?php echo $lang['etu_index_insform_h2']; ?></h2>
			
			<h3><?php echo $lang['etu_index_insform_p']; ?></h3>
			
			<form method="post" action="" enctype="multipart/form-data">
				<table>
				<?php if(!empty($compte_validé)) { ?>
					
					<div class="success"><?php if(isset($compte_validé)) echo $compte_validé; ?></div>
				<?php } ?>
				
				<?php if(!empty($error)) { ?>	
								
					<div class="error"><?php if(isset($error)) echo $error; ?></div>
				<?php } ?>
				
					<tr>
						<td><input type="text" name="num_etu" <?php if(!empty($_POST['num_etu'])){ 
																			echo 'value="'.$_POST['num_etu'].'"'; 
																			}else{
																			echo 'placeholder="'.$lang['etu_index_insform_num'].'"'; }?> /></td>
					</tr>
					<tr>
						<td><input type="text" name="name" <?php if(!empty($_POST['name'])){ 
																			echo 'value="'.$_POST['name'].'"'; 
																			}else{
																			echo 'placeholder="'.$lang['etu_index_insform_name'].'"';}?> /></td>
					</tr>
					<tr>
						<td><input type="text" name="forname" <?php if(!empty($_POST['forname'])){ 
																			echo 'value="'.$_POST['forname'].'"'; 
																			}else{
																			echo 'placeholder="'.$lang['etu_index_insform_forname'].'"';}?> /></td>
					</tr>
					<tr>
						<td><input type="text" name="email" <?php if(!empty($_POST['email'])){ 
																			echo 'value="'.$_POST['email'].'"'; 
																			}else{
																			echo 'placeholder="'.$lang['etu_index_insform_email'].'"';}?> /></td>
					</tr>
					<tr>
						<td><input type="password" name="mdp" placeholder="<?php echo $lang['etu_index_insform_mdp']; ?>"/></td>
					</tr>
					<tr>
						<td><input type="password" name="conf_mdp" placeholder="<?php echo $lang['etu_index_insform_conf']; ?>"/></td>
					</tr>
					<tr>
						<td><select name="fil" id="fil">
							<option disabled selected value><?php echo $lang['etu_index_insform_fil']; ?></option>
						<?php
						$allfilieres = glob('../etudiants/donnees/' . '/*' , GLOB_ONLYDIR);
								$nbDossiers = count($allfilieres);
 
								//On parcourt
								for($filiere=0; $filiere<$nbDossiers; $filiere++){
									$dir_name_cut = explode('/',$allfilieres[$filiere]);
									$filierefullname = end($dir_name_cut);
									$filierechoix = str_replace('_', ' ', $filierefullname);
									
									echo '<option value="'.$filierechoix.'">'.$filierechoix.'</option>';
								}
						?>							   
							 </select>
						</td>
					</tr>
					<tr>
						<td><input type="submit" name="inscription" value="<?php echo $lang['etu_index_insform_submit']; ?>" /></td>
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