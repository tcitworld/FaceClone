<?php

// pour oracle: $dsn="oci:dbname=//serveur:1521/base
//$dsn="sqlite:/tmp/base.sqlite"

class Database {

	var $connexion;

	function __construct() {
		try {
			require("connect_db.php");
			$dsn="mysql:dbname=".BASE.";host=".SERVER;
			$this->connexion=new PDO($dsn,USER,PASSWD);
		} catch(PDOException $e){
			printf("Échec de la connexion : %s\n", $e->getMessage());
			exit(); // Pas beau
		}
	}

	public function createMember($nom,$prenom,$email,$birth,$password) {
		$query = $this->connexion->prepare('INSERT INTO MEMBER (nom, prenom, mail, dateNaissance, password, dateInscription, dateLastConnexion) 
			VALUES (:nom,:prenom,:email,:birth,:password, :dateInscription, :dateInscription)');
		$query->bindParam(':nom',$nom);
		$query->bindParam(':prenom',$prenom);
		$query->bindParam(':email',$email);
		$birthtime = strtotime($birth);
		$birthdate = date('Y-m-d',$birthtime);
		$query->bindParam(':birth',$birthdate);
		$query->bindParam(':password',$password);
		$today = date("Y/m/d");
		$query->bindParam(':dateInscription',$today);
		$query->execute();
	}


	public function userUniq($mail) {
		$query = $this->connexion->prepare('SELECT mail FROM MEMBER WHERE mail = :mail');
		$query->bindParam(':mail',$mail);
		$query->execute();
		if( $query->rowCount() > 0 ) { # If rows are found for query
	    	return false;
		}
		else {
	    	return true;
		}
	}

	public function login($login, $pwd) {
		$query = $this->connexion->prepare('SELECT password FROM MEMBER WHERE mail = ?');
		$query->execute(array($login));
		$donnees = $query->fetch();
		if (password_verify($pwd,$donnees['password'])) {
			echo 'mot de passe vérifié';
			return true;
		} else {
			echo 'mauvais mot de passe';
			return false;
		}
	}
}
?>