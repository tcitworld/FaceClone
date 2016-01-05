<?php
session_start();
require_once('global.php');

$database = new Database;
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
	$posts = $database->getPostsForUser($user->getid());
	
}

if (isset($_GET['id'])) {
	$user = new User($database->getMailForId($_GET['id'])[0]);
	$posts = $database->getPostsForUser($user->getid());
}

Tools::callTwig('profile.twig',array('connected' => Tools::isLogged(),'user' => $user,'posts' => $posts));
