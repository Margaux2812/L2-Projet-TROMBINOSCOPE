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

/*************************************************************************Ajouter un td*/
if(!empty($_POST['add_td'])){
	/*Regarder si les champs sont remplis*/
	foreach($_POST as $key=>$value) {
		if(empty($_POST[$key])) {
		$erreur = $lang['error_fields'];
		break;
		}
	}
	if(!isset($erreur)){
		if(!isset($_POST["fil_td"])) {
			$erreur = $lang['error_fields'];
		}
	}	

	/*Regarder si l'effectif est >1 et < 45*/
	if(!isset($erreur)){
		if(isset($_POST['eff_td'])){
			if(((int)$_POST['eff_td'] > 45) || ((int)$_POST['eff_td']<1) || !ctype_digit($_POST['eff_td'])){
					$erreur=$lang['admin_td_add_error_eff'];
			}
		}
	}

	/*On ajoute le td*/

	if(!isset($erreur)){
		if(isset($_POST['name_td']) && isset($_POST['eff_td'])){
			$nametd = $_POST['name_td'];
			$dirpath = '../etudiants/donnees/'.$_POST['fil_td'].'/'.htmlspecialchars(strtoupper($nametd));
			if (file_exists($dirpath)) {
				$erreur = $lang['admin_td_add_error_exists'];
			}
			else{
				mkdir($dirpath);
				
				/*On renseigne l'effectif dans un fichier .txt*/
				
				$txtpath = $dirpath."/effectif.txt";
				$eff_td = fopen($txtpath, "a+");
				fputs($eff_td, $_POST['eff_td']);
				fclose($eff_td);
				
				$success_message=$lang['admin_td_add_success'];
			}
		}
	}
}

/*************************************************************************Supprimer un td*/
if(!empty($_POST['del_td'])){
	/*Regarder si les champs sont remplis*/

	if(empty($_POST['del_td'])) {
		$erreur_del = $lang['error_fields'];
		break;
	}

	if(!isset($erreur_del)){
		if(!isset($_POST["fil_td_del"])) {
			$erreur_del = $lang['error_fields'];
		}
	}

	/*On supprime le td*/

	if(!isset($erreur_del)){
		if(isset($_POST['name_td_del'])){
			$nametd = $_POST['name_td_del'];
			$temp = explode('-',$nametd);
		
			if(count($temp)>1){//On a un groupe de TD
				$filiere= substr($temp[0], 0, -1);
				$td=substr( $temp[1], 1);
				$dirpath = '../etudiants/donnees/'.$filiere.'/'.$td;
			}else{
				$filiere= $temp[0];
				$dirpath = '../etudiants/donnees/'.$filiere;
			}	
			if (file_exists($dirpath)) {
				
				/*On supprime le fichier .txt*/
				
				$txtpath = $dirpath."/effectif.txt";
				unlink($txtpath);
				
				/*On efface le dossier*/
			
				rmdir($dirpath);
				
				$success_message_del=$lang['admin_td_supp_success'];
			}
			else{
				
				$erreur_del = $lang['admin_td_supp_error_exists'];
			}
		}
	}
}

/**********************************************************************************************Modifier une filiere*/

