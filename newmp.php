<?php
session_start();
require_once('global.php');

$database = new Database;
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
}

$friendj = $user->getFriends();
$friends = array();
foreach ($friendj as $friend) {
	$friends[] = new User($database->getMailForId($friend)[0]);
}

Tools::callTwig('newmp.twig',array('connected' => Tools::isLogged(),'user' => $user,'friends' => $friends));