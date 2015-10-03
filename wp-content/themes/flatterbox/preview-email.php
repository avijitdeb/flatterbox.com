<?php 

/* Template Name: Preview Email !! NOT USED -- See functions.php get_preview_email() !! */

?>
<?php
	echo 'WRONG FILE'; exit; // Remove this line if we switch back
	$PID = 0;
	if(isset($_SESSION['sentimentPID'])) : $PID = $_SESSION["sentimentPID"]; endif;
	if(isset($_SESSION['new_flatterbox_id'])) : $PID = $_SESSION["new_flatterbox_id"]; endif;
	if(isset($_GET['PID'])) : $PID = $_GET["PID"]; endif;

	//$sentimentneeded = date_create(get_field("date_sentiments_complete",$PID));
	$date = DateTime::createFromFormat('Ymd', get_field('date_sentiments_complete', $PID));
	if( $date ) :
		$sentimentneeded = $date->format('m/d/Y');
	endif;
	if (strpos(get_field("box_theme",$PID), '(name)') > 0) :
		$box_theme = str_replace('(name)', get_field("who_is_this_for",$PID), get_field("box_theme",$PID));
	else :
		$box_theme = get_field("box_theme",$PID).' '.get_field("who_is_this_for",$PID);
	endif;
	$box_theme = trim($box_theme);
	$sentimentneeded = trim($sentimentneeded);
?>
<html>
<head>
<title>Flatterbox_Eblast_final</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
table {
	letter-spacing: 0;
}
</style>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="background-color:white;">
<center>
<table id="Table_01" width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color:white;">
<!--
	<tr>
		<td width="650" height="107" colspan="5" align="center" valign="top"><img src="<?php echo bloginfo('url'); ?>/emails/sentiment_invite/images/fb_invite_header_logo.png" width="370" height="80" alt="Your Flatterbox® Invite"></td>
	</tr>
-->
	<tr>
		<td width="100%" height="307" colspan="5" align="center" valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #0e2240; margin:0;"><p style="font-weight: bold;">You have been invited by <span id="who_from"><?php if (strlen(get_field("who_is_this_from",$PID)) > 0) : echo get_field("who_is_this_from",$PID); else : echo '<i>&lt;from&gt;</i>'; endif; ?></span> to participate in a gift for </p>
			<p style="font-size: 40px; font-weight: bold; margin:25px 0; line-height: 40px;" id="who_for"><?php if (strlen(get_field("who_is_this_for",$PID)) > 0) : the_field("who_is_this_for",$PID); else : echo '<i>&lt;Flatterbox Recipient&gt;</i>'; endif; ?></p>
		  	<p style="font-size: 18px; margin:5px 0 10px 0;">The occasion: <strong id="the_occasion"><?php the_field("occasion",$PID) ?></strong></p>
		  	<p style="margin:0;">The gift is called a Flatterbox and we need just<br/>one minute of your time to make it happen.</p>
		  	<div style="padding: 10px; width: 310px; background-color: #f38707; color: #fff; font-size: 19px; font-weight: bold; margin:20px 0;"><em><a href="#" onclick="window.alert('This is a sample of your email.  The link will be enabled when your Flatterers receive their invitation.'); return false;" style="color: #fff;">Click here</a></em> to share<br><span id="the_theme"><?php if(strlen($box_theme) > 0) : echo $box_theme; else : echo '&lt;Sentiment Box Theme&gt;';endif;?></span> <span id="theme_name"><?php if (strlen(get_field("who_is_this_from",$PID)) > 0) : echo get_field("who_is_this_from",$PID); else : echo '<i>&lt;from&gt;</i>'; endif; ?></span></div>
        	<!--<p style="font-size: 14px; color: #797979; font-weight: bold; margin:0;">SENTIMENT NEEDED BY: <span id="date_needed"><?php if ($sentimentneeded) : echo $sentimentneeded; else : echo '&lt;Date Needed&gt;'; endif; ?></span></p>
      		<p style="font-size: 28px; color: #f38707; font-weight: bold; margin-top: 20px; margin-bottom: 20px;">Creating a sentiment is as simple as...</p>-->
  			<p style="margin:20px 55px;"><strong id="special_inst"><?php if(strlen(get_field("special_instructions_to_flatterers",$PID)) > 0) : echo nl2br(get_field("special_instructions_to_flatterers",$PID)); else :echo '&lt;Personal Message&gt;'; endif; ?></strong></p>
  			<?php if(is_page_template('page-reminders.php')) : ?><p style="margin:20px 55px;"><strong id="reminder_inst"><?php if(strlen(get_field("reminder_instructions",$PID)) > 0) : echo nl2br(get_field("reminder_instructions",$PID)); else :echo '&lt;Reminder Instructions&gt;'; endif; ?></strong></p><?php endif; ?>
  		</td>
	</tr>

	<tr>
		<td colspan="5">
			<img src="<?php echo bloginfo('url'); ?>/emails/sentiment_invite/images/Flatterbox_Eblast_final_08.jpg" width="100%" alt="Your sentiment will appear on a card like this. Click on the link  share <?php echo $box_theme; ?>. - Flatterbox"></td>
	</tr>
	<tr>
		<td width="100%" height="28" colspan="5" align="center" valign="middle" style="font-size: 11px; background-color: #0D2065; color: #fff;">Thank you for participating in this special gift! | <a href="http://www.flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">flatterbox.com</a> | <a href="mailto:info@flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">info@flatterbox.com</a></td>
	</tr>
</table>
</center>
</body>
</html>