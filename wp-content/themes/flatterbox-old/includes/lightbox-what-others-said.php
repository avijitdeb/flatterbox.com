<div class="holder">
	<a href="#" class="close" id="close">close</a>
	<strong class="title">Other people wrote:</strong>
	<ul class="card-list">
	
		<?php
		
		$PID = $_SESSION["sentimentPID"];
		
		if($_GET["PID"])
		{
			$PID = $_GET["PID"];
		}
		
		//Loop through all sentiments in the flatterbox

		
		$sentiment_results = $wpdb->get_results( "SELECT * FROM sentiments WHERE sentiment_text != '' AND PID = " . $PID, ARRAY_A);

		if ($sentiment_results) {

		foreach ($sentiment_results as $Row)
		
		  {
		
		?>
		
		<li>
			<div class="frame">
				<p><?php echo stripslashes($Row["sentiment_text"]); ?></p>
				<span class="name">-<?php echo stripslashes($Row["sentiment_name"]); ?></span>
			</div>
		</li>		
		
		<?php
		  }  // foreach

		} // if $results

		?>			
	
	</ul>
</div>
<script>
jQuery( ".close" ).click(function($) {
	event.preventDefault();
  	jQuery(this).parent().parent().fadeOut("fast");
});
</script>