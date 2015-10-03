<?php 

/* Template Name: Sentiment Step 4 Confirm */

get_header(); ?>
<?php // $page_background_photo = getOccasionBGImg(0); ?>
<main id="main" role="main">
<?php if (false) : ?><main id="main" role="main" style="background-image: url('<?php the_field('page_background_photo'); ?>');>"><?php endif; ?>
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<ul class="gallery-holder">
			<li><a href="#">
				<div class="img-holder">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/img12.jpg" height="290" width="290" alt="img description">
					<span class="box">Birthday</span>
				</div></a>
			</li>
			<li><a href="#">
				<div class="img-holder">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/img13.jpg" height="290" width="290" alt="img description">
					<span class="box">Funeral</span>
				</div></a>
			</li>
			<li><a href="#">
				<div class="img-holder">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/img14.jpg" height="290" width="290" alt="img description">
					<span class="box">Wedding</span>
				</div></a>
			</li>
			<li><a href="#">
				<div class="img-holder">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/img15.jpg" height="290" width="290" alt="img description">
					<span class="box">Engagement</span>
				</div></a>
			</li>
			<li><a href="#">
				<div class="img-holder">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/img16.jpg" height="290" width="290" alt="img description">
					<span class="box">Newborn</span>
				</div></a>
			</li>
			<li><a href="#">
				<div class="img-holder">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/img17.jpg" height="290" width="290" alt="img description">
					<span class="box">Anniversary</span>
				</div></a>
			</li>
			<li><a href="#">
				<div class="img-holder">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/img18.jpg" height="290" width="290" alt="img description">
					<span class="box">Bar Mitzvah</span>
				</div></a>
			</li>
			<li><a href="#">
				<div class="img-holder">
					<img src="<?php bloginfo('stylesheet_directory'); ?>/images/img19.jpg" height="290" width="290" alt="img description">
					<span class="box">Get Well</span>
				</div></a>
			</li>
		</ul>
	</div>
</main>
<?php get_footer(); ?>		