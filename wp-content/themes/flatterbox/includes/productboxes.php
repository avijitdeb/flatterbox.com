<div class="holder">
	<a href="#" class="close" id="close">close</a>
	<strong class="title">Flatterbox Sizes</strong>
	<div class="giftboxes">
		<div class="giftbox">
			<div class="boximage" style="background-image: url('<?php bloginfo('stylesheet_directory'); ?>/images/renderings/50.png');">
			</div>
			<div class="boxinfo boxes">
				<h2>Small 1.5in x 3.5x3.5</h2>
				<p>Holds up to 50 cards</p>
			</div>
		</div>
		<div class="giftbox">
			<div class="boximage" style="background-image: url('<?php bloginfo('stylesheet_directory'); ?>/images/renderings/100.png');">
			</div>
			<div class="boxinfo boxes">
				<h2>Medium 2.5in x 3.5 x 3.5</h2>
				<p>Holds up to 100 cards</p>
			</div>
		</div>
		<div class="giftbox">
			<div class="boximage" style="background-image: url('<?php bloginfo('stylesheet_directory'); ?>/images/renderings/200.png');">
			</div>
			<div class="boxinfo boxes">
				<h2>Large 3.5in x 3.5in x 3.5in</h2>
				<p>Holds up to 200 cards</p>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery( ".close" ).click(function($) {
	event.preventDefault();
  	jQuery(this).parent().parent().fadeOut("fast");
});
</script>