<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<div class="generic">
			<?php if (has_post_thumbnail()) : ?>
			<div class="leftcol">
				<?php the_post_thumbnail(); ?>
			</div>
			<div class="rightcol">
				<?php the_content(); ?>
			</div>
			<?php else : ?>
				<?php the_content(); ?>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>		