<?php

require_once('../global.php');

$database = new Database;
if (isset($_GET['idpost'])) {
	if ($database->deletePost($_GET['idpost'])) {
		echo "ok";
	}
}