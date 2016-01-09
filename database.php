<?php
require_once('global.php');

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

/*

	Member-related functions

*/

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

	public function getUser($login) {
		$query = $this->connexion->prepare('SELECT * FROM MEMBER WHERE mail = ?');
		$query->execute(array($login));
		$donnees = $query->fetch();
		return $donnees;
	}

	public function getAllPeople($userid) {
		$query = $this->connexion->prepare('SELECT idmembre FROM MEMBER WHERE idmembre <> ?');
		$query->execute(array($userid));
		return $query->fetchAll();
	}

	public function getMailForId($userid) {
		$query = $this->connexion->prepare('SELECT mail FROM MEMBER WHERE idmembre = ?');
		$query->execute(array($userid));
		$donnees = $query->fetch();
		return $donnees;
	}

	/*


	Posts-related functions

	*/

	public function newMessage($user,$msg,$attachment) {
		$query = $this->connexion->prepare('INSERT INTO POST (idmembre, contenupost, datemessage,attachment) 
			VALUES (:user,:msg,:datemessage,:attachment)');
		$query->bindParam(':user',$user);
		$query->bindParam(':msg',$msg);
		$query->bindParam(':attachment',$attachment);
		$today = date('Y/m/d H/i/s', time());
		$query->bindParam(':datemessage',$today);
		$query->execute();

	}

	public function getPostsForUser($userid) {
		$query = $this->connexion->prepare('SELECT * FROM POST WHERE idmembre = ?');
		$query->execute(array($userid));
		return $query->fetchAll();
	}

	public function getPost($idpost) {
		$query = $this->connexion->prepare('SELECT * FROM POST WHERE idpost = ?');
		$query->execute(array($idpost));
		$donnees = $query->fetch();
		return $donnees;
	}

	public function deletePost($idpost) {
		$query = $this->connexion->prepare('DELETE FROM POST WHERE idpost = ?');
		$query->execute(array($idpost));
	}


	/*
	
	Likes

	*/

	public function likePost($idconversation,$userid) {
		$query = $this->connexion->prepare('INSERT INTO LIKES (idconversation,idmembre) 
			VALUES (:idconversation, :idmembre)');
		$query->bindParam(':idconversation',$idconversation);
		$query->bindParam(':idmembre',$userid);
		$query->execute();
	}

	public function getLikes($idpost) {
		$query = $this->connexion->prepare('SELECT idmembre FROM LIKES WHERE idpost = ?');
		$query->execute(array($idpost));
		$donnees = $query->fetchAll();
		return $donnees;
	}

	/*
	
	Comments

	*/

	public function getComments($idpost) {
		$query = $this->connexion->prepare('SELECT * FROM COMMENT WHERE idpost = ?');
		$query->execute(array($idpost));
		$donnees = $query->fetchAll();
		return $donnees;
	}

	/*

	Embeed-URLs

	*/

	public function setAttachment ($url,$summary,$title,$picture = NULL) {
		$query = $this->connexion->prepare('INSERT INTO ATTACHMENT (url,title,summary,picture) 
			VALUES (:url, :title, :summary, :picture)');
		$query->bindParam(':url',$url);
		$query->bindParam(':title',$title);
		$query->bindParam(':summary',$summary);
		$query->bindParam(':picture',$picture);
		$query->execute();
	}


	public function getAttachment($url) {
		$query = $this->connexion->prepare('SELECT * FROM ATTACHMENT WHERE url = ?');
		$query->execute(array($url));
		$donnees = $query->fetch();
		return $donnees;
	}

	/*
	
	Friends-related functions

	*/

	public function setFriends($userid,$friends) {
		$query = $this->connexion->prepare('UPDATE MEMBER SET Friends = :friends WHERE idmembre = :userid');
		$query->bindParam(':friends',$friends);
		$query->bindParam(':userid',$userid);
		$query->execute();
	}

	public function setFriendRequest($userid,$friend) {
		$query = $this->connexion->prepare('INSERT INTO ASKFRIEND (user, friend) 
			VALUES (:user,:friend)');
		$query->bindParam(':user',$userid);
		$query->bindParam(':friend',$friend);
		$query->execute();
	}

	public function getFriendRequests($userid) {
		$query = $this->connexion->prepare('SELECT user FROM ASKFRIEND WHERE friend = ?');
		$query->execute(array($userid));
		return $query->fetchAll();
	}

	public function getOwnFriendRequests($userid) {
		$query = $this->connexion->prepare('SELECT friend FROM ASKFRIEND WHERE user = ?');
		$query->execute(array($userid));
		return $query->fetchAll();
	}

	public function deleteFriendRequest($userid,$friend) {
		$query = $this->connexion->prepare('DELETE FROM ASKFRIEND WHERE user = ? AND friend = ?');
		$query->execute(array($userid,$friend));
	}

	/*
	
	Private Message-related functions

	*/
	

	public function getConversationsForUser($userid) {
		$query = $this->connexion->prepare('SELECT idconversation FROM PARTICIPENT WHERE idmembre = ?');
		$query->execute(array($userid));
		$donnees = $query->fetchAll();
		return $donnees;
	}

	public function getFullConversationByID($idconversation) {
		$query = $this->connexion->prepare('SELECT * FROM MP WHERE idconversation = ?');
		$query->execute(array($idconversation));
		$donnees = $query->fetchAll();
		return $donnees;
	}

	public function getConversationByID($idconversation) {
		$query = $this->connexion->prepare('SELECT * FROM PARTICIPENT WHERE idconversation = ?');
		$query->execute(array($idconversation));
		$donnees = $query->fetchAll();
		return $donnees;
	}

	public function newMP($userid,$idconversation,$contenump) {
		$query = $this->connexion->prepare('INSERT INTO MP (idconversation, idmembre, contenump, datemp) 
			VALUES (:idconversation,:idmembre,:contenump,:datemp)');
		$query->bindParam(':idconversation',$idconversation);
		$query->bindParam(':idmembre',$userid);
		$query->bindParam(':contenump',$contenump);
		$today = date('Y/m/d H/i/s', time());
		$query->bindParam(':datemp',$today);
		$query->execute();	
	}

	public function getConversationDest($userid, $idconversation) {
		$query = $this->connexion->prepare('SELECT idmembre FROM PARTICIPENT WHERE idconversation = ? AND idmembre <> ?');
		$query->execute(array($idconversation,$userid));
		$donnees = $query->fetch();
		return $donnees;
	}

	public function changeConversationReadStatus($idconversation,$userid,$status) {
		$query = $this->connexion->prepare('UPDATE PARTICIPENT SET markread = :status
			WHERE idconversation = :idconversation AND idmembre = :idmembre');
		$query->bindParam(':idconversation',$idconversation);
		$query->bindParam(':idmembre',$userid);
		$query->bindParam(':status',$status);
		$query->execute();
	}

	public function getConversationReadStatus($idconversation,$idmembre) {
		$query = $this->connexion->prepare('SELECT markread FROM PARTICIPENT WHERE idconversation = ? AND idmembre = ?');
		$query->execute(array($idconversation,$idmembre));
		$donnees = $query->fetch();
		return $donnees[0];
	}

	public function newConversation($titre) {
		$query = $this->connexion->prepare('INSERT INTO CONVERSATIONS (titre) VALUES (:titre)');
		$query->bindParam(':titre',$titre);
		$query->execute();
		return $this->connexion->lastInsertId();
	}

	public function addMPParticipants($idconversation,$userid,$readstatus) {
		$query = $this->connexion->prepare('INSERT INTO PARTICIPENT (idconversation,idmembre,markread) 
			VALUES (:idconversation, :idmembre, :markread)');
		$query->bindParam(':idconversation',$idconversation);
		$query->bindParam(':idmembre',$userid);
		$query->bindParam(':markread',$readstatus);
		$query->execute();
	}

}
?>