if(!empty($_POST['mod_td'])){
	/*Regarder si les champs sont remplis*/

	if(empty($_POST['name_td_mod'])) {
		$erreur_mod = $lang['error_fields'];
		break;
	}
	

	/*On modifie la filiere*/

	if(!isset($erreur_mod)){
		$nametd = str_replace(' ', '_', $_POST['name_td_mod']);
		$temp = explode('-',$nametd);
		
		if(count($temp)>1){//On a un groupe de TD
			$filiere= substr($temp[0], 0, -1);
			$td=substr( $temp[1], 1);
			$dirpath = '../etudiants/donnees/'.$filiere.'/'.$td;
		}else{
			$filiere= $temp[0];
			$dirpath = '../etudiants/donnees/'.$filiere;
		}
		$txtpath = $dirpath."/effectif.txt";
		if (file_exists($txtpath)) {
				
			if(count($temp)>1){
				$TD_de_filiere = getTD($filiere);
				
				/*On calcule l'effectif maximal que peut avoir le groupe de TD*/
				$effectifMax = getEffectif($filiere);
				for($i=0; $i<count($TD_de_filiere); $i++){
					$effectifMax -= getEffectif($filiere, $TD_de_filiere[$i]);
				}
				
				/*On remplace le fichier et on ajoute (ou supprime) la différence à l'effectif
				de la filiere*/
					unlink($txtpath);
					$eff_td = fopen($txtpath, "a+");
					fputs($eff_td, $_POST['eff_td_mod']);
					fclose($eff_td);
					
					$new_eff = getEffectif($filiere) + ($_POST['eff_td_mod'] - $effectifMax);
					$eff_fil = fopen('../etudiants/donnees/'.$filiere.'/effectif.txt', "a+");
					fputs($eff_fil, $new_eff);
					fclose($eff_fil);				
			}else{
				unlink($txtpath);
				$eff_td = fopen($txtpath, "a+");
				fputs($eff_td, $_POST['eff_td_mod']);
				fclose($eff_td);
			}
			$success_message_mod=$lang['admin_td_mod_success'];
		}
		else{
			$erreur_mod = $lang['admin_td_mod_error_exists'];
		}
	}
}

