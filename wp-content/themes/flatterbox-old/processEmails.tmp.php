<?php
/* Template Name: Process Emails */

$remind = 0;
$summary = 0;

if ( isset($_GET['remind']) ) : $remind = $_GET['remind']; endif;
if ( isset($_GET['summary']) ) : $summary = $_GET['summary']; endif;

if ( $remind == 1 ) :
	processFlattererReminder();
endif;


if ( $summary == 1 ) :
	processFlaterboxCreateSummary();
endif;

function processFlattererReminder() {
	global $wpdb;
	global $post;

	$DaysBefore = 3;

	$dyear = date('Y',strtotime('+ '.$DaysBefore.' Days '));
	$month = date('n',strtotime('+ '.$DaysBefore.' Days '));
	$list_day = date('j',strtotime('+ '.$DaysBefore.' Days '));

	$subject = 'Reminder: You\'re invited to send a Flatterbox sentiment!';

	$toArr[] = array();

	$args = array(
			'post_type' => 'flatterboxes',
			'posts_per_page' => -1,
			'meta_query' => array(
	    						array(
									'key' => 'date_sentiments_complete',
									'value' => array( $dyear.'-'.$month.'-'.$list_day.' 00:00:00', $dyear.'-'.$month.'-'.$list_day.' 23:59:59' ),
									'compare' => 'BETWEEN',
									'type' => 'date',
								)
							),
			);
	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			$PID = intval(get_the_ID());
			$dateneeded = get_field('date_sentiments_complete');
			$dateneeded = date('F j, Y',mktime(0, 0, 0, $month, $list_day, $dyear));
			$occasion = get_field('occasion');
			$creator = get_field('who_is_this_from');
			$recipient = get_field('who_is_this_for');
			if ( strlen($creator) == 0 ) : $creator = get_the_author(); endif;
			$boxtheme = get_field('box_theme');
			$private = get_field("private",$PID);

			$flatterer_results = $wpdb->get_results( "SELECT * FROM flatterers WHERE responded = 0 AND PID = " . $PID, ARRAY_A);

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
      <p style="font-weight: bold;">'.$creator.' has invited you to participate in a gift for </p>
		  <p style="font-size: 48px; font-weight: bold;">'.$recipient.'</p>
		  <p style="font-size: 18px;">The occasion: <strong>'.$occasion.'</strong></p>
		  <p>The gift is called a Flatterbox and we need just <br>
	      one minute of your time to make it happen.</p>
	    <div style="padding: 10px; width: 310px; background-color: #f38707; color: #fff; font-size: 19px; font-weight: bold;"><em><a href="'.$linkURL.'" target="_blank" style="color: #fff;">Click here</a></em> to share<br>'.$boxtheme.' '.$row["flatterer_name"].'</div>
        <p style="font-size: 14px; color: #797979; font-weight: bold;">SENTIMENT NEEDED BY: '.$dateneeded.'</p>
        <p style="font-size: 28px; color: #f38707; font-weight: bold; margin-bottom: 20px;">Creating a sentiment is as simple as…</p></td>
	</tr>
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
	<tr>';
	if($private) :
    	$message .= "<br/><br/>And don't forget your passcode.  Your passcode is: " . $row["passcode"];
    endif; 
    $message .= '</tr>
	<tr>
		<td colspan="5">
			<img src="'.$bloginfo2.'/emails/sentiment_reminder/images/Flatterbox_Eblast_final_08.jpg" width="650" height="300" alt="Your sentiment will appear on a card like this. Click on the link and share '.$boxtheme.' '.$row["flatterer_name"].'. - Flatterbox"></td>
	</tr>
	<tr>
		<td width="650" height="28" colspan="5" align="center" valign="middle" style="font-size: 11px; font-family: Arial, Helvetica, sans-serif; background-color: #0D2065; color: #fff;">Thank you for participating in their Flatterbox! <em>- Heather Clayton</em> | <a href="http://www.flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">flatterbox.com</a> | <a href="mailto:info@flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">info@flatterbox.com</a></td>
	</tr>
</table>
</center>
</body>
</html>';
					$toArr[] = $row["flatterer_email"];
					//echo $message;
				endforeach;
			endif;
		endwhile;
	endif;

	if ( count($toArr) > 0 ) : processEmail($toArr,$subject, $message); endif;
}

