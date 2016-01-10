<?php
session_start();
date_default_timezone_set('Europe/Paris');

// get all included files
require_once('global.php');


$database = new Database;

// if the user reconnects, welcome him !
$newsession = false;

// empty array for our posts
$posts = array();

/*

If user is connected, we can start working : creating a User object and getting his posts

*/
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
	$posts = getPosts($database,$user);
	
}

/*

If user tries to login

*/

if (isset($_POST['login']) and isset($_POST['password'])) {
	$user = $database->login($_POST['login'],$_POST['password']); // compare his password.
	if ($user) { // if $user != false : user has correct password
		$newsession = true;
		$_SESSION['login'] = $_POST['login']; // create session for this user
		$posts = getPosts($database,$user); // get his posts
	}
}

/*

If user tries to logout, destroy his session.

*/

if(isset($_GET['logout']) || empty($_SESSION['login'])) {
	$_SESSION = array();
	session_destroy();
	unset($_SESSION);
	$user = NULL;

}

/*

	Posting a new message.

*/

if (isset($_POST['msg']) && Tools::isLogged()) {
	$attachment = new Attachment(Tools::getURL($_POST['msg'])); // Trying to get information about an eventual url in text
	$database->newMessage($user->getid(),$_POST['msg'],$attachment->getUrl()); // saving new message
	$posts = getPosts($database,$user); // getting his posts
}

/*

	Rendering the template with its variables

*/

Tools::callTwig('index.twig',array('connected' => Tools::isLogged(),'user' => $user ,
	'posts' => $posts,'newsession' => $newsession));

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

