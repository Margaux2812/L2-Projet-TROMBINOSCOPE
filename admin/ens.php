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
include('functions.inc.php');
include('deconnexion_admin.php');

if(!empty($_POST["add_ens"])) {
	
	/* Regarder s'il y a tous les champs */
		foreach($_POST as $key=>$value) {
			if(empty($_POST[$key])) {
			$erreur = $lang['error_fields'];
			break;
			}
		}
	
	/*On cherche notre enseignant dans le fichier csv*/
	
	if(!isset($error)){
		$login = htmlspecialchars(strtoupper($_POST['forname']))[0].htmlspecialchars(strtoupper($_POST['name']));
		if(already_exists($login) == 1){
			$erreur = $lang['admin_ens_add_exist'];
		}
		else{ /*L'enseignant est nouveau*/
		
			/*On crypte le mot de passe */
			$mdp_hash = password_hash(htmlspecialchars($_POST['password']), PASSWORD_DEFAULT);

			$donnees=array( array(
				$login, 
				htmlspecialchars(strtoupper($_POST['name'])),
				htmlspecialchars(strtoupper($_POST['forname'])),
				$mdp_hash,
				)
			);
			
			$fp = fopen('../enseignants/donnees/enseignants.csv', 'a+');
			foreach($donnees as $fields){
				fputcsv($fp, $fields);
			}
			
			fclose($fp);
		
			$error = "";
			$compte_validé = $lang['admin_ens_add_success'];	
			unset($_POST);
		}
	}
	
	
}

if(!empty($_POST["sup_ens"])) {
	/*On recrée le login (PNOM) et on le cherche, on crée un nouveau fichier qui sera le meme sans ce nom*/
	$ens_to_del = explode(' ', $_POST['name_ens_del']);
	$name = $ens_to_del[0];
	$forname = $ens_to_del[1];
	$login = strtoupper($forname[0].$name);
	
	try{
		$lire = fopen('../enseignants/donnees/enseignants.csv', 'a+');  //lire
		$ecrire = fopen('../enseignants/donnees/temporary.csv', 'w'); //ecrire

		while(( $data = fgetcsv($lire, 1000, ",")) !== FALSE ){ 
		   if ($data[0] !== $login) {
				fputcsv( $ecrire, $data);
		   }
		}

		//close both files
		fclose( $lire );
		fclose( $ecrire );

		//clean up
		unlink('../enseignants/donnees/enseignants.csv');// Delete obsolete BD
		rename('../enseignants/donnees/temporary.csv', '../enseignants/donnees/enseignants.csv'); //Rename temporary to new
		$compte_supprime = $lang['admin_ens_supp_success'];
	}
	catch(Exception $e){
		$erreur_supp = $lang['admin_ens_supp_error'];
	}	
}

