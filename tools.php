<?php
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

?>