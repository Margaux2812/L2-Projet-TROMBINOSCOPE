<?php

/*Si le login existe dans le fichier enseignants.csv, alors
on retourne vrai*/
function already_exists_ens($login_recherche){
	try{
		
		$row = 1;
		if (($handle = fopen("donnees/enseignants.csv", "a+")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$row++;
				if($data[0]==$login_recherche){
					return 1;
					break;
				}
			}
			fclose($handle);
		}
		
	}
	catch(Exception $e){
		return "Erreur";
	}
}

/*Si l'enseignant existe, on parcourt de le fichier .csv pour 
trouver l'identifiant et stocker le mdp trouvé. password_verify nous dira 
si les mots de passe hachés correspondent donc comme la fonction 
s'appelle dontmatch, on renvoie la negation*/
function dontmatch_ens($login, $pass){

	if(already_exists_ens($login)){
		try{	
			$row = 1;
			if (($handle = fopen("donnees/enseignants.csv", "a+")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					$row++;
					if($data[0]==$login){
						$mdp_trouve = $data[3];
						break;
					}
				}
				fclose($handle);
			}
			
			return !$isPasswordCorrect = password_verify($pass, $mdp_trouve);
			
		}
		catch(Exception $e){
			return "Erreur";
		}
	}
	else{
		return TRUE;
	}
}

/*Si on trouve l'identifiant, on stocke ses donnees
dans un array qu'on retourne.*/
function recuperer_ens($login_recherche){
	try{		
		$row = 1;
		if (($handle = fopen("donnees/enseignants.csv", "a+")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$row++;
				if($data[0]==$login_recherche){
					$name = $data[1];
					$forname = $data[2];
					$mdp = $data[3];
					
					break;
				}
			}
			fclose($handle);
		}
				$infos = array(
					'login' => $login_recherche,
					'name' => $name,
					'forname' => $forname,
					'pass' => $mdp
					);
					
				return $infos;
		
	}
	catch(Exception $e){
		return 0;
	}
}

/*On récupère les noms de tous les dossiers dans etudiants/donnees car
c'est ici que l'on a créé l'arborescence des filieres. On met ensuite dans un 
array qu'on retourne*/
function getFilieres(){
	
	$listfilieres = array();
	
	$allfilieres = glob('../etudiants/donnees/*', GLOB_ONLYDIR);
	$nbFilieres = count($allfilieres);
 
	//On parcourt
	for($filiere=0; $filiere<$nbFilieres; $filiere++){
		
		/*Comme on reçoit l'arborescence complete, on prend
		la derniere partie avec donc le nom du dossier*/
		$dir_name_cut = explode('/',$allfilieres[$filiere]);
		$nomfiliere = end($dir_name_cut);
			
		/*On remplace les _ par des espaces*/
		$nom_filiere = str_replace( '_', ' ', $nomfiliere);	
		
		array_push($listfilieres, $nom_filiere);
	}
	
	return $listfilieres;

}

/*On récupère les filières existantes, puis on parcourt chaque dossier
de filière pour connaitre ses groupes de TD (comme getFilieres() nous renvoie
un array avec les noms sans le underscore, on le rajoute dans l'arborescence).
On ajoute ensuite à un array 'filiere - groupe', puis on retourne l'array*/
function getTD($filiere=NULL){
	
	if($filiere==NULL){
		$listTD = array();
		$allfilieres = getFilieres();
		$nbFilieres = count($allfilieres);

		for($i=0; $i<$nbFilieres; $i++){
			$alltds = glob('../etudiants/donnees/'.str_replace( ' ', '_', $allfilieres[$i]). '/*' , GLOB_ONLYDIR);
			$nbtds = count($alltds);
				
			if($nbtds !==0){
				for($td=0; $td<$nbtds; $td++){
					
				/*Comme on reçoit l'arborescence complete, on prend
					la derniere partie avec donc le nom du dossier*/
					$dir_name_cut = explode('/',$alltds[$td]);
					$tdchoix = end($dir_name_cut);
								
					array_push($listTD, $allfilieres[$i].' - '.$tdchoix);
						
				}
			}else{
				array_push($listTD, $allfilieres[$i]);
			}
		}
	}else{
		$listTD = array();
		
		$alltds = glob('../etudiants/donnees/'.str_replace( ' ', '_', $filiere). '/*' , GLOB_ONLYDIR);
			$nbtds = count($alltds);
				
			if($nbtds !==0){
				for($td=0; $td<$nbtds; $td++){
					
				/*Comme on reçoit l'arborescence complete, on prend
					la derniere partie avec donc le nom du dossier*/
					$dir_name_cut = explode('/',$alltds[$td]);
					$tdchoix = end($dir_name_cut);
								
					array_push($listTD, $tdchoix);
						
				}
			}
	}
	return $listTD;
}

