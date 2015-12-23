<?php
session_start();
require_once('database.php');
require_once('tools.php');
require_once 'vendor/autoload.php';
$database = new Database;
$connected = false;
if (isset($_POST['login']) and isset($_POST['password'])) {
	$connected = $database->login($_POST['login'],$_POST['password']);
}
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates/'); // Dossier contenant les templates

$twig = new Twig_Environment($loader, array('cache' => false));

echo $twig->render('index.twig', array('connected' => $connected));

?>


