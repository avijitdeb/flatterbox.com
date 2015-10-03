<?php
/* Template Name: Flatterbox Action */

require_once('../../../wp-load.php');

$action = $_POST["action"];
$PID = $_POST["PID"];
$order_id = $_POST["order_id"];

if(isset($_GET['action'])) : $action = $_GET['action']; endif;
if(isset($_GET['PID'])) : $PID = $_GET['PID']; endif;
if(isset($_GET['order_id'])) : $order_id = $_GET['order_id']; endif;

if($action == "delete") {
	wp_trash_post($PID);
	echo "ok";
} elseif($action == "perm-delete") {
	wp_delete_post($PID);
	echo "ok";
} elseif ($action == "createxml") {
	reprintXML($order_id, $PID);
	echo "ok";
} elseif ($action == "createxmlclose") {
	reprintXML($order_id, $PID);
	andclose($PID, $order_id);
	echo "ok";
} elseif ($action == "reopen") {
	reopen($PID,$order_id);
	echo "ok";
} else {
	echo "no";
}

function reprintXML($order_id, $PID){
	send_to_printer2($order_id, $PID, "ajaxfunction");
}

function reopen($PID, $order_id) {
	// NEED TO ADD ORDER ARCHIVE FILTER
	$archive = get_field('order_count', $PID);
	__update_post_meta( $PID, 'order_archive', $value = $archive);
	__update_post_meta( $PID, 'order_count', $value = '');
}

function andclose($PID, $order_id) {
	// NEED TO ADD ORDER ARCHIVE FILTER
	$archive = get_field('order_archive', $PID);
	__update_post_meta( $PID, 'order_archive', $value = '');
	__update_post_meta( $PID, 'order_count', $value = $archive);
}

?>