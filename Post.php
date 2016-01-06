<?php

class Post {

	private $idpost;
	private $idmembre;
	private $contenupost;
	private $datemessage;
	private $database;
	private $user;
	private $likes;
	private $comments;
	private $attachment;

	function __construct($idpost) {
		$this->idpost = $idpost;
		$this->database = new Database();
		$post = $this->database->getPost($idpost);
		$this->idmembre = $post['idmembre'];
		$this->user = new User($this->database->getMailForId($this->idmembre)[0]);
		$this->contenupost = $post['contenupost'];
		$this->datemessage = $post['datemessage'];
		$this->attachment = new Attachment($post['attachment']);
		$peopleliking = $this->database->getLikes($this->idpost);
		foreach ($peopleliking as $people) {
			$this->likes[] = new User($this->database->getMailForId($people['idmembre'])[0]);
		}
		$comments = $this->database->getComments($this->idpost);
		foreach ($comments as $comment) {
			$this->comments[] = new Comment($comment);
		}
	}

	public function getUser() {
		return $this->user;
	}

	public function getContenuPost() {
		return $this->contenupost;
	}

	public function getDateMessage() {
		return $this->datemessage;
	}

	public function getid() {
		return $this->idpost;
	}

	public function getLikes() {
		return $this->likes;
	}

	public function getComments() {
		return $this->comments;
	}

	public function getAttachment() {
		return $this->attachment;
	}
}