<?php 

/* Template Name: Flatterer Step 1 Sentiment */

get_header(); 

$_SESSION["sentimentPID"] = $_GET["PID"];
$_SESSION["sentimentFID"] = $_GET["FID"];
$_SESSION["flatterernames"] = array();
$_SESSION["flattereremails"] = array();

// Shareing URL Stuff
if (isset($_GET['fb'])) : $fbox = $_GET['fb']; endif; // incase legacy link exisits
if (isset($_GET['fbox'])) : $fbox = $_GET['fbox']; endif;

if(isset($fbox)) :
	$querystr = "
		    SELECT $wpdb->posts.* 
		    FROM $wpdb->posts, $wpdb->postmeta
		    WHERE $wpdb->posts.ID = $wpdb->postmeta.post_id 
		    AND $wpdb->postmeta.meta_key = 'unique_url' 
		    AND $wpdb->postmeta.meta_value = '".$fbox."' 
		    AND $wpdb->posts.post_status = 'publish' 
		    AND $wpdb->posts.post_type = 'flatterboxes'
		    ORDER BY $wpdb->posts.post_date DESC
		 ";
	//echo $querystr;
	$flatterbox_results = $wpdb->get_results( $querystr, ARRAY_A);
	$the_id = -1;
	if ($flatterbox_results) :
		foreach ($flatterbox_results as $row) :
			$the_id = $row["ID"];
		endforeach;
	endif;
	$_SESSION["sentimentPID"] = $the_id;
	$_SESSION["sentimentFID"] = -1;

endif;
// END Sharing URL

$PID = intval($_SESSION["sentimentPID"]);
$validUser = true;
if ( $_SESSION["sentimentFID"] > 0 ) :
	$querystr = "
		    SELECT FID, PID
		    FROM flatterers
		    WHERE FID = ".$_SESSION["sentimentFID"]."
		    AND PID = ".$_SESSION["sentimentPID"];
	//echo $querystr;
	$flatterer_result = $wpdb->get_results( $querystr, ARRAY_A);
	if ($flatterer_result) : 
		$validUser = false;
		foreach ($flatterer_result as $row) :
			$validUser = true; // Making sure there is a real valid row
		endforeach;
	else :
		$validUser = false;
	endif;
endif;

// Box Check
if ($validUser) : // Just to save on Queries
	$querystr = "
		    SELECT $wpdb->posts.* 
		    FROM $wpdb->posts
		    WHERE $wpdb->posts.ID = ".$PID."
		    AND $wpdb->posts.post_status = 'publish' 
		    AND $wpdb->posts.post_type = 'flatterboxes'
		    ORDER BY $wpdb->posts.post_date DESC
		 ";
	//echo $querystr;
	$flatterbox_exists_results = $wpdb->get_results( $querystr, ARRAY_A);
	if ($flatterbox_exists_results) : 
		$validUser = false;
		foreach ($flatterbox_exists_results as $row) :
			$validUser = true; // Making sure there is a real valid row
		endforeach;
	else :
		$validUser = false;
	endif;
endif;
// End Box Check

