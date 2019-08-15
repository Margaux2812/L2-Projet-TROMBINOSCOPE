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
	header('Location: index.php');
}
include('functions.inc.php');

/******************************************************************Déconnexion*/	

if(!empty($_POST["logout"])) {
	session_destroy();
	header('Location: ../index.php');
}

if(isset($_GET['filiere'])){
	$filiere = $_GET['filiere'];
	$td='';
}elseif(isset($_GET['td'])){
	$tdwithfil = $_GET['td'];
	/*On sépare ce qu'on a reçu par le - */
	$temp = explode('-', $tdwithfil);
	$nbParameters = count($temp);
	/*S'il y avait un -, alors on avait une filiere et un td, sinon, cétait une filiere aec un seul groupe de td*/
	if($nbParameters>1){
		$filiere = substr($temp[0], 0, -1);
		$td = substr($temp[1], 1);
	}else{
		$filiere = $temp[0];
		$td='';
	}
}else{
	$td='';
	$filiere='';
}

/******************************************************************PDF*/

if(!empty($_POST['telecharger'])){
	if(!empty($_POST['filiere'])){
		$listFilieresCochees = $_POST['filiere'];
		printToPdf($listFilieresCochees);
	}
}
if(!empty($_POST['telechargerFav'])){
	printToPdf($_SESSION['favorite']);
}
if(!empty($_POST['telechargerfil'])){
	printToPdf(str_replace($_GET['filiere']));
}
if(!empty($_POST['telechargertd'])){
	printToPdf($_GET['td']);
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<!-- On changera le titre de la page selon la filiere et/ou le TD -->
		<title><?php echo $lang['trombi'];
		if(isset($_GET['filiere'])){
			echo' - '.str_replace('_', ' ', $_GET['filiere']);
		}elseif(isset($_GET['td'])){
			echo' - '.str_replace('_', ' ', $_GET['td']);
		}elseif(isset($_GET['favorite'])){
			echo $lang['accueil_title_fav'];
		}
		?></title>
		<link rel="stylesheet" type="text/css" href="styles/style_allfil.css"/>
		<link rel="icon" href="../images/icon.png" sizes="16x16" type="image/png" />
		<link rel="icon" href="../images/iconbigger.png" sizes="32x32" type="image/png" />
</head>
<body>
	<nav>
		<table>
		<!-- Retour à la page d'accueil -->
			<tr>
				<td class='icons'><img src="images/accueil_logo.png" alt="accueil" /></td>
				<td class='label' colspan='2'><a href="accueil_ens.php"><?php echo $lang['ens_menu_accueil']; ?></a></td>
			</tr>
		<!-- Voir les favoris 
		Si $_GET['favorite'] est en mode ON, alors, on est sur cette page, donc on change l'image-->
			<tr <?php if(isset($_GET['favorite'])){echo 'class="en-cours"';}?>>
				<td class='icons'><img src="images/fav_logo<?php 
				if(isset($_GET['favorite'])){
					 echo '_select';
					 }?>.png" alt="favorites" /></td>
				<td class="label"><a href="trombinoscope.php?favorite=ON"><?php echo $lang['ens_menu_myfil']; ?></a></td>
				<td class="buttons"><form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"><input type="submit" value="+" name="favfil+"/></form></td>
				<?php
				
		/*Si on a appuyé sur le '+', on veut voir nos filieres favorites*/
				if(!empty($_POST["favfil+"])) {
				?>
			</tr>
			<tr>
				<td class='icons'></td>
				<td class='list'>
				<?php
				$listFavoris = $_SESSION['favorite'];
				$nbFav = count($listFavoris);
				
				echo '<ul>';
				
				for($fav=0; $fav<$nbFav; $fav++){
						$filierename = str_replace('_', ' ',$listFavoris[$fav]); 
						/*C'est un lien qui nous redirigera vers la page avec le paramètre de la filière*/
						
						echo '<li><a href="trombinoscope.php?filiere='.$listFavoris[$fav].'">'.$filierename.'</a></li>';
					}
					
				echo'</ul>';
				?></td>
					<?php
				}
					?>
				
			</tr>
		<!-- Voir les filieres 
			Si aucun TD n'est séléctionné mais qu'on regarde le trombinoscope,
			alors soit une filiere est selectionnée, soit on les regarde toutes, donc
			on est sur l'onglet 'Toutes les filieres'-->
			<tr <?php if(!isset($_GET['td']) && !isset($_GET['favorite'])){ ?> class="en-cours"<?php }?>>
				<td class='icons'><img src="images/filiere_logo<?php if(isset($_GET['td']) || isset($_GET['favorite'])){ 
						/*On affiche l'image normale*/
						echo '.png ';
					}else{
						/*On affiche l'image en blanc*/
						echo'_select.png';
					}?>" alt="filieres" /></td>
				
				<td class="label"><a href="trombinoscope.php"><?php echo $lang['ens_menu_fil']; ?></a></td>
				<td class="buttons"><form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"><input type="submit" value="+" name="fil+"/></form></td>
				<?php
			/*Si on a appuyé sur le '+', on veut voir les filieres*/
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
						/*C'est un lien qui nous redirigera vers la page avec le paramètre de la filière*/
						
						echo '<li><a href="trombinoscope.php?filiere='.$filierename.'">'.$listFilieres[$fil].'</a></li>';
					}
					echo'</ul>';
				?></td>
					<?php
				}
					?>
			</tr>
			<tr <?php if(isset($_GET['td'])){ ?> class="en-cours" <?php }?>>
			<!--L'image change si on est sur la page des td -->
			
				<td class='icons'><img src="images/td_logo<?php 
					if(isset($_GET['td'])){ 
						/*On affiche l'image en blanc*/
						echo '_select.png"';
					}else{
						echo'.png"';
					}?> alt="td" /></td>
				
				
				<td class="label"><?php echo $lang['ens_menu_td']; ?></td>
				<td class="buttons"><form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>"><input type="submit" value="+" name="td+"/></form></td>
			<?php
		/*Si on a appuyé sur le '+', on veut voir les différents groupes de tds*/
				if(!empty($_POST["td+"])) {
				?>
			</tr>
			<tr>
				<td class='icons'></td>
				<td class='list'><?php 
				$listTD = getTD();
				$list_lenght = count($listTD);
				
				echo'<ul>';
				for($tds=0; $tds<$list_lenght; $tds++){
					$tdfullname=str_replace(' ', '_', $listTD[$tds]);
					
					echo '<li><a href="trombinoscope.php?td='.$tdfullname.'">'.$listTD[$tds].'</a></li>';
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
	
		<header>
			<div id='formats'>
			<?php if(!isset($_POST['rechercher'])) {?>
				<form method='post' action='<?php echo $_SERVER['REQUEST_URI']; ?>'>
					<input type="submit" name="format" value="4by4" id="four" title="<?php echo $lang['ens_fil_format1']; ?>"/>
					<input type="submit" name="format" value="5by5" id="five" title="<?php echo $lang['ens_fil_format2']; ?>"/>
					<input type="submit" name="format" value="6by6" id="six" title="<?php echo $lang['ens_fil_format3']; ?>"/>
				</form>
			<?php } ?>
			</div>
			<?php if(!isset($_GET['favorite']) && !isset($_GET['filiere']) && !isset($_GET['td'])){?>
			<div id='pdfAll'>
				<div id='center'>
					<a href='#printScreen' id='printPDF'><?php echo $lang['ens_fils_download']; ?></a>
					<a href="#printScreen" class="button"><img src='images/pdfprint.png' alt='PDF Logo'/></a>
				</div>
				<div id="printScreen">
					<a href="#" class="cancel">&times;</a>
					<h2><?php echo $lang['ens_fils_download_form']; ?></h2>
					<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="formprint">
					<table>
					<?php
						$listFilieres = getFilieres();
						
						for($i=0; $i<count($listFilieres); $i++){
							$filierefullname=str_replace(' ', '_', $listFilieres[$i]);
							if($i%2==0){
								echo '<tr><td><label for="'.$filierefullname.'" class="container" ><input type="checkbox" name="filiere[]" value="'.$filierefullname.'" id="'.$filierefullname.'" />'.$listFilieres[$i].'<span class="checkmark"></span></label></td>';
							}else{
								echo '<td><label for="'.$filierefullname.'" class="container"><input type="checkbox" name="filiere[]" value="'.$filierefullname.'" id="'.$filierefullname.'"/>'.$listFilieres[$i].'<span class="checkmark"></span></label></td></tr>';
							}
						}
					?>
					</table>
					<input type='submit' value='<?php echo $lang['ens_fils_download_form_submit']; ?>' name='telecharger' />
					</form>
				</div>
				<div id="cover" >
				</div>
			</div>
			<?php }elseif(isset($_GET['favorite'])){ ?>
			<div id='pdf'>
			<form method='post' action='<?php echo $_SERVER['REQUEST_URI'];?>'>
				<p><?php echo $lang['ens_myfil_download']; ?><br>
				<input type="submit" name="telechargerFav" value="telecharger" id="telechargerFav"/>
				</p>
			</form>
			</div>
			<?php }elseif(isset($_GET['filiere'])){ ?>
			<div id='pdf' >
			<form method='post' action='<?php echo $_SERVER['REQUEST_URI'];?>'>
				<p><?php echo $lang['ens_fil_download']; ?><br>
				<input type="submit" name="telechargerfil" value="telecharger" id="telechargerfil"/>
				</p>
			</form>
			</div>
			<?php }elseif(isset($_GET['td'])){ ?>
			<div id='pdf' >
			<form method='post' action='<?php echo $_SERVER['REQUEST_URI'];?>'>
				<p><?php echo $lang['ens_td_download']; ?><br>
				<input type="submit" name="telechargertd" value="telecharger" id="telechargertd"/>
				</p>
			</form>
			</div> 
			<?php } ?>
			<div id='search'>
				<form method='post' action='<?php echo $_SERVER['REQUEST_URI']; ?>'>
				<p>
					<input type="search" name="search" placeholder="<?php echo $lang['ens_fil_search']; ?>" title="Rechercher (ALT + SHIFT + s)" accesskey="s" id="searchInput" autocomplete='off' size='30'/>
					<input type="submit" name="rechercher" value="rechercher" id="searchButton"/>
				</p>
				</form>
			</div>
		</header>
		<div id='trombinoscope_section'>
		
		<?php
		/*Si on a choisi un format spécifique*/
				if(isset($_POST['format']) && !isset($_GET['favorite'])){
					if($_POST['format'] == '4by4'){
						echo getTrombi($filiere, $td);
					}elseif($_POST['format'] == '5by5'){
						echo getTrombi($filiere, $td, 5);
					}elseif($_POST['format'] == '6by6'){
						echo getTrombi($filiere, $td, 6);
					}	
		/*Si on a recherche qq chose*/
				}elseif(isset($_POST['rechercher'])){
					$list_eleves_recherches = resultat_recherche(htmlspecialchars($_POST['search']));
					$nbEleves = count($list_eleves_recherches);
					$eleve=0;
					
					echo "<table class=\"trombi\">
									<tr>
										<th colspan = \"4\" style=\"font-size: 30px;\">Résultats de votre recherche</th>
									</tr>";
									
					/*Tant qu'on ne dépasse pas le nombre d'élèves*/
					while($eleve < $nbEleves){
						echo "<tr>";
						/*On écrit sur la ligne*/
						for($colonne=1; $colonne <= 4; $colonne++){
							/*On affiche le tableau de l'élève et on finit avec des cases vides*/
							if($eleve<$nbEleves){
								echo  "<td style=\" width: 25%;\">".getEleveTable($list_eleves_recherches[$eleve])."</td>";
								$eleve++;
							}
							else{
								echo "<td></td>";
							}
						}	
						echo "</tr>";
					}
					
					echo "</table>";
				}
		/*Si on veut voir nos favoris*/
				elseif(isset($_GET['favorite'])){?>
					<div id='tdfavorisdisplay'>
						<?php
					/*On affiche les td favoris
					dans un premier lieu on crée deux arrays vides*/
					
					$filierefav = array();
					$tdfav = array();
					$nbTDFav = count($_SESSION['favorite']);
					
					/*On récupere les noms de la filiere et du td, comme ils seront au meme
					index, alors on pourra afficher leur trombinoscope*/
					for($i=0; $i<$nbTDFav; $i++){
						$filettd = explode('-', $_SESSION['favorite'][$i]);
						/*Si la filiere a un groupe de TD, on enleve l'espace a la fin du nom de la filiere et l'espace au début
						du nom du TD, sinon c'est juste $_SESSION['favorite']
						On les ajoute aux array*/
						if(count($filettd) > 1){
							$tdfavname = substr($filettd[1], 1);
							$filierefavname = substr($filettd[0], 0, -1);
						}else{
							$tdfavname = '';
							$filierefavname = $filettd[0];
						}
						array_push($filierefav, $filierefavname);
						array_push($tdfav, $tdfavname);
					}
					
					/*On parcourt les array et on affiche leur trombinoscope*/
					for($filtdfav=0; $filtdfav<$nbTDFav; $filtdfav++){
						if(isset($_POST['format'])){
							if($_POST['format'] == '4by4'){
								echo getTrombi($filierefav[$filtdfav], $tdfav[$filtdfav]);
							}elseif($_POST['format'] == '5by5'){
								echo getTrombi($filierefav[$filtdfav], $tdfav[$filtdfav], 5);
							}elseif($_POST['format'] == '6by6'){
								echo getTrombi($filierefav[$filtdfav], $tdfav[$filtdfav], 6);
							}	
				
						}else{
							echo getTrombi($filierefav[$filtdfav], $tdfav[$filtdfav]);
						}
					}
						?>
					</div>
					
					<div id='conteneur_fav'>
						<div id='add_favorite'>				
							<form method="post" action='<?php echo $_SERVER['REQUEST_URI']; ?>'>
								<table>
								<tr><th><?php echo $lang['ens_myfil_add']; ?></th></tr>
							<?php
							$listTD = getTD();
							$list_lenght = count($listTD);
							/*On affiche la liste des filieres existantes*/
								for($td=0; $td<$list_lenght; $td++){
									$tdname = str_replace(' ', '_',$listTD[$td]); 
									
									/*On ne peux pas ajouter les TD déjà dans nos favoris*/
									if(!in_array($tdname, $_SESSION['favorite'])){	
										echo ' <tr><td class=\'normal\'><input type="checkbox" name="td_favoris[]" value="'.$tdname.'" id="'.$tdname.'"/> <label for="'.$tdname.'">'.$listTD[$td].'</label></td></tr>';
									}	
								}?>
						
								<tr><td class='button'><input type="submit" name="td_choisis" value="Ajouter" id="td_choisis" /></td></tr>
								</table>   
							</form>

							<?php
								$favoris = $_SESSION['favorite'];
										
								/*On regarde quelles cases ont été cochées*/
		
								if(!empty($_POST['td_favoris'])){
									$tdcoches=$_POST['td_favoris'];
									$nbTD = count($_POST['td_favoris']);
									for($i=0; $i<$nbTD; $i++){
										if(!in_array($tdcoches[$i], $favoris)){	
											array_push($favoris, $tdcoches[$i]);
										}
									}
									$_SESSION['favorite'] = $favoris;
									setFavorites($_SESSION['login'], $favoris);
								}						
							?>
						</div>
						<div id='remove_favorite'>
							<form method="post" action='<?php echo $_SERVER['REQUEST_URI']; ?>'>
								<p id='supp_fav_txt'><?php echo $lang['ens_myfil_supp']; ?></p>
								<select name="td_sel_del">
							<?php
							$listTDFav = $_SESSION['favorite'];
							$list_lenght = count($listTDFav);
							/*On affiche la liste des filieres existantes*/
								for($td=0; $td<$list_lenght; $td++){
									$tdname = str_replace('_', ' ',$listTDFav[$td]); 
									
									echo '<option value="'.$listTDFav[$td].'">'.$tdname.'</option>';
								}?>
								</select>
								<input type="submit" name="td_to_del" value="Supprimer" id="td_to_del" />
								   
							</form>
							
							<?php
								if(isset($_POST['td_to_del'])){
									supprimer_fav($_SESSION['login'], $_POST['td_sel_del']);
									$_SESSION['favorite'] = getFavorites($_SESSION['login']);
								}
							?>
						</div>
					</div>
		<?php   }
				else{
					echo getTrombi($filiere, $td);
				}?>
		</div>
	
		<?php
			create_charts();
			create_charts_tds();
		if(!isset($_GET['favorite']) && !isset($_GET['filiere']) && !isset($_GET['td'])){
		?>
		<div id='charts'>
			<figure>
			<?php
			$listFilieres = getFilieres();
				
			for($i=0; $i<count($listFilieres); $i++){
			$filierefullname = str_replace(' ', '_', $listFilieres[$i]);
				echo '<img src=\'charts/percentage_'.$filierefullname.'.php\' alt="'.$listFilieres[$i].'">';
			}
			?>
			</figure>
		</div>
		<?php
		}elseif(isset($_GET['filiere'])){
		?>
		<div id='charts'>
			<figure>
			<?php
			$filierefullname = str_replace(' ', '_', $_GET['filiere']);
			echo '<img src=\'charts/percentage_'.$filierefullname.'.php\' alt="'.$filierefullname.'">';
			
			?>
			</figure>
		</div>
		<?php
		}elseif(isset($_GET['td'])){
		?>
		<div id='charts'>
			<figure>
			<?php
			$filierefullname = str_replace(' ', '_', $_GET['td']);
			echo '<img src=\'charts/percentage_'.$filierefullname.'.php\' alt="'.$filierefullname.'"/>';
			
			?>
			</figure>
		</div>
		<?php
		}?>
			<?php include('footer.php'); ?>
	</div>
</body>
</html>