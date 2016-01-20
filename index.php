<?php
session_start();
date_default_timezone_set('Europe/Paris');

// get all included files
require_once('global.php');


$database = new Database;

// if the user reconnects, welcome him !
$newsession = false;

$callLogin = false;

// empty array for our posts
$posts = array();


/*

	Pagination

*/

$page = 1;
if(!empty($_GET['page'])) {
    $page = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT);
    if(false === $page) {
        $page = 1;
    }
}

$itemsPerPage = 5;

$offset = ($page - 1) * $itemsPerPage;

/*

If user is connected, we can start working : creating a User object and getting his posts

*/
if (Tools::isLogged()) {
	$user = new User($_SESSION['login']);
	$posts2 = getPosts($database,$user,$offset,$itemsPerPage);
	$posts = $posts2[0];
	$numArticles = $posts2[1];
	
} else {
	$callLogin = true;
}

/*

If user tries to login

*/

if (isset($_POST['login']) and isset($_POST['password'])) {
	$user = $database->login($_POST['login'],$_POST['password']); // compare his password.
	if ($user) { // if $user != false : user has correct password
		$newsession = true;
		$callLogin = false;
		$_SESSION['login'] = $_POST['login']; // create session for this user
		$posts2 = getPosts($database,$user,$offset,$itemsPerPage); // get his posts
		$posts = $posts2[0];
		$numArticles = $posts2[1];
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
	$callLogin = true;
}

/*

	Posting a new message.

*/

if (isset($_POST['msg']) && Tools::isLogged()) {
	$attachment = new Attachment(Tools::getURL($_POST['msg'])); // Trying to get information about an eventual url in text
	$database->newMessage($user->getid(),$_POST['msg'],$attachment->getUrl()); // saving new message
	$posts2 = getPosts($database,$user,$offset,$itemsPerPage); // getting his posts
	$posts = $posts2[0];
	$numArticles = $posts2[1];
}

/*

	Rendering the template with its variables

*/

if ($callLogin) {
	Tools::callTwig('login.twig',array());
} else {
	if (Tools::isLogged()) {
		Tools::callTwig('index.twig',array('connected' => Tools::isLogged(),'user' => $user ,'posts' => $posts,'newsession' => $newsession,'page' => $page,'pagecount' => (int)ceil($numArticles / $itemsPerPage)));
	}
}
/*

getPosts : Get array of Post objects for the current user
@param Database $database
@param User $user
@return [Post]

*/

function getPosts($database,$user,$offset,$itemsPerPage) {
	$posts = $database->getPostsForUser($user->getid(),$offset,$itemsPerPage);
	$numArticles = $database->getNumberOfPostsForUser($user->getid());
	$userfriends = $user->getFriends();
	if ($userfriends) {
		foreach ($userfriends as $friend) {
			$posts = array_merge($posts,$database->getPostsForUser($friend,$offset,$itemsPerPage));
			$numArticles += $database->getNumberOfPostsForUser($user->getid());
		}
	}
	$postobj = array();
	foreach ($posts as $post) {
		$postobj[] = new Post($post['idpost']);
	}
	usort($postobj, "Tools::sortFunction");
	return array($postobj,$numArticles);
}

