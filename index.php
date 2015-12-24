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
if(!empty($_SESSION['login'])) {
	$connected = true;
	$user = new User($_SESSION['login']);
	$posts = $database->getPostsForUser($user->getid());
}
if (isset($_POST['login']) and isset($_POST['password'])) {
	$user = $database->login($_POST['login'],$_POST['password']);
	if ($user) {
		$connected = true;
	}
}

if(isset($_GET['logout'])) {
	$_SESSION = array();
	session_destroy();
	unset($_SESSION);
	header('faceclone/');
}

if (isset($_POST['msg']) && $connected) {
	$database->newMessage($user->getid(),$_POST['msg']);
	header('faceclone/');
}

callTwig('index.twig',array('connected' => $connected, 'posts' => $posts));

?>


