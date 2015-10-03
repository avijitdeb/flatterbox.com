<?php 

/* Template Name: Home */

get_header(); ?>
<main id="main" role="main">
	<div class="banner">
		<?php if (false) : ?>
		<div class="bg-stretch">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/images/img1.jpg" alt="Flatterbox - Little gift, huge impact.">
		</div>
		<div class="home carousel cycle-gallery">
			<div class="mask">
				<div class="slideset">
					<?php if( have_rows('slideshow') ): ?>
					<?php while ( have_rows('slideshow') ) : the_row(); ?>
					<div class="holder slide">
						<div class="card-holder">
							<?php $imageURL = get_sub_field('slide_image'); ?>
							<?php if ($imageURL == "") : $imageURL = "".bloginfo('stylesheet_directory')."/images/cards.png"; endif; ?>
							<img src="<?php echo $imageURL; ?>" alt="<?php echo strip_tags(get_sub_field('slide_content')); ?>">
						</div>
						<div class="box">
							<?php the_sub_field('slide_content'); ?>
							<?php if (get_sub_field('slide_cta_link') != "") : ?>
							<a href="<?php the_sub_field('slide_cta_link'); ?>" class="btn-more"><?php the_sub_field('slide_cta_text'); ?></a>
							<?php endif; ?>
						</div>
					</div>
					<?php endwhile; ?>
					<?php else : ?>

    				// no rows found

					<?php endif; ?>
				</div>
			</div>
			<a class="btn-prev" href="#">Previous</a>
			<a class="btn-next" href="#">Next</a>
		</div>
		<?php endif; ?>
		<?php echo do_shortcode('[rev_slider homepage]'); ?>
	</div>
	<section class="intro-block">
		<?php $sectionTitle = get_field('step_bar_headline'); ?>
		<?php if ($sectionTitle == "") : $sectionTitle = "What is a Flatterbox?"; endif; ?>
		<h1><?php echo $sectionTitle; ?></h1>
		<div class="step-sub-content"><?php echo get_field('step_bar_sub_content'); ?></div>
		<div class="three-columns">
			<?php if( have_rows('flatterbox_steps') ): ?>
			<?php while ( have_rows('flatterbox_steps') ) : the_row(); ?>
			<?php if (get_sub_field('step_link')) : echo '<a href="'.home_url().get_sub_field('step_link').'" title="'.get_sub_field('step_headline').'">'; endif; ?>
			<div class="col">
				<div class="img-holder">
					<?php $imageColURL = get_sub_field('step_image'); ?>
					<?php if ($imageColURL == "") : $imageURL = "".bloginfo('stylesheet_directory')."/images/img2.png"; endif; ?>
					<img src="<?php echo $imageColURL; ?>" alt="<?php echo strip_tags(get_sub_field('step_headline')); ?>">
				</div>
				<h2><?php the_sub_field('step_number'); ?></h2>
				<h3<?php if (get_sub_field('step_link')) : echo ' class="h3-link"'; endif; ?>><?php the_sub_field('step_headline'); ?></h3>
				<?php the_sub_field('step_description'); ?>
			</div>
			<?php if (get_sub_field('step_link')) : echo '</a>'; endif; ?>
			<?php endwhile; ?>
			<?php else : ?>

			// no rows found

			<?php endif; ?>
		</div>

		<?php if (false) : ?>

		<?php if ( have_rows('occasion_slideshow') ) : ?>	
		<div class="slickshow">
			<?php while ( have_rows('occasion_slideshow') ) : the_row(); ?>
				<div>
					<?php $imageURL = get_sub_field('occasion_image');
					$imageURLExtension = substr($imageURL, -4);
					$imageURLBase = substr($imageURL, 0, -4);
					$imageURLRecalc = ''.$imageURLBase.'-290x290'.$imageURLExtension; ?>
					<div class="img-holder">
						<span class="box"><span><?php the_sub_field('occasion_name'); ?></span></span>
						<img src="<?php echo $imageURLRecalc; ?>" height="290" width="290" alt="<?php the_sub_field('occasion_name'); ?>">
					</div></a>
				</div>
			<?php endwhile; ?>
		</div>
		<?php endif; ?>
		
		<?php endif; ?>
	</section>

<?php 
$args = array(
	'post_type'  => 'testimonials',
	'orderby'    => 'date',
	'order'      => 'DESC',
);
$testimonials_query = new WP_Query( $args );
?>
<?php if ( $testimonials_query->have_posts() ) : ?>	
	<section class="review-block">
		<div class="bg-stretch">
			<img src="<?php bloginfo('stylesheet_directory'); ?>/images/img5.jpg" alt="img description">
		</div>
		<div class="slider-box">
			<div class="carousel cycle-gallery">
				<div class="mask">
					<div class="slideset">
						<?php while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post(); ?>
						<div class="slide">
							<h2><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterbox-testimonials">What people are saying.</a></h2>
							<blockquote>
								<q><?php the_field('quote'); ?></q>
								<cite>-<?php the_field('quote_speaker'); ?></cite>
							</blockquote>
						</div>
						<?php endwhile; ?>
					</div>
				</div>
				<a class="btn-prev" href="#">Previous</a>
				<a class="btn-next" href="#">Next</a>
			</div>
		</div>
		<a href="<?php echo do_shortcode('[site_url]'); ?>/share-your-story" class="btn-share">share your story</a>
	</section>
<?php endif; ?>
</main>
<?php get_footer(); ?>		