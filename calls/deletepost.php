<?php
session_start();
require_once('../global.php');

$database = new Database;
if (Tools::isLogged() && isset($_GET['idpost'])) {
	$user = new User($_SESSION['login']);
	$post = new Post($_GET['idpost']);
	if ($user->getid() == $post->getUser()->getid()) { 
		$database->deletePost($_GET['idpost']);
		echo 'ok';
	}
}