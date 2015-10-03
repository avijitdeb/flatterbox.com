<?php 

/* Template Name: Reminder Emails */
// Redirect if not logged in
if ( !is_user_logged_in() ) : wp_redirect(home_url()); endif;

$PID = $_GET['PID'];

$args = array(
			'post_type' => 'flatterboxes',
			'posts_per_page' => 1,
			'p' => $PID
			);
$the_query = new WP_Query( $args );

if ( $the_query->have_posts() ) :
	while ( $the_query->have_posts() ) : $the_query->the_post();
		if (get_post_field( 'post_author', $PID ) != get_current_user_id()) : wp_redirect(home_url().'/my-flatterbox/'); exit; endif;
	endwhile;
endif;

get_header();

?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main">
	<div class="container">
		<form method="POST">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<div class="box">
			<div class="box-padding">
				<?php 
				if ($_POST['approved'] == 1) : 
					echo '<h2>Reminders Sent</h2>';
					the_content();
					// Special Instructions
					__update_post_meta( $PID, 'special_instructions_to_flatterers', $value = $_POST['special-instructions']); 
					// Reminder
					if ( false ) : // Hiding, but keeping incase we want to add it back in 7/14/2015 
					__update_post_meta( $PID, 'reminder_instructions', $value = $_POST['additional-reminder']); 
					endif;
					processFlattererReminder(0, $PID, $_POST['flatterers'], false); // Change to true to re-enable reminder portion (also in includes/emailFunctions.php)
					$hideSubmit = true;
				else : 
					?>
				<h2>Reminder to Send</h2>
				<div id="flattererfield-single">
				<?php 
				$flatterer_results = $wpdb->get_results( "SELECT * FROM flatterers WHERE flatterer_email <> '' AND PID = " . $PID, ARRAY_A);

				$rowCount = $wpdb->num_rows; $nCount =0;
				$hideSubmit = false;
				?>
				<?php
				if ($flatterer_results) :
					echo '<div class="chk-reminder"><input type="checkbox" id="selectall" value="" checked="checked">&nbsp;Select / Deselect All</div><div class="clearing"></div>';
					foreach ($flatterer_results as $row) :
						if (true || $nCount < $rowCount / 2) : $float = ' fleft'; else : $float = ' fright'; endif;
						echo '<div class="chk-reminder'.$float.'"><input type="checkbox" class="chk-boxes" name="flatterers[]" value="'.$row["FID"].'" checked="checked">&nbsp;'.$row["flatterer_email"].'</div>';
						$nCount++;
					endforeach;
					echo '<div class="clearing"></div>';
					?>
					<h3 class="additional-reminder-text">Edit Personal Message</h3>
					<textarea class="additional-reminder" name="special-instructions" id="special-instructions" placeholder="Please enter any flatterer instructions..."><?php if (get_field('special_instructions_to_flatterers', $PID)) : echo get_field('special_instructions_to_flatterers', $PID); endif; ?></textarea>
					
					<?php if ( false ) : // Hiding, but keeping incase we want to add it back in 7/14/2015 ?>
					<h3 class="additional-reminder-text">Reminder Instructions</h3>
					<textarea class="additional-reminder" name="additional-reminder" id="additional-reminder" placeholder="Please enter reminder instructions..."><?php if (get_field('reminder_instructions', $PID)) : echo get_field('reminder_instructions', $PID); endif; ?></textarea>
					<?php endif; ?>
					
					<input type="hidden" name="approved" value="1" />

				<br>
				<?php 
				else :
					$hideSubmit = true;
					echo 'There are no Flatterers associated with your Flatterbox.<br/><br/> <a class="btn small actions fleft" href="'.home_url().'/invite-more/?PID='.$PID.'">Invite More Flatterers</a>';
				endif;
				?>
				</div>
				<div id="flatterer-preview">
					<h3>Your Flatterbox Reminder – Preview</h3>
					<div class="flatterer-preview-options">
						<?php 
							//include ("preview-email.php"); // Changed to the Functions
							echo get_preview_email($PID, false); // Set to true for reminders page tracking
						?>
					</div>
				</div>
				<div class="clearing"></div>
			<?php endif; ?>
			</div>
		</div>
		<div class="control-bar bottom">
			<a class="btn" href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/" style="float:left;">Back to My Flatterbox</a>
			<input type="submit" class="btn orangebtn" value="Send Reminders" <?php if ($hideSubmit) : echo ' style="display:none;" onclick="return false;"'; endif; ?> />
		</div>
		</form>
	</div>
</main>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#selectall').click(function(event) {  //on click 
        if(this.checked) { // check select status
            jQuery('.chk-boxes').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            jQuery('.chk-boxes').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
    });
    <?php if(is_page_template('page-reminders.php')) : ?>
    	// Special Instructions
		jQuery('#special-instructions').keyup(function() { jQuery('#special_inst').html(jQuery('#special-instructions').val().replace(/(?:\r\n|\r|\n)/g, '<br>')); });
		<?php if ( false ) : // Hiding, but keeping incase we want to add it back in 7/14/2015 ?>
		// Reminder Instructions
		jQuery('#additional-reminder').keyup(function() { jQuery('#reminder_inst').html(jQuery('#additional-reminder').val().replace(/(?:\r\n|\r|\n)/g, '<br>')); });
		<?php endif; ?>
	<?php endif; ?>
});
</script>
<?php endwhile; endif; ?>
<?php get_footer(); ?>