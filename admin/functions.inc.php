<?php
function already_exists($login_recherche){
	try{
		
		$row = 1;
		if (($handle = fopen("../enseignants/donnees/enseignants.csv", "a+")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
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

function getEnseignants(){
	try{	
		$listEns = array();
			/*On parcourt le fichier .csv pour prendre tous les enseignants et les stocker dans un array */
			$row = 1;
			if (($handle = fopen("../enseignants/donnees/enseignants.csv", "a+")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$nom = $data[1];
					$prenom = $data[2];
					$infosEns = ucfirst(strtolower($nom)).' '.ucfirst(strtolower($prenom));
					array_push($listEns, $infosEns);
					$row++;
				}
				fclose($handle);
			}
			sort($listEns);
			return $listEns;
			
		}
		catch(Exception $e){
			return "Erreur";
		}
}

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
			
		/*On l'ajoute à notre array à retourner*/
		array_push($listfilieres, $nomfiliere);
	}
	
	return $listfilieres;

}

function getEffectif($filiere, $td=''){
	if($td==''){
		$result = file_get_contents('../etudiants/donnees/'.$filiere.'/effectif.txt');
	}else{
		$result = file_get_contents('../etudiants/donnees/'.$filiere.'/'.$td.'/effectif.txt');
	}
	return $result;
}

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

?>