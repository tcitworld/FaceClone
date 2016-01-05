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
		$this->friends = json_decode($user['Friends']);
		$this->conversations = $this->database->getConversationsForUser($this->id);
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
		return ($this->friends ? $this->friends : false);
	}

	public function getNumberOfFriends() {
		return count($this->getFriends());
	}

	public function getNumberOfPosts() {
		return count($this->database->getPostsForUser($this->id));
	}

	public function getGravatar() {
		return Tools::get_gravatar($this->email);
	}

	public function getConversations() {
		$conv = $this->conversations;
		foreach ($conv as $conversation) {
			$convobj[] = new Conversation($conversation['idconversation'],$this);
		}
		return $convobj;
	}

	public function getUnreadConversations() {
		$conv = $this->getConversations();
		$conv2 = array();
		foreach ($conv as $con) {
			if ($con->getReadStatus() == '1') {
				$conv2[]=$con;
			}
		}
		return $conv2;
	}

}

?>