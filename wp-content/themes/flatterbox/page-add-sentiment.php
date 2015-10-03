<?php 

/* Template Name: Add Sentiment */

get_header(); 

$_SESSION["sentimentPID"] = $_GET["PID"];
$_SESSION["sentimentFID"] = $_GET["FID"];

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
<main id="main" role="main">
<?php if (false) : ?><main id="main" role="main" style="background-image: url('<?php the_field('page_background_photo'); ?>');>"><?php endif; ?>

	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>

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
				<form action="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/review-sentiments/?PID=<?php echo $_GET["PID"] ?>" class="compose-form" id="sentiment-form" method="post">
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
								<input type="text" id="namefamily" name="namefamily" onkeyup="countChar(this,35);cloneName(this);" onmouseup="countChar(this,35);cloneName(this);" placeholder="Your Name (or family)" value="<?php echo stripslashes($sentimentdata->sentiment_name); ?>" required />
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
								<input type="text" id="namefamily" name="namefamily" onkeyup="countChar(this,35);cloneName(this);" onmouseup="countChar(this,35);cloneName(this);" onBlur="countChar(this,35);cloneName(this, true);"placeholder="Your Name (or family)" required />
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
			<a href="javascript:;" id="continuebtn" onclick="jQuery('#submitcard').trigger('click');" class="btn save">Continue</a>
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
	jQuery("#card-preview-text").html(sentiment.value.replace(/(?:\r\n|\r|\n)/g, '<br>'));
	var curPos = getCaretPosition("#"+sentiment.id);
	var curPos1 = jQuery("#"+sentiment.id).getCursorPosition();
	console.log(curPos + ' ' + curPos1);
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
function getCaretPosition (oField) {

  // Initialize
  var iCaretPos = 0;

  // IE Support
  if (document.selection) {

    // Set focus on the element
    oField.focus ();

    // To get cursor position, get empty selection range
    var oSel = document.selection.createRange ();

    // Move selection start to 0 position
    oSel.moveStart ('character', -oField.value.length);

    // The caret position is selection length
    iCaretPos = oSel.text.length;
  }

  // Firefox support
  else if (oField.selectionStart || oField.selectionStart == '0')
    iCaretPos = oField.selectionStart;

  // Return results
  return (iCaretPos);
}
function setCaretPosition(elemId, caretPos) {
    var elem = document.getElementById(elemId);

    if(elem != null) {
        if(elem.createTextRange) {
            var range = elem.createTextRange();
            range.move('character', caretPos);
            range.select();
        }
        else {
            if(elem.selectionStart) {
                elem.focus();
                elem.setSelectionRange(caretPos, caretPos);
            }
            else
                elem.focus();
        }
    }
}
(function ($, undefined) {
    $.fn.getCursorPosition = function() {
        var el = $(this).get(0);
        var pos = 0;
        if('selectionStart' in el) {
            pos = el.selectionStart;
        } else if('selection' in document) {
            el.focus();
            var Sel = document.selection.createRange();
            var SelLength = document.selection.createRange().text.length;
            Sel.moveStart('character', -el.value.length);
            pos = Sel.text.length - SelLength;
        }
        return pos;
    }
})(jQuery);
</script>

<?php get_footer(); ?>		