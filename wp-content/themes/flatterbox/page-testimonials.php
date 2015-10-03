<?php 

/* Template Name: Testimonials */

get_header(); ?>
<?php get_header(); ?>
<?php // $page_background_photo = getOccasionBGImg(0); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main">
<?php if (false) : ?><main id="main" role="main" style="background-image: url('<?php the_field('page_background_photo'); ?>');>"><?php endif; ?>
	<div class="container">
		<div class="heading">
			<h2><?php the_title(); ?></h2>
		</div>
		<?php $args = array(
			'post_type' => 'testimonials',
			'posts_per_page'    => '-1'
		);
		$query = new WP_Query( $args ); ?>
		<?php if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
		<div class="blog-content semitrans">
			<div class="content-block testimonial">
				<blockquote>
					<q><?php echo $post->post_content; ?></q>
					<cite>-<?php the_field('quote_speaker'); ?></cite>
				</blockquote>
			</div>
		</div>
		<?php endwhile; ?>
		<?php else : ?>
		<div class="blog-content semitrans">
			<div class="content-block">
				Currently, there are no FAQs to view.
			</div>
		</div>
		<?php endif; ?>
		<?php wp_reset_query(); ?>
	</div>
</main>
<?php endwhile; endif; ?>

<script>
jQuery( ".faqtoggle" ).click(function() {
	event.preventDefault();
	jQuery(this).parent().siblings().slideToggle("fast");
	jQuery(this).toggleClass("minus");
});
</script>

<?php get_footer(); ?>		