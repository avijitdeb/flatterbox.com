<?php 

/* Template Name: Order Step 3 - Order Confirmation */

get_header(); 

$flatterbox_info = get_post($_SESSION["sentimentPID"]); 
$PID = $flatterbox_info->ID;

?>
<?php // $page_background_photo = getOccasionBGImg(0); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<?php
			foreach (get_the_terms($_SESSION["sentimentPID"], 'flatterbox_type') as $cat) : 
				$catimage =  z_taxonomy_image_url($cat->term_id);
				$catname = $cat->name;
			endforeach;
			?>			
			<h1><?php the_field('who_is_this_for',$_SESSION["sentimentPID"]); ?>'s <?php echo $catname; ?></h1>
		</div>
		<h2>Flatterbox Preview</h2>
		<div class="preview">
			<div class="sample">
				<?php if (get_field('card_style',$PID) == "Blue") {
					$backgroundCardStyle = "<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/images/fb_cards_full_blue.png";
				} else if (get_field('card_style',$PID) == "Tan") {
					$backgroundCardStyle = "<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/images/fb_cards_full_tan.png";
				} else {
					$backgroundCardStyle = "<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/images/fb_cards_full_blue.png";
				} ?>
				<div class="sentiment" style="background-image: url(<?php echo $backgroundCardStyle?>);"></div>
				
			</div>
			<div class="sample">
				<div class="box"><img src="<?php the_field('box_image_url',$PID); ?>" /></div>
				
			</div>
			<div class="sample">
				<div class="sentiment">
					<div class="frame">
						<h2><?php the_field('card_quantity',$PID); ?></h2>
						<p>Sentiments</p>
					</div>
				</div>
				
			</div>
		</div>
		<div class="divider"></div>
		
		<div class="generic order-shipping-address">
		
		<?php
		
		//Loop through all addresses associated with the flatterbox
		
		$address_results = $wpdb->get_results( "SELECT * FROM orderinfo WHERE PID = " . $PID, ARRAY_A);
		$totalquantity = 0;
		if ($address_results) {
		
		foreach ($address_results as $Row)
		
		  {
		
		?>
		  
			<div class="one-third">
				<h3 class="box-count"><?php echo $Row["QUANTITY"]; ?> Flatterbox<?php if($Row["QUANTITY"] > 1) { echo "es"; } ?></h3>
				<h3><?php echo $Row["FNAME"]; ?> <?php echo $Row["LNAME"]; ?></h3>
				<p><?php echo $Row["ADDRESS"]; ?><br>
				<?php 
				if($Row["ADDRESS2"] <> "")
				{
					echo $Row["ADDRESS2"] . "<br>";
				}
				?>
				<?php echo $Row["CITY"]; ?>, <?php echo $Row["STATE"]; ?> <?php echo $Row["ZIP"]; ?></p>
			</div>
		
		<?php
		
			$totalquantity = $totalquantity + $Row["QUANTITY"];
		
		  }  // foreach

		} // if $results

		?>	
		
		</div>
		<div class="divider"></div>
		<h2>Order Total</h2>
		<div class="generic grand-total">
			<div class="one-third text-center">
				<h3 class="box-count">Flatterboxes with <?php the_field('card_quantity',$PID); ?> Sentiments</h3>
				<?php
					$boxprice = get_field('total_price',$PID);
					$subtotal = $boxprice * $totalquantity;
				?>
				<p><?php echo $totalquantity; ?> Flatterboxes <span class="math">X</span> $<?php the_field('total_price',$PID); ?> Ea. <span class="math">=</span> $<?php echo $subtotal; ?> Total</p>
			</div>
			<div class="one-third text-center">
				<h3 class="box-count">Shipping</h3>
				<?php $totalshipping = $totalquantity * 15; ?>
				<p><?php echo $totalquantity; ?> Flatterboxes <span class="math">X</span> $15 Ea. <span class="math">=</span> $<?php echo $totalshipping; ?> Total</p>
			</div>
			<div class="one-third text-center">
				<h3 class="box-count">Grant Total</h3>
				<?php $grandtotal = $subtotal + $totalshipping; ?>
				<h2>$<?php echo $grandtotal; ?><h2>
			</div>
		</div>
		<div class="control-bar bottom">
			<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox" class="btn save">Return to My Flatterbox</a>
		</div>
	</div>
</main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>		