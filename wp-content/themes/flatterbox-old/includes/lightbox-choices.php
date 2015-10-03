<div class="holder">
	<a href="#" class="close" id="close">close</a>
	<strong class="title">Login or Register</strong>
	<ul class="card-list">
		<li class="login">
			<div class="frame choices">
				<h2>Thank You!</h2>
				<p>Your sentiment has been received.</p>
				<?php echo do_shortcode('[divider]'); ?>
				<?php if (!isset($_POST['present_item'])) : ?>
					<a href="#" class="btn save" id="close_too">Add Another Sentiment</a>
					<a href="#" class="btn save" id="close_too_too">Review Your Sentiment</a>
					<?php if ( get_field("can_invite",$_SESSION["sentimentPID"]) ) : ?><a href="<?php echo home_url('/flatterer-create-account-and-invite/'); ?>" class="btn save">Invite Others</a><?php endif; ?>
					<a href="<?php echo home_url(); ?>" class="btn save">Go to Homepage</a>
					<?php if (false) : ?>
					<a href="<?php echo home_url('/create-an-account/'); ?>" class="btn save">Create Your Account</a>
					<?php endif; ?>
				<?php else : ?>
					<a href="#" class="continue btn save">Continue</a>
				<?php endif;?>
			</div>
		</li>
	</ul>
	
</div>
<script>
jQuery( ".close" ).click(function(event) {
	event.preventDefault();
  	jQuery(this).parent().parent().fadeOut("fast");
});
jQuery( ".continue" ).click(function(event) {
	event.preventDefault();
  	jQuery( ".close" ).trigger('click');
});
jQuery( "#close_too" ).click(function(event) {
	event.preventDefault();
  	jQuery(this).parent().parent().parent().parent().parent().fadeOut("fast");
});
jQuery( "#close_too_too" ).click(function(event) {
	event.preventDefault();
  	jQuery( "#close_too" ).trigger('click');
});
</script>