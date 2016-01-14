<?php

class User {

	private $id;
    private $nom;
    private $prenom;
    private $password;
    private $email;
    private $dateNaissance;
    private $dateInscription;
    private $dateLastConnexion; 
    private $friends;
    private $database;
    private $conversations;

	function __construct($mail) {
		$this->email = $mail;
		$this->database = new Database();
		$user = $this->database->getUser($mail);
		$this->id = $user['idmembre'];
		$this->nom = $user['nom'];
		$this->prenom = $user['prenom'];
		$this->password = $user['password'];
		$this->dateNaissance = $user['dateNaissance'];
		$this->dateInscription = $user['dateInscription'];
		$this->dateLastConnexion = $user['dateLastConnexion'];
		$this->friends = json_decode($user['Friends']); // decode the list of friends in database to an array
		$this->conversations = $this->database->getConversationsForUser($this->id);
		$this->notifications = $this->database->getNotifications($this->id);
	}

	public function getid() {
		return $this->id;
	}

	public function getMail() {
		return $this->email;
	}

	public function getNom() {
		return $this->nom;
	}

	public function getPrenom() {
		return $this->prenom;
	}

	public function getPassword() {
		return $this->password;
	}

	public function getDateNaissance() {
		return $this->dateNaissance;
	}

	public function getDateInscription() {
		return $this->dateInscription;
	}

	public function getDateLastConnexion() {
		return $this->dateLastConnexion;
	}

	public function getFriends() {
		return ($this->friends ? $this->friends : false); // if no friends, return false
	}

	public function getNumberOfFriends() {
		return count($this->getFriends());
	}

	public function getNumberOfPosts() {
		return count($this->database->getPostsForUser($this->id));
	}

	public function getGravatar() {
		return Tools::get_gravatar($this->email); // get gravatar for a given email
	}

	public function getConversations() { // return proper Conversation objects
		$conv = $this->conversations;
		$convobj = array();
		foreach ($conv as $conversation) {
			$convobj[] = new Conversation($conversation['idconversation'],$this);
		}
		return $convobj;
	}

	public function getUnreadConversations() { 
		$conv = $this->getConversations();
		$conv2 = array();
		foreach ($conv as $con) {
			if ($con->getReadStatus() == '1') { // return only unread conversations
				$conv2[]=$con;
			}
		}
		return $conv2;
	}

	public function getNotifications() {
		$notifobj = array();
		foreach ($this->notifications as $notif) {
			$notifobj[] = new Notification($notif['idnotif'],$notif['idmembre'],$notif['action'],$notif['autremembre'],$notif['readstatus'],$notif['datenotif']);
		}
		return $notifobj;
	}

	public function getUnreadNotifications() { 
		$notifs = $this->getNotifications();
		$notifs2 = array();
		foreach ($notifs as $notif) {
			if ($notif->getReadStatus() == '1') { // return only unread conversations
				$notifs2[]=$notif;
			}
		}
		return $notifs2;
	}

}

?>