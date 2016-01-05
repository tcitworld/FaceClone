<?php
session_start();
require_once('global.php');

$database = new Database;
if (Tools::isLogged() && isset($_POST['mpcontenu'])) {
		$conversationid = $_GET['mpid'];
		$user = new User($_SESSION['login']);
		$database->newMP($user->getid(),$conversationid,$_POST['mpcontenu']);
		$dest = new User($database->getMailForId(
			$database->getConversationDest($user->getid(),$conversationid)[0])[0]);
		$database->changeConversationReadStatus($conversationid,$dest->getid(),'1');
		// mark the conversation from the other user as unread
	}

if (Tools::isLogged() && isset($_GET['mpid'])) {
	$user = new User($_SESSION['login']);
	$conversation = $database->getFullConversationByID($_GET['mpid']);
	$database->changeConversationReadStatus($_GET['mpid'],$user->getid(),'0');
}

Tools::callTwig('detailmp.twig',array('connected' => Tools::isLogged(),'user' => $user,
	'conversation' => getMessages($conversation,$user), 'conversationinfo' => getMessages($conversation,$user)[0]->getConversation()));

function getMessages($conversation,$user) {
	foreach ($conversation as $message) {
		$messagesobj[] = new Message($message,$user);
	}
	return $messagesobj;
}