<?php

require_once('../../../wp-load.php');

global $wpdb;

$PID = $_POST["PID"];

	$wpdb->delete( 'sentiments', array( 'PID' => $PID, 'FID' => '0', 'sentiment_text' => ''  ), array( '%d', '%d', '%s') );

	echo "ok";

?>
