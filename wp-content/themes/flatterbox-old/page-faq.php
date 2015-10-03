<?php 

/* Template Name: FAQs */

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
		<?php if( have_rows('faq_repeater') ): ?>
		<?php while ( have_rows('faq_repeater') ) : the_row(); ?>
		<div class="faq">
			<div class="faq-question-content">
				<a href="#" class="faqtoggle plus"<?php if (strlen(get_sub_field('faq_tag')) > 0) : echo ' name="faq_'.get_sub_field('faq_tag').'" id="faq_'.get_sub_field('faq_tag').'"'; endif; ?>>
					<span class="initialcap">Q.</span>
					<?php the_sub_field('question'); ?>
				</a>
			</div>
			<div class="faq-answer-content semitrans">
				<span class="initialcap">A.</span>
				<?php the_sub_field('answer'); ?>
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
	</div>
</main>
<?php endwhile; endif; ?>

<script>
jQuery( ".faqtoggle" ).click(function() {
	event.preventDefault();
	jQuery(this).parent().siblings().slideToggle("fast");
	jQuery(this).toggleClass("minus");
});
jQuery( document ).ready(function($) {
	<?php if(isset($_GET['faq_tag'])) : ?>
	$("html, body").delay(500).animate({scrollTop: $("#faq_<?php echo $_GET['faq_tag']; ?>").offset().top }, 1000);
	$("#faq_<?php echo $_GET['faq_tag']; ?>").delay(2000).trigger("click");
	<?php endif; ?>
});
</script>

<?php get_footer(); ?>		