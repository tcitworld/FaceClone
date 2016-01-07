<?php

class Demande {
	private $user;
	private $friend;
	private $database;

	function __construct($user,$friend) {
		$this->database = new Database;
		$this->user = new User($this->database->getMailForId($user)[0]);
		$this->friend = new User($this->database->getMailForId($friend)[0]);
	}

	public function getUser() {
		return $this->user;
	}

	public function getFriend() {
		return $this->friend;
	}
}