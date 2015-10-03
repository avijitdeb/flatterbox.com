<div class="holder">
	<?php
	global $wpdb;
	if(isset($_SESSION["new_flatterbox_id"]))
	{
		$PID = $_SESSION["new_flatterbox_id"];
	}

	if(isset($_SESSION["sentimentPID"]))
	{
		$PID = $_SESSION["sentimentPID"];
	}
	if(isset($_GET['PID']))
	{
		$PID = $_GET['PID'];
	}
	
	foreach (get_the_terms($PID, 'flatterbox_type') as $cat) : 
		$catid =  $cat->term_id;
	endforeach;
	?>
	<a href="#" class="close" id="close">close</a>
	<strong class="title">Invites for your Flatterbox</strong>
	<strong class="title">Responded</strong>
	<ul class="card-list">
		<?php
		$results = $wpdb->get_results( "SELECT * FROM flatterers WHERE invalid = 0 AND PID = " . $PID ." ORDER BY responded DESC", ARRAY_A);
		$responded = true;

		if ($results) {

			foreach ($results as $Row) {
				if ($Row["responded"] == "0" && $responded) :
					$responded = false;
					?>
	</ul>
	<strong class="title">Yet to Respond</strong>
	<ul class="card-list">
					<?php
				endif;
			?>
			<li>
				<div class="frame">
					<p><?php echo $Row["flatterer_email"]; ?></p>
				</div>
			</li>
			<?php
			}  // foreach
		} // if $results
		?>				
	</ul>
</div>
<script>
jQuery( ".close" ).click(function(event) {
	event.preventDefault();
  	jQuery(this).parent().parent().fadeOut("fast");
});
</script>