/* Si on nous donne une filière spécifique, on récupère les élèves étant 
dans celle-ci sous la forme d'une liste au format NOM.PRE.numetu. On crée
ensuite un tableau avec un nombre de colonnes demandé (par défaut 4). On ajoute
ensuite dans chaque case du tableau, le sous tableau contenant la fiche de l'élève.

Si on ne demande pas de filiere spécifique, alors on veut le trombinoscope général, auquel
cas, chaque filiere représente une colonne du tableau résultat.*/
function getTrombi($filiere, $td, $colonne_demande = 4){
	if($filiere !=''){	
		$eleve=0;
		$listEleves = getEleves($filiere, $td);
		$nbEleves = count($listEleves);
		
		/*On récupere le vrai nom de la filiere sans le _*/
		$nom_filiere = str_replace('_', ' ', $filiere);
		if($td!=''){
			$th = $nom_filiere.' - '.$td;
		}else{
			$th= $nom_filiere;
		}

		$trombi = "<table class=\"trombi\">
						<tr>
							<th colspan = \"".$colonne_demande."\">".$th."</th>
						</tr>";
						
		while($eleve < $nbEleves){
			$colonne_width = (1/$colonne_demande)*100;
			$trombi.= "<tr>";
			for($colonne=1; $colonne <= $colonne_demande; $colonne++){
				/*On affiche le tableau de l'élève et on finit avec des cases vides*/
				if($eleve<$nbEleves){
					$trombi .= "<td style=\" width: ".$colonne_width."%;\">".getEleveTable($listEleves[$eleve])."</td>";
					$eleve++;
				}
				else{
					$trombi .= "<td></td>";
				}
			}	
			$trombi .="</tr>";
		}
		
		$trombi .= "</table>";
	
	}else{
		$allfilieres = glob('../etudiants/donnees/*' , GLOB_ONLYDIR);
		$nbDossiers = count($allfilieres);
		
		$trombi = '<table class=\'trombiAll\'>';

		for($filiere=0; $filiere<$nbDossiers; $filiere++){
			$dir_name_cut = explode('/',$allfilieres[$filiere]);
			$filierefullname = end($dir_name_cut);
			
			$listEleves = getEleves($filierefullname, $td);
			$nbEleves = count($listEleves);
			
			/*Si le nombre d'élève est inférieur à 4, alors on change la colonne demandée
			pour que le colspan du titre corresponde*/
			if($nbEleves < 4){
				switch($nbEleves){
					case 0: $colonne=1;
							break;
					case 1: $colonne=1;
							break;
					case 2: $colonne=2;
							break;
					case 3: $colonne=3;
							break;
				}
				
			}else{
				$colonne = $colonne_demande;
			}
			$trombi.= '<tr> <td>'.getTrombi($filierefullname, $td, $colonne).'</td></tr>';
		}
		$trombi .= '</table>';
	}
	return $trombi;
}

function str_contains($string, $search){
    return strpos($string, $search) !== false;
}

/*On scanne le fichier sous la forme 'filiere/td'. Si on ne demande pas de td
spécifique, alors on scanne juste dans 'filiere/'. On ne prend pas en compte . et .. 
comme dossier. Si le fichier trouvé est une image, alors on ajoute l'élève à un array
qu'on retourne. On trie ensuite l'array par ordre alphabétique*/
function getEleves($filiere, $td=''){
	$listEleves = array();
	
	if($td != ''){
		$listfile = scandir('../etudiants/donnees/'.$filiere.'/'.$td);
	}else{
		$listfile = scandir('../etudiants/donnees/'.$filiere);
	}
	
	for($i=2; $i<count($listfile); $i++){
	
		$file_name = $listfile[$i];
		$infosfile = explode('.', $file_name);
		$imgarray = array('gif', 'jpg', 'jpeg', 'png');
		
		if(in_array(end($infosfile), $imgarray)){
			$eleve=$infosfile[0].'.'.$infosfile[1].'.'.$infosfile[2];
			array_push($listEleves, $eleve);	
		}elseif(end($infosfile) != 'txt'){//C'est un groupe de td
		
			$listTd = getEleves($filiere, $file_name);
			for($j=0; $j<count($listTd); $j++){
				array_push($listEleves, $listTd[$j]);
			}
		}
	}
	sort($listEleves);
	return $listEleves;
}

