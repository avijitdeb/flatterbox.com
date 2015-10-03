<?php 

/* Template Name: Flatterer Step 2 Sentiment Approval */

get_header(); ?>
<?php

//Save sentiment info to populate if they click to edit.

$_SESSION["sentimenttext"] = $_POST["sentimenttext"];
$_SESSION["sentimentname"] = $_POST["namefamily"];

?>
<main id="main" role="main" style="background-image: url('<?php the_field('page_background_photo'); ?>');>">
	<div class="lightbox others">
		<?php include('includes/lightbox-what-others-said.php'); ?>
	</div>
	<div class="lightbox sentences">
		<?php include('includes/lightbox-sentence-starters.php'); ?>
	</div>
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<section class="sentiment-holder">
			<div class="compose">
				<h2>Review Your Sentiment.</h2>
			</div>
			<div class="example">
				<div id="card-preview" class="img-holder blank-card" style="margin-left:auto !important;margin-right:auto !important;">
					<span id="card-preview-text"><?php echo $_POST["sentimenttext"]; ?></span>
					<span id="card-preview-name"><?php echo $_POST["namefamily"]; ?></span>
				</div>
			</div>			
		</section>
		<div class="control-bar bottom">
			<a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/" class="back">Edit My Sentiment</a>
			<a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-create-account-and-invite/" class="btn save">I Approve My Sentiment - Please Send</a>
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
</script>
<?php get_footer(); ?>		