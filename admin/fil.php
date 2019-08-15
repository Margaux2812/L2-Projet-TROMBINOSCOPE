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

/**********************************************************************************************Ajouter une filiere*/
if(!empty($_POST['add_fil'])){
	/*Regarder si les champs sont remplis*/
	foreach($_POST as $key=>$value) {
		if(empty($_POST[$key])) {
		$erreur = $lang['error_fields'];
		break;
		}
	}
	

	/*Regarder si l'effectif est >1 et < 45*/
	if(!isset($erreur)){
		if(((int)$_POST['eff_fil']<1) || !ctype_digit($_POST['eff_fil'])){
				$erreur=$lang['admin_fil_add_error_eff'];
		}
	}

	/*On ajoute la filiere*/

	if(!isset($erreur)){
		//On remplace les espaces par underscore et on met la filiere en majuscule
		$namefil = str_replace(' ', '_', $_POST['name_fil']);
		$dirpath = '../etudiants/donnees/'.htmlspecialchars(strtoupper($namefil));
		if (file_exists($dirpath)) {
			$erreur = $lang['admin_fil_add_error_exists'];
		}
		else{
			mkdir($dirpath);
			
			/*On renseigne l'effectif dans un fichier .txt*/
				
			$txtpath = $dirpath."/effectif.txt";
			$eff_fil = fopen($txtpath, "a+");
			fputs($eff_fil, $_POST['eff_fil']);
			fclose($eff_fil);
			
			$success_message=$lang['admin_fil_add_success'];
		}
	}
}

/**********************************************************************************************Supprimer une filiere*/
if(!empty($_POST['del_fil'])){
	/*Regarder si les champs sont remplis*/

	if(empty($_POST['name_fil_del'])) {
		$erreur_del = $lang['error_fields'];
		break;
	}
	
	/*On supprime la filiere*/

	if(!isset($erreur_del)){
		$namefil = str_replace(' ', '_', $_POST['name_fil_del']);
		$dirpath = '../etudiants/donnees/'.htmlspecialchars(strtoupper($namefil));
		if (file_exists($dirpath)) {
			
			/*On efface l'effectif dans un fichier .txt*/
				
			$txtpath = $dirpath."/effectif.txt";
			unlink($txtpath);
			
			$allDir = glob($dirpath.'/*', GLOB_ONLYDIR);
			
			for($i=0; $i<count($allDir); $i++){
				$txtpath = $allDir[$i]."/effectif.txt";
				unlink($txtpath);
				rmdir($allDir[$i]);
			}
			
			/*On efface le dossier*/
			
			rmdir($dirpath);
			
			$success_message_del=$lang['admin_fil_supp_success'];
		}
		else{
			$erreur_del = $lang['admin_fil_supp_error_noexists'];
		}
	}
}

/**********************************************************************************************Modifier une filiere*/

