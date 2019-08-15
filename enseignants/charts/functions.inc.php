<?php
/*Fonctions utilisée dans 'functions.inc.php' mais
l'arborescence change (le '../' à rajouter)*/
function getFilieres(){
					
	$listfilieres = array();
				
	$allfilieres = glob('../../etudiants/donnees/*', GLOB_ONLYDIR);
	$nbFilieres = count($allfilieres);

	for($filiere=0; $filiere<$nbFilieres; $filiere++){

		$dir_name_cut = explode('/',$allfilieres[$filiere]);
		$nomfiliere = end($dir_name_cut);
							
		$nom_filiere = str_replace( '_', ' ', $nomfiliere);	
							
		array_push($listfilieres, $nom_filiere);
	}
					
	return $listfilieres;

}

/*Idem*/
function getEffectif($filiere, $td=''){
	if($td==''){
		$result = file_get_contents('../../etudiants/donnees/'.$filiere.'/effectif.txt');
	}else{
		$result = file_get_contents('../../etudiants/donnees/'.$filiere.'/'.$td.'/effectif.txt');
	}
	return $result;
}

function getTD($filiere=NULL){
	
	if($filiere==NULL){
		$listTD = array();
		$allfilieres = getFilieres();
		$nbFilieres = count($allfilieres);

		for($i=0; $i<$nbFilieres; $i++){
			$alltds = glob('../../etudiants/donnees/'.str_replace( ' ', '_', $allfilieres[$i]). '/*' , GLOB_ONLYDIR);
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
		
		$alltds = glob('../../etudiants/donnees/'.str_replace( ' ', '_', $filiere). '/*' , GLOB_ONLYDIR);
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

function getEleves($filiere, $td=''){
	$listEleves = array();
	
	if($td != ''){
		$listfile = scandir('../../etudiants/donnees/'.$filiere.'/'.$td);
	}else{
		$listfile = scandir('../../etudiants/donnees/'.$filiere);
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
?>