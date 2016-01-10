<?php

class Comment{
	private $idcomment;
	private $membre;
	private $database;
	private $contenucomment;

	function __construct($comment) {
		$this->idcomment = $comment['idcomment'];
		$this->database = new Database;
		$this->membre = new User($this->database->getMailForId($comment['idmembre'])[0]);
		$this->contenucomment = $comment['contenucomment'];
	}

	/*
	
	function getIdComment() : Retourne l'id d'un commentaire
	@return int

	*/

	public function getIdComment() {
		return $this->idcomment;
	}

	/*
	
	function getMembre() : Retourne un objet User sur l'auteur du commentaire
	@return User

	*/

	public function getMembre() {
		return $this->membre;
	}

	/*
	
	function getContenu() : Retourne le contenu du commentaire
	@return str

	*/

	public function getContenu() {
		return $this->contenucomment;
	}
 
}