function processFlaterboxCreateSummary() {
	global $wpdb;
	global $post;

	$DaysBefore = 3;

	$dyear = date('Y',strtotime('today'));
	$month = date('n',strtotime('today'));
	$list_day = date('j',strtotime('today'));
	$currentDay = date('l',strtotime('today'));

	$subject = 'Your Flatterbox Update';

	$toArr[] = array();

	$args = array(
			'post_type' => 'flatterboxes',
			'posts_per_page' => -1,
			'meta_query' => array(
	    						array(
									'key' => 'date_of_project_complete',
									'value' => $dyear.'-'.$month.'-'.$list_day.' 00:00:00',
									'compare' => '>',
									'type' => 'date',
								)
							),
			);
	$the_query = new WP_Query( $args );

	if ( $the_query->have_posts() ) :
		while ( $the_query->have_posts() ) : $the_query->the_post();
			if ( (	strtolower(get_field('notification_frequency')) == 'onceaday' || // Every Day
					strtolower(get_field('notification_frequency')) == strtolower($currentDay)) || // Specific Day
					((strtolower(get_field('notification_frequency')) == 'onceaweek' || strtolower(get_field('notification_frequency')) == 'twiceaweek') && strtolower($currentDay) == 'monday') || // Monday for once and twice a week
					(strtolower(get_field('notification_frequency')) == 'twiceaweek' && strtolower($currentDay) == 'thursday') // Thursday for twice a week
				) :

				$PID = intval(get_the_ID());
				$occasion = get_field('occasion');
				$toArr[] = get_the_author_email(); //creator email

				$sentimentsduedate = explode("/", get_field('date_of_project_complete'));
				$sentimentsduedate = date('F j, Y',mktime(0, 0, 0, $sentimentsduedate[1], $sentimentsduedate[0], $sentimentsduedate[2]));

				$dateneeded = explode("/", get_field('date_of_project_complete'));
				$today = date('d/m/Y', strtotime('today'));
				$today = explode("/", $today);

				$date1=date_create($today[2].'-'.$today[1].'-'.$today[0]);
				$date2=date_create($dateneeded[2].'-'.$dateneeded[1].'-'.$dateneeded[0]);
				$numberofdaysremaining=$date1->diff($date2);
				$numberofdaysremaining=$numberofdaysremaining->days;

				$giftdate = explode("/", get_field('date_of_delivery'));
				$giftdate = date('F j, Y',mktime(0, 0, 0, $giftdate[1], $giftdate[0], $giftdate[2]));

				$sentimentsneeded = 0;
				$numberofsentiments = 0;
				$numberofinvitations = 0;
				$flatterername = '';

				$flatterer_results = $wpdb->get_results( "SELECT count(*) AS Not_Responded FROM flatterers WHERE responded = 0 AND PID = " . $PID, ARRAY_A);

				if ($flatterer_results) :
					foreach ($flatterer_results as $row) :
						$sentimentsneeded = $row["Not_Responded"];
					endforeach;
				endif;

				$flatterer_results = $wpdb->get_results( "SELECT count(*) AS Responded FROM flatterers WHERE responded = 1 AND PID = " . $PID, ARRAY_A);

				if ($flatterer_results) :
					foreach ($flatterer_results as $row) :
						$numberofsentiments = $row["Responded"];
					endforeach;
				endif;
				
				$flatterer_results = $wpdb->get_results( "SELECT count(*) AS Sentiments, flatterer_name FROM flatterers WHERE PID = " . $PID, ARRAY_A);

				if ($flatterer_results) :
					foreach ($flatterer_results as $row) :
						$numberofinvitations = $row["Sentiments"];
						$flatterername = $row["flatterer_name"];
					endforeach;
				endif;
				$bloginfo2 = "http://www.sbdcstage.com/flatterbox";
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
        	<div style="text-align: left; width: 150px; float: left; display:inline-block; font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #0e2240;"><strong>Gift Delivery 
       	    Date</strong></div>
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
		<td width="650" height="28" colspan="3" align="center" valign="middle" style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; background-color: #0D2065; color: #fff;">Thank you for participating in their Flatterbox! <em>- Heather Clayton</em> | <a href="http://www.flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">flatterbox.com</a> | <a href="mailto:info@flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">info@flatterbox.com</a></td>
	</tr>
</table>
</center>
</body>
</html>';
				//echo $message;
				if ( count($toArr) > 0 ) : processEmail($toArr, $subject, $message); endif;
			endif;
		endwhile;
	endif;

}

function processEmail($toArr = array(), $subject, $message){
	add_filter( 'wp_mail_content_type', 'set_html_content_type' );
	foreach ($toArr as $to) :
		try {
			$mailheaders = 'From: Flatterbox <info@flatterbox.com>' . "\r\n";
			wp_mail( $to, $subject, $message, $mailheaders );
		}
		catch (Exception $e) {
    		//echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	endforeach;
	// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
	remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
}

function set_html_content_type() {

	return 'text/html';
}
?>