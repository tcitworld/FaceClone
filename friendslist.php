<?php
session_start();
require_once('global.php');


$database = new Database();
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
}


$friends = $user->getFriends() ? $user->getFriends() : array();
$people = $database->getAllPeople($user->getid());

if (isset($_GET['adduser'])) {
	if (!in_array($_GET['adduser'],$friends)) {
		array_push($friends,$_GET['adduser']);
		$database->setFriends($user->getid(),json_encode($friends));
	}
}

if (isset($_GET['removefriend'])) {
		$friends = array_diff($friends,array($_GET['removefriend']));
		$database->setFriends($user->getid(),json_encode($friends));
}

Tools::callTwig('friendslist.twig',array('connected' => Tools::isLogged(), 'user' => $user,
 'friends' => callFriends($database,$user,$friends), 'people' => callAllPeople($database,$user,$people,$friends)));

function callFriends($database,$user,$friends) {

	$friendsinfo = array();
	foreach($friends as $friend) {
		$friendsinfo[]= new User($database->getMailForId($friend)[0]);
	}
	return $friendsinfo;
}

function callAllPeople($database,$user,$people,$friends) {
	$peopleinfo = array();
	foreach ($people as $person) {
		if (!in_array($person['idmembre'],$friends)) {
			$peopleinfo[] = new User($database->getMailForId($person['idmembre'])[0]);
		}
	}
	return $peopleinfo;
}