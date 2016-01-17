<?php
session_start();
require_once('../global.php');

$database = new Database;
if (Tools::isLogged() && isset($_GET['idpost'])) {
	$user = new User($_SESSION['login']);
	$post = new Post($_GET['idpost']);
	$detected = false;
	$i = 0;
	while (!$detected && $i < count($database->getLikes($_GET['idpost']))) { // if the user that wants to like the post already has
		if ($database->getLikes($_GET['idpost'])[$i]['idmembre'] == $user->getid()) {
			$detected = true;
		}
		$i++;
	}
	if (!$detected) {
		$database->likePost($_GET['idpost'],$user->getid());
		$notifAction = 'like';
		if ($post->getUser()->getid() != $user->getid()) {
			$database->setNotification($post->getUser()->getid(),$notifAction,$user->getid(),$post->getid());
		}
	} else {
		echo 'no';
	}
}