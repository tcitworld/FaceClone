<?php
session_start();
date_default_timezone_set('Europe/Paris');
require_once('global.php');

$database = new Database;
$connected = false;
$newsession = false;
$posts = array();
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
	$posts = getPosts($database,$user);
	
}
if (isset($_POST['login']) and isset($_POST['password'])) {
	$user = $database->login($_POST['login'],$_POST['password']);
	$newsession = true;
	if ($user) {
		$posts = getPosts($database,$user);
	}
}

if(isset($_GET['logout']) || empty($_SESSION['login'])) {
	$_SESSION = array();
	session_destroy();
	unset($_SESSION);
	$user = NULL;

}

if (isset($_POST['msg']) && Tools::isLogged()) {
	$attachment = new Attachment(Tools::getURL($_POST['msg']));
	$database->newMessage($user->getid(),$_POST['msg'],$attachment->getUrl());
	$posts = getPosts($database,$user);
}

Tools::callTwig('index.twig',array('connected' => Tools::isLogged(),'user' => $user ,
	'posts' => $posts,'newsession' => $newsession));

function getPosts($database,$user) {
	$posts = $database->getPostsForUser($user->getid());
	$userfriends = $user->getFriends();
	if ($userfriends) {
		foreach ($userfriends as $friend) {
			$posts = array_merge($posts,$database->getPostsForUser($friend));
		}
	}
	$postobj = array();
	foreach ($posts as $post) {
		$postobj[] = new Post($post['idpost']);
	}
	usort($postobj, "Tools::sortFunction");
	return $postobj;
}

