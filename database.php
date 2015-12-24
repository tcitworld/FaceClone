<?php
require_once('User.php');
date_default_timezone_set('Europe/Paris');


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
			$_SESSION['login'] = $login;
			return new User($login);
		} else {
			return false;
		}
	}

	public function newMessage($user,$msg) {
		$query = $this->connexion->prepare('INSERT INTO POST (idmembre, contenupost, datemessage) 
			VALUES (:user,:msg,:datemessage)');
		$query->bindParam(':user',$user);
		$query->bindParam(':msg',$msg);
		$today = date('Y/m/d h/i/s', time());
		$query->bindParam(':datemessage',$today);
		$query->execute();

	}

	public function getUser($login) {
		$query = $this->connexion->prepare('SELECT * FROM MEMBER WHERE mail = ?');
		$query->execute(array($login));
		$donnees = $query->fetch();
		return $donnees;
	}

	public function getPostsForUser($userid) {
		$query = $this->connexion->prepare('SELECT * FROM POST WHERE idmembre = ?');
		$query->execute(array($userid));
		return $query->fetchAll();
	}

	public function getFriendsForUser($userid) {
		$query = $this->connexion->prepare('SELECT Friends FROM MEMBER WHERE idmembre = ?');
		$query->execute(array($userid));
		return $query->fetch();
	}

	public function getAllPeople($userid) {
		$query = $this->connexion->prepare('SELECT * FROM MEMBER WHERE idmembre <> ?');
		$query->execute(array($userid));
		return $query->fetchAll();
	}

	public function setFriends($userid,$friends) {
		$query = $this->connexion->prepare('UPDATE MEMBER SET Friends = :friends WHERE idmembre = :userid');
		$query->bindParam(':friends',$friends);
		$query->bindParam(':userid',$userid);
		$query->execute();
	}

	public function getMailForId($userid) {
		$query = $this->connexion->prepare('SELECT mail FROM MEMBER WHERE idmembre = ?');
		$query->execute(array($userid));
		$donnees = $query->fetch();
		return $donnees;
	}
}
?>