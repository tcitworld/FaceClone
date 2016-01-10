<?php
session_start();
require_once('global.php');
$database = new Database;
if (Tools::isLogged()) { // if we are logged, just load conversations for our user
	$user = new User($_SESSION['login']);
	$conversations = $database->getConversationsForUser($user->getid());	
}

/*
	
	If we are trying to create a new conversation

*/

if (Tools::isLogged() && isset($_POST['dest']) && isset($_POST['message']) && isset($_POST['titre'])) {
	$destinataires = $_POST['dest'];
	$message = $_POST['message'];
	$titre = $_POST['titre'];
	$idconversation = $database->newConversation($titre); // get the id of the newly created conversation
	foreach ($destinataires as $destinataire) { // for each destinataire, add him to table participants
		$database->addMPParticipants($idconversation,$destinataire,true);
	}
	$database->addMPParticipants($idconversation,$user->getid(),false); // adding ourselves
	$database->newMP($user->getid(),$idconversation,$message); // adding the first message to the list of mps

	$conversations = $database->getConversationsForUser($user->getid()); // finally, get the new list of conversations

}

Tools::callTwig('mp.twig',array('connected' => Tools::isLogged(),'user' => $user,
	'conversations' => getConversations($conversations,$user)));

/*

	get proper conversation objects for our user

*/

function getConversations($conversations,$user) {
	$mpsobj = array();
	foreach ($conversations as $conversation) {
		$mpsobj[] = new Conversation($conversation['idconversation'],$user);
	}
	return $mpsobj;
}