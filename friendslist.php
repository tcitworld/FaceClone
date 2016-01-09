<?php
session_start();
require_once('global.php');


$database = new Database();
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
}

// create empty array if no friends
$friends = $user->getFriends() ? $user->getFriends() : array();

// Message to display
$message = NULL;

/*

	Sending a friendship request

*/

if (isset($_GET['adduser'])) {
	if (!in_array($_GET['adduser'],$friends)) {
		$database->setFriendRequest($user->getid(),$_GET['adduser']);
		$message = "L'invitation a bien été envoyée";
	}
}


/*

Cancel a friendship request

*/

if (isset($_GET['cancel'])) {
	if (!in_array($_GET['cancel'],$friends)) {
		$database->deleteFriendRequest($user->getid(),$_GET['cancel']);
		$message = "L'invitation a bien été annulée";
		getOwnFriendshipDemands($user,$database);
	}
}



/*
Launch a friend request : check that it is not already a friend

*/

/*

Removing a friend from your friends

*/

if (isset($_GET['removefriend'])) {
		$friends = array_diff($friends,array($_GET['removefriend']));
		$database->setFriends($user->getid(),json_encode($friends));
		$message = "L'utilisateur a été supprimé de vos amis";

		$friend = new User($database->getMailForId($_GET['removefriend'])[0]);
		$friendsOfFriend = $friend->getFriends() ? $friend->getFriends() : array();
		$friendsOfFriend = array_diff($friendsOfFriend,array($user->getid()));
}

/*

	Accepting a friend request

*/

if (isset($_GET['accept'])) {
	$database->deleteFriendRequest($_GET['accept'],$user->getid()); // delete friend request
	if (!in_array($_GET['accept'],$friends)) { // check if new person is not already a friend
		array_push($friends,$_GET['accept']); // adding the new person id to the array of friends
		$database->setFriends($user->getid(),json_encode($friends)); // saving the array to database
	}
	$friend = new User($database->getMailForId($_GET['accept'])[0]); // creating user for friend
	$friendsOfFriend = $friend->getFriends() ? $friend->getFriends() : array(); // getting the friends of our new friend
	if (!in_array($user->getid(),$friendsOfFriend)) { // if we are not already in his array of friend ids
		array_push($friendsOfFriend,$user->getid()); // adding ourselves to his array of friends ids
		$database->setFriends($friend->getid(),json_encode($friendsOfFriend)); // saving his friend list
	}
	$message = "Vous êtes désormais ami avec cette personne";
}

Tools::callTwig('friendslist.twig',array('connected' => Tools::isLogged(), 'user' => $user,
 'friends' => callFriends($database,$user,$friends), 'people' => callAllPeople($database,$user,$friends),
 'asks' => getFriendshipDemands($user, $database), 'ownasks' => getOwnFriendshipDemands($user,$database), 'message' => $message));

/*

Function callFriends : return list of User objects for current user's friends (i.e : convert ints into objects)
@param $database Database object
@param $user current user
@param $friends array of friends id (yup, bad, handled with json)
@return [User]

*/

function callFriends($database,$user,$friends) {

	$friendsinfo = array();
	foreach($friends as $friend) {
		$friendsinfo[]= new User($database->getMailForId($friend)[0]);
	}
	return $friendsinfo;
}

/*

Function callAllPeople : return list of all users registered on the server
@param $database Database object
@param $user current user
@param $friends array of friends id (yup, bad, handled with json)
@return [User]

*/

function callAllPeople($database,$user,$friends) {
	$people = $database->getAllPeople($user->getid());
	$peopleinfo = array();
	foreach ($people as $person) {
		if (!in_array($person['idmembre'],$friends)) {
			$peopleinfo[] = new User($database->getMailForId($person['idmembre'])[0]);
		}
	}
	return $peopleinfo;
}

/*

Function getFriendshipDemands : get friendship requests sent to an user
@param $user current user
@param $database Database object
@return [Demande]

*/

function getFriendshipDemands($user,$database) {
	$friendsAsking = $database->getFriendRequests($user->getid());
	$asks = array();
	foreach ($friendsAsking as $asking) {
		$asks[] = new Demande($asking['user'],$user->getid());
	}
	return $asks;
}

/*

Function getOwnFriendshipDemands : get friendship requests sent by current user
@param $user current user
@param $database Database object
@return [Demande]

*/

function getOwnFriendshipDemands($user,$database) {
	$friends2Ask = $database->getOwnFriendRequests($user->getid());
	$asks = array();
	foreach ($friends2Ask as $asking) {
		$asks[] = new Demande($user->getid(),$asking['friend']);
	}
	return $asks;
}
