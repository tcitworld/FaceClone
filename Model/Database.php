<?php
require_once(dirname(__FILE__) . '/../global.php');

date_default_timezone_set('Europe/Paris');


// pour oracle: $dsn="oci:dbname=//serveur:1521/base
//$dsn="sqlite:/tmp/base.sqlite"

class Database {

	var $connexion;

	function __construct() {
		try {
			require(dirname(__FILE__) . "/../connect_db.php");
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
		$birthtime = strtotime($birth); // date au bon format
		$birthdate = date('Y-m-d',$birthtime);
		$query->bindParam(':birth',$birthdate);
		$query->bindParam(':password',$password);
		$today = date("Y/m/d"); // date du jour
		$query->bindParam(':dateInscription',$today);
		$query->execute();
	}


	/*
	
	function userUniq : return if user with this mail already exists in database
	@param str $mail
	@return bool

	*/

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
		$query = $this->connexion->prepare('SELECT MEMBER.idmembre FROM MEMBER WHERE idmembre <> ?');
		$query->execute(array($userid));
		return $query->fetchAll();
	}

	/*

	function getMailForId : Return mail of user for a given user id.
	@param int $userid
	@return str

	*/


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

	public function likePost($idpost,$userid) {
		$query = $this->connexion->prepare('INSERT INTO LIKES (idpost,idmembre) 
			VALUES (:idpost, :idmembre)');
		$query->bindParam(':idpost',$idpost);
		$query->bindParam(':idmembre',$userid);
		$query->execute();
	}

	/*
	
	function getLikes : return array of people id who like a given post
	@param int $idpost
	@return [int]

	*/

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

	public function addComment($idpost,$idmembre,$contenucom) {
		$query = $this->connexion->prepare('INSERT INTO COMMENT 
			(idmembre,idpost,contenucomment,datecommentaire) 
			VALUES (:idmembre, :idpost, :contenucomment, :datecommentaire)');
		$query->bindParam(':idmembre',$idmembre);
		$query->bindParam(':idpost',$idpost);
		$query->bindParam(':contenucomment',$contenucom);
		$today = date('Y/m/d H/i/s', time());
		$query->bindParam(':datecommentaire',$today);
		$query->execute();
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

	/*

	function setFriends : set friends for a given user
	@param int $userid
	@param json_serialized array $friends	

	*/

	public function setFriends($userid,$friends) {
		$query = $this->connexion->prepare('UPDATE MEMBER SET Friends = :friends WHERE idmembre = :userid');
		$query->bindParam(':friends',$friends);
		$query->bindParam(':userid',$userid);
		$query->execute();
	}

	/*

	function setFriendRequest : send a new friend request from $userid to $friend
	@param int $userid
	@param int $friend

	*/

	public function setFriendRequest($userid,$friend) {
		$query = $this->connexion->prepare('INSERT INTO ASKFRIEND (user, friend) 
			VALUES (:user,:friend)');
		$query->bindParam(':user',$userid);
		$query->bindParam(':friend',$friend);
		$query->execute();
	}

	/*

	function getFriendRequests : get friends requests sent to an user
	@param int $userid

	*/

	public function getFriendRequests($userid) {
		$query = $this->connexion->prepare('SELECT user FROM ASKFRIEND WHERE friend = ?');
		$query->execute(array($userid));
		return $query->fetchAll();
	}

	/*

	function getOwnFriendRequests : get friends requests an user sent
	@param int $userid

	*/

	public function getOwnFriendRequests($userid) {
		$query = $this->connexion->prepare('SELECT friend FROM ASKFRIEND WHERE user = ?');
		$query->execute(array($userid));
		return $query->fetchAll();
	}

	/*

	function deleteFriendRequest : delete a friend request, if it's accepted or canceled
	@param int $userid
	@param int $friend

	*/

	public function deleteFriendRequest($userid,$friend) {
		$query = $this->connexion->prepare('DELETE FROM ASKFRIEND WHERE user = ? AND friend = ?');
		$query->execute(array($userid,$friend));
	}

	/*
	
	Private Message-related functions

	*/

	/*

	function getConversationsForUser : get array for all conversation ids where the user is involved
	@param int $userid
	@return [int]	

	*/
	

	public function getConversationsForUser($userid) {
		$query = $this->connexion->prepare('SELECT idconversation FROM PARTICIPENT WHERE idmembre = ?');
		$query->execute(array($userid));
		$donnees = $query->fetchAll();
		return $donnees;
	}

	/*

	function getFullConversationByID : get all messages for a given conversation
	@param int $idconversation
	@return [mixed]	

	*/

	public function getFullConversationByID($idconversation) {
		$query = $this->connexion->prepare('SELECT * FROM MP WHERE idconversation = ?');
		$query->execute(array($idconversation));
		$donnees = $query->fetchAll();
		return $donnees;
	}

	/*

	function getConversationByID : get array of participant ids for a given conversation id
	@param int $idconversation
	@return [mixed]	

	*/

	public function getConversationByID($idconversation) {
		$query = $this->connexion->prepare('SELECT * FROM PARTICIPENT WHERE idconversation = ?');
		$query->execute(array($idconversation));
		$donnees = $query->fetchAll();
		return $donnees;
	}

	/*

	function newMP : insert a new MP inside a conversation
	@param int $userid
	@param int $idconversation
	@param str $contenump
	
	*/

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

	/*

	function getConversationDest : get recipients for a given conversation
	@param int $idconversation
	@param int $userid
	@return [int]	

	*/

	public function getConversationDest($userid, $idconversation) {
		$query = $this->connexion->prepare('SELECT idmembre FROM PARTICIPENT WHERE idconversation = ? AND idmembre <> ?');
		$query->execute(array($idconversation,$userid));
		$donnees = $query->fetchAll();
		return $donnees;
	}

	/*

	function changeConversationReadStatus : Change read status for a given user for a given conversation
	@param int $idconversation
	@param int $userid
	@param bool|int $status

	*/

	public function changeConversationReadStatus($idconversation,$userid,$status) {
		$query = $this->connexion->prepare('UPDATE PARTICIPENT SET markread = :status
			WHERE idconversation = :idconversation AND idmembre = :idmembre');
		$query->bindParam(':idconversation',$idconversation);
		$query->bindParam(':idmembre',$userid);
		$query->bindParam(':status',$status);
		$query->execute();
	}

	/*

	function getConversationReadStatus : Get read status for a given user for a given conversation
	@param int $idconversation
	@param int $userid
	@return bool|int

	*/

	public function getConversationReadStatus($idconversation,$idmembre) {
		$query = $this->connexion->prepare('SELECT markread FROM PARTICIPENT WHERE idconversation = ? AND idmembre = ?');
		$query->execute(array($idconversation,$idmembre));
		$donnees = $query->fetch();
		return $donnees[0];
	}

	/*

	function newConversation : create a new conversation and return its id
	@param str $titre le titre de la conversation 
	@return id

	*/

	public function newConversation($titre = NULL) {
		$query = $this->connexion->prepare('INSERT INTO CONVERSATIONS (titre) VALUES (:titre)');
		$query->bindParam(':titre',$titre);
		$query->execute();
		return $this->connexion->lastInsertId();
	}

	/*

	function addMPParticipants : Adding participants to a conversation
	@param int $idconversation
	@param int $userid
	@param bool|int $readstatus

	*/

	public function addMPParticipants($idconversation,$userid,$readstatus) {
		$query = $this->connexion->prepare('INSERT INTO PARTICIPENT (idconversation,idmembre,markread) 
			VALUES (:idconversation, :idmembre, :markread)');
		$query->bindParam(':idconversation',$idconversation);
		$query->bindParam(':idmembre',$userid);
		$query->bindParam(':markread',$readstatus);
		$query->execute();
	}

	/*
	
	Notifications-related function

	*/

	/*

	function getNotifications : get notifications for an user
	@param int $userid
	@return [mixed]

	*/

	public function getNotifications($userid) {
		$query = $this->connexion->prepare('SELECT * FROM NOTIFICATIONS WHERE idmembre = ?');
		$query->execute(array($userid));
		$donnees = $query->fetchAll();
		return $donnees;
	}

	/*

	function getNotifications : set a new notification
	@param int $userid
	@param str $action
	@param int $autremembre

	*/

	public function setNotification($userid,$action,$autremembre = NULL, $post= NULL) {
		$query = $this->connexion->prepare('INSERT INTO NOTIFICATIONS (idmembre,action,autremembre,idpost,readstatus,datenotif)
			VALUES (:idmembre,:action,:autremembre,:idpost,:readstatus,:datenotif)');
		$query->bindParam(':idmembre',$userid);
		$query->bindParam(':action',$action);
		$query->bindParam(':autremembre',$autremembre);
		$query->bindParam(':idpost',$post);
		$query->bindValue(':readstatus',true);
		$today = date('Y/m/d H/i/s', time());
		$query->bindParam(':datenotif',$today);
		$query->execute();
	}

	/*

	function updateNotification : set notification as read or unread
	@param int $notificationid
	@param int|bool $notificationStatus

	*/

	public function updateNotification($notificationid,$notificationStatus) {
		$query = $this->connexion->prepare('UPDATE NOTIFICATIONS SET readstatus = :status
			WHERE idnotif = :idnotif');
		$query->bindParam(':idnotif',$notificationid);
		$query->bindParam(':status',$notificationStatus);
		$query->execute();
	}

	/*
	
	function markAllNotificationsAsRead : set all notifications as read for an user
	@param int userid

	*/

	public function markAllNotificationsAsRead($userid) {
		$query = $this->connexion->prepare('UPDATE NOTIFICATIONS SET readstatus = 0
			WHERE idmembre = :idmembre');
		$query->bindParam(':idmembre',$userid);
		$query->execute();
	}

}
?>