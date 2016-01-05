<?php
session_start();
require_once('../global.php');

$database = new Database;
if (Tools::isLogged() && isset($_GET['idpost'])) {
	$user = new User($_SESSION['login']);
	if ($database->likePost($_GET['idpost'],$user->getid())) {
		echo "ok";
	}
}