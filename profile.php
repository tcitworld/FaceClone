<?php
session_start();
require_once('global.php');

$database = new Database;
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
	$userp = $user; // we are looking at our own profile
	getPosts($database,$user);
	
}

if (isset($_GET['id'])) { // we are looking at someone's profile
	$userp = new User($database->getMailForId($_GET['id'])[0]);
	getPosts($database,$userp);
}

Tools::callTwig('profile.twig',array('connected' => Tools::isLogged(),'user' => $user, 'userp' => $userp, 'posts' => getPosts($database,$userp)));


/*

getPosts : Get array of Post objects for the current user
@param Database $database
@param User $user
@return [Post]

*/

function getPosts($database,$user) {
	$posts = $database->getPostsForUser($user->getid());
	$userfriends = $user->getFriends();
	if ($userfriends) {
		foreach ($userfriends as $friend) {
			$posts = array_merge($posts,$database->getPostsForUser($friend));
		}
	}
	$postobj = array();
	foreach ($posts as $post) {
		$postobj[] = new Post($post['idpost']);
	}
	usort($postobj, "Tools::sortFunction");
	return $postobj;
}