/*Cette fonction renvoie la 'fiche' d'un élève, un tableau avec la photo de celui-ci
avec son nom, prénom, et groupe de TD. On a l'élève sous la forme NOM.PRE.num_etu, 
donc on extrait le numero etudiant. On récupère ensuite les informations de l'élève qu'on
ajoute au tableau. De plus, quand l'image sera survolée, on verra sa dernière date de modification.
Si l'élève est dans une filière n'ayant pas de groupe de TD, On affiche juste sa filière.*/
function getEleveTable($eleve_demande){
	
	$temp = explode('.', $eleve_demande);
	$num_etu = $temp[2];
	
	$infoseleve = recuperer($num_etu);
	$img_path = '../etudiants/'.$infoseleve['profile_pic'];

	$eleveTable = '<table class=\'inside\'>
						<tr>
							<td class="images"><img src="'.$img_path.'" alt="profile_pic" title="'.date ("F d Y H:i:s.", filemtime($img_path)).'"></td>
						</tr>
						<tr>
							<td title="'.$infoseleve['email'].'" class=\'toHover\'><strong>'.str_replace('_', ' ', $infoseleve['name']).'</strong> '.str_replace('_', ' ', $infoseleve['forname']).'</td>
						</tr>
						<tr>';
						
	if($infoseleve['td'] !== ''){
		$eleveTable .= '<td>'.str_replace('_', ' ', $infoseleve['filiere']).' Groupe '.$infoseleve['td'].'</td>';
	}else{
		$eleveTable.= '<td>'.str_replace('_', ' ', $infoseleve['filiere']).'</td>';
	}
	$eleveTable .='</tr>
					</table>';
						
	return $eleveTable;
}

/*On cherche dans le fichier .csv un élève, quand on l'a trouvé, on récupère
ses informations qu'on renvoit grâce à un tableau associatif*/
function recuperer($login_recherche){
	try{		
		$row = 1;
		
		if (($handle = fopen("../etudiants/donnees/etudiants.csv", "a+")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				$row++;
				if($data[0]==$login_recherche){
					$name = $data[1];
					$forname = $data[2];
					$email = $data[3];
					$profile_pic = $data[5];
					$filiere = $data[6];
					$TD = $data[7];
					break;
				}
			}
			fclose($handle);
		}
				$infos = array(
					'login' => $login_recherche,
					'name' => $name,
					'forname' => $forname,
					'email' => $email,
					'filiere' => $filiere,
					'td' => $TD,
					'profile_pic' => $profile_pic
					);
					
				return $infos;
	}
	catch(Exception $e){
		return 0;
	}
}

/*On a tout mis en majuscule dans nos donnees sauf notre email
donc on va cherche les deux formats au cas où l'enseignant chercherait l'email.
Si l'une des colonnes d'un élève contient l'expression recherchée, alors on renvoie
le nom de la photo pour ensuite utiliser la fonction getEleveTable()*/
function resultat_recherche($string){
	$results=array();
	
	$string_recherche = strtoupper($string);
	
	try{
		
		$row = 1;
		if (($handle = fopen("../etudiants/donnees/etudiants.csv", "a+")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				$row++;
				for($i=0; $i<8; $i++){
					if(str_contains($data[$i], $string_recherche) || str_contains($data[$i], $string)){
						$profile_pic = $data[5];
						array_push($results, $profile_pic);	
						break;
					}
				}				
			}
			fclose($handle);
		}
		sort($results);
		return($results);	
	}
	catch(Exception $e){
		return "Erreur";
	}	
}

/*Si l'enseignant à des favoris on les récupère dans un tableau qu'on retournera*/
function getFavorites($login){
	try{
		$fav = array();
		$row = 1;

		if (($handle = fopen("donnees/enseignants_fav.csv", "a+")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				$row++;
				if($data[0]==$login){
					for($i=1; $i<$num; $i++){
						array_push($fav, $data[$i]);
					}
				}
			}
			fclose($handle);
		}
		return $fav;
	}
	catch(Exception $e){
		return "Erreur";
	}
}

