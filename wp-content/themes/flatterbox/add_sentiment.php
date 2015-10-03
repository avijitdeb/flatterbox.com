<?php

require_once('../../../wp-load.php');

global $wpdb;

$sentence_id = $_POST["sentence_id"];
$cardcount = $_POST["cardcount"];
$PID = $_POST["PID"];

if($sentence_id == 0)
{

	
	for ($i = 1; $i <= $cardcount; $i++) {
	
	$wpdb->insert( 
		'sentiments', 
		array( 
			'FID' => 0, 
			'PID' => $PID,
			'sentiment_text' => '',
			'sentiment_name' => '',
			'private' => 0,
			'approved' => 1
		), 
		array( 
			'%d', 
			'%d',
			'%s',
			'%s',
			'%d',
			'%d'
		) 
	);	
		
	}

	echo "ok";
		
	
} else {

	$sentence = get_field('sentence',$sentence_id);

	$wpdb->insert( 
		'sentiments', 
		array( 
			'FID' => 0, 
			'PID' => $PID,
			'sentiment_text' => $sentence,
			'sentiment_name' => '',
			'private' => 0,
			'approved' => 1
		), 
		array( 
			'%d', 
			'%d',
			'%s',
			'%s',
			'%d',
			'%d'
		) 
	);
	
	echo "ok";

}


?>