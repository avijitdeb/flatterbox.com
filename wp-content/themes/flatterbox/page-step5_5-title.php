<?php 

/* Template Name: FB Step 5_5 Title Card */

get_header('steps'); 
?>
<?php

	include 'flatterer-invitation.php';

?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="lightbox confirm">
	<?php include('includes/lightbox-confirm.php'); ?>
</div>
<main id="main" role="main">
	<div class="container">
		<div class="heading steps">
			<h1><?php the_field('page_title'); ?></h1>
		</div>

		<ul id="status-bar">
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/choose-card/?preservesession=1">Create It</a></li>
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterbox-options/">Personalize It</a></li>
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/invitations/">Invite Flatterers</a></li>
			<li class="active">Title Card</li>
			<li class="clearpadding">Write the First One!</li>
			<li>Complete</li>
		</ul>
		<?php 
			$populatePID = 0;
			if ($_SESSION['new_flatterbox_id']) : $populatePID = $_SESSION['new_flatterbox_id']; endif;
			if ($_GET['PID']) : $populatePID = $_GET['PID']; endif;
			if ($populatePID > 0) : $headline = get_field("title_card_headline",$populatePID); $fromname = get_field("title_card_name",$populatePID); endif;
			$headlinep = 'Box Headline.';
			$fromnamep = 'Who\'s This Box From?';
		?>
		<section id="sentiment-form-holder" class="sentiment-holder">
			<div class="compose full-height">
				<h2>Compose Your Title Card.</h2>
				<p>Add a welcome message in the box.</p>
				<p>Occasion: <strong><?php the_field("occasion",$_SESSION["new_flatterbox_id"]); ?></strong></p>
				<p>Give a little introduction to the box to <strong><?php the_field("who_is_this_for",$_SESSION["new_flatterbox_id"]); ?></strong>, welcome them to their gift.</p>
				
				<form action="<?php echo do_shortcode('[site_url]'); ?>/compose-your-sentiment/<?php if ($_GET['PID']) { echo '?redirect=1'; }?>" class="compose-form" id="sentiment-form" method="post">
					<fieldset>
						<div class="row">
							<input type="text" id="sentimenttext" name="sentimenttext" onkeyup="countChar(this,30);cloneText(this);" onblur="if (this.value.length > 0) { countChar(this,30);cloneText(this); }" placeholder="<?php echo $headlinep; ?>" required />
							<span class="note"><strong class="charNum">30</strong> CHARACTERS LEFT</span>
						</div>
						<div class="row">
							<input type="text" id="titlenamefamily" name="titlenamefamily" onkeyup="countChar(this,35);cloneName(this);" onblur="if (this.value.length > 0) { countChar(this,35);cloneName(this, true); }" value="<?php echo $fromname; ?>" required placeholder="<?php echo $fromnamep; ?>" />
							<span class="note"><strong class="charNum">35</strong> CHARACTERS LEFT</span>
						</div>
						<input id="sentimentPID" name="sentimentPID" type="hidden" value="<?php echo $populatePID; ?>" />
						<input id="submittitlecard" name="submittitlecard" type="submit" value="submit" style="display:none;" />
					</fieldset>
				</form>
			</div>
			<div class="example">
				<div id="card-preview" class="img-holder blank-card titlecard">
					<span id="card-preview-text"><?php echo $headlinep; ?></span>
					<span id="card-preview-name"><?php echo $fromnamep; ?></span>
					<div class="fb_squ_logo"><img src="<?php echo get_template_directory_uri(); ?>/images/squarelogo.png" alt="<?php echo stripslashes(''); ?>"></div>
				</div>
			</div>
		</section>
		<div class="control-bar bottom">
			<a href="<?php echo do_shortcode('[site_url]'); ?>/invitations/" class="btn back" onclick="gotoStep5();">Back to Invite Flatterers</a>
			<a href="javascript:;" id="continuebtn" onclick="jQuery('#submittitlecard').trigger('click');" class="btn save">Next</a>
		</div>		
	</div>
</main>
<?php endwhile; endif; ?>
<script type="text/javascript">

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
	jQuery("#"+sentiment.id).val(htmlDecode(jQuery("#card-preview-text").html()));
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
	function gotoStep5() {
		document.location = '<?php echo do_shortcode('[site_url]'); ?>/invitations/';
	}
	jQuery(document).ready(function($) {

		if (window.history && window.history.pushState) {

			$(window).on('popstate', function() {
				var hashLocation = location.hash;
				var hashSplit = hashLocation.split("#!/");
				var hashName = hashSplit[1];

				if (hashName !== '') {
					var hash = window.location.hash;
					if (hash === '') {
						// Call Back...
						doConfirm("Clicking the Browser Back Button may cause you to lose information. Please use the Back button link at the bottom of the page instead. Are you sure you want to leave this page?", function yes()
						{
							gotoStep5();
						}, function no() {
						    // do nothing
						});
					}
				}
			});

			window.history.pushState('forward', null, './#forward');
		}

	});

	function doConfirm(msg, yesFn, noFn)
	{
	  	jQuery( ".lightbox.confirm" ).fadeToggle("fast");
	    var confirmBox = jQuery("#confirmBox");
	    confirmBox.find(".message").text(msg);
	    confirmBox.find(".yes,.no").unbind().click(function()
	    {
	        confirmBox.hide();
	    });
	    confirmBox.find(".yes").click(yesFn);
	    confirmBox.find(".no").click(noFn);
	    confirmBox.show();
	}
</script>


<?php if($populatePID > 0) { ?>
<script>
jQuery( document ).ready(function() {
	<?php if ( strlen($headline) > 0) : ?>
	jQuery("#sentimenttext").val("<?php echo $headline; ?>");
	countChar(jQuery("#sentimenttext").get(0),30);
	cloneText(jQuery("#sentimenttext").get(0));
	<?php endif; ?>
	<?php if ( strlen($fromname) > 0) : ?>
	jQuery("#titlenamefamily").val("<?php echo $fromname; ?>");
	cloneName(jQuery("#titlenamefamily").get(0));
	<?php endif; ?>
});	
</script>
<?php } ?>

<?php get_footer(); ?>		