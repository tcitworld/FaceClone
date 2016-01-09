<?php
session_start();
require_once('global.php');

$database = new Database;
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
	$userp = $user;
	$posts = $database->getPostsForUser($user->getid());
	
}

if (isset($_GET['id'])) {
	$userp = new User($database->getMailForId($_GET['id'])[0]);
	$posts = $database->getPostsForUser($user->getid());
}

Tools::callTwig('profile.twig',array('connected' => Tools::isLogged(),'user' => $user, 'userp' => $userp, 'posts' => $posts));
