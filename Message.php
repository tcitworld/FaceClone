<?php

class Message {
	private $idmp;
	private $conversation;
	private $membre;
	private $database;
	private $contenump;
	private $datemp;

	function __construct($message,$user) {
		$this->idmp = $message['idmp'];
		$this->database = new Database;
		$this->conversation = new Conversation($message['idconversation'],$user);
		$this->membre = new User($this->database->getMailForId($message['idmembre'])[0]);
		$this->contenump = $message['contenump'];
		$this->datemp = $message['datemp'];
	}

	public function getMembre() {
		return $this->membre;
	}

	public function getContenu() {
		return $this->contenump;
	}

	public function getDateMP() {
		return $this->datemp;
	}

	public function getConversation() {
		return $this->conversation;
	}
}