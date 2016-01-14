<?php

class Attachment {

	private $url;
	private $summary;
	private $picture;
	private $title;
	private $database;

	function __construct($url) {
		$this->database = new Database;
		$dbattachment = $this->database->getAttachment($url);
		if (!$dbattachment) {
			$attachment = Tools::fetchURL($url);
			$this->url = $attachment['url'];
			$this->summary = $attachment['summary'];
			$this->picture = isset($attachment['open_graph']['og_image']) ? $attachment['open_graph']['og_image'] : NULL;
			$this->title = $attachment['title'];
			$this->database->setAttachment($this->url,$this->summary,$this->title,$this->picture);
		} else {
			$this->url = $dbattachment['url'];
			$this->summary = $dbattachment['summary'];
			$this->picture = $dbattachment['picture'];
			$this->title = $dbattachment['title'];
		}
	}

	public function getUrl() {
		return $this->url;
	}

	public function getSummary() {
		return $this->summary;
	}

	public function getPicture() {
		return $this->picture;
	}

	public function getTitle() {
		return $this->title;
	}
}