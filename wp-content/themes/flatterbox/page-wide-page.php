<?php 
/* Template Name: Default - Wide Page */
get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main" class="wide-page">
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<?php the_content(); ?>
	</div>
</main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>		