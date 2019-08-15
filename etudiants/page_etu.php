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

//Si quelqu'un essaye d'arriver sur cette page sans s'être authentifié, on le redirige
session_start();
if(!$_SESSION['login']){
   header("location:index.php");
   die;
}	
/******************************************************************Changement de la photo de profil*/
/*Si on a rempli le formulaire, on verifie que le photo donnée est valide et si l'etudiant a renseigné son
groupe de TD (pour avoir le bon chemin d'accès). Si elle l'est,
on ajoute dans le dossier 'FILIERE/' ou 'FILIERE/TD' (si celle-ci a des groupes de TD) la photo
en la retaillant et en la sauvegardant au nom de NOM.PRE.NUM_ETU.extension.
On change ensuite dans le fichier csv le chemind'accès de la photo.*/
if(isset($_FILES['pic'])){
	if(!isnotvalid($_FILES['pic'])){
			$fil = str_replace(' ', '_', $_SESSION['filiere']);
			$alltds = glob('../etudiants/donnees/'.$fil. '/*' , GLOB_ONLYDIR);
			$nbtds=count($alltds);
	
		if(($_SESSION['TD'] !== '') ||($nbtds==0)){
			$img_path = upload($_FILES['pic'], $_SESSION['name'], $_SESSION['forname'], $_SESSION['login'], str_replace(' ', '_', $_SESSION['filiere']), $_SESSION['TD']);
			
			update($img_path, '5', $_SESSION['login']);
			
			$_SESSION["profile_pic"]=$img_path;
			unset($_POST);
		}else{
			$erreur = $lang['etu_page_img_error_td'];
		}
	}else{
		$erreur =$lang['etu_page_img_error_format'];
	}
}
/******************************************************************Changement du nom*/
/*Si on change le nom, on modifie par conséquence le nom de la photo également (si elle est déjà
mise en ligne). On change donc son chemin d'accès qu'on réécrit dans le fichier .csv, et on renomme
la photo dans le serveur. On change ensuite le nom dans le fichier .csv*/
if(!empty($_POST["name"])) {
	if($_SESSION['profile_pic'] !== ''){
		$explode = explode('.', $_SESSION['profile_pic']);
		$extension = end($explode);
		$first_3_letters_of_forname = substr($_SESSION['forname'], 0, 3);
		if($_SESSION['TD']!=''){
			$new_img_path = 'donnees/'.str_replace(' ', '_', $_SESSION['filiere']).'/'.$_SESSION['TD'].'/'.strtoupper($_POST['name']).'.'.$first_3_letters_of_forname.'.'.$_SESSION['login'].'.'.$extension;
		}else{
			$new_img_path = 'donnees/'.str_replace(' ', '_', $_SESSION['filiere']).'/'.strtoupper($_POST['name']).'.'.$first_3_letters_of_forname.'.'.$_SESSION['login'].'.'.$extension;
		}
		
		update($new_img_path, '5', $_SESSION['login']);
		rename($_SESSION['profile_pic'], $new_img_path);
		$_SESSION['profile_pic'] = $new_img_path;
	}
	update(htmlspecialchars(strtoupper($_POST['name'])), '1', $_SESSION['login']);
	$_SESSION["name"] = strtoupper($_POST["name"]);
	unset($_POST);
}
/******************************************************************Changement du prénom*/
/*Même fonctionnement que pour le changement du prénom*/
if(!empty($_POST["forname"])) {
	/*Il faut changer le nom de l'image*/
	if($_SESSION['profile_pic'] !== ''){
		$explode = explode('.', $_SESSION['profile_pic']);
		$extension = end($explode);
		$first_3_letters_of_forname = substr(strtoupper($_POST['forname']), 0, 3);
		if($_SESSION['TD']!=''){
			$new_img_path = 'donnees/'.str_replace(' ', '_', $_SESSION['filiere']).'/'.$_SESSION['TD'].'/'.$_SESSION['name'].'.'.$first_3_letters_of_forname.'.'.$_SESSION['login'].'.'.$extension;
		}else{
			$new_img_path = 'donnees/'.str_replace(' ', '_', $_SESSION['filiere']).'/'.$_SESSION['name'].'.'.$first_3_letters_of_forname.'.'.$_SESSION['login'].'.'.$extension;
		}
		update($new_img_path, '5', $_SESSION['login']);
		rename($_SESSION['profile_pic'], $new_img_path);
		$_SESSION['profile_pic'] = $new_img_path;
	}
	update(htmlspecialchars(strtoupper($_POST['forname'])), '2', $_SESSION['login']);
	$_SESSION["forname"] = strtoupper($_POST["forname"]);
	unset($_POST);
}
/******************************************************************Changement de l'adresse email*/
if(!empty($_POST["modmail"])) {
	if(isset($_POST['mail'])){
		if(filter_var($_POST['mail'], FILTER_VALIDATE_EMAIL)) {
			update(htmlspecialchars($_POST['mail']), '3', $_SESSION['login']);
			$_SESSION["email"] = $_POST["mail"];
			unset($_POST);
		}
		else{
			$erreur=$lang['etu_page_email_error'];
		}
	}
}
/******************************************************************Changement de la filière*/
/*Si cette nouvelle filiere n'a pas de TD, la session change aussi son TD*/
if(!empty($_POST["modfil"])) {
	if(!empty($_POST["fil"])){
		$alltds = glob('../etudiants/donnees/'.$_POST["fil"]. '/*' , GLOB_ONLYDIR);
		$nbtds=count($alltds);
		if($nbtds==0){
			$_SESSION['TD'] ='';
		}
		
		if($_SESSION['profile_pic'] !== ''){
			$explode = explode('.', $_SESSION['profile_pic']);
			$extension = end($explode);
			$first_3_letters_of_forname = substr($_SESSION['forname'], 0, 3);
			if($_SESSION['TD']!=''){
				$new_img_path = 'donnees/'.$_POST["fil"].'/'.$_SESSION['TD'].'/'.$_SESSION['name'].'.'.$first_3_letters_of_forname.'.'.$_SESSION['login'].'.'.$extension;
			}else{
				$new_img_path = 'donnees/'.$_POST["fil"].'/'.$_SESSION['name'].'.'.$first_3_letters_of_forname.'.'.$_SESSION['login'].'.'.$extension;
			}
			
			rename($_SESSION['profile_pic'], $new_img_path);
			
			$_SESSION['profile_pic'] = $new_img_path;
		}
		
		update($_POST['fil'], '6', $_SESSION['login']);
		$_SESSION["filiere"] = str_replace('_', ' ', $_POST["fil"]);
		unset($_POST);
	}
}
/******************************************************************Changement du groupe de TD*/
if(!empty($_POST["modTD"])) {
	if(!empty($_POST["td"])){
		
		if($_SESSION['profile_pic'] !== ''){
			$explode = explode('.', $_SESSION['profile_pic']);
			$extension = end($explode);
			$first_3_letters_of_forname = substr($_SESSION['forname'], 0, 3);
			if($_SESSION['TD']!=''){
				$new_img_path = 'donnees/'.str_replace(' ', '_', $_SESSION["filiere"]).'/'.$_POST["td"].'/'.$_SESSION['name'].'.'.$first_3_letters_of_forname.'.'.$_SESSION['login'].'.'.$extension;
			}else{
				$new_img_path = 'donnees/'.str_replace(' ', '_', $_SESSION["filiere"]).'/'.$_SESSION['name'].'.'.$first_3_letters_of_forname.'.'.$_SESSION['login'].'.'.$extension;
			}
			
			rename($_SESSION['profile_pic'], $new_img_path);
			
			$_SESSION['profile_pic'] = $new_img_path;
		}
		
		update($_POST['td'], '7', $_SESSION['login']);
		$_SESSION["TD"] = $_POST["td"];
		unset($_POST);
	}
}
/******************************************************************Changement du mot de passe*/	
/*On change le mot de passe, on n'utilise pas la fonction 'update' car on ne peut pas comparer
l'ancien mot de passe avec celui dans le fichier .csv, comme il est crypté*/
if(!empty($_POST['changemdp'])){
	foreach($_POST as $key=>$value) {
		if(empty($_POST[$key])) {
			$erreur_change_pass = $lang['error_fields'];
		break;
		}
	}
	
	/*S'ils ne correspondent pas*/
	if(!isset($erreur_change_pass)){
		if($_POST['newpass'] != $_POST['confnewpass']){ 
			$erreur_change_pass = $lang['etu_page_mdp_error_match']; 
		}
	}
	
	/*Si l'ancien n'est pas bon*/
	if(!isset($erreur_change_pass)){
		$isCorrect = password_verify($_POST['oldpass'], $_SESSION['mdp']);
		if(!$isCorrect){
			$erreur_change_pass = $lang['etu_page_mdp_error_false'];
		}
	}
	
	/*Si le nouveau n'est pas aux normes*/
	if(!isset($erreur_change_pass)){
		if(strlen($_POST['newpass'])<5) {
			$erreur_change_pass = $lang['etu_page_mdp_error_security'];
		}
		elseif(!preg_match("#[A-Z]#", $_POST['newpass']) || !preg_match("#[0-9]#", $_POST['newpass'])){
			$erreur_change_pass = $lang['etu_page_mdp_error_security2'];
		}
	}
	
	if(!isset($erreur_change_pass)){
		$mdphash = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
		try{
			$lire = fopen('donnees/etudiants.csv', 'r');
			$ecrire = fopen('donnees/temporary.csv', 'w');
			while( false !== ( $data = fgetcsv($lire) ) ){ 
			   if ($data[0] == $_SESSION['login']) {
				  $data[4] = $mdphash;
			   }
			   fputcsv( $ecrire, $data);
			}
			fclose( $lire );
			fclose( $ecrire );
			unlink('donnees/etudiants.csv');
			rename('donnees/temporary.csv', 'donnees/etudiants.csv');
			
			$_SESSION['mdp'] = $mdphash;
			unset($_POST);
			
			$success = $lang['etu_page_mdp_error_success'];
		}
		catch(Exception $e){
			$erreur_change_pass = $lang['etu_page_mdp_error'];
		}
	}
}		
/******************************************************************Déconnexion*/	

