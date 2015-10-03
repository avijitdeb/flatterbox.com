<?php 

/* Template Name: FB Step 6 Sentiment */

	// Save Title Card
	if ( $_POST['submittitlecard'] )	:
		$newpid = $_POST['sentimentPID'];
		__update_post_meta( $newpid, 'title_card_headline', $value = $_POST['sentimenttext']);
		__update_post_meta( $newpid, 'title_card_name', $value = $_POST['titlenamefamily']);
		
		if ( isset($_GET['redirect']) ) : wp_redirect( home_url().'/my-flatterbox/' ); exit; endif;
	endif;

get_header('steps'); 
?>
<?php

	//If this is the first time the box is being configured, get the initial flatterer list and insert into DB.
	if($_GET["initialinvite"]) // Shouldnt be called anymore, but leaving present
	{
		global $wpdb;
	
		for ($i = 0; $i < count($_SESSION['flattereremails']); $i++)
		{
			if($_SESSION['flattereremails'][$i] != "")
			{
	
				$wpdb->insert( 
					'flatterers', 
					array( 
						'PID' => $_SESSION["new_flatterbox_id"], 
						'flatterer_email' => $_SESSION["flattereremails"][$i],
						'flatterer_name' => $_SESSION["flatterernames"][$i],
						'responded' => 0
					), 
					array( 
						'%d', 
						'%s',
						'%s',
						'%d'
					) 
				);
				
				$flatterer_id = $wpdb->insert_id;
				
				
				// check if flatterbox is private and assign a passcode if so.
				
				$flatterbox_post = get_post($_SESSION["new_flatterbox_id"]); 
				$PID = $flatterbox_post->ID;
				$private = get_field("private",$PID); // For Passcode
				$can_invite = get_field("can_invite",$PID); // For Flatterer able to Invite
				
				if($private) :
					$passcode = getRandomCode();
				
					$wpdb->update( 'flatterers', array('passcode' => $passcode), array( 'FID' => $flatterer_id ), array('%s'), array('%d') ); 
				endif;

				//send invitation e-mail
				$bloginfo = get_bloginfo( 'url' ); 
				$bloginfo2 = home_url();
				$sentimentneeded = date_create(get_field("date_sentiments_complete",$_SESSION["new_flatterbox_id"]));
				
				$message = '<center>
				<table id="Table_01" width="650" height="848" border="0" cellpadding="0" cellspacing="0">
					<tr>
					  <td width="650" height="307" colspan="5" align="center" valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #0e2240;"><p style="font-weight: bold;">' . get_field("who_is_this_from",$_SESSION["new_flatterbox_id"]) . ' has invited you to participate in a gift for </p>
						  <p style="font-size: 48px; font-weight: bold;">' . get_field("who_is_this_for",$_SESSION["new_flatterbox_id"]) . '</p>
						  <p style="font-size: 18px;">The occasion: <strong>' . get_field("occasion",$_SESSION["new_flatterbox_id"]) . '</strong></p>
						  <p>The gift is called a Flatterbox and we need just<br>
						  one minute of your time to make it happen.</p>
						<div style="padding: 10px; width: 310px; background-color: #f38707; color: #fff; font-size: 19px; font-weight: bold;"><em><a href="' . $bloginfo . '/sentiment/?PID='.$_SESSION["new_flatterbox_id"].'&FID='.$flatterer_id.'" target="_blank" style="color: #fff;">Click here</a></em> to share<br>'.get_field("box_theme",$_SESSION["new_flatterbox_id"]).' '.get_field("who_is_this_for",$_SESSION["new_flatterbox_id"]).'</div>
						<p style="font-size: 14px; color: #797979; font-weight: bold;">SENTIMENT NEEDED BY: ' . date_format($sentimentneeded, 'm/d/Y') .  '</p>';
						
						if($private) :
							$message .= '<p style="font-size: 18px;">Your Passcode: <strong>' . $passcode . '</strong></p>';
						endif; 						
						
				$message .= '<p style="font-size: 28px; color: #f38707; font-weight: bold; margin-bottom: 20px;">Creating a sentiment is as simple as…</p></td>
					</tr>
					<tr>
						<td width="34" height="106">&nbsp;</td>
					  <td width="183" height="106" align="center" valign="top"><p><img src="' .  $bloginfo2 . '/emails/sentiment_invite/images/fb_invite_number_1.png" width="29" height="29" alt="Step 1"></p>
						<p style="font-family:Arial, Helvetica, sans-serif; font-size: 14px; color:#0D2240;">By ' . date_format($sentimentneeded, 'm/d/Y') .  '<br>
					  <a href="' . $bloginfo . '/sentiment/?PID='.$_SESSION["new_flatterbox_id"].'&FID='.$flatterer_id.'" target="_blank" style="color:#0D2240;">click this link</a></p></td>
						<td width="191" height="106" align="center" valign="top"><p><img src="' . $bloginfo . '/emails/sentiment_invite/images/fb_invite_number_2.png" width="29" height="29" alt="Step 2"></p>
						<p style="font-family:Arial, Helvetica, sans-serif; font-size: 14px; color:#0D2240;">Share <strong>' . get_field("box_theme",$_SESSION["new_flatterbox_id"]) .  'about ' . get_field("who_is_this_for",$_SESSION["new_flatterbox_id"]) . '</strong></p></td>
						<td width="209" height="106" align="center" valign="top"><p><img src="' . $bloginfo . '/emails/sentiment_invite/images/fb_invite_number_3.png" width="29" height="29" alt="Step 3"></p>
						<p style="font-family:Arial, Helvetica, sans-serif; font-size: 14px; color:#0D2240;">Your sentiment will be included<br>in a beautiful Flatterbox</p></td>
						<td width="33" height="106">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5">
							<img src="' . $bloginfo2 . '/emails/sentiment_invite/images/Flatterbox_Eblast_final_08.jpg" width="650" height="300" alt="Your sentiment will appear on a card like this. Click on the link share '.get_field("box_theme",$_SESSION["new_flatterbox_id"]).' '.get_field("who_is_this_for",$_SESSION["new_flatterbox_id"]).' - Flatterbox"></td>
					</tr>
					<tr>
						<td width="650" height="28" colspan="5" align="center" valign="middle" style="font-size: 11px; background-color: #0D2065; color: #fff;">Thank you for participating in their Flatterbox! <em>- From the Flatterbox Team</em> | <a href="http://www.flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">flatterbox.com</a> | <a href="mailto:info@flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">info@flatterbox.com</a></td>
					</tr>
				</table>
				</center>';



				add_filter( 'wp_mail_content_type', 'set_html_content_type' );
				$mailheaders = 'From: Flatterbox <info@flatterbox.com>' . "\r\n";
				wp_mail( $_SESSION["flattereremails"][$i], "You're invited to send a Flatterbox sentiment!", $message, $mailheaders );
				
				// Reset content-type to avoid conflicts -- http://core.trac.wordpress.org/ticket/23578
				remove_filter( 'wp_mail_content_type', 'set_html_content_type' );
				
			}
		}		
	}
	/* Placed into Functions
	function set_html_content_type() {
		return 'text/html';
	}				
	*/
	
	function getRandomCode(){
    $an = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $su = strlen($an) - 1;
    return substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1) .
            substr($an, rand(0, $su), 1);
	}
	
	//If this page was reached by a flatterer inviting other flatterers, get the new flatterer list and insert into DB.
	if($_GET["flattererinvite"])
	{
		global $wpdb;
	
		for ($i = 0; $i < count($_SESSION['flattereremails']); $i++)
		{
			if($_SESSION['flattereremails'][$i] != "")
			{
	
				$wpdb->insert( 
					'flatterers', 
					array( 
						'PID' => $_SESSION["sentimentPID"], 
						'flatterer_email' => $_SESSION["flattereremails"][$i],
						'flatterer_name' => $_SESSION["flatterernames"][$i],
						'responded' => 0
					), 
					array( 
						'%d', 
						'%s',
						'%s',
						'%d'
					) 
				);
			}
		}		
	}

