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

	public function getIdComment() {
		return $this->idcomment;
	}

	public function getMembre() {
		return $this->membre;
	}

	public function getContenu() {
		return $this->contenucomment;
	}
 
}