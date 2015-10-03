<div class="holder">
	<a href="#" class="close" id="close">close</a>
	<strong class="title">Sentence starters:</strong>
	<ul class="card-list">
		<?php

		if(isset($_SESSION["new_flatterbox_id"]))
		{
			$PID = $_SESSION["new_flatterbox_id"];
		}

		if(isset($_SESSION["sentimentPID"]))
		{
			$PID = $_SESSION["sentimentPID"];
		}
		
		foreach (get_the_terms($PID, 'flatterbox_type') as $cat) : 
			$catid =  $cat->term_id;
		endforeach;

		// args
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'sentence_starter',
		);

		// get results
		$the_query = new WP_Query( $args );

		// The Loop
		?>
		<?php if( $the_query->have_posts() ): ?>
					
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>	
			
				<?php if(in_array($catid, get_field('flatterbox_type',get_the_ID()))) { ?>
				
					<li class="sentencestarter">
						<div class="frame">
							<p><?php the_field('sentence'); ?></p>
						</div>
					</li>		
				
				<?php } ?>

			<?php endwhile; ?>
		
		<?php endif; ?>

		<?php wp_reset_query();  // Restore global post data stomped by the_post(). ?>		
		
	</ul>
</div>
<script>
jQuery( ".close" ).click(function(event) {
	event.preventDefault();
  	jQuery(this).parent().parent().fadeOut("fast");
});
</script>