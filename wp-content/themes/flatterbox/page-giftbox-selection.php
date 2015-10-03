<?php
/* Template Name: FB Gift Boxes - Gift Box Products */
get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<div class="largebox">
			<div class="boximage" style="background-image: url('<?php the_field('flatterbox_product_image'); ?>');">
			</div>
			<div class="boxinfo">
				<h2><?php the_field('flatterbox_product_name'); ?></h2>
				<?php the_field('flatterbox_product_description'); ?>
				<?php the_field('flatterbox_product_sub_description'); ?>
				<div class="inforow">
					<h3><?php the_field('flatterbox_product_price'); ?></h3>
					<a href="<?php the_field('flatterbox_product_link'); ?>" class="btn orange"><?php the_field('flatterbox_product_link_text'); ?></a>
				</div>
			</div>
		</div>
		<?php if (false) : // 2nd Product - more should be created as a repeater ?>
		<div class="largebox">
			<div class="boximage" style="background-image: url('<?php the_field('flatterbox_2nd_product_image'); ?>');">
			</div>
			<div class="boxinfo">
				<h2><?php the_field('flatterbox_2nd_product_name'); ?></h2>
				<?php the_field('flatterbox_2nd_product_description'); ?>
				<?php the_field('flatterbox_2nd_product_sub_description'); ?>
				<div class="inforow">
					<h3><?php the_field('flatterbox_product_price'); ?></h3>
					<a href="<?php the_field('flatterbox_product_link'); ?>?<?php the_field('flatterbox_2nd_product_url_variable'); ?>" class="btn orange"><?php the_field('flatterbox_product_link_text'); ?></a>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<?php if( have_rows('giftbox_products') ): ?>
			<div class="giftboxes">
			<?php while ( have_rows('giftbox_products') ) : the_row(); ?>
				<div class="giftbox">
					<div class="boximage boxheight" style="background-image: url('<?php the_sub_field('giftbox_product_image'); ?>');">
					</div>
					<div class="boxinfo boxheight">
						<h2><?php the_sub_field('giftbox_product_name'); ?></h2>
						<?php the_sub_field('giftbox_product_description'); ?>
						<div class="inforow">
							<h3><?php the_sub_field('giftbox_product_price'); ?></h3>

							<?php 
							$variation1 = "";
							$variation2 = "";
							$variation3 = "";
							$variation4 = "";
							?>

							<?php if( have_rows('variation_id') ): ?>
							<?php while ( have_rows('variation_id') ) : the_row();
								$variation1 = get_sub_field('variation_id_for_50_cards');
								$variation2 = get_sub_field('variation_id_for_100_cards');
								$variation3 = get_sub_field('variation_id_for_150_cards');
								$variation4 = get_sub_field('variation_id_for_200_cards'); ?>
							<?php endwhile; endif; ?>


							<a href="<?php echo do_shortcode('[site_url]'); ?>/products-customize/?productid=<?php the_sub_field('giftbox_product_id'); ?>&variation1=<?php echo $variation1; ?>&variation2=<?php echo $variation2; ?>&variation3=<?php echo $variation3; ?>&variation4=<?php echo $variation4; ?>" class="btn orange"><?php the_sub_field('giftbox_product_link_text'); ?></a>
						</div>
					</div>
				</div>
			<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
</main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>		