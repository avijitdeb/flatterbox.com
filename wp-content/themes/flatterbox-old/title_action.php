<?php

require_once('../../../wp-load.php');

global $wpdb;

$action = $_POST["action"];
$PID = $_POST["PID"];

if($action == "delete")
{
	__update_post_meta( $PID, 'title_card_headline', $value = '');
	__update_post_meta( $PID, 'title_card_name', $value = '');
	echo "ok";
}

?>