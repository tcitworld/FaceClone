<?php
require_once('global.php');

date_default_timezone_set('Europe/Paris');
$database = new Database();

if (isset($_POST['email']) && isset($_POST['nom']) && isset($_POST['prenom']) && 
	isset($_POST['birth']) && isset($_POST['pwd'])) {
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && Tools::validateDate($_POST['birth'])) {
		$email = $_POST['email'];
		$nom = Tools::valider($_POST['nom']);
		$prenom = Tools::valider($_POST['prenom']);
		$birth = $_POST['birth'];
		$password = password_hash($_POST['pwd'],PASSWORD_DEFAULT);
		if ($database->userUniq($email)) {
			$database->createMember($nom,$prenom,$email,$birth,$password);
	        echo 'L\'inscription a été effectuée';
    	} else {
    		echo 'un utilisateur avec ce mail est déjà inscrit';
    	}
    }
} elseif (isset($_POST['email']) || isset($_POST['nom']) || isset($_POST['prenom']) || 
	isset($_POST['birth']) || isset($_POST['pwd'])) {
	echo 'merci de remplir tous les champs';
} else {
}


Tools::callTwig('createaccount.twig',array());

?>