if(!empty($_POST['mod_fil'])){
	/*Regarder si les champs sont remplis*/

	if(empty($_POST['name_fil_mod'])) {
		$erreur_mod = $lang['error_fields'];
		break;
	}

	/*On modifie la filiere*/

	if(!isset($erreur_mod)){
		$namefil = str_replace(' ', '_', $_POST['name_fil_mod']);
		$dirpath = '../etudiants/donnees/'.htmlspecialchars(strtoupper($namefil));
		if (file_exists($dirpath)) {
			
			/*On efface l'effectif dans un fichier .txt*/
				
			$txtpath = $dirpath."/effectif.txt";
			unlink($txtpath);
			$allTD = scandir('../etudiants/donnees/'.str_replace(' ', '_', $_POST['name_fil_mod']));
			$nbTD = count($allTD);
			
			/*S'il n'y a que . et ..*/
			if($nbTD<3){
				
				/*On le remplace le fichier*/
				
				$eff_fil = fopen($txtpath, "a+");
				fputs($eff_fil, $_POST['eff_fil_mod']);
				fclose($eff_fil);
				
				$success_message_mod=$lang['admin_fil_mod_success'];
			}else{
			$effectif_td = 0;
				for($i=2; $i<$nbTD; $i++){
					$effectif_td += getEffectif(str_replace(' ', '_', $_POST['name_fil_mod']), $allTD[$i]);
				}
				if($effectif_td>(int)$_POST['eff_fil_mod']){
					$erreur_mod = $lang['admin_fil_mod_error_eff'];
				}else{
					$eff_fil = fopen($txtpath, "a+");
					fputs($eff_fil, $_POST['eff_fil_mod']);
					fclose($eff_fil);
					
					$success_message_mod=$lang['admin_fil_mod_success'];
				}
			}
		}
		else{
			$erreur_mod = $lang['admin_fil_mod_error_noexists'];
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
		<title><?php echo $lang['admin_fil_title']; ?></title>
		<link rel="stylesheet" type="text/css" href="styles/filiere.css"/>
		<link rel="icon" href="../images/iconadmin.png" sizes="16x16" type="image/png" />
		<link rel="icon" href="../images/iconadminbigger.png" sizes="32x32" type="image/png" />
</head>
<body>
	<header>
		<nav>
			<ul>
				<li><a href="admin.php"><strong><?php echo $lang['admin_menu_home']; ?></strong></a></li>
				<li id='en-cours'><a href="fil.php"><?php echo $lang['admin_menu_fil']; ?></a></li>
				<li><a href="td.php"><?php echo $lang['admin_menu_td']; ?></a></li>
				<li><a href="ens.php" class='two_lines'><?php echo $lang['admin_menu_ens']; ?></a></li>
				<li><?php include('deconnexion_form.php'); ?></li>
			</ul>
		</nav>
	</header>
	<div id='contenu'>
	<div id='sections'>
		<section id='add_fil'>
		<h2><?php echo $lang['admin_fil_add']; ?></h2>
			<form method="post" action='fil.php'>
					<table>
						<tr>
							<td> <label for="name_fil"><?php echo $lang['admin_fil_add_name']; ?></label> </td>
							<td> <label for="eff_fil"><?php echo $lang['admin_fil_add_eff']; ?></label> </td>
							<td></td>
						</tr>
						<tr>
							<td><input type="text" name="name_fil" id="name_fil" size="15"/></td>
							<td><input type="number" name="eff_fil" id="eff_fil" size="15"/></td>
							<td><input type="submit" name="add_fil" value="<?php echo $lang['admin_fil_add_submit']; ?>"/></td>
						</tr>
						<tr class="erreur">
							<td><?php if(!empty($erreur)) { echo $erreur; } if(!empty($success_message)) { echo $success_message; }?></td>
							<td></td>
							<td></td>
						</tr>
					</table>
			</form>
		</section>
		<section id='del_fil'>
		<h2><?php echo $lang['admin_fil_supp']; ?></h2>
			<form method="post" action='fil.php'>
					<table>
						<tr>
							<td> <label for="name_fil_del"><?php echo $lang['admin_fil_supp_name']; ?></label> </td>
							<td></td>
						</tr>
						<tr>
							<td><select name="name_fil_del" id="name_fil_del">
							<?php 
								$listFilieres = getFilieres();
								$nbFilieres = count($listFilieres);
								
								for($i=0; $i<$nbFilieres; $i++){
									echo' <option value="'.$listFilieres[$i].'">'.str_replace('_', ' ', $listFilieres[$i]).'</option>';
								}
								 ?>
								</select></td>
							<td><input type="submit" name="del_fil" value="<?php echo $lang['admin_fil_supp_submit']; ?>"/></td>
						</tr>
						<tr class="erreur">
							<td><?php if(!empty($erreur_del)) { echo $erreur_del; } if(!empty($success_message_del)) { echo $success_message_del; }?></td>
							<td></td>
							<td></td>
						</tr>
					</table>
			</form>
		</section>
		<section id='mod_fil'>
		<h2><?php echo $lang['admin_fil_mod']; ?></h2>
			<form method="post" action='fil.php'>
					<table>
						<tr>
							<td> <label for="name_fil_mod"><?php echo $lang['admin_fil_mod_name']; ?></label> </td>
							<td> <label for="eff_fil_mod"><?php echo $lang['admin_fil_mod_eff']; ?></label> </td>
							<td></td>
						</tr>
						<tr>
							<td><select name="name_fil_mod" id="name_fil_mod">
							<?php 
								$listFilieres = getFilieres();
								$nbFilieres = count($listFilieres);
								
								for($i=0; $i<$nbFilieres; $i++){
									echo' <option value="'.$listFilieres[$i].'">'.str_replace('_', ' ', $listFilieres[$i]).'</option>';
								}
								 ?>
								</select></td>
							<td><input type="number" name="eff_fil_mod" id="eff_fil_mod" size="15"/></td>
							<td><input type="submit" name="mod_fil" value="<?php echo $lang['admin_fil_mod_submit']; ?>"/></td>
						</tr>
						<tr class="erreur">
							<td><?php if(!empty($erreur_mod)) { echo $erreur_mod; } if(!empty($success_message_mod)) { echo $success_message_mod; }?></td>
							<td></td>
							<td></td>
						</tr>
					</table>
			</form>
		</section>
	</div>
	<div id='graphsTD'>
		<figure>
		<?php 
		$imgpath = '../enseignants/charts/barre_graph_fil_eleves.'.$langUsed.'.php';
		?>
			<img src='<?php echo $imgpath; ?>' alt="% d'élèves" >
		</figure>
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