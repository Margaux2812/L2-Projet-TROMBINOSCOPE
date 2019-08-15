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



date_default_timezone_set('Etc/UTC');
require '../PHPMailer/PHPMailerAutoload.php';
include('functions.inc.php');

function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = '';
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < 8; $i++) {
        $n = rand(0, $alphaLength);
        $pass .= $alphabet[$n];
    }
    return $pass;
}
function sendMail($adress, $name, $lang){
	//Create a new PHPMailer instance
	$mail = new PHPMailer;
	//Tell PHPMailer to use SMTP
	$mail->isSMTP();
	//Enable SMTP debugging
	// 0 = off (for production use)
	// 1 = client messages
	// 2 = client and server messages
	$mail->SMTPDebug = 0;
	//Ask for HTML-friendly debug output
	$mail->Debugoutput = 'html';
	//Set the hostname of the mail server
	$mail->Host = 'smtp.gmail.com';
	// use
	// $mail->Host = gethostbyname('smtp.gmail.com');
	// if your network does not support SMTP over IPv6
	//Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
	$mail->Port = 587;
	//Set the encryption system to use - ssl (deprecated) or tls
	$mail->SMTPSecure = 'tls';
	//Whether to use SMTP authentication
	$mail->SMTPAuth = true;
	//Username to use for SMTP authentication - use full email address for gmail
	$mail->Username = "devwebucp@gmail.com";
	//Password to use for SMTP authentication
	$mail->Password = "Ucp2018@";
	//Set who the message is to be sent from
	$mail->setFrom('devwebucp@gmail.com', 'Le Trombinoscope');
	//Set who the message is to be sent to
	$mail->addAddress($adress, $name);
	//Set the subject line
	$mail->Subject = $lang['etu_mail_subject'];
	//Replace the plain text body with one created manually
	$newpass = randomPassword();
	$mail->Body = $lang['etu_mail_body'].$newpass;
	//send the message, check for errors
	if (!$mail->send()) {
		$return = array(
		'message' => "Mailer Error: " . $mail->ErrorInfo,
		'mdp' => $newpass
		);
		return $return;
	} else {
		$return = array(
		'message' => $lang['etu_mail_success'],
		'mdp' => $newpass
		);
		return $return;
	}
}

function exists_email($email){
	try{
		/*On ouvre le fichier csv des etudiants*/
		$row = 1;
		$array = array(
			'is' => 0,
			'name' => ''
		);
		if (($handle = fopen("donnees/etudiants.csv", "a+")) !== FALSE) {
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE){
				/*Tant qu'on arrive pas à la fin, si on trouve le login recherché, alors on retourne 1*/
				$num = count($data);
				$row++;
				if($data[3]==$email){
					$arrayreplace = array(
					'is' => 1,
					'name' => $data[0]
					);
					$array = array_replace($array, $arrayreplace);
					break;
				}
			}
			fclose($handle);
		}
		return $array;
		
	}
	catch(Exception $e){
		return "Erreur";
	}
}

if(!empty($_POST['lost'])){
	if(filter_var($_POST['login'], FILTER_VALIDATE_EMAIL) && (exists_email($_POST['login'])['is'] == 1)){
		$email = $_POST['login'];
		$name=exists_email($email)['name'];

		$message = sendMail($email, $name, $lang);
		if($message['message']==$lang['etu_mail_success']){
			$success=$lang['etu_mail_success'];
			$newpass=$message['mdp'];
			update($newpass, '4', $name);
		}else{
			$erreur = $message['message'];
		}
	}elseif(already_exists($_POST['login']) == 1){
		$eleve = recuperer($_POST['login']);
		$email = $eleve['email'];
		$nameToDisplay = $eleve['name'].' '.$eleve['forname'];
		$name = $eleve['login'];
		$message = sendMail($email, $nameToDisplay, $lang);
		if($message['message']==$lang['etu_mail_success']){
			$success=$lang['etu_mail_success'];
			$newpass=password_hash(htmlspecialchars($message['mdp']), PASSWORD_DEFAULT);
			update($newpass, '4', $name);
		}else{
			$erreur = $message['message'];
		}
	}else{
		$erreur = $lang['etu_mail_error'];
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo $lang['mail_title']; ?></title>
	<link rel="stylesheet" type="text/css" href="styles/accueil.css"/>
	<link rel="icon" href="../images/icon.png" sizes="16x16" type="image/png" />
	<link rel="icon" href="../images/iconbigger.png" sizes="32x32" type="image/png" />
</head>
<body id='mdp'>
<a href='index.php'><?php echo $lang['index_ens_retour']; ?></a>
	<h1><?php echo $lang['mail_title']; ?></h1>
	<form method="post" action='mdpoublie.php' id="mdpoublie">
					<table>
						<tr>
							<td class='labels'><label for="login">Login <?php echo $lang['mail_or']; ?> e-mail</label> </td>
						</tr>
						<tr>
							<td><input type="text" name="login" id="login" size="15"/></td>
						</tr>
						<tr>
							<td class='button'><input type="submit" name="lost" value="<?php echo $lang['mail_submit']; ?>"/>
						</tr>
					</table>
			</form>
	<?php if(isset($erreur)){
		echo '<p class="error">'.$erreur.'</p>';
	}
		if(isset($success)){
		echo '<p class="success">'.$success.'</p>';
	}
	?>
	<footer>
			<p>
			<a href='?lang=fr'>fr</a>
			<a href='?lang=en'>en</a>
			</p>
		</footer>
</body>
</html>