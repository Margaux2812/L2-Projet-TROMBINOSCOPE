<?php
/*-------------LANGUES ALTERNATIVES-------------*/
	
	/*Création d'un array des langues disponibles*/
	$langues = array(
	'fr' => 'Français',
	'en' => 'English',
	);
	if(isset($_GET['lang']) && array_key_exists($_GET['lang'], $langues)){
		$langUsed = $_GET['lang'];
		setcookie('lang', $langUsed, time() + (3600*24*365)); 
	}
	elseif(isset($_COOKIE['lang'])){
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
	header('Location: index.php');
}

include('functions.inc.php');

/******************************************************************Déconnexion*/	

if(!empty($_POST["logout"])) {
	session_destroy();
	header('Location: ../index.php');
}

/******************************************************************Changement du mot de passe*/
if(!empty($_POST['changemdp'])){
	foreach($_POST as $key=>$value) {
		if(empty($_POST[$key])) {
			$erreur_change_pass = $lang['error_fields'];
		break;
		}
	}
	
	/*S'ils ne correspondent pas*/
	if(!isset($erreur_change_pass)){
		if($_POST['newpass'] != $_POST['confpass']){ 
			$erreur_change_pass = 'Vos mots de passes ne correspondent pas '; 
		}
	}
	
	/*Si l'ancien n'est pas bon*/
	
	if(!isset($erreur_change_pass)){
		
		$isCorrect = password_verify($_POST['oldpass'], $_SESSION['pass']);
		if(!$isCorrect){
			$erreur_change_pass = "Le mot de passe actuel est faux";
		}
	}
	
	/*Si le nouveau n'est pas aux normes*/
	if(!isset($erreur_change_pass)){
		if(strlen($_POST['newpass'])<5) {
			$erreur_change_pass = "Veuillez entrer un mot de passe ayant minimum 6 caractères";
		}
		elseif(!preg_match("#[A-Z]#", $_POST['newpass']) || !preg_match("#[0-9]#", $_POST['newpass'])){
			$erreur_change_pass = "Veuillez entrer un mot de passe contenant au moins un chiffre et une majuscule";
		}
	}
	
	if(!isset($erreur_change_pass)){
		
		$mdphash = password_hash($_POST['newpass'], PASSWORD_DEFAULT);
		
		try{
		
			$lire = fopen('donnees/enseignants.csv', 'r');  //lire
			$ecrire = fopen('donnees/temporary.csv', 'w'); //ecrire
			while( false !== ( $data = fgetcsv($lire) ) ){ 

			   //modify data here
			   if ($data[0] == $_SESSION['login']) {
				  //Remplacer la ligne
				  $data[3] = $mdphash;
			   }

			   //write modified data to new file
			   fputcsv( $ecrire, $data);
			}

			//close both files
			fclose( $lire );
			fclose( $ecrire );

			//clean up
			unlink('donnees/enseignants.csv');// Delete obsolete BD
			rename('donnees/temporary.csv', 'donnees/enseignants.csv'); //Rename temporary to new
			
			$_SESSION['pass'] = $mdphash;
			unset($_POST);
			
			$success = 'Votre mot de passe a été modifié avec succès';
		}
		catch(Exception $e){
			$erreur_change_pass = "Erreur lors de la sauvegarde du mot de passe";
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
		<title><?php echo $lang['accueil_title']; ?></title>
		<link rel="stylesheet" type="text/css" href="styles/style_accueil.css"/>
		<link rel="icon" href="../images/icon.png" sizes="16x16" type="image/png" />
		<link rel="icon" href="../images/iconbigger.png" sizes="32x32" type="image/png" />
</head>
<body>
<nav>
		<table>
			<tr class="en-cours">
				<td class='icons'><img src="images/accueil_logo_select.png" alt="accueil" /></td>
				<td class='label' colspan='2'><a href="accueil_ens.php"><?php echo $lang['ens_menu_accueil']; ?></a></td>
			</tr>
			<tr>
				<td class='icons'><img src="images/fav_logo.png" alt="favorites" /></td>
				<td class="label"><a href="trombinoscope.php?favorite=ON"><?php echo $lang['ens_menu_myfil']; ?></a></td>
				<td class="buttons"><form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"><input type="submit" value="+" name="favfil+"/></form></td>
				<?php
				if(!empty($_POST["favfil+"])) {
				?>
			</tr>
			<tr>
				<td class='icons'></td>
				<td class='list'><?php 
				$listFilieresFav = $_SESSION['favorite'];
				$list_lenght = count($listFilieresFav);
				
				echo'<ul>';
				for($fil=0; $fil<$list_lenght; $fil++){
					$filierename = str_replace('_', ' ',$listFilieresFav[$fil]); 
					echo '<li><a href="trombinoscope.php?filiere='.$listFilieresFav[$fil].'">'.$filierename.'</a></li>';
				}
				echo'</ul>';
				?></td>
					<?php
				}
					?>
				
			</tr>
			<tr>
				<td class='icons'><img src="images/filiere_logo.png" alt="filieres" /></td>
				<td class="label"><a href="trombinoscope.php"><?php echo $lang['ens_menu_fil']; ?></a></td>
				<td class="buttons"><form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"><input type="submit" value="+" name="fil+"/></form></td>
				<?php
				if(!empty($_POST["fil+"])) {
				?>
			</tr>
			<tr>
				<td class='icons'></td>
				<td class='list'><?php 
				$listFilieres = getFilieres();
				$list_lenght = count($listFilieres);
				
				echo'<ul>';
				for($fil=0; $fil<$list_lenght; $fil++){
					$filierename = str_replace(' ', '_',$listFilieres[$fil]); 
					echo '<li><a href="trombinoscope.php?filiere='.$filierename.'">'.$listFilieres[$fil].'</a></li>';
				}
				echo'</ul>';
				?></td>
					<?php
				}
					?>
			</tr>
			<tr>
				<td class='icons'><img src="images/td_logo.png" alt="td" /></td>
				<td class="label"><?php echo $lang['ens_menu_td']; ?></td>
				<td class="buttons"><form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"><input type="submit" value="+" name="td+"/></form></td>
			<?php
				if(!empty($_POST["td+"])) {
				?>
			</tr>
			<tr>
				<td class='icons'></td>
				<td class='list'><?php 
				$listTD = getTD();
				$list_lenght = count($listTD);
				
				echo'<ul>';
				for($td=0; $td<$list_lenght; $td++){
					echo '<li><a href="trombinoscope.php?td='.$listTD[$td].'">'.$listTD[$td].'</a></li>';
				}
				echo'</ul>';
				?></td>
					<?php
				}
					?>
			</tr>
		</table>
		
		<form method="post" action='<?php echo $_SERVER['REQUEST_URI']; ?>' id="logout">
			<input type="submit" name="logout" value="<?php echo $lang['ens_menu_deco']; ?>" />
		</form>
</nav>



	<div id='contenu'>
	<section id='charts'>
	<?php
	$imgpath = 'charts/global_fil_chart.'.$langUsed.'.php';
	?>
		<img src='<?php echo $imgpath; ?>' alt='graph' />
	</section>
	<section id='changepass'>
		<h1><?php echo $lang['ens_accueil']; ?></h1>
		
		<form method='post' action='<?php echo $_SERVER['REQUEST_URI'];?>'>
		<table>
			<tr>
				<td><?php echo $lang['ens_accueil_old']; ?></td>
				<td><input type="password" name="oldpass"></td>
			</tr>
			<tr>
				<td><?php echo $lang['ens_accueil_new']; ?></td>
				<td><input type="password" name="newpass"></td>
			</tr>
			<tr>
				<td><?php echo $lang['ens_accueil_confnew']; ?></td>
				<td><input type="password" name="confpass"></td>
			</tr>
			<tr>
				<td colspan='2'><input type="submit" name='changemdp' value="<?php echo $lang['ens_accueil_submit']; ?>"></td>
			</tr>
		</table>
		</form>
		<?php
		if(!empty($erreur_change_pass)) { ?>
					
					<div class="error"><p><?php echo $erreur_change_pass; ?></p></div>
	<?php } if(!empty($success)) { ?>
					
					<div class="success"><p><?php echo $success; ?></p></div>
	<?php }?>
	</section>
	<?php include('footer.php'); ?>
	</div>
</body>
</html>