if(!empty($_POST["logout"])) {
	session_destroy();
	header('Location: index.php');
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
		<title><?php echo $lang['etu_page_title']; ?></title>
		<link rel="stylesheet" type="text/css" href="styles/style.css"/>
		<link rel="icon" href="../images/icon.png" sizes="16x16" type="image/png" />
		<link rel="icon" href="../images/iconbigger.png" sizes="32x32" type="image/png" />
</head>
<body>
	<table id='header'>
		<tr>
			<td id='img'><form method="post" action='page_etu.php'>
				<input id="image" type="image" alt="change_mdp" src="images/gear.png" value="change_mdp" title='<?php echo $lang['etu_page_mdp_title']; ?>'>
				<input type="hidden" name="change_mdp" value="Changer">
			</form></td>
			<td id='button'><form method="post" action='page_etu.php'>
				<input type="submit" name="logout" id="logout" value="<?php echo $lang['etu_page_deco']; ?>" />
			</form></td>
		</tr>
	</table>
	<?php if(!empty($erreur)) { ?>
					
					<div class="error"><?php if(isset($erreur)) echo $erreur; ?></div>
	<?php } ?>
	<table id='outside'>
		<tr>
			<td rowspan='5'>
				<form method="post" action='page_etu.php' enctype="multipart/form-data">
				<table class='inside'>
					<tr>
						<td class="label_img"><?php 
						
						if(!empty($_POST["modpic"])) {
							   echo "<input type=\"file\" name=\"pic\" class=\"input-picture\" />";
						}elseif($_SESSION['profile_pic'] !== ''){		
							echo "<img src=\"".$_SESSION['profile_pic']."\" alt=\"Profil picture\" >"; 
						}else{ echo "<img src=\"images/empty.png\" alt=\"Profil picture\">";}
						?>
						</td>
					</tr>
					<tr>
						<td class='changepic'><input type="submit" name="modpic" id="modpic" value="<?php echo $lang['etu_page_submit']; ?>" /></td>
					</tr>
				</table>
				</form>
			</td>
			<td>
				<form method="post" action='page_etu.php'>
				<table class='inside'>
					<tr>
						<td class="label"><?php echo $lang['etu_page_name']; ?></td>
						<td class='input'><?php 
						if(!empty($_POST["modname"])) {
						if(array_key_exists('modname',$_POST)){
						   echo"	<input type=\"text\" name=\"name\" id=\"name\"/ size=\"38\">";
						}}else{		
							echo '<p>'.str_replace('_', ' ', $_SESSION['name']).'</p>'; 
							}?></td>
						<td class='change'><input type="submit" name="modname" id="modname" value="<?php echo $lang['etu_page_submit']; ?>" /></td>
					</tr>
				</table>
				</form>
			</td>
		</tr>
		<tr>
			<td>
			<form method="post" action='page_etu.php'>
			<table class='inside'>
				<tr>
					<td class="label"><?php echo $lang['etu_page_forname']; ?></td>
					<td class='input'><?php 
					if(!empty($_POST["modforname"])) {
					if(array_key_exists('modforname',$_POST)){
					   echo"	<input type=\"text\" name=\"forname\" id=\"forname\"/ size=\"38\">";
					}}else{		
						echo '<p>'.str_replace('_', ' ', $_SESSION['forname']).'</p>'; 
						}?></td>
					<td class='change'><input type="submit" name="modforname" id="modforname" value="<?php echo $lang['etu_page_submit']; ?>" /></td>
				</tr>
			</table>
			</form>
			</td>
		</tr>
		<tr>
			<td>
			<form method="post" action='page_etu.php'>
				<table class='inside'>
					<tr>
						<td class="label"><?php echo $lang['etu_page_email']; ?></td>
						<td class='input'><?php 
						/*Si on a cliqué*/
						if(!empty($_POST["modmail"])) {
						   echo"	<input type=\"text\" name=\"mail\" id=\"mail\"/ size=\"38\">";
						}else{		
							echo '<p>'.$_SESSION['email'].'</p>'; 
							}?></td>
						<td class='change'><input type="submit" name="modmail" id="modmail" value="<?php echo $lang['etu_page_submit']; ?>" /></td>
					</tr>
				</table>
				</form>
			</td>
		</tr>
		<tr>
			<td>
			<form method="post" action='page_etu.php'>
			<table class='inside'>
				<tr>
					<td class="label"><?php echo $lang['etu_page_fil']; ?></td>
					<td class='input'><?php 
						/*Si on a cliqué*/
						if(!empty($_POST["modfil"])) {
							echo' <select name="fil" id="fil">
							<option disabled selected value> -- Filière -- </option>';
						
							$allfilieres = glob('../etudiants/donnees/*', GLOB_ONLYDIR);
							$nbFilieres = count($allfilieres);
 
							//On parcourt
							for($filiere=0; $filiere<$nbFilieres; $filiere++){
									$dir_name_cut = explode('/',$allfilieres[$filiere]);
									$filierefullname = end($dir_name_cut);
									$filierechoix = str_replace('_', ' ', $filierefullname);
									
									echo '<option value="'.$filierefullname.'">'.$filierechoix.'</option>';
							}
							
						   echo" </select>";
						}else{		
							echo '<p>'.str_replace('_', ' ', $_SESSION['filiere']).'</p>'; 
							}?></td>
					<td class='change'><input type="submit" name="modfil" id="modfil" value="<?php echo $lang['etu_page_submit']; ?>" /></td>
				</tr>
			</table>
			</form>
			</td>
		</tr>
		<?php
		$fil = str_replace(' ', '_', $_SESSION['filiere']);
		$alltds = glob('../etudiants/donnees/'.$fil. '/*' , GLOB_ONLYDIR);
		$nbtds=count($alltds);
		if($nbtds!==0){
		?>
		<tr>
			<td>
			<form method="post" action='page_etu.php'>
			<table class='inside'>
				<tr>
					<td class="label"><?php echo $lang['etu_page_td']; ?></td>
					<td class='input'><?php 
						if(($_SESSION['TD'] == '') && (empty($_POST['modTD']))){
							echo 'Choisir un groupe de TD';
						}
						/*Si on a cliqué*/
						if(!empty($_POST["modTD"])) {
							echo' <select name="td" id="td">
							<option disabled selected value> -- Groupe de TD -- </option>';
							$fil = str_replace(' ', '_', $_SESSION['filiere']);
						
							$alltds = glob('donnees/'.$fil. '/*' , GLOB_ONLYDIR);
							$nbtds = count($alltds);
 
							//On parcourt

							for($td=0; $td<$nbtds; $td++){
									$dir_name_cut = explode('/',$alltds[$td]);
									$tdchoix = end($dir_name_cut);
										
									echo '<option value="'.$tdchoix.'">'.$tdchoix.'</option>';
							}
							
						   echo" </select>";
						}else{		
							echo '<p>'.$_SESSION['TD'].'</p>'; 
							}?></td>
					<td class='change'><input type="submit" name="modTD" id="modTD" value="<?php echo $lang['etu_page_submit']; ?>" /></td>
				</tr>
			</table>
			</form>
			</td>
		</tr>
		<?php }?>
	</table>
	
	<?php 
		if(isset($_POST['change_mdp'])){
			if($_POST['change_mdp'] == 'Changer'){
	?>
	<form action='page_etu.php' method='post'>
		<table id="changepswd">
			<tr>
				<td>
					<input type="password" name="oldpass" id="oldpass" placeholder="<?php echo $lang['etu_page_mdp_old']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<input type="password" name="newpass" id="newpass" placeholder="<?php echo $lang['etu_page_mdp_new']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<input type="password" name="confnewpass" id="confnewpass" placeholder="<?php echo $lang['etu_page_mdp_conf']; ?>" />
				</td>
			</tr>
			<tr>
				<td>
					<input type="submit" name="changemdp" id="changemdp" value="<?php echo $lang['etu_page_mdp_submit']; ?>" />
				</td>
			</tr>
		</table>
	</form>
	<?php
			}
		}
		if(!empty($erreur_change_pass)) { ?>
					
					<div class="error"><p><?php echo $erreur_change_pass; ?></p></div>
	<?php } if(!empty($success)) { ?>
					
					<div class="success"><p><?php echo $success; ?></p></div>
	<?php }?>
	
	<footer>
			<p>
			<a href='?lang=fr'>fr</a>
			<a href='?lang=en'>en</a>
			</p>
		</footer>
</body>
</html>