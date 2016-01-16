<?php

class Notification {
	private $idnotification;
	private $membre;
	private $action;
	private $autremembre;
	private $readstatus;
	private $datenotif;
	private $database;

	function __construct($idnotification,$idmembre,$action,$autremembre,$readstatus,$datenotif) {
		$this->database = new Database;
		$this->idnotification = $idnotification;
		$this->membre = new User($this->database->getMailForId($idmembre)[0]);
		$this->action = $action;
		if(isset($autremembre)){
			$this->autremembre = new User($this->database->getMailForId($autremembre)[0]);
		} else {
			$this->autremembre = NULL;
		}
		$this->readstatus = $readstatus;
		$this->datenotif = $datenotif;
	}

	/*
	
	function getIdComment() : Retourne l'id de la notification
	@return int

	*/

	public function getIdNotification() {
		return $this->idnotification;
	}

	/*
	
	function getMembre() : Retourne un objet User sur l'auteur du commentaire
	@return User

	*/

	public function getMembre() {
		return $this->membre;
	}

	/*
	
	function getAction() : Retourne le contenu de la notification
	@return str

	*/

	private function getAction() {
		return $this->action;
	}

	/*

	function getOtherMember : retourne un autre membre en jeu dans la notification (s'il y a lieu)
	@return User

	*/

	public function getOtherMember() {
		return $this->autremembre;
	}

	public function getFullNotification() {
		switch ($this->getAction()) {
			case 'poke':
				return array('content' => $this->getOtherMember()->getPrenom() . ' ' . $this->getOtherMember()->getNom() . ' vous a envoyé un poke', 'link' => 'profile.php?id=' . $this->getOtherMember()->getId());
				break;
			case 'friendAsk':
				return array('content' => $this->getOtherMember()->getPrenom() . ' ' . $this->getOtherMember()->getNom() . ' vous a demandé en ami', 'link' => 'profile.php?id=' . $this->getOtherMember()->getId());
					break;
			case 'friendReqAccept':
				return array('content' => $this->getOtherMember()->getPrenom() . ' ' . $this->getOtherMember()->getNom() . ' a accepté votre demande d\'ami', 'link' => 'profile.php?id=' . $this->getOtherMember()->getId());
			
			default:
				# code...
				break;
		}
	}

	/*

	function getDateNotif : retourne la date de la notification
	@return Date

	*/

	public function getDateNotif() {
		return $this->datenotif;
	}

	public function getReadStatus() {
		return $this->readstatus;
	}

	public function markAsRead() {
		$this->database->updateNotification($this->notificationid);
	}
 
}