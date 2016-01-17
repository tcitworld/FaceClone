<?php
session_start();
require_once('global.php');


$database = new Database();
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
}

if (isset($_GET['idpost'])) {
	$post = new Post($_GET['idpost']);
}

Tools::callTwig('post.twig',array('connected' => Tools::isLogged(), 'user' => $user, 'post' => $post));