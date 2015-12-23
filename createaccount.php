<form action="createaccount.php" method="post" accept-charset="utf-8">
	<label for="email">Email : </label>
	<input type="email" name="email" value="" placeholder="">
	<label for="nom">Nom : </label>
	<input type="text" name="nom" value="" placeholder="">
	<label for="prenom">Prenom : </label>
	<input type="text" name="prenom" value="" placeholder="">
	<label for="birth">Date de naissance</label>
	<input type="date" name="birth" placeholder="JJ/MM/AAAA" pattern="(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).[0-9]{4}">
	<label for="pwd">Mot de passe</label>
	<input type="password" name="pwd">
	<button type="submit">Envoyer</button>
</form>
<a href="index.php">Retour au site</a>
<?php
require_once('database.php');
require_once('tools.php');

date_default_timezone_set('Europe/Paris');
$database = new Database();

if (isset($_POST['email']) && isset($_POST['nom']) && isset($_POST['prenom']) && isset($_POST['birth']) && isset($_POST['pwd'])) {
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && validateDate($_POST['birth'])) {
		$email = $_POST['email'];
		$nom = valider($_POST['nom']);
		$prenom = valider($_POST['prenom']);
		$birth = $_POST['birth'];
		$password = password_hash($_POST['pwd'],PASSWORD_DEFAULT);
		if ($database->userUniq($email)) {
			$database->createMember($nom,$prenom,$email,$birth,$password);
	        echo 'L\'inscription a été effectuée';
    	} else {
    		echo 'un utilisateur avec ce mail est déjà inscrit';
    	}
    }
} elseif (isset($_POST['email']) || isset($_POST['nom']) || isset($_POST['prenom']) || isset($_POST['birth']) || isset($_POST['pwd'])) {
	echo 'merci de remplir tous les champs';
}




?>