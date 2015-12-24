<?php
session_start();
require_once('database.php');
require_once('tools.php');
require_once 'vendor/autoload.php';
require_once('User.php');

$database = new Database();
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
}

$friends = explode(',', $database->getFriendsForUser($user->getid())[0]);

$friendsinfo = array();
foreach($friends as $friend) {
	array_push($friendsinfo, new User($database->getMailForId($friend)[0]));
}

$people = $database->getAllPeople($user->getid());

if (isset($_GET['adduser'])) {
	if (!in_array($_GET['adduser'],$friends)) {
		array_push($friends,$_GET['adduser']);
		$database->setFriends($user->getid(),implode(',', $friends));
	}
}

Tools::callTwig('friendslist.twig',array('connected' => Tools::isLogged(), 'friends' => $friendsinfo, 'people' => $people));