if(!empty($_POST['ch_ens'])){
	/* Regarder s'il y a tous les champs */
	foreach($_POST as $key=>$value) {
		if(empty($_POST[$key])) {
		$erreur_ch = $lang['error_fields'];
		break;
		}
	}
	
	$ens_to_search = explode(' ', $_POST['name_ens_ch']);
	$name = $ens_to_search[0];
	$forname = $ens_to_search[1];
	$login = strtoupper($forname[0].$name);
	$newpass = password_hash($_POST['chgpass'], PASSWORD_DEFAULT);
	
	try{
		$lire = fopen('../enseignants/donnees/enseignants.csv', 'a+'); 
		$ecrire = fopen('../enseignants/donnees/temporary.csv', 'w'); 

		while(( $data = fgetcsv($lire, 1000, ",")) !== FALSE ){ 
			if ($data[0] == $login) {
				$data[3] = $newpass;
			}
			fputcsv( $ecrire, $data);
		}

		//close both files
		fclose( $lire );
		fclose( $ecrire );

		//clean up
		unlink('../enseignants/donnees/enseignants.csv');// Delete obsolete BD
		rename('../enseignants/donnees/temporary.csv', '../enseignants/donnees/enseignants.csv'); //Rename temporary to new
		$success_ch = $lang['admin_ens_mod_success'];
	}
	catch(Exception $e){
		$erreur_ch = $lang['admin_ens_mod_error'];
	}	
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
		<title><?php echo $lang['admin_ens_title']; ?></title>
		<link rel="stylesheet" type="text/css" href="styles/filiere.css"/>
		<link rel="icon" href="../images/iconadmin.png" sizes="16x16" type="image/png" />
		<link rel="icon" href="../images/iconadminbigger.png" sizes="32x32" type="image/png" />
</head>
<body>
	<header>
		<nav>
			<ul>
				<li><a href="admin.php"><strong><?php echo $lang['admin_menu_home']; ?></strong></a></li>
				<li><a href="fil.php"><?php echo $lang['admin_menu_fil']; ?></a></li>
				<li><a href="td.php"><?php echo $lang['admin_menu_td']; ?></a></li>
				<li id='en-cours'><a href="ens.php" class='two_lines'><?php echo $lang['admin_menu_ens']; ?></a></li>
				<li><?php include('deconnexion_form.php'); ?></li>
			</ul>
		</nav>
	</header>
	<div id='contenu'>
	<div id='sections'>
		<section id='ens'>
			<h2><?php echo $lang['admin_ens_add']; ?></h2>
			<form method="post" action='ens.php'>
					<table>
						<tr>
							<td><?php echo $lang['admin_ens_add_name']; ?></td>
							<td><?php echo $lang['admin_ens_add_forname']; ?></td>
							<td><?php echo $lang['admin_ens_add_mdp']; ?></td>
							<td></td>
						</tr>
						<tr>
							<td><input type="text" name="name" id="name" size="15"/></td>
							<td><input type="text" name="forname" id="forname" size="15"/></td>
							<td><input type="password" name="password" id="password"/></td>
							<td><input type="submit" name="add_ens" value="<?php echo $lang['admin_ens_add_submit']; ?>"/></td>
						</tr>
						<tr class="erreur">
							<td colspan='3'><?php if(!empty($erreur)) { echo $erreur; } if(!empty($compte_validé)) { echo $compte_validé; }?></td>
							<td></td>
						</tr>
					</table>
			</form>
		</section>
		
		<section>
			<h2><?php echo $lang['admin_ens_supp']; ?></h2>
			<form method="post" action='ens.php'>
					<table>
						<tr>
							<td><?php echo $lang['admin_ens_supp_name']; ?></td>
							<td>
							<select name="name_ens_del" id="name_ens_del">
							<?php
							$listEnseignants = getEnseignants();
							$nbEns = count($listEnseignants);
							for($i=0; $i<$nbEns; $i++){
								echo' <option value="'.$listEnseignants[$i].'">'.$listEnseignants[$i].'</option>';
							}
							?></select></td>
							<td><input type="submit" name="sup_ens" value="<?php echo $lang['admin_ens_supp_submit']; ?>"/></td>
						</tr>
						<tr class="erreur">
							<td colspan='3'><?php if(!empty($erreur_supp)) { echo $erreur_supp; } if(!empty($compte_supprime)) { echo $compte_supprime; }?></td>
						</tr>
					</table>
			</form>
		</section>
		<section>
			<h2><?php echo $lang['admin_ens_mod']; ?></h2>
			<form method="post" action='ens.php'>
					<table>
						<tr>
							<td><?php echo $lang['admin_ens_mod_name']; ?></td>
							<td><?php echo $lang['admin_ens_mod_mdp']; ?></td>
							<td></td>
						</tr>
						<tr>
							<td>
							<select name="name_ens_ch" id="name_ens_ch">
							<?php
							$listEnseignants = getEnseignants();
							$nbEns = count($listEnseignants);
							for($i=0; $i<$nbEns; $i++){
								echo' <option value="'.$listEnseignants[$i].'">'.$listEnseignants[$i].'</option>';
							}
							?></select></td>
							<td><input type='password' name='chgpass' />
							<td><input type="submit" name="ch_ens" value="<?php echo $lang['admin_ens_mod_submit']; ?>"/></td>
						</tr>
						<tr class="erreur">
							<td colspan='3'><?php if(!empty($erreur_ch)) { echo $erreur_ch; } if(!empty($success_ch)) { echo $success_ch; }?></td>
						</tr>
					</table>
			</form>
		</section>
	</div>
	<div id='ensTable'>
		<table>
			<tr>
				<th><?php echo $lang['admin_ens_list']; ?></th>
			</tr>
			<?php 
			$listEnseignants = getEnseignants();
			for($i=0; $i<count($listEnseignants); $i++){
				echo' <tr>
						<td>'.$listEnseignants[$i].'</td>
				</tr>';
			}
			?>
		</table>
	</div>
	</div>
	<footer>
		<p>
		<a href='?lang=fr'>fr</a>
		<a href='?lang=en'>en</a>
		</p>
	</footer>

</body>
</html>