?>
<?php if( $PID > 0 && strlen(get_field('order_count',$PID )) == 0 && strlen(get_field('who_is_this_for',$PID )) > 0 && $validUser ) { // PID Exists and there is nothing in the order_count ?>
<div class="form_hidden" style="display:none;"><?php echo $PID; print_r(get_the_terms($PID, 'flatterbox_type')); ?></div>
<?php
if( get_the_terms($PID, 'flatterbox_type')) :
	foreach (get_the_terms($PID, 'flatterbox_type') as $cat) : 
		$catid =  $cat->term_id;
	endforeach;
else :
	$catid = 0;
endif;
$can_invite = get_field("can_invite",$PID); // echo '!'.$can_invite.'!'.get_field("can_invite",$PID).'!';
// $page_background_photo = getOccasionBGImg($catid); ?>

<?php if(get_field('allow_to_see_eachother',$PID)) {?>
	<div class="lightbox others">
		<?php include('includes/lightbox-what-others-said.php'); ?>
	</div>
<?php } ?>
	<div class="lightbox sentences">
		<?php include('includes/lightbox-sentence-starters.php'); ?>
	</div>
	<div class="lightbox gallery">
		<?php include('includes/lightbox-sentence-gallery.php'); ?>
	</div>
<main id="main" role="main" class="flattererview">
<?php if (false) : ?><main id="main" role="main" style="background-image: url('<?php the_field('page_background_photo'); ?>');>"><?php endif; ?>

	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<ul id="status-bar">
			<li class="active<?php if( empty($can_invite) || !$can_invite ) : echo ' no_invites'; endif; ?>"><a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&newcard=1">Compose Sentiment</a></li>
			<li<?php if( empty($can_invite) || !$can_invite ) : echo ' class="no_invites"'; endif; ?>>Review Sentiment</li>
			<?php if( $can_invite ) : ?>
			<li>Invite Flatterers</li>
			<li>Send Invitations</li>
			<?php endif; ?>
			<?php
				$laststep = 'Create Account';
				if ( is_user_logged_in() ) : $laststep = 'Complete'; endif;
			?>
			<li<?php if( empty($can_invite) || !$can_invite ) : echo ' class="no_invites"'; endif; ?>><?php echo $laststep ?></li>
		</ul>
		<section id="sentiment-passcode-form" class="sentiment-holder" style="display:none;">
			<div class="compose">
			<h2>Enter passcode</h2>
			<p>In order to compose your sentiment, you must first enter in the passcode that was sent to you along with your invitation.</p>
			<p>Passcode: <input id="passcode" name="passcode" type="text" /></p>
			<input type="button" class="btn" value="Submit" id="passcodesubmit" /><br/>
			<span style="color:red;display:none;" id="passcode_err">Code does not match, please check the code sent in your e-mail and try again.</span>
			</div>
		</section>
		<section id="sentiment-form-holder" class="sentiment-holder">

<?php		

if(isset($_GET["SID"]))
{

$_SESSION["sentimentSID"] = $_GET["SID"];
$sentiment2edit = $wpdb->get_results("SELECT * FROM sentiments WHERE SID = " . $_GET["SID"]);


}
		
?>
		
			<div class="compose">
				<h2>Compose your sentiment.</h2>
				<p>Occasion: <strong><?php the_field("occasion",$_SESSION["sentimentPID"]); ?></strong></p>
				<p>This gift is meant to be a reflection of your personal relationship with <strong><?php the_field("who_is_this_for",$_SESSION["sentimentPID"]); ?></strong>, so there are no rules.  No need for greetings and salutations, just go for it.</p>
				<p>Please share "<strong><?php the_field("box_theme",$_SESSION["sentimentPID"]); ?></strong>" in the box below.  Don’t forget your name, too.</p>

				<?php if (false) : ?><p>Write a heart-felt sentiment to <?php the_field("who_is_this_for",$_SESSION["sentimentPID"]); ?> for their <?php the_field("occasion",$_SESSION["sentimentPID"]); ?> Flatterbox.</p>
				<p>Please share one <?php echo strtolower(get_field("occasion",$_SESSION["sentimentPID"])); ?> tip. Write your sentiment in the form below. You’ll have the opportunity to write another card after completing this one.</p>
				<?php endif; ?>
				<?php if (false) : ?><p>Somebody thinks you’re pretty special. You have been selected to write a heart-felt sentiment to Jack & Diane for their 1st Anniversary Flatterbox.</p>
				<p>Please share one happy marriage tip. Write your sentiment in the form below. You’ll have the opportunity to write another card after completing this one.</p>
				<?php endif; ?>

				<form action="<?php echo do_shortcode('[site_url]'); ?>/flatterer-review-sentiments/" class="compose-form" id="sentiment-form" method="post">
				<?php
				if(isset($_GET["SID"]))
				{
					foreach ( $sentiment2edit as $sentimentdata ) 
					{
					?>
						<fieldset>
							<div class="row">
								<textarea class="textarea" id="sentimenttext" name="sentimenttext" onkeyup="countChar(this,270);cloneText(this);" onmouseup="countChar(this,270);cloneText(this);" placeholder="Compose your sentiment here." required><?php echo stripslashes($sentimentdata->sentiment_text); ?></textarea>
								<script>
								$(document).ready(function() {
									countChar(document.getElementById('sentimenttext'),270);cloneText(document.getElementById('sentimenttext'));
								});
								</script>
								<span class="note"><strong class="charNum">270</strong> CHARACTERS LEFT</span>
							</div>
							<div class="row">
								<input type="text" id="namefamily" name="namefamily" onkeyup="countChar(this,35);cloneName(this);" onmouseup="countChar(this,35);cloneName(this);" onBlur="countChar(this,35);cloneName(this, true);" placeholder="Your Name (or family)" value="<?php echo stripslashes($sentimentdata->sentiment_name); ?>" required />
								<script>
								$(document).ready(function() {
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
								<input type="text" id="namefamily" name="namefamily" onkeyup="countChar(this,35);cloneName(this);" onmouseup="countChar(this,35);cloneName(this);" placeholder="Your Name (or family)" required />
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
					<?php if(get_field('allow_to_see_eachother',$_SESSION["sentimentPID"])) {?>
					or of course you can <a id="others-wrote" href="<?php echo do_shortcode('[site_url]'); ?>/">See what others wrote</a>
					<?php } ?>
				</div>
			</div>
			<div class="example">
				<div id="card-preview" class="img-holder blank-card">
					<span id="card-preview-text"><?php if ($sentimentdata->sentiment_text) : echo stripslashes($sentimentdata->sentiment_text); else : ?>Compose your sentiment here.<?php endif; ?></span>
					<span id="card-preview-name">- <?php if ($sentimentdata->sentiment_name) : echo stripslashes($sentimentdata->sentiment_name); else : ?>Your Name (or family)<?php endif; ?></span>
				</div>
			</div>			
		</section>
		<div class="control-bar bottom">
			<a href="javascript:;" id="continuebtn" onclick="valdreqrd();" class="btn save">Save &amp; Continue</a>
		</div>		
	</div>
</main>
<script type="text/javascript">

function valdreqrd() {
		
		var gettextmsg = jQuery('#sentimenttext').val();
		
		var getyourname =  jQuery('#namefamily').val();
		
		if (gettextmsg == "") {
			alert('Please enter Compose your sentiment here');
			jQuery('#sentimenttext').focus();
		} else if (getyourname == "") {
			alert('Please enter Your Name (or family)');
			jQuery('#namefamily').focus();
		}
		else{
			jQuery('#submitcard').trigger('click');
		}
		
	}


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
<?php

if(isset($_GET["PID"]))
{
	$private = get_field("private",$_GET["PID"]); // Is this the right one? Shouldnt it be "allow_to_see_eachother"
	
		if($private)
		{
		$_SESSION["sentimentprivacy"] = 1;
		} else {
		$_SESSION["sentimentprivacy"] = 0;
		}	
		
}

if(isset($_GET["PID"]) && !$_SESSION["flatterer_logged_in"])
{

		$private = get_field("private",$_GET["PID"]);

		if($private)
		{
		?>
		<script type="text/javascript">
		jQuery( document ).ready(function() {
			jQuery("#sentiment-form-holder").css("display","none");
			jQuery("#sentiment-passcode-form").css("display","block");
			jQuery("#continuebtn").css("display","none");
		});
		</script>
		<?php
		}		
}

?>

<script type="text/javascript">

 jQuery("#passcodesubmit").click(function(){
	jQuery.ajax({
	url:"/flatterbox/wp-content/themes/flatterbox/process_passcode.php",
	type:'post',
	data:{ 'passcode': jQuery("#passcode").val(), 'PID':'<?php echo $_GET["PID"] ?>', 'FID':'<?php echo $_GET["FID"] ?>' },
	success: function(data, status) {
        if(data == "ok") {
			jQuery("#sentiment-form-holder").css("display","block");
			jQuery("#sentiment-passcode-form").css("display","none");
			jQuery("#continuebtn").css("display","block");
        } else {
			jQuery("#passcode_err").css("display","block");
		}
      },
      error: function(xhr, desc, err) {
        console.log(xhr);
        console.log("Details: " + desc + "\nError:" + err);
      }	
	 }
	);

 });

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
<?php } else { ?>
	<main id="main" role="main">
		<div class="container">
			<div class="largebox">
				<div class="boximage" style="background-image: url('<?php echo home_url(); ?>/wp-content/uploads/2015/02/266x266.jpg');"></div>
				<div class="boxinfo">
					<h2>Flatterbox</h2>
					<p>We're sorry this Flatterbox project is finished and is not accepting any further sentiments, all is not lost, you can of course create your own Flatterbox right with us.</p>
					<div class="inforow">
						<h3>Start Yours today!</h3>
						<a href="<?php echo home_url(); ?>/choose-card/" class="btn orange">Create Yours!</a>
					</div>
				</div>
			</div>
		</div>
	</main>
<?php } ?>
<?php get_footer(); ?>		