<?php

require_once 'vendor/autoload.php';


function valider ($texte) {
	if (preg_match('/^[A-Za-z0-9_]+$/',$texte)) {
		return $texte;
	} else {
		die('mauvais logins');
	}
}

function validateDate($date, $format = 'd/m/Y')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function callTwig($file,$vars) {
$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates/'); // Dossier contenant les templates

$twig = new Twig_Environment($loader, array('cache' => false,'debug' => true));
$twig->addExtension(new Twig_Extension_Debug());
echo $twig->render($file, $vars);
}
?>