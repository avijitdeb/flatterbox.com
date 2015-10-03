<?php
	/* Template Name: Create an Account */

	get_header();
	if($_GET["creatingbox"])
	{
		
		$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$querystring = parse_url($url, PHP_URL_QUERY);
		
		$_SESSION["returnURL"] = do_shortcode('[site_url]') . "/flatterbox-options/?" . $querystring;
		?>
		<script type="text/javascript">
			jQuery('#redirect-step2').val('<?php echo $_SESSION["returnURL"]; ?>');
		</script>
		<?php
	}
?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<div class="generic loginout">
			<h3 style="float: left;">Already Have an Account?</h3> <a href="<?php echo do_shortcode('[site_url]'); ?>/wp-login.php" class="btn save" style="float:right;">Sign In</a>
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