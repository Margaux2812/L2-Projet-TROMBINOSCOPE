<?php

function already_exists($login_recherche){
	try{
		/*On ouvre le fichier csv des etudiants*/
		$row = 1;
		if (($handle = fopen("donnees/etudiants.csv", "a+")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				/*Tant qu'on arrive pas à la fin, si on trouve le login recherché, alors on retourne 1*/
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

function dontmatch($login, $pass){

/*On verifie que l'identifiant existe*/
	if(already_exists($login)){
		try{	
			/*On parcourt le fichier .csv pour trouver l'identifiant et stocker le mdp trouvé*/
			$row = 1;
			if (($handle = fopen("donnees/etudiants.csv", "a+")) !== FALSE) {
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$num = count($data);
					$row++;
					if($data[0]==$login){
						$mdp_trouve = $data[4];
						break;
					}
				}
				fclose($handle);
			}
			
			/*password_verify nous dira si les mots de passe hachés correspondent
			donc comme la fonction s'appelle dontmatch, on envoie la negation*/
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

function isnotvalid($picture){
	if ($picture['error'] == 0){
		// Testons si le fichier n'est pas trop gros
		if ($picture['size'] <= 1000000){
			if(preg_match('#image#', $picture['type'])){	
					// Testons si l'extension est autorisée
					$infosfichier = pathinfo(htmlspecialchars($picture['name']));
					$extension_upload = $infosfichier['extension'];
					$extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
					
				if (in_array($extension_upload, $extensions_autorisees)){
					return FALSE;
				}else{
					return TRUE;
				}
			}else{
				return TRUE;
			}
		}
		else{
			return TRUE;
		}
	}else{
		return TRUE;
	}
}

function recuperer($login_recherche){
	try{		
		$row = 1;
		if (($handle = fopen("donnees/etudiants.csv", "a+")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$num = count($data);
				$row++;
				
				/*Si on a trouvé l'dentifiant on recupere toutes ses informations*/
				if($data[0]==$login_recherche){
					$name = $data[1];
					$forname = $data[2];
					$email = $data[3];
					$mdp = $data[4];
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
					'mdp' => $mdp,
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

function resize($img){
	$dimensions = getimagesize($img);

	$ratio_w = $dimensions[0] / 110; // 110 est la largeur maximale et 142 la hauteur maximale
	$ratio_h = $dimensions[1] / 142;

	if ($ratio_w > $ratio_h)
	{
		$newh = round($dimensions[1] / $ratio_w);
		$neww = 110;
	}
	else
	{
		$neww = round($dimensions[0]/ $ratio_h);
		$newh = 142;
	}
	 $new_dimensions = array(
		'width' => $neww,
		'height' =>$newh
	 );
	 
	 return $new_dimensions;
}


function update($file, $rank, $login){
	try{
		$lire = fopen('donnees/etudiants.csv', 'r');  //lire
		$ecrire = fopen('donnees/temporary.csv', 'w'); //ecrire

		while(( $data = fgetcsv($lire, 1000, ",")) !== FALSE ){ 
		   //modify data here
		   if ($data[0] == $login) {
			  //Remplacer la ligne
			  $data[$rank] = $file;
		   }

		   //write modified data to new file
		   fputcsv( $ecrire, $data);
		}

		//close both files
		fclose( $lire );
		fclose( $ecrire );

		//clean up
		unlink('donnees/etudiants.csv');// Delete obsolete BD
		rename('donnees/temporary.csv', 'donnees/etudiants.csv'); //Rename temporary to new
	}
	catch(Exception $e){
		return "Erreur";
	}
}

function upload($newfile, $name, $forname, $login, $filiere, $td){
	
	/*On prend le nom de la photo pour recuperer son extension*/

	$infosfichier = pathinfo(htmlspecialchars($newfile['name']));
	$extension_upload = $infosfichier['extension'];
	
	/*On la renomme au format NOM.PRE.login*/
	$first_3_letters_of_forname = substr($forname, 0, 3);
	
	if($td!=''){
		$img_path = 'donnees/'.$filiere.'/'.$td.'/'.$name.'.'.$first_3_letters_of_forname.'.'.$login.'.'.$extension_upload;
	}else{
		$img_path = 'donnees/'.$filiere.'/'.$name.'.'.$first_3_letters_of_forname.'.'.$login.'.'.$extension_upload;
	}
	/*On crée une nouvelle image où nous allons copier notre image redimensionnée*/
	$uploadedfile  = $newfile['tmp_name'];
	if($extension_upload=="jpg" || $extension_upload=="jpeg" ){
		$src = imagecreatefromjpeg($uploadedfile);
	}else if($extension_upload=="png"){
		$src = imagecreatefrompng($uploadedfile);
	}else {
		$src = imagecreatefromgif($uploadedfile);
	}
	
	/*On prend nos deux dimensions (déprat, arrivée)*/
	list($width,$height)=getimagesize($uploadedfile);
	$new_dimensions = resize($uploadedfile);
	$tmp=imagecreatetruecolor($new_dimensions['width'],$new_dimensions['height']);
	imagecopyresampled($tmp,$src,0,0,0,0,$new_dimensions['width'],$new_dimensions['height'], $width,$height);
	imagejpeg($tmp,$img_path,100);
	
	imagedestroy($src);
	imagedestroy($tmp);
	return $img_path;
}


?>