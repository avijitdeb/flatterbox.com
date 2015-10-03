<?php 

/* Template Name: Present Sentiment */

global $wpdb;

$_SESSION["sentimentPID"] = $_GET["PID"];
$_SESSION["sentimentprivacy"] = get_field('private', $_SESSION["sentimentPID"]);
$isLive = true;
if(isset($_POST["sentimenttext"]))
{
	$wpdb->insert( 
		'flatterers', 
		array( 
			'PID' => $_SESSION["sentimentPID"], 
			'flatterer_email' => $_POST["flattereremail"],
			'flatterer_name' => '',
			'responded' => 1
		), 
		array( 
			'%d', 
			'%s',
			'%s',
			'%d'
		) 
	);
	$FID = $wpdb->insert_id;

	$wpdb->insert( 
		'sentiments', 
		array( 
			'FID' => $FID, 
			'PID' => $_SESSION["sentimentPID"],
			'sentiment_text' => $_POST["sentimenttext"],
			'sentiment_name' => $_POST["namefamily"],
			'private' => $_SESSION["sentimentprivacy"]
		), 
		array( 
			'%d', 
			'%d',
			'%s',
			'%s',
			'%d'
		) 
	);
}

get_header('present'); 

if(isset($_POST["sentimenttext"])) : 
	echo '<script type="text/javascript">jQuery(document).ready(function(){jQuery( ".lightbox.choice" ).fadeToggle("fast");});</script>';
endif;

foreach (get_the_terms($_SESSION["sentimentPID"], 'flatterbox_type') as $cat) : 
	$catid =  $cat->term_id;
endforeach;
?>		
<?php // $page_background_photo = getOccasionBGImg($catid); ?>
<?php if(get_field('allow_to_see_eachother',$_SESSION["sentimentPID"])) {?>
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
<div class="lightbox choice">
	<?php include('includes/lightbox-choices.php'); ?>
</div>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>

		<section id="sentiment-form-holder" class="sentiment-holder">
	
			<div class="compose">
				<h2>Compose your sentiment.</h2>
				<p>Occasion: <strong><?php the_field("occasion",$_SESSION["sentimentPID"]); ?></strong></p>
				<p>This gift is meant to be a reflection of your personal relationship with <strong><?php the_field("who_is_this_for",$_SESSION["sentimentPID"]); ?></strong>, so there are no rules.  No need for greetings and salutations, just go for it.</p>
				<p>Please share "<strong><?php the_field("box_theme",$_SESSION["sentimentPID"]); ?></strong>" in the box below.  Don’t forget your name, too.</p>

				<form action="<?php echo do_shortcode('[site_url]'); ?>/present-sentiment/?PID=<?php echo $_GET["PID"] ?>" class="compose-form" id="sentiment-form" method="post">
					<fieldset>
						<div class="row">
							<textarea class="textarea" id="sentimenttext" name="sentimenttext" onkeyup="countChar(this,270);cloneText(this);" onmouseup="countChar(this,270);cloneText(this);" placeholder="Compose your sentiment here." required></textarea>
							<span class="note"><strong class="charNum">270</strong> CHARACTERS LEFT</span>
							<span class="required" id="sSError"></span>
						</div>
						<div class="row">
							<input type="text" id="namefamily" name="namefamily" onkeyup="countChar(this,35);cloneName(this);" onmouseup="countChar(this,35);cloneName(this);" onBlur="countChar(this,35);cloneName(this, true);" placeholder="Your Name (or family)" required />
							<span class="note"><strong class="charNum">35</strong> CHARACTERS LEFT</span>
							<span class="required" id="sNError"></span>
						</div>
						<div class="row">
							<input type="text" id="flattereremail" name="flattereremail" onkeyup="" onmouseup="" placeholder="Your Email Address Here" required />
							<span class="required" id="sEError"></span>
						</div>
						<input id="sentimentPID" name="sentimentPID" type="hidden" value="<?php echo $_GET["PID"]; ?>" />
						<input id="sentimentFID" name="sentimentFID" type="hidden" value="0" />
						<input id="updatesentiment" name="updatesentiment" type="hidden" value="<?php if(isset($_GET["SID"])) { echo '1'; } else { echo '0'; } ?>" />
						<input id="present_item" name="present_item" type="hidden" value="present" />
						<input id="submitcard" name="submitcard" type="submit" value="submit" style="display:none;" />
					</fieldset>
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
			<a href="javascript:;" id="continuebtn" onclick="return fncSubmit();" class="btn save">Save</a>
		</div>		
	</div>
</main>
<script type="text/javascript">
function fncSubmit() {
	jQuery( "#sSError" ).text("").hide();
	jQuery( "#sNError" ).text("").hide();
	jQuery( "#sEError" ).text("").hide();
	rtn = confirm('Are you sure that you wish to Save this sentiment?');
	if(rtn) {
		valid = true;
		emailvalid = true;
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;

		if ( jQuery( "#sentimenttext" ).val().length <= 3 ) {
			jQuery( "#sSError" ).text( "Must be at least 4 characters." ).show();
			valid = false;
		} else {
			jQuery( "#sUError" ).text("").hide();
		}
		if ( jQuery( "#namefamily" ).val().length <= 3 ) {
			jQuery( "#sNError" ).text( "Must be at least 4 characters." ).show();
			valid = false;
		} else {
			jQuery( "#sFError" ).text("").hide();
		}
		if ( jQuery( "#flattereremail" ).val().length <= 3 || !emailReg.test(jQuery("#flattereremail").val())) {
			jQuery( "#sEError" ).text( "Please enter a valid email.").show();
			valid = false; 
			emailvalid = false;
		} else {
			jQuery( "#sEError" ).text("").hide();
		}
		rtn = valid;
		jQuery('#submitcard').trigger('click');
	}
	return rtn;
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

<script type="text/javascript">
function cloneText(sentiment)
{
	jQuery("#card-preview-text").html(sentiment.value.replace(/(?:\r\n|\r|\n)/g, '<br />'));
	<?php if ( !get_field('allow_profanity',$_SESSION["sentimentPID"]) ) : ?>
	removeProfanity("#card-preview-text");
	jQuery("#"+sentiment.id).val(jQuery("#card-preview-text").html());
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

<?php get_footer('present'); ?>		