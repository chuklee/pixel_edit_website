<?php
header("Cache-Control: no-cache");
header("Pragma: nocache");
header("Content-Type: text/html; charset=ISO-8859-15");
require_once("ConfigPds.php");
//On commence par vérifier que les champs sont bien remplis
	if (!isset ($_POST['EmailContact']) || empty ($_POST['EmailContact']) || !isset ($_POST['SujetContact']) || empty ($_POST['SujetContact']) || !isset ($_POST['MessageContact']) || empty ($_POST['MessageContact']) || !isset ($_POST['AntiSpamContact']) || empty ($_POST['AntiSpamContact'])) {
		echo 0;
	}
	//On vérifie que le code anti spam est bon
	else if ($_POST['AntiSpamContact']!==$_SESSION['CaptchaPdsContact']) {
		echo 1;
	}
	else{
		//C'est bon, on envoi le message
		$to = ''.EMAIL_WEBMASTER.'';
		$subject = ''.stripslashes($_POST['SujetContact']).'';
		$headers = 'From: '.$_POST['EmailContact'].''."\r\n";
		$headers.= 'Reply-to: '.$_POST['EmailContact'].''."\r\n";
			//On regarde si les emails en HTML sont autorisés
			if (AUTORISER_HTML==="oui") {
				//Oui, on signale le mail en HTML et on ajoute la fonction nl2br au message por convertir les sauts de ligne en balise <br />
				$headers.= 'Content-Type: text/html; charset="ISO-8859-15"'."\r\n";
				$msg = ''.nl2br(stripslashes($_POST['MessageContact'])).'';
			}
			else{
				//Non, on signale le mail comme texte et on formate le message
				$headers.= 'Content-Type: text/plain; charset="ISO-8859-15"'."\r\n";
				$msg = ''.stripslashes($_POST['MessageContact']).'';
			}
		$headers.= 'Content-Transfer-Encoding: 8bit'.".\r\n";
		$headers .= "\r\n";
		mail($to, $subject, $msg, $headers);
		echo 2;
	}

?>