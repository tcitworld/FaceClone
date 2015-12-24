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

	function __construct($mail) {
		$this->email = $mail;
		$database = new Database();
		$user = $database->getUser($mail);
		$this->id = $user['idmembre'];
		$this->nom = $user['nom'];
		$this->prenom = $user['prenom'];
		$this->password = $user['password'];
		$this->dateNaissance = $user['dateNaissance'];
		$this->dateInscription = $user['dateInscription'];
		$this->dateLastConnexion = $user['dateLastConnexion'];
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

}

?>