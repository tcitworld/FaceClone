<?php
session_start();
require_once('global.php');


$database = new Database();
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
}


$friends = $user->getFriends() ? $user->getFriends() : array();
$people = $database->getAllPeople($user->getid());
$message = NULL;

if (isset($_GET['adduser'])) {
	if (!in_array($_GET['adduser'],$friends)) {
		$database->setFriendRequest($user->getid(),$_GET['adduser']);
		$message = "L'invitation a bien été envoyée";
	}
}

/*
Launch a friend request : check that it is not already a friend

*/

if (isset($_GET['removefriend'])) {
		$friends = array_diff($friends,array($_GET['removefriend']));
		$database->setFriends($user->getid(),json_encode($friends));
		$message = "L'utilisateur a été supprimé de vos amis";

		$friend = new User($database->getMailForId($_GET['removefriend'])[0]);
		$friendsOfFriend = $friend->getFriends() ? $friend->getFriends() : array();
		$friendsOfFriend = array_diff($friendsOfFriend,array($user->getid()));
}

if (isset($_GET['accept'])) {
	$database->deleteFriendRequest($_GET['accept'],$user->getid());
	if (!in_array($_GET['accept'],$friends)) {
		array_push($friends,$_GET['accept']);
		$database->setFriends($user->getid(),json_encode($friends));
	}
	$friend = new User($database->getMailForId($_GET['accept'])[0]);
	$friendsOfFriend = $friend->getFriends() ? $friend->getFriends() : array();
	if (!in_array($user->getid(),$friendsOfFriend)) {
		array_push($friendsOfFriend,$user->getid());
		$database->setFriends($friend->getid(),json_encode($friendsOfFriend));
	}
	$message = "Vous êtes désormais ami avec cette personne";
}

Tools::callTwig('friendslist.twig',array('connected' => Tools::isLogged(), 'user' => $user,
 'friends' => callFriends($database,$user,$friends), 'people' => callAllPeople($database,$user,$people,$friends),
 'asks' => getFriendshipDemands($user, $database)));

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

function getFriendshipDemands($user,$database) {
	$friendsAsking = $database->getFriendRequests($user->getid());
	$asks = array();
	foreach ($friendsAsking as $asking) {
		$asks[] = new Demande($asking['user'],$user->getid());
	}
	return $asks;
}