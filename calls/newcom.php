<?php
session_start();
require_once('../global.php');

$database = new Database;
if (Tools::isLogged() && isset($_POST['idpost']) && isset($_POST['comment'])) {
	$user = new User($_SESSION['login']);
	if ($database->addComment($_POST['idpost'],$user->getid(),$_POST['comment'])) {
		echo "ok";
	}
}