<?php get_header(); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<?php get_header(); ?>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<h2>Flatternews</h2>
		</div>
		<?php if (has_post_thumbnail()) { ?>
			<div class="blog-header">
				<?php the_post_thumbnail('blog-header'); ?>
			</div>
		<?php } ?>
		<div class="blog-content semitrans">
			<?php $month = get_the_date('M');
			$day = get_the_date('d'); ?>
			<div class="date"><?php echo $month; ?><span><?php echo $day; ?></span></div>
			<div class="title-block">
				<?php the_title('<h1>', '</h1>'); ?>
				<?php the_author_meta('first_name'); ?> <?php the_author_meta('last_name'); ?>
			</div>
			<div class="content-block">
				<?php the_content(); ?>
			</div>
		</div>
		<div class="blog-nav">
			<a href="<?php echo do_shortcode('[site_url]'); ?>/flatternews" class="back-button">Back</a>
			<div class="social">
				<?php echo do_shortcode('[st_buttons]'); ?>
			</div>
		</div>
	</div>
	<section class="review-block">
		<div class="slider-box blue">
			<div class="carousel">
				<div class="mask">
					<div class="slideset">
						<div class="slide">
							<h2>What people are saying.</h2>
							<blockquote>
								<q>Duis eget feguit orci, eu comindo quam. Preasent varius dictum enigma. Preasenti facilis congue etse mattis. Porin acru.</q>
								<cite>-Sunny D.</cite>
							</blockquote>
						</div>
						<div class="slide">
							<h2>What people are saying.</h2>
							<blockquote>
								<q>Duis eget feguit orci, eu comindo quam. Preasent varius dictum enigma. Preasenti facilis congue etse mattis. Porin acru.</q>
								<cite>-Sunny D.</cite>
							</blockquote>
						</div>
					</div>
				</div>
				<a class="btn-prev" href="#">Previous</a>
				<a class="btn-next" href="#">Next</a>
			</div>
		</div>
		<a href="#" class="btn-share">share your story</a>
	</section>
</main>
<?php get_footer(); ?>		
<?php endwhile; endif; ?>