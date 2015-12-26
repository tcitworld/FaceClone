<?php
session_start();
date_default_timezone_set('Europe/Paris');

require_once('database.php');
require_once('tools.php');
require_once 'vendor/autoload.php';
require_once('User.php');
$database = new Database;
$connected = false;
$posts = array();
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
	$posts = getPosts($database,$user);
	
}
if (isset($_POST['login']) and isset($_POST['password'])) {
	$user = $database->login($_POST['login'],$_POST['password']);
	if ($user) {
		$posts = $database->getPostsForUser($user->getid());
	}
}

if(isset($_GET['logout']) || empty($_SESSION['login'])) {
	$_SESSION = array();
	session_destroy();
	unset($_SESSION);

}

if (isset($_POST['msg']) && Tools::isLogged()) {
	$database->newMessage($user->getid(),$_POST['msg']);
	$posts = getPosts($database,$user);
}

Tools::callTwig('index.twig',array('connected' => Tools::isLogged(),'user' => $user ,'posts' => $posts));

function getPosts($database,$user) {
	$posts = $database->getPostsForUser($user->getid());
	$userfriends = $user->getFriends();
	foreach ($userfriends as $friend) {
		$posts = array_merge($posts,$database->getPostsForUser($friend));
	}
	usort($posts, "Tools::sortFunction");
	return $posts;
}

