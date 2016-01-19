<?php
session_start();
require_once('global.php');


$database = new Database();
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
}

$message = '';
$valide = true;

if (isset($_POST['education']) && isset($_POST['location']) && isset($_POST['skills']) && 
	isset($_POST['job']) && isset($_POST['email']) && isset($_POST['pwd']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['oldpwd'])) {
	$email = $_POST['email'] == '' ? $user->getMail() : $_POST['email'];
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$message = "Format d'adresse mail non reconnue";
		$valide = false;
	}
	if ($valide) {
		$password = $_POST['pwd'];
		$oldpwd = $_POST['oldpwd'];
		if ($password != '' && $oldpwd != '') {
			if (password_verify($oldpwd,$user->getPassword())) {
				$pwd = password_hash($password,PASSWORD_DEFAULT);
			} else {
				$valide = false;
				$message = "Mot de passe incorrect !";
			}
		}
		if ($valide) {
			$nom = $_POST['nom'] == '' ? $user->getNom() : $_POST['nom'];
			$prenom = $_POST['prenom'] == '' ? $user->getPrenom() : $_POST['prenom'];
			$password = $password == '' ? $user->getPassword() : $pwd;
			$education = $_POST['education'] == '' ? $user->getEducation() : $_POST['education'];
			$location = $_POST['location'] == '' ? $user->getLocation() : $_POST['location'];
			$skills = $_POST['skills'] == '' ? $user->getSkills() : $_POST['skills'];
			$jobs = $_POST['job'] == '' ? $user->getJob() : $_POST['job'];
			$database->changeUserTrivialInformations($user->getid(),$nom, $prenom, $email, $password, $education,$location,$skills,$jobs);
			$message = "Votre profil est à jour";
		}
		
		// check mdp
		//$password = password_hash($_POST['pwd'],PASSWORD_DEFAULT);
	
    //}
    
}

}

Tools::callTwig('edit_profile.twig',array('message' => $message,'valide' => $valide,'connected' => Tools::isLogged(), 'user' => $user));

?>