/*Si cet enseignant avait déjà des favoris, on ajoute (s'il n'existe pas déjà)
les favoris qui ont été envoyé dans la fonction. On a deux fichiers différents afin
de recopier dans l'un les données de l'autre, puis de le remplacer.
Si l'enseignant n'a pas été trouvé, il n'avait pas encore de favoris. On ajoute
alors au début de l'array des favoris le login de l'enseignant*/
function setFavorites($login, $favorites){
	
	$nbFav = count($favorites);
	try{
		$lire = fopen('donnees/enseignants_fav.csv', 'a+');
		$ecrire = fopen('donnees/temporary.csv', 'w');
		$found = FALSE;
		
		while(( $data = fgetcsv($lire, 1000, ",")) !== FALSE ){ 
		   if ($data[0] == $login) {
			$found = TRUE;
			$nbFavSaved = count($data);
			   for($i = 0; $i < $nbFav; $i++){
					if(!in_array($favorites[$i],$data)){
					   $nbFavSaved ++;
					   $data[$nbFavSaved] = $favorites[$i];
					}
			    }
		   }
		   fputcsv( $ecrire, $data);
		}
		
		if(!$found){
			array_unshift($favorites, $login);
	
			$donnees = array(
				$favorites
			);
				
			foreach($donnees as $fields){
				fputcsv($ecrire, $fields);
			}
		}
		fclose( $lire );
		fclose( $ecrire );

		unlink('donnees/enseignants_fav.csv');
		rename('donnees/temporary.csv', 'donnees/enseignants_fav.csv');
	}
	catch(Exception $e){
		return "Erreur";
	}
}

/*Si on trouve l'identifiant, on cherche le td favoris a supprimé,
puis on décale tous les favoris restants vers la gauche afin d'écraser celui à
supprimer. On efface ensuite la derniere donnees, comme l'avant derniere est 
la même*/
function supprimer_fav($login, $td){
	try{
		$lire = fopen('donnees/enseignants_fav.csv', 'a+');
		$ecrire = fopen('donnees/temporary.csv', 'w');

		while(( $data = fgetcsv($lire, 1000, ",")) !== FALSE ){ 
			if ($data[0] == $login) {
				$num=count($data);
				for($i=1; $i<$num; $i++){
					if ($data[$i] == $td){
						if($i=$num-1){
							unset($data[$num-1]);
						}
						$j = $i+1;
						while($j<$num-1){
							$data[$i] = $data[$j];
							$i++;
							$j++;
						}
						unset($data[$num-1]);
						break;
					}
				}
			}
			fputcsv( $ecrire, $data);
		}

		fclose( $lire );
		fclose( $ecrire );

		unlink('donnees/enseignants_fav.csv');
		rename('donnees/temporary.csv', 'donnees/enseignants_fav.csv');
	}
	catch(Exception $e){
		return 'Erreur';
	}	
}

/*On cherche l'effectif d'après l'arborescence*/
function getEffectif($filiere, $td=''){
	if($td==''){
		$result = file_get_contents('../etudiants/donnees/'.$filiere.'/effectif.txt');
	}else{
		$result = file_get_contents('../etudiants/donnees/'.$filiere.'/'.$td.'/effectif.txt');
	}
	return $result;
}

/*On renvoie un tableau associatif donnant le nombre
d'élèves inscrits dans le filière (voire td) et l'effectif attendu*/
function getPourcentage($filiere, $td=''){
	$effectif = getEffectif($filiere, $td);
	$elevesInscrits = count(getEleves($filiere, $td));
	
	$result = array(
		'effectif' => $effectif,
		'eleves' => $elevesInscrits
	);
	
	return $result;
}


