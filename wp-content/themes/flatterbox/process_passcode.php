<?php

require_once('../../../wp-load.php');

global $wpdb;

$passcode = $_POST["passcode"];
$PID = $_POST["PID"];
$FID = $_POST["FID"];


$passcode_db = $wpdb->get_var( "SELECT passcode FROM flatterers WHERE invalid = 0 AND PID = " . $PID . " and FID = " . $FID );

if($passcode == $passcode_db)
{
	echo "ok";
	$_SESSION["flatterer_logged_in"] = true;
} else {
	echo "Does not match: " . $passcode . " = " . $passcode_db;
}


?>