<?php

class Conversation{
	private $idconversation;
	private $membres;
	private $database;
	private $readstatus;
	private $lastmessage;

	function __construct($conversation,$user) {
		$this->idconversation = $conversation;
		$this->database = new Database;
		$members = $this->database->getConversationByID($conversation);
		foreach ($members as $member) {
			if ($member['idmembre'] != $user->getid()) {
				$this->membres[] = new User($this->database->getMailForId($member['idmembre'])[0]);
			}
		}
		$this->readstatus = $this->database->getConversationReadStatus($this->idconversation,$user->getid());
		$convmessages = $this->database->getFullConversationByID($this->idconversation);
		$this->lastmessage = end($convmessages);
	}

	public function getIDConversation() {
		return $this->idconversation;
	}

	public function getMembres() {
		return $this->membres;
	}

	public function getReadStatus() {
		return $this->readstatus;
	}

	public function getLastMessage() {
		return $this->lastmessage;
	}
 
}