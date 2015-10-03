<?php 

/* Template Name: Flatterer Step 1 Sentiment */

get_header(); ?>

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
				
				<form action="<?php echo do_shortcode('[site_url]'); ?>/sentiment-approval" class="compose-form" id="sentiment-form" method="post">
					<fieldset>
						<div class="row">
							<textarea class="textarea" id="sentimenttext" name="sentimenttext" onkeyup="countChar(this,300);cloneText(this);" placeholder="A vetri olorem sei mas nostra proin acru vetri otto viverra nec. Pdui eges congue loremi magnaiana. Praesent varium disctus alorem dei sun." <?php if(isset($_SESSION["sentimenttext"])) { ?>value="<?php echo $_SESSION["sentimenttext"]?>" <?php } ?>></textarea>
							<span class="note"><strong class="charNum">300</strong> CHARACTERS LEFT</span>
						</div>
						<div class="row">
							<input type="text" id="namefamily" name="namefamily" onkeyup="countChar(this,100);cloneName(this);" placeholder="Your Name (or family)" <?php if(isset($_SESSION["sentimentname"])) { ?>value="<?php echo $_SESSION["sentimentname"]?>" <?php } ?>>
							<span class="note"><strong class="charNum">100</strong> CHARACTERS LEFT</span>
						</div>
					</fieldset>
				</form>
				<div class="wrap">
					Need inspiration?<br/>We’ve got you covered with 
					<a id="sentence-starters" href="<?php echo do_shortcode('[site_url]'); ?>/">Sentence starters</a>
				 	<?php //and our Sentiment Library -- Not using this as the "See what others wrote" is used in place?>
					<?php if(get_field('allow_to_see_eachother',$_SESSION["sentimentPID"])) {?>
					or of course you can <a id="others-wrote" href="<?php echo do_shortcode('[site_url]'); ?>/">See what others wrote</a>
					<?php } ?>
				</div>
			</div>
			<div class="example">
				<div id="card-preview" class="img-holder blank-card">
					<span id="card-preview-text"><?php if(isset($_SESSION["sentimenttext"])) { echo stripslashes($_SESSION["sentimenttext"]) } else { ?>A vetri olorem sei mas nostra proin acru vetri otto viverra nec. Pdui eges congue loremi magnaiana. Praesent varium disctus alorem dei sun.<?php } ?></span>
					<span id="card-preview-name"><?php if(isset($_SESSION["sentimentname"])) { echo stripslashes($_SESSION["sentimentname"]) } else { ?>Your Name (or family)<?php } ?></span>
				</div>
			</div>			
		</section>
		<div class="control-bar bottom">
			
			<a href="javascript:;" id="continuebtn" onclick="$('#sentiment-form').submit();" class="btn save">Confirm</a>
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

<?php

if(isset($_GET["PID"]) && !$_SESSION["flatterer_logged_in"])
{

		$shareable = get_field("allow_to_share",$_GET["PID"]);

		if(!$shareable)
		{
		?>
		<script>
		$( document ).ready(function() {
			$("#sentiment-form-holder").css("display","none");
			$("#sentiment-passcode-form").css("display","block");
			$("#continuebtn").css("display","none");
		});
		</script>
		<?php
		}
		
}

?>

<script>

 $("#passcodesubmit").click(function(){
	$.ajax({
	url:"/flatterbox/wp-content/themes/flatterbox/process_passcode.php",
	type:'post',
	data:{ 'passcode': $("#passcode").val(), 'PID':'<?php echo $_GET["PID"] ?>', 'FID':'<?php echo $_GET["FID"] ?>' },
	success: function(data, status) {
        if(data == "ok") {
			$("#sentiment-form-holder").css("display","block");
			$("#sentiment-passcode-form").css("display","none");
			$("#continuebtn").css("display","block");
        } else {
			$("#passcode_err").css("display","block");
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

<script>
function cloneText(sentiment)
{
	$("#card-preview-text").html(sentiment.value);
}

function cloneName(sentimentname)
{
	$("#card-preview-name").html(sentimentname.value);
}
</script>

<?php get_footer(); ?>		