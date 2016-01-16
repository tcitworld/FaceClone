<?php
session_start();
require_once(dirname(__FILE__) . '/../global.php');

$database = new Database;
if (Tools::isLogged() && $_GET['idnotif']) {
	$user = new User($_SESSION['login']);
	if ($database->updateNotification($_GET['idnotif'],0)) {
		echo "ok";
	}
}