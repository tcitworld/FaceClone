<?php

require_once('global.php');
use Aptoma\Twig\Extension\MarkdownExtension;
use Aptoma\Twig\Extension\MarkdownEngine;
use Graby\Graby;


class Tools {

	/*
	
	TODO : Remove or change, because it doesn't accept accents

	*/

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

	/*
		Handles the Template Engine calls
	*/

	public static function callTwig($file,$vars) {

		$engine = new MarkdownEngine\MichelfMarkdownEngine();


		$loader = new Twig_Loader_Filesystem(__DIR__ . '/../templates/'); // Dossier contenant les templates

		$twig = new Twig_Environment($loader, array('cache' => false,'debug' => true));
		$twig->addExtension(new Twig_Extension_Debug()); // for debugging
		$twig->addExtension(new MarkdownExtension($engine)); // for markdown <3
		$twig->addExtension(new AutoLinkTwigExtension()); // for autolinking, see on bottom of the page
		echo $twig->render($file, $vars);
	}

	public static function isLogged() { // check if user is logged
		return isset($_SESSION['login']);
	}

	public static function sortFunction( $a, $b ) {
    	return strtotime($a->getDateMessage()) - strtotime($b->getDateMessage()); // to sort posts according to date
	}

	/*

		Fetching an avatar from Gravatar

	*/

	public static function get_gravatar( $email, $s = 80, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'http://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
	}

	/*
	
	Parsing content to get an URL inside

	*/

	public static function getURL($string) {
        preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $string, $match);
        if (count($match[0]) > 0) {
			return $match[0][0];
		} else {
			return false;
		}
	}

	/*

	Fetching informations about the URL
	<3 j0k3r

	*/

	public static function fetchURL ($url) {
		$graby = new Graby();
		try {
			$r = $graby->fetchContent($url);
			return $r;
		} catch (Exception $e) {
		}
	}
}

/*

Uses lib_autolink to create a Twig Extension to parse text and automatically create <a> markup on links.

*/

class AutoLinkTwigExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array('auto_link_text' => new \Twig_Filter_Method($this, 'auto_link_text', array('is_safe' => array('html'))),
        );
    }

    public function getName()
    {
        return "auto_link_twig_extension";
    }

    static public function auto_link_text($string)
    {

        return autolink($string);
    }
}