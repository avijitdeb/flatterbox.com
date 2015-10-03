<?php

require_once('../../../wp-load.php');

global $wpdb;

$action = $_POST["action"];
$SID = $_POST["SID"];

if($action == "approve")
{
	$wpdb->update( 'sentiments', array('approved' => '1'), array( 'SID' => $SID ), array('%d'), array('%d') ); 
	echo "ok";
}

if($action == "reject")
{
	$wpdb->update( 'sentiments', array('approved' => '2'), array( 'SID' => $SID ), array('%d'), array('%d') ); 
	echo "ok";
}

if($action == "delete")
{
	$wpdb->delete( 'sentiments', array( 'SID' => $SID ), array( '%d' ) );
	echo "ok";
}

?>