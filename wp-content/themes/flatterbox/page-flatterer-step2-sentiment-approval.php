<?php 

/* Template Name: Flatterer Step 2 Sentiment Approval */

get_header(); ?>
<?php // Do we need this file ?? ?>
<?php

//Save sentiment info to populate if they click to edit.

$_SESSION["sentimenttext"] = $_POST["sentimenttext"];
$_SESSION["sentimentname"] = $_POST["namefamily"];
$_SESSION["sentimentPID"] = $_POST["sentimentPID"];
$_SESSION["sentimentFID"] = $_POST["sentimentFID"];

foreach (get_the_terms($_SESSION["sentimentPID"], 'flatterbox_type') as $cat) : 
	$catid =  $cat->term_id;
endforeach;

$PID = intval($_SESSION["sentimentPID"]);
$can_invite = get_field("can_invite",$PID); // echo '!'.$can_invite.'!'.get_field("can_invite",$PID).'!';
?>		
<?php // $page_background_photo = getOccasionBGImg($catid); ?>
<div class="lightbox confirm">
	<?php include('includes/lightbox-confirm.php'); ?>
</div>
<main id="main" role="main" class="flattererview">
<?php if (false) : ?><main id="main" role="main" style="background-image: url('<?php the_field('page_background_photo'); ?>');>"><?php endif; ?>
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
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<ul id="status-bar">
			<li class="done<?php if( empty($can_invite) || !$can_invite ) : echo ' no_invites'; endif; ?>">Compose Sentiment</li>
			<li class="active<?php if( empty($can_invite) || !$can_invite ) : echo ' no_invites'; endif; ?>">Review Sentiment</li>
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
		<section class="sentiment-holder">
			<div class="compose">
				<h2>Review Your Sentiment.</h2>
			</div>
			<div class="example">
				<div id="card-preview" class="img-holder blank-card" style="margin-left:auto !important;margin-right:auto !important;">
					<span id="card-preview-text"><?php echo stripslashes($_POST["sentimenttext"]); ?></span>
					<span id="card-preview-name"><?php echo stripslashes($_POST["namefamily"]); ?></span>
				</div>
			</div>			
		</section>
		<div class="control-bar bottom">
			<a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/<?php if(isset($_SESSION["sentimentPID"])) { ?>?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"];  } ?>" class="back">Edit My Sentiment</a>
			<?php if( $can_invite ) : ?>
			<a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-create-account-and-invite/" class="btn save" style="display:block;margin-left:15px;">Confirm and continue</a>
			<?php endif; ?>
			<a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-review-sentiments/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&addcard=1" class="btn save" style="display:block;margin-left:15px;">Confirm and review all cards</a>
			<a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/<?php if(isset($_SESSION["sentimentPID"])) { ?>?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"];  } ?>&newcard=1" class="btn save">Add Another Card</a>
			
			<div class="divider"></div>
			<form class="marketing text-right">
				<h3>Send Me Flatterbox Updates</h3> <label><input type="radio" checked name="email" value="Yes">Yes</label><label><input type="radio" name="email" value="No">No</label>
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
    if (len >= countLimit) {
        val.value = val.value.substring(0, countLimit);
    } else {
        jQuery("#"+val.id+"").siblings().find(".charNum").text(countLimit - len);
    }
};

	function gotoStep1() {
		document.location = '<?php echo do_shortcode('[site_url]'); ?>/sentiment/<?php if(isset($_SESSION["sentimentPID"])) { ?>?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"];  } ?>';
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
							gotoStep1();
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
<?php get_footer(); ?>		