/********************************Reinitialiser les groupes de TD****/
if(!empty($_POST['reset_td'])){
	$are_you_sure = $lang['admin_td_vide_sure'];
}
if(!empty($_POST['reset_td_yes'])){
	/*On parcourt toutes les filières*/
	$allfil = getFilieres();
	$nbFil = count($allfil);
	for($i=0; $i<$nbFil; $i++){
		$alltds = glob('../etudiants/donnees/'.$allfil[$i].'/*' , GLOB_ONLYDIR);
		$nbtds = count($alltds);
		if($nbtds>0){
			for($j=0; $j<$nbtds; $j++){
				$files = glob($alltds[$j].'/*');
				foreach($files as $file){ 
				  if(!is_file($file))
					unlink($file); 
				}
			}
		}else{/*La filiere n'a pas de groupe de TD*/
			$files = glob('../etudiants/donnees/'.$allfil[$i].'/*');
			foreach($files as $file){ 
			/*On regarde l'extension du fichier*/
				$arraytmp = explode('.', $file);
				$last_char = end($arraytmp);
				$img_extensions = array('jpg', 'jpeg', 'gif', 'png');
				if(in_array($last_char, $img_extensions)){
					unlink($file); 
				}
			}
		}
	}
	unlink('../etudiants/donnees/etudiants.csv');
	
	$success_message_reset = $lang['admin_td_vide_success'];
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
		<title><?php echo $lang['admin_td_title']; ?></title>
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
				<li id='en-cours'><a href="td.php"><?php echo $lang['admin_menu_td']; ?></a></li>
				<li><a href="ens.php" class='two_lines'><?php echo $lang['admin_menu_ens']; ?></a></li>
				<li><?php include('deconnexion_form.php'); ?></li>
			</ul>
		</nav>
	</header>
	<div id='contenu'>
	<div id='sections'>
		<section id='ajouter_td'>
			<h2><?php echo $lang['admin_td_add']; ?></h2>
			<form method="post" action='td.php'>
					<table>
						<tr>
							<td> <label for="name_td"><?php echo $lang['admin_td_add_name']; ?></label> </td>
							<td> <label for="eff_td"><?php echo $lang['admin_td_add_eff']; ?></label> </td>
							<td><label for="fil_td"><?php echo $lang['admin_td_add_fil']; ?></label></td>
							<td></td>
						</tr>
						<tr>
							<td><input type="text" name="name_td" id="name_td" size="15"/></td>
							<td><input type="number" name="eff_td" id="eff_td" size="15"/></td>
							<td><select name="fil_td" id="fil_td">
							<?php
								$allfilieres = glob('../etudiants/donnees/*' , GLOB_ONLYDIR);
								$nbDossiers = count($allfilieres);
 
								//On parcourt
								for($filiere=0; $filiere<$nbDossiers; $filiere++){
									$dir_name_cut = explode('/',$allfilieres[$filiere]);
									$filierefullname = end($dir_name_cut);
									$filierechoix = str_replace('_', ' ', $filierefullname);
									
									echo '<option value="'.$filierefullname.'">'.$filierechoix.'</option>';
								}
								
							?>
							   </select></td>
							<td><input type="submit" name="add_td" value="<?php echo $lang['admin_td_add_submit']; ?>"/></td>
						</tr>
						<tr class="erreur">
							<td colspan='3'><?php if(!empty($erreur)) { echo $erreur; } if(!empty($success_message)) { echo $success_message; }?></td>
							<td></td>
						</tr>
					</table>
			</form>
		</section>
		
		<section id='supp_td'>
			<h2><?php echo $lang['admin_td_supp']; ?></h2>
			<form method="post" action='td.php'>
					<table>
						<tr>
							<td> <label for="name_td_del"><?php echo $lang['admin_td_supp_name']; ?></label> </td>
							<td></td>
						</tr>
						<tr>
							<td><select name="name_td_del" id="name_td_del">
							<?php
								$alltds = getTD();
 
								//On parcourt
								for($td=0; $td<count($alltds); $td++){
									$tdchoix = str_replace('_', ' ', $alltds[$td]);
									echo '<option value="'.$alltds[$td].'">'.$tdchoix.'</option>';
								}
								
							?>
							   </select></td>
							<td><input type="submit" name="del_td" value="<?php echo $lang['admin_td_supp_submit']; ?>"/></td>
						</tr>
						<tr class="erreur">
							<td colspan='3'><?php if(!empty($erreur_del)) { echo $erreur_del; } if(!empty($success_message_del)) { echo $success_message_del; }?></td>
						</tr>
					</table>
			</form>
		</section>
		<section id='mod_td'>
			<h2><?php echo $lang['admin_td_mod'];?></h2>
			<form method="post" action='td.php'>
					<table>
						<tr>
							<td> <label for="name_td_mod"><?php echo $lang['admin_td_mod_name'];?></label> </td>
							<td> <label for="eff_td_mod"><?php echo $lang['admin_td_mod_eff'];?></label> </td>
							<td></td>
						</tr>
						<tr>
							<td><select name="name_td_mod" id="name_td_mod">
							<?php 
								$listTDs = getTD();
								$nbTDs = count($listTDs);
								
								for($i=0; $i<$nbTDs; $i++){
									$tdchoix = str_replace('_', ' ', $alltds[$i]);
									echo '<option value="'.$tdchoix.'">'.$tdchoix.'</option>';
								}
								 ?>
								</select></td>
							<td><input type="number" name="eff_td_mod" id="eff_td_mod" size="15"/></td>
							<td><input type="submit" name="mod_td" value="<?php echo $lang['admin_td_mod_submit'];?>"/></td>
						</tr>
						<tr class="erreur">
							<td><?php if(!empty($erreur_mod)) { echo $erreur_mod; } if(!empty($success_message_mod)) { echo $success_message_mod; }?></td>
							<td></td>
							<td></td>
						</tr>
					</table>
			</form>
		</section>
		<section id='reset'>
		<h2><?php echo $lang['admin_td_vide']; ?></h2>
		<form method="post" action='td.php'><input type="submit" name="reset_td" value="<?php echo $lang['admin_td_vide_submit']; ?>"/></form>
		<?php if(!empty($success_message_reset)) {
			echo "<p class='advert'>".$success_message_reset."</p>"; 
		}
		if(!empty($are_you_sure)) {
			echo "<p class='advert'>".$are_you_sure."</p>"; ?>
			<form method="post" action='<?php echo $_SERVER['PHP_SELF']?>' id='verif'><input type="submit" name="reset_td_yes" value="<?php echo $lang['admin_td_vide_oui']; ?>"/><input type="submit" name="reset_td_no" value="<?php echo $lang['admin_td_vide_non']; ?>"/></form>
			
			<?php }?>
		</section>
	</div>
	<div id='graphsTD'>
	<?php 
		$imgpath = '../enseignants/charts/barre_graph_td_eleves.'.$langUsed.'.php'
		?>
		<figure>
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