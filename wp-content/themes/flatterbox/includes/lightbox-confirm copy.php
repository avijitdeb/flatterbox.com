<div class="holder">
	<a href="#" class="close" id="close">close</a>
	<strong class="title"></strong>
	<ul class="loginbox">
		<li class="outline">
			<div id="msgBox" class="frame">
    			<p class="message"></p>
				<p class="subtitle"></p>
				<a id="msg_close" href="#" class="btn save no">Close</a>
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