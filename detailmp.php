<?php
session_start();
require_once('global.php');

$database = new Database;
if (Tools::isLogged() && isset($_POST['mpcontenu'])) {
		$conversationid = $_GET['mpid'];
		$user = new User($_SESSION['login']); // this is us

		// create new mp
		$database->newMP($user->getid(),$conversationid,$_POST['mpcontenu']);

		// Set conversation as unread for all recipients
		$recipients = $database->getConversationDest($user->getid(),$conversationid);
		foreach ($recipients as $dest) {
			$database->changeConversationReadStatus($conversationid,$dest[0],'1');
		}
	}

if (Tools::isLogged() && isset($_GET['mpid'])) {
	$user = new User($_SESSION['login']);
	$conversation = $database->getFullConversationByID($_GET['mpid']); // if we are just looking at the conversation, get its posts
	$database->changeConversationReadStatus($_GET['mpid'],$user->getid(),'0'); // make it read
}

Tools::callTwig('detailmp.twig',array('connected' => Tools::isLogged(),'user' => $user,
	'conversation' => getMessages($conversation,$user), 'conversationinfo' => getMessages($conversation,$user)[0]->getConversation()));

function getMessages($conversation,$user) {
	foreach ($conversation as $message) {
		$messagesobj[] = new Message($message,$user);
	}
	return $messagesobj;
}