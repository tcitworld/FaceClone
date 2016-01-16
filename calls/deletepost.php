<?php
session_start();
require_once('../global.php');

$database = new Database;
if (Tools::isLogged() && isset($_GET['idpost'])) {
	if ($database->deletePost($_GET['idpost'])) {
		echo "ok";
	}
}