/*Cette fonction génère le fichier .php permettant de visualiser le taux de
renseignement de chaque filiere. On enregistre chaque fichier sous la forme 
'charts/percentage_FILIERE.php'. On écrit dans ce fichier le code permettant la
création d'un camembert.*/
function create_charts(){
	$listFilieres = getFilieres();
	
	for($i=0; $i<count($listFilieres); $i++){
		$filierefullname = str_replace(' ', '_', $listFilieres[$i]);
		$effectif = getEffectif($filierefullname);
		$pourcentages = getPourcentage($filierefullname);
		$pourcentagerestant = $pourcentages['effectif']-$pourcentages['eleves'];
		
		$filename = 'charts/percentage_'.$filierefullname.'.php';
		if(file_exists($filename)){
			unlink($filename);
		}
			$handle = fopen($filename, 'a+');
			$pourcentage_titre = round((($pourcentages['eleves'])*100)/($pourcentages['eleves']+$pourcentagerestant));
			
			$write = '<?php
			require_once (\'../../jpgraph/src/jpgraph.php\');
			require_once (\'../../jpgraph/src/jpgraph_pie.php\');

			$data = array('.$pourcentages['eleves'].','.$pourcentagerestant.');
			
			$graph = new PieGraph(200,200, \'auto\');
			$graph->SetShadow();
			 
			$graph->title->Set("'.$listFilieres[$i].'");
			 
			$p1 = new PiePlotC($data);
			$p1->value->SetFont(FF_ARIAL, FS_NORMAL, 0);
			$p1->midtitle->Set("'.$pourcentage_titre.'%");
			$p1->midtitle->SetFont(FF_ARIAL,FS_NORMAL,10);
			$graph->Add($p1);
			$p1->SetSliceColors(array("skyblue2", "#cccccc"));
			$graph->Stroke();

			?>';
			
			fwrite($handle, $write);
			
			fclose($handle);
		
	}
}

function create_charts_tds(){
	$listTDs = getTD();
	
	for($i=0; $i<count($listTDs); $i++){
		$temp = explode('-', $listTDs[$i]);
		
		/*Il y a l'espace à la fin et il y a l'espace au début si le td existe
		sinon le fichier a déjà été créé grace a create_charts()*/
		if(count($temp)>1){
			$filierename = substr($temp[0], 0, -1);
			$tdname = substr($temp[1], 1);
		
			$filierefullname = str_replace(' ', '_', $filierename);
			$effectif = getEffectif($filierefullname, $tdname);
			$pourcentages = getPourcentage($filierefullname, $tdname);
			$pourcentagerestant = $pourcentages['effectif']-$pourcentages['eleves'];
			
			$filename = 'charts/percentage_'.$filierefullname.'_-_'.$tdname.'.php';
			if(file_exists($filename)){
				unlink($filename);
			}
				$handle = fopen($filename, 'a+');
				$pourcentage_titre = round((($pourcentages['eleves'])*100)/($pourcentages['eleves']+$pourcentagerestant));
				
				$write = '<?php
				require_once (\'../../jpgraph/src/jpgraph.php\');
				require_once (\'../../jpgraph/src/jpgraph_pie.php\');

				$data = array('.$pourcentages['eleves'].','.$pourcentagerestant.');
				
				$graph = new PieGraph(200,200, \'auto\');
				$graph->SetShadow();
				 
				$graph->title->Set("'.$listTDs[$i].'");
				 
				$p1 = new PiePlotC($data);
				$p1->value->SetFont(FF_ARIAL, FS_NORMAL, 0);
				$p1->midtitle->Set("'.$pourcentage_titre.'%");
				$p1->midtitle->SetFont(FF_ARIAL,FS_NORMAL,10);
				$graph->Add($p1);
				$p1->SetSliceColors(array("skyblue2", "#cccccc"));
				$graph->Stroke();

				?>';
				
				fwrite($handle, $write);
				
				fclose($handle);
		}
	}
}

function printToPdf($listFiliere){
	require_once('tcpdf/tcpdf.php');
	
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
	$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
	$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

	if(is_array($listFiliere)){
		for($i=0; $i<count($listFiliere); $i++){
			$temp = explode('-', $listFiliere[$i]);
			if(count($temp)<2){
				$td='';
				$filiere=$temp[0];
			}else{
				$td = substr($temp[1], 1);
				$filiere = substr($temp[0], 0, -1);
			}
			
			$pdf->AddPage();
			$trombi = '<style>th{font-size: 30px;
			color: white;
			background-color: #5cb300;
			text-align: center;}</style>'.getTrombi($filiere, $td);

			$pdf->writeHTML($trombi, true, false, true, false, '');

			$pdf->lastPage();
		}
	}else{
		$temp = explode('-', $listFiliere);
		if(count($temp)<2){
			$td='';
			$filiere=$temp[0];
		}else{
			$td = substr($temp[1], 1);
			$filiere = substr($temp[0], 0, -1);
		}
		
		$pdf->AddPage();
		$trombi = '<style>th{font-size: 30px;
		color: white;
		background-color: #5cb300;
		text-align: center;}</style>'.getTrombi($filiere, $td);

		$pdf->writeHTML($trombi, true, false, true, false, '');

		$pdf->lastPage();
	}

	$pdf->Output('impression_trombinoscope.pdf', 'I');
}
?>