?>

<main id="main" role="main">
	<div class="lightbox others">
		<?php include('includes/lightbox-what-others-said.php'); ?>
	</div>
	<div class="lightbox sentences">
		<?php include('includes/lightbox-sentence-starters.php'); ?>
	</div>
	<div class="lightbox gallery">
		<?php include('includes/lightbox-sentence-gallery.php'); ?>
	</div>
	<div class="container">
		<div class="heading steps">
			<?php if (false) : ?>
			<h1>Create Your <?php echo $_SESSION["occasion_name"]; ?> Flatterbox</h1>
			<?php endif; ?>
			<h1><?php the_field('page_title'); ?></h1>
		</div>

		<ul id="status-bar">
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/choose-card/?preservesession=1">Create It</a></li>
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterbox-options/">Personalize It</a></li>
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/invitations/">Invite Flatterers</a></li>
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/title-card/">Title Card</a></li>
			<li class="clearpadding active">Write the First One!</li>
			<li>Complete</li>
		</ul>

		<section id="sentiment-form-holder" class="sentiment-holder">

<?php		

if(isset($_GET["SID"]))
{

$_SESSION["sentimentSID"] = $_GET["SID"];
$sentiment2edit = $wpdb->get_results("SELECT * FROM sentiments WHERE SID = " . $_GET["SID"]);


}
		
?>
		
			<div class="compose">
				<h2>Compose Your Sentiment.</h2>
				<p>Kick it off by writing the first sentiment in the box.</p>
				<p>Occasion: <strong><?php the_field("occasion",$_SESSION["new_flatterbox_id"]); ?></strong></p>
				<p>This gift is meant to be a reflection of your personal relationship with <strong><?php the_field("who_is_this_for",$_SESSION["new_flatterbox_id"]); ?></strong>, so there are no rules.  No need for greetings and salutations, just go for it.</p>
				<p>Please share <strong>"<?php the_field("box_theme",$_SESSION["new_flatterbox_id"]); ?>"</strong> in the box below.  Don’t forget your name, too.</p>

				<?php if (false) : ?><p>Write a heart-felt sentiment to <strong><?php the_field("who_is_this_for",$_SESSION["new_flatterbox_id"]); ?></strong> for their <strong><?php the_field("occasion",$_SESSION["new_flatterbox_id"]); ?></strong> Flatterbox.</p>
				<p>Please share one <strong><?php echo strtolower(get_field("occasion",$_SESSION["new_flatterbox_id"])); ?></strong> tip. Write your sentiment in the form below. You’ll have the opportunity to write another card after completing this one.</p>
				<?php endif; ?>

				<form action="<?php echo do_shortcode('[site_url]'); ?>/review-sentiments/" class="compose-form" id="sentiment-form" method="post">
				<?php
				if(isset($_GET["SID"]))
				{
					foreach ( $sentiment2edit as $sentimentdata ) 
					{
					?>
						<fieldset>
							<div class="row">
								<textarea class="textarea" id="sentimenttext" name="sentimenttext" onkeyup="countChar(this,270);cloneText(this);" onmouseup="countChar(this,270);cloneText(this);" placeholder="Write your sentiment here and it will appear in the preview on the left." required><?php echo stripslashes($sentimentdata->sentiment_text); ?></textarea>
								<script>
								jQuery(document).ready(function() {
									countChar(document.getElementById('sentimenttext'),270);cloneText(document.getElementById('sentimenttext'));
								});
								</script>
								<span class="note"><strong class="charNum">270</strong> CHARACTERS LEFT</span>
							</div>
							<div class="row">
								<input type="text" id="namefamily" name="namefamily" onkeyup="countChar(this,35);cloneName(this);" onmouseup="countChar(this,35);cloneName(this);" placeholder="Your Name (or family)" value="<?php echo stripslashes($sentimentdata->sentiment_name); ?>" required />
								<script>
								jQuery(document).ready(function() {
									countChar(document.getElementById('namefamily'),35);cloneName(document.getElementById('namefamily'));
								});
								</script>								
								<span class="note"><strong class="charNum">35</strong> CHARACTERS LEFT</span>
							</div>
							<input id="sentimentPID" name="sentimentPID" type="hidden" value="<?php echo $_GET["PID"]; ?>" />
							<input id="sentimentFID" name="sentimentFID" type="hidden" value="<?php echo $_GET["FID"]; ?>" />
							<input id="updatesentiment" name="updatesentiment" type="hidden" value="<?php if(isset($_GET["SID"])) { echo '1'; } else { echo '0'; } ?>" />
							<input id="submitcard" name="submitcard" type="submit" value="submit" style="display:none;" />
						</fieldset>
				<?php
					}
				} else {
				?>
						<fieldset>
							<div class="row">
								<textarea class="textarea" id="sentimenttext" name="sentimenttext" onkeyup="countChar(this,270);cloneText(this);" onmouseup="countChar(this,270);cloneText(this);" placeholder="Compose your sentiment here." required></textarea>
								<span class="note"><strong class="charNum">270</strong> CHARACTERS LEFT</span>
							</div>
							<div class="row">
								<input type="text" id="namefamily" name="namefamily" onkeyup="countChar(this,35);cloneName(this);" onmouseup="countChar(this,35);cloneName(this);" onBlur="countChar(this,35);cloneName(this, true);" placeholder="Your Name (or family)" required />
								<span class="note"><strong class="charNum">35</strong> CHARACTERS LEFT</span>
							</div>
							<input id="sentimentPID" name="sentimentPID" type="hidden" value="<?php echo $_GET["PID"]; ?>" />
							<input id="sentimentFID" name="sentimentFID" type="hidden" value="<?php echo $_GET["FID"]; ?>" />
							<input id="updatesentiment" name="updatesentiment" type="hidden" value="<?php if(isset($_GET["SID"])) { echo '1'; } else { echo '0'; } ?>" />
							<input id="submitcard" name="submitcard" type="submit" value="submit" style="display:none;" />
						</fieldset>
				<?php
				}
				?>
				</form>
				<div class="wrap">
					Need inspiration?<br/>We’ve got you covered with
					<a id="sentence-starters" href="<?php echo do_shortcode('[site_url]'); ?>/">Sentence starters</a>
					and our <a id="gallery" href="<?php echo do_shortcode('[site_url]'); ?>/">Sentiment Library</a>
					<?php if(get_field('allow_to_see_eachother',$_GET["PID"])) {?>
					or of course you can <a id="others-wrote" href="<?php echo do_shortcode('[site_url]'); ?>/">See what others wrote</a>
					<?php } ?>
				</div>
			</div>
			<div class="example">
				<div id="card-preview" class="img-holder blank-card">
					<span id="card-preview-text">Compose your sentiment here.</span>
					<span id="card-preview-name">- Your Name (or family)</span>
				</div>
			</div>			
		</section>
		<div class="control-bar bottom">
			<a href="<?php echo do_shortcode('[site_url]'); ?>/title-card/" class="btn back">Back to Title Card</a>
			<a href="javascript:;" id="continuebtn" onclick="jQuery('#submitcard').trigger('click');" class="btn save">Next</a>
		</div>		
	</div>
</main>
<script>
jQuery( "#others-wrote" ).click(function($) {
	event.preventDefault();
  	jQuery( ".lightbox.others" ).fadeToggle("fast");
});
jQuery( "#sentence-starters" ).click(function($) {
	event.preventDefault();
  	jQuery( ".lightbox.sentences" ).fadeToggle("fast");
});
jQuery( "#gallery" ).click(function($) {
	event.preventDefault();
  	jQuery( ".lightbox.gallery" ).fadeToggle("fast");
});
jQuery( "#btn-confirm" ).click(function() {
	jQuery( "#sentiment-form" ).submit();
});
function countChar(val,limit) {
    var len = val.value.length;
    var countLimit = parseInt(limit);
    var jelm = val.id;
    if (len > countLimit) {
        val.value = val.value.substring(0, countLimit);
    } else {
        jQuery("#"+val.id+"").siblings().find(".charNum").text(countLimit - len);
    }
};
</script>

<script type="text/javascript">
function cloneText(sentiment)
{
	jQuery("#card-preview-text").html(sentiment.value.replace(/(?:\r\n|\r|\n)/g, '<br />'));
	<?php if ( !get_field('allow_profanity',$_SESSION["sentimentPID"]) ) : ?>
	removeProfanity("#card-preview-text");
	jQuery("#"+sentiment.id).val(htmlDecode(jQuery("#card-preview-text").html().replace(/(?:<br>)/g, '\r\n')));
	<?php endif; ?>
}

function cloneName(sentimentname, blur)
{
	jQuery("#card-preview-name").html('- '+sentimentname.value);
	<?php if ( !get_field('allow_profanity',$_SESSION["sentimentPID"]) ) : ?>
	removeProfanity("#card-preview-name");
	if (blur == true) {
		jQuery("#"+sentimentname.id).val(htmlDecode(jQuery("#card-preview-name").html().substr(2)));
	}
	<?php endif; ?>
}
function removeProfanity(objID) {
	jQuery(objID).profanityFilter({replaceWith:'*$#@!^%~', externalSwears: '<?php echo home_url(); ?>/wp-content/themes/flatterbox/js/swearWords.json'});
}

function htmlDecode(input){
  var e = document.createElement('div');
  e.innerHTML = input;
  return e.childNodes.length === 0 ? "" : e.childNodes[0].nodeValue;
}
</script>


<?php if($_GET["SID"]) { ?>
<script>
jQuery( document ).ready(function() {
	cloneText(jQuery("#sentimenttext"));
	cloneName(jQuery("#namefamily"));
});	
</script>
<?php } ?>

<?php get_footer(); ?>		