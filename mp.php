<?php
session_start();
require_once('global.php');
$database = new Database;
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
	$conversations = $database->getConversationsForUser($user->getid());	
}

if (Tools::isLogged() && isset($_POST['dest']) && isset($_POST['message']) && isset($_POST['titre'])) {
	$destinataires = $_POST['dest'];
	$message = $_POST['message'];
	$titre = $_POST['titre'];
	$idconversation = $database->newConversation($titre);
	foreach ($destinataires as $destinataire) {
		$database->addMPParticipants($idconversation,$destinataire,true);
	}
	$database->addMPParticipants($idconversation,$user->getid(),false);
	$database->newMP($user->getid(),$idconversation,$message);

	$conversations = $database->getConversationsForUser($user->getid());

}

Tools::callTwig('mp.twig',array('connected' => Tools::isLogged(),'user' => $user,
	'conversations' => getConversations($conversations,$user)));

function getConversations($conversations,$user) {
	foreach ($conversations as $conversation) {
		$mpsobj[] = new Conversation($conversation['idconversation'],$user);
	}
	return $mpsobj;
}