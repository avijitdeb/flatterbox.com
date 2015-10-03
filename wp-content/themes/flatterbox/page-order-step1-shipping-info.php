<?php 

/* Template Name: Order Step 1 - Shipping Information */

get_header(); ?>
<?php // $page_background_photo = getOccasionBGImg(0); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main">
<?php if (false) : ?><main id="main" role="main" style="background-image: url('<?php the_field('page_background_photo'); ?>');>"><?php endif; ?>
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
		<div class="generic">
		
		<?php if($_GET["adding"])
		{
		echo "Your shipping address has been added, use the form below to add another:";
		}
		?>
		
			<?php if (has_post_thumbnail()) : ?>
			<div class="leftcol">
				<?php the_post_thumbnail(); ?>
			</div>
			<div class="rightcol">
				<?php the_content(); ?>
			</div>
			<?php else : ?>
				<?php the_content(); ?>
				<a href="javascript:;" class="btn" onclick="jQuery('#input_3_7').val('1');jQuery('#gform_3').submit();">Add Another Address</a>
			<?php endif; ?>
		</div>
		<div class="control-bar bottom">
			<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/" class="back">Back to My Flatterbox</a>
			<a href="javascript:;" class="btn save" onclick="jQuery('#gform_3').submit();">Save &amp; Continue</a>
		</div>
	</div>
</main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>	

<?php

if(isset($_GET["OID"]))
{
		
		$address_results = $wpdb->get_results( "SELECT * FROM orderinfo WHERE OID = " . $_GET["OID"], ARRAY_A);
		if ($address_results) {
		
		foreach ($address_results as $Row)
		
		  {

?>

			<script>

			jQuery("#input_3_2_3").val('<?php echo $Row["FNAME"]; ?>');
			jQuery("#input_3_2_6").val('<?php echo $Row["LNAME"]; ?>');
			jQuery("#input_3_3_1").val('<?php echo $Row["ADDRESS"]; ?>');
			jQuery("#input_3_3_2").val('<?php echo $Row["ADDRESS2"]; ?>');
			jQuery("#input_3_3_3").val('<?php echo $Row["CITY"]; ?>');
			jQuery("#input_3_3_4").val('<?php echo $Row["STATE"]; ?>');
			jQuery("#input_3_3_5").val('<?php echo $Row["ZIP"]; ?>');
			jQuery("#input_3_3_6").val('<?php echo $Row["COUNTRY"]; ?>');
			jQuery("#input_3_5").val('<?php echo $Row["QUANTITY"]; ?>');
			jQuery("#input_3_4").val('<?php echo $Row["COMMENTS"]; ?>');
			jQuery("#input_3_8").val('<?php echo $Row["OID"]; ?>');

			</script>

<?php
			}
		}
}

?>	