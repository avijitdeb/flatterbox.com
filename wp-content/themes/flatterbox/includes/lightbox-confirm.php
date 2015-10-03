<div class="holder">
	<a href="#" class="close" id="close">close</a>
	<strong class="title">Are You Sure?</strong>
	<ul class="loginbox">
		<li class="outline">
			<div id="confirmBox" class="frame">
    			<p class="message"></p>
				<a id="confirm" href="#" class="btn save yes">Yes</a>
				<a id="cancel" href="#" class="btn save no">No</a>
			</div>
		</li>
	</ul>
	
</div>
<script type="text/javascript">
jQuery( ".close" ).click(function(event) {
	event.preventDefault();
  	jQuery(this).parent().parent().fadeOut("fast");
});
</script>