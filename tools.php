<?php

require_once 'vendor/autoload.php';
use Aptoma\Twig\Extension\MarkdownExtension;
use Aptoma\Twig\Extension\MarkdownEngine;

class Tools {

	public static function valider($texte) {
		if (preg_match('/^[A-Za-z0-9_]+$/',$texte)) {
			return $texte;
		} else {
			die('mauvais logins');
		}
	}

	public static function validateDate($date, $format = 'd/m/Y')
	{
	    $d = DateTime::createFromFormat($format, $date);
	    return $d && $d->format($format) == $date;
	}

	public static function callTwig($file,$vars) {

		$engine = new MarkdownEngine\MichelfMarkdownEngine();


		$loader = new Twig_Loader_Filesystem(__DIR__ . '/templates/'); // Dossier contenant les templates

		$twig = new Twig_Environment($loader, array('cache' => false,'debug' => true));
		$twig->addExtension(new Twig_Extension_Debug());
		$twig->addExtension(new MarkdownExtension($engine));
		echo $twig->render($file, $vars);
	}

	public static function isLogged() {
		return isset($_SESSION['login']);
	}

	public static function sortFunction( $a, $b ) {
    	return strtotime($a["datemessage"]) - strtotime($b["datemessage"]);
	}
}