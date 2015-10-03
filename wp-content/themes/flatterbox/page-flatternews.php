<?php 

/* Template Name: FlatterBox News */

get_header(); ?>
<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<h2>Flatternews</h2>
		</div>
		<?php 
		$args = array(
			'post_type' => 'post',
		);
		?>
		<?php $the_query = new WP_Query( $args ); ?>
		<?php if ( $the_query->have_posts() ) : ?>
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
		<?php if (has_post_thumbnail()) { ?>
		<div class="blog-header">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('blog-header'); ?></a>
		</div>
		<?php } ?>
		<div class="blog-content semitrans">
			<?php $month = get_the_date('M');
			$day = get_the_date('d'); ?>
			<div class="date"><?php echo $month; ?><span><?php echo $day; ?></span></div>
			<div class="title-block">
				<h1><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
				<?php the_author_meta('first_name'); ?> <?php the_author_meta('last_name'); ?>
			</div>
			<div class="content-block">
				<?php the_excerpt(); ?>
			</div>
			<div class="blog-nav">
				<a href="<?php the_permalink(); ?>" class="back-button readmore-button">Read More</a>
				<div class="social">
					<?php echo do_shortcode('[st_buttons]'); ?>
				</div>
			</div>
		</div>
		<?php wp_reset_postdata(); ?>
		<?php endwhile; ?>
		<?php else : ?>
		<div class="blog-content semitrans">
			<?php $month = get_the_date('M');
			$day = get_the_date('d'); ?>
			<div class="date"><?php echo $month; ?><span><?php echo $day; ?></span></div>
			<div class="title-block">
				<?php the_title('<h1>', '</h1>'); ?>
				<?php the_author_meta('first_name'); ?> <?php the_author_meta('last_name'); ?>
			</div>
		</div>
		<?php endif; ?>

	</div>

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
		<div class="slider-box">
			<div class="carousel cycle-gallery">
				<div class="mask">
					<div class="slideset">
						<?php while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post(); ?>
						<div class="slide">
							<h2>What people are saying.</h2>
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
<?php endwhile; endif; ?>
<?php get_footer(); ?>		