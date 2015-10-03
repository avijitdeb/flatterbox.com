<?php

/* Email Functions */

function processFlattererReminder($DaysBefore = 5, $PID = 0, $toArrID = array(), $isremind = false) {
	global $wpdb;
	global $post;

	$dyear = date('Y',strtotime('+ '.$DaysBefore.' Days '));
	$month = date('n',strtotime('+ '.$DaysBefore.' Days '));
	$list_day = date('j',strtotime('+ '.$DaysBefore.' Days '));

	if ( isset($_GET['dyear']) && isset($_GET['month']) && isset($_GET['list_day']) ) :
		$dyear = $_GET['dyear'];
		$month = $_GET['month'];
		$list_day = $_GET['list_day'];
	endif;

	$subject = 'Reminder: You\'re invited to send a Flatterbox sentiment!';

	$toArr = array();

	if ($PID == 0) :
	$args = array(
			'post_type' => 'flatterboxes',
			'posts_per_page' => -1,
			'meta_query' => array(
								'relation' => 'AND',
	    						array(
									'key' => 'date_sentiments_complete',
									'value' => array( $dyear.'-'.$month.'-'.$list_day.' 00:00:00', $dyear.'-'.$month.'-'.$list_day.' 23:59:59' ),
									'compare' => 'BETWEEN',
									'type' => 'date',
								),
								array(
									'relation' => 'OR',
									array(
										'key' => 'order_count',
										'value' => '',
										'compare' => '=',
									),
							        array(
							            'key' => 'order_count',
							            'compare' => 'NOT EXISTS'
							        )
							    )
							),
			);
	else :
	$args = array(
			'post_type' => 'flatterboxes',
			'posts_per_page' => 1,
			'p' => $PID
			);
	endif;
	$the_query = new WP_Query( $args );

	if(isset($_GET['showme'])) :
		print_r($the_query);
	endif;

	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$PID = intval(get_the_ID());
			try {
				//echo get_field('date_sentiments_complete', $PID);
				if(substr_count(get_field('date_sentiments_complete', $PID),'/') > 0) :
					$dateneeded = DateTime::createFromFormat('d/m/Y', get_field('date_sentiments_complete', $PID))->format('m/d/Y');
				else :
					$dateneeded = DateTime::createFromFormat('Ymd', get_field('date_sentiments_complete', $PID))->format('m/d/Y');
				endif; 
			} catch (Exception $e) {
			    $dateneeded = '';
			}

			//echo '!!!';
			//echo get_field("date_sentiments_complete",$PID);
			//echo '$'.$dateneeded.'$';
			//echo '!!!';

			if( !validateDate($dateneeded, 'm/d/Y') ) :
				$dateneeded = date_format(date_create(get_field("date_sentiments_complete",$PID)), 'm/d/Y');
			endif;

			//$dateneeded = date('F j, Y',mktime(0, 0, 0, $month, $list_day, $dyear));
			$occasion = get_field('occasion');
			$creator = get_field('who_is_this_from');
			$recipient = get_field('who_is_this_for');
			if ( strlen($creator) == 0 ) : $creator = get_the_author(); endif;
			$boxtheme = get_field('box_theme');
			if (strpos($boxtheme, '(name)') > 0) :
				$boxtheme = str_replace('(name)', $recipient, $boxtheme);
			else :
				$boxtheme = $boxtheme.' '.get_field("who_is_this_for",$PID);
			endif;
			$boxtheme = trim($boxtheme);					
			$private = get_field("private",$PID);
			$instuctions = nl2br(get_field("special_instructions_to_flatterers",$PID));
			$rem_instuctions = nl2br(get_field("reminder_instructions",$PID));
			if (!$isremind) : $rem_instuctions = ''; endif;

			if (empty($toArrID)) :
				$flatterer_results = $wpdb->get_results( "SELECT * FROM flatterers WHERE invalid = 0 AND responded = 0 AND PID = " . $PID, ARRAY_A);
			else :
				$prefix = ''; $idList = '';
				foreach ($toArrID as $toID)
				{
				    $idList .= $prefix . '"' . $toID . '"';
				    $prefix = ', ';
				}
				$flatterer_results = $wpdb->get_results( "SELECT * FROM flatterers WHERE invalid = 0 AND responded = 0 AND FID IN (".$idList.") AND PID = " . $PID, ARRAY_A);
			endif;

			unset($toArr); $toArr = array(); // Reset to avoid people seeing different items
			unset($messageArr); $messageArr = array();

			if ($flatterer_results) :
				foreach ($flatterer_results as $row) :
					$linkURL = home_url().'/sentiment/?PID='.$PID.'&FID='.$row['FID'];
					$bloginfo2 = home_url();
					$message = '<html>
<head>
<title>Flatterbox_Eblast_final</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table id="Table_01" width="650" height="848" border="0" cellpadding="0" cellspacing="0">
	<tr>
	  <td width="650" height="307" colspan="5" align="center" valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #0e2240;">
      <p style="font-size: 28px; color: #f38707; font-weight: bold; margin-bottom: 20px;"><em>Don\'t Forget!</em></p>
      <p style="font-weight: bold;">You have been invited by ' . $creator . ' to participate in a gift for </p>
		  <p style="font-size: 48px; font-weight: bold;">'.$recipient.'</p>
		  <p style="font-size: 18px;">The occasion: <strong>'.$occasion.'</strong></p>
		  <p>The gift is called a Flatterbox and we need just <br>
	      one minute of your time to make it happen.</p>
	    <div style="padding: 10px; width: 310px; background-color: #f38707; color: #fff; font-size: 19px; font-weight: bold;"><em><a href="'.$linkURL.'" target="_blank" style="color: #fff;">Click here</a></em> to share<br>'.$boxtheme.'</div>
        <!--<p style="font-size: 14px; color: #797979; font-weight: bold;">SENTIMENT NEEDED BY: '.$dateneeded.'</p>-->
        <p style="margin:20px 55px;"><strong>'.$instuctions.'</strong></p>';
        if(false && $rem_instuctions) : // Hiding but keeping incase we need it.
        	$message .= '<p style="margin:20px 55px;"><strong>'.$rem_instuctions.'</strong></p>';
    	endif;
    	$message .= '
	</tr>';
	
	/*	
	<tr>
		<td width="34" height="106">&nbsp;</td>
	  <td width="183" height="106" align="center" valign="top"><p><img src="'.$bloginfo2.'/emails/sentiment_reminder/images/fb_invite_number_1.png" width="29" height="29" alt="Step 1"></p>
	    <p style="font-family:Arial, Helvetica, sans-serif; font-size: 14px; color:#0D2240;">By '.$dateneeded.'<br>
      <a href="'.$linkURL.'" target="_blank" style="color:#0D2240;">click this link</a></p></td>
		<td width="191" height="106" align="center" valign="top"><p><img src="'.$bloginfo2.'/emails/sentiment_reminder/images/fb_invite_number_2.png" width="29" height="29" alt="Step 2"></p>
	    <p style="font-family:Arial, Helvetica, sans-serif; font-size: 14px; color:#0D2240;">Share <strong>'.$boxtheme.' '.$row["flatterer_name"].'</strong></p></td>
		<td width="209" height="106" align="center" valign="top"><p><img src="'.$bloginfo2.'/emails/sentiment_reminder/images/fb_invite_number_3.png" width="29" height="29" alt="Step 3"></p>
	    <p style="font-family:Arial, Helvetica, sans-serif; font-size: 14px; color:#0D2240;">Your sentiment will be included<br>in a beautiful Flatterbox</p></td>
		<td width="33" height="106">&nbsp;</td>
	</tr>
	*/
	
	$message .= '<tr>';
	if($private) :
    	$message .= "<br/><br/>And don't forget your passcode.  Your passcode is: " . $row["passcode"];
    endif; 
    $message .= '</tr>
	<tr>
		<td colspan="5">
			<img src="'.$bloginfo2.'/emails/sentiment_reminder/images/Flatterbox_Eblast_final_08.jpg" width="650" height="300" alt="Your sentiment will appear on a card like this. Click on the link and share '.$boxtheme.' '.$recipient.'. - Flatterbox"></td>
	</tr>
	<tr>
		<td width="650" height="28" colspan="5" align="center" valign="middle" style="font-size: 11px; font-family: Arial, Helvetica, sans-serif; background-color: #0D2065; color: #fff;">Thank you for participating in their Flatterbox! <em>- From the Flatterbox Team</em> | <a href="http://www.flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">flatterbox.com</a> | <a href="mailto:info@flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">info@flatterbox.com</a></td>
	</tr>
</table>
</center>
</body>
</html>';
					$toArr[] = $row["flatterer_email"];
					$messageArr[] = $message;
					//echo $message;
				endforeach;

				if(!isset($_GET['showme'])) : 
					if ( count($toArr) > 0 ) : 
						processEmail($toArr,$subject, $messageArr, $PID); 
					endif;
					//echo 'REMINDER OK';
				else :
					echo '<br/><br/>';
					print_r($the_query);
					echo '<br/><br/>';
					print_r($toArr);
					echo '<br/><br/>';
					echo $subject;
					echo '<br/><br/>';
					print_r($messageArr);
					//echo 'REMINDER OK';
				endif;
			endif;
		endwhile;
	endif;

//echo 'COMPLETE';

}

function processFlaterboxCreateSummary() {
	global $wpdb;
	global $post;

	$dyear = date('Y',strtotime('today'));
	$month = date('n',strtotime('today'));
	$list_day = date('j',strtotime('today'));
	$currentDay = date('l',strtotime('today'));

	$subject = 'Your Flatterbox Update';

	$toArr = array();

	if ( isset($_GET['dyear']) && isset($_GET['month']) && isset($_GET['list_day']) ) :
		$dyear = $_GET['dyear'];
		$month = $_GET['month'];
		$list_day = $_GET['list_day'];
	endif;

	$args = array(
			'post_type' => 'flatterboxes',
			'posts_per_page' => -1,
			'meta_query' => array(
								'relation' => 'AND',
	    						array(
									'key' => 'date_of_delivery',
									'value' => $dyear.'-'.$month.'-'.$list_day.' 00:00:00',
									'compare' => '>',
									'type' => 'date',
								),
								array(
									'relation' => 'OR',
									array(
										'key' => 'order_count',
										'value' => '',
										'compare' => '=',
									),
							        array(
							            'key' => 'order_count',
							            'compare' => 'NOT EXISTS'
							        )
							    )
							),
			);
	$the_query = new WP_Query( $args );
	if ( isset($_GET['showme']) ) :
		echo '<br/><br/>';
		print_r($the_query);
	endif;

	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			if ( (	strtolower(get_field('notification_frequency')) == 'onceaday' || // Every Day
					strtolower(get_field('notification_frequency')) == strtolower($currentDay)) || // Specific Day
					((strtolower(get_field('notification_frequency')) == 'onceaweek' || strtolower(get_field('notification_frequency')) == 'twiceaweek') && strtolower($currentDay) == 'monday') || // Monday for once and twice a week
					(strtolower(get_field('notification_frequency')) == 'twiceaweek' && strtolower($currentDay) == 'thursday') // Thursday for twice a week
				) :

				$PID = intval(get_the_ID());
				$occasion = get_field('occasion');
				unset($toArr); $toArr = array(); // Reset to avoid people seeing different items
				unset($messageArr); $messageArr = array();
				$toArr[] = get_the_author_email(); //creator email

				if ( isset($_GET['showme']) ) : echo get_field('date_sentiments_complete').'<br/>'; endif;
				//$sentimentsduedate = explode("/", date('d/m/Y', strtotime(get_field('date_sentiments_complete')))); //date_of_project_complete
				//$sentimentsduedate = explode("/", get_field('date_sentiments_complete')); //date_of_project_complete
				$sentimentsduedate = explode("/", date('d/m/Y', strtotime(get_field('date_sentiments_complete')))); //date_of_project_complete
				if ( isset($_GET['showme']) ) : print_r($sentimentsduedate); echo '<br/>'; endif;
				$sentimentsduedate = date('F j, Y',mktime(0, 0, 0, $sentimentsduedate[1], $sentimentsduedate[0], $sentimentsduedate[2]));
				//$sentimentsduedate = get_field('date_sentiments_complete'); //date_of_project_complete
				if ( isset($_GET['showme']) ) : echo $sentimentsduedate.'<br/>'; endif;

				$dateneeded = explode("/", date('d/m/Y', strtotime(get_field('date_of_delivery')))); //date_of_project_complete
				$today = date('d/m/Y', strtotime('today'));
				$today = explode("/", $today);

				$date1=date_create($today[2].'-'.$today[1].'-'.$today[0]);
				$date2=date_create($dateneeded[2].'-'.$dateneeded[1].'-'.$dateneeded[0]);
				$numberofdaysremaining=$date1->diff($date2);
				$numberofdaysremaining=$numberofdaysremaining->days;

				if ( isset($_GET['showme']) ) : echo get_field('date_of_delivery').'<br/>'; endif;
				$giftdate = explode("/", get_field('date_of_delivery'));
				$giftdate = explode("/", date('d/m/Y', strtotime(get_field('date_of_delivery'))));
				if ( isset($_GET['showme']) ) : print_r($giftdate); echo '<br/>'; endif;
				$giftdate = date('F j, Y',mktime(0, 0, 0, $giftdate[1], $giftdate[0], $giftdate[2]));
				//$giftdate = get_field('date_of_delivery');
				if ( isset($_GET['showme']) ) : echo $giftdate.'<br/>'; endif;

				$sentimentsneeded = 0;
				$numberofsentiments = 0;
				$numberofinvitations = 0;
				$flatterername = get_field('who_is_this_for');

				$flatterer_results = $wpdb->get_results( "SELECT count(*) AS Not_Responded FROM flatterers WHERE invalid = 0 AND responded = 0 AND PID = " . $PID, ARRAY_A);

				if ($flatterer_results) :
					foreach ($flatterer_results as $row) :
						$sentimentsneeded = $row["Not_Responded"];
					endforeach;
				endif;
				$cardamount = get_field('card_quantity');
				if (strlen(trim(get_field('title_card_headline'))) > 0) :
					$sentimentsneeded = $sentimentsneeded + 1;
				endif;
				$sentimentsneeded = $cardamount - $sentimentsneeded;	

				$flatterer_results = $wpdb->get_results( "SELECT count(*) AS Responded FROM sentiments WHERE sentiment_text <> '' AND PID = " . $PID, ARRAY_A);

				if ($flatterer_results) :
					foreach ($flatterer_results as $row) :
						$numberofsentiments = $row["Responded"];
					endforeach;
				endif;
				
				$flatterer_results = $wpdb->get_results( "SELECT count(*) AS Sentiments, flatterer_name FROM flatterers WHERE invalid = 0 AND PID = " . $PID, ARRAY_A);

				if ($flatterer_results) :
					foreach ($flatterer_results as $row) :
						$numberofinvitations = $row["Sentiments"];
						//$flatterername = $row["flatterer_name"];
					endforeach;
				endif;
				$bloginfo2 = home_url();
				$message = '<html>
<head>
<title>Flatterbox_Eblast_final</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<center>
<table id="Table_01" width="650" border="0" cellpadding="0" cellspacing="0">
	<tr>
		<td width="650" height="107" colspan="3" align="center" valign="top"><img src="'.$bloginfo2.'/emails/creator_summary/images/fb_update_header_logo.png" width="390" height="80" alt="Your Flatterbox® Order"></td>
	</tr>
	<tr>
	  <td width="650" colspan="3" align="center" valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #0e2240;">
      <p>Here\'s an update on your Flatterbox gift for </p>
		  <p style="font-size: 48px; font-weight: bold;">'.$flatterername.'</p>
		  <p style="font-size: 18px; margin-bottom: 20px;">The occasion: <strong>'.$occasion.'</strong></p>
		</td>
	</tr>
	<tr>
	  <td width="325" align="center" valign="top"><h3 style="font-family: Arial, Helvetica, sans-serif; border-bottom: 1px solid #bdb5c9; padding-bottom: 10px; margin-bottom: 10px; width: 300px; color: #1a3667;">Schedule</h3>
      	<div style="width: 300px; margin: 0 auto 10px auto; position: relative; overflow: hidden;">
        	<div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;"><strong>Days Left to Complete</strong></div>
            <div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;">'.$numberofdaysremaining.'</div>
		</div>
        <div style="width: 300px; margin: 0 auto 10px auto; position: relative; overflow: hidden;">
        	<div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;"><strong>Sentiments Due By</strong></div>
            <div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;">'.$sentimentsduedate.'</div>
		</div>
        <div style="width: 300px; margin: 0 auto 10px auto; position: relative; overflow: hidden;">
        	<div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;"><strong>Gift Delivery Date</strong></div>
            <div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;">'.$giftdate.'</div>
		</div>
      </td>
		<td width="325" align="center" valign="top"><h3 style="font-family: Arial, Helvetica, sans-serif; border-bottom: 1px solid #bdb5c9; padding-bottom: 10px; margin-bottom: 10px; width: 300px; color: #1a3667;">Sentiment Summary</h3>
        <div style="width: 300px; margin: 0 auto 10px auto; position: relative; overflow: hidden;">
        	<div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;"><strong>Invitations 
       	    Sent</strong></div>
          <div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;">'.$numberofinvitations.'</div>
		</div>
        <div style="width: 300px; margin: 0 auto 10px auto; position: relative; overflow: hidden;">
        	<div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;"><strong>Sentiments Received</strong></div>
            <div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;">'.$numberofsentiments.'</div>
		</div>
        <div style="width: 300px; margin: 0 auto 10px auto; position: relative; overflow: hidden;">
        	<div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;"><strong>Sentiments Needed</strong></div>
            <div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;">'.$sentimentsneeded.'</div>
		</div>
	    </td>
	</tr>
   <tr>
  <td width="650" colspan="3" align="center" valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #0e2240;">
	    <div style="margin: 20px; padding: 10px; width: 310px; background-color: #f38707; color: #fff; font-size: 19px; font-weight: bold;"><em><a href="'.home_url().'" target="_blank" style="color: #fff;">Login to Flatterbox</a></em> to review your sentiments and more</div></td>
	</tr>    
	<tr>
		<td width="650" height="28" colspan="3" align="center" valign="middle" style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; background-color: #0D2065; color: #fff;">Thank you for participating in their Flatterbox! <em>- From the Flatterbox Team</em> | <a href="http://www.flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">flatterbox.com</a> | <a href="mailto:info@flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">info@flatterbox.com</a></td>
	</tr>
</table>
</center>
</body>
</html>';
				//echo $message;
				$messageArr[] = $message;
				if(!isset($_GET['showme'])) : 
					if ( count($toArr) > 0 ) : 
						processEmail($toArr, $subject, $messageArr, $PID); 
					endif;
					//echo 'SUMMARY OK';
				else :
					echo '<br/><br/>';
					print_r($toArr);
					echo '<br/><br/>';
					echo $subject;
					echo '<br/><br/>';
					echo $message;
				endif;
			endif;
		endwhile;
	endif;

}

function processEmail($toArr = array(), $subject, $message = array(), $invitePID){
	add_filter( 'wp_mail_content_type', 'set_html_content_type' );
	$msg = 0;
	foreach ($toArr as $to) :
		try {
			//$mailheaders = 'From: Flatterbox <info@flatterbox.com>' . "\r\n";
			$mailheaders = 'From: '.get_field("who_is_this_from",$invitePID).' <info@flatterbox.com>' . "\r\n" .
							'Reply-To: '.get_field("who_is_this_from",$invitePID).' <'.get_the_author_meta('email', get_post_field( 'post_author', $invitePID )).'>' . "\r\n" ;
			wp_mail( $to, $subject, $message[$msg], $mailheaders );
			//wp_mail( 'andy@gosbdc.com', $subject.' - '.$to, $message, $mailheaders );
		}
		catch (Exception $e) {
    		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
		$msg++;
	endforeach;
	// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
	remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
}

function set_html_content_type() {

	return 'text/html';
}
?>