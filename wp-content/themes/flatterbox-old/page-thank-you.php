<?php 
/* Template Name: Thank You */

get_header();
?>

<?php
// Added incase of bypass on Invite ability
if(isset($_SESSION["sentimenttext"]) && !isset($_GET["fromlist"])) :
	global $wpdb;

	$wpdb->insert( 
		'sentiments', 
		array( 
			'FID' => $_SESSION["sentimentFID"], 
			'PID' => $_SESSION["sentimentPID"],
			'sentiment_text' => $_SESSION["sentimenttext"],
			'sentiment_name' => $_SESSION["sentimentname"],
			'private' => $_SESSION["sentimentprivacy"]
		), 
		array( 
			'%d', 
			'%d',
			'%s',
			'%s',
			'%d'
		) 
	);

	$wpdb->update( 'flatterers', array('responded' => '1'), array( 'FID' => $_SESSION["sentimentFID"] ), array('%d'), array('%d') ); 
endif;
$can_invite = get_field("can_invite",$_SESSION["sentimentPID"]); // For Flatterer able to Invite
				

	include 'flatterer-invitation.php';
	
	
?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<ul id="status-bar">
			<li class="done<?php if( empty($can_invite) || !$can_invite ) : echo ' no_invites'; endif; ?>"><a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&newcard=1">Compose Sentiment</a></li>
			<li class="done<?php if( empty($can_invite) || !$can_invite ) : echo ' no_invites'; endif; ?>"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-review-sentiments/?listview=1">Review Sentiment</a></li>
			<?php if( $can_invite ) : ?>
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-create-account-and-invite/">Invite Flatterers</a></li>
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-invitation-review/">Send Invitations</a></li>
			<?php endif; ?>
			<?php 
				$laststep = 'Create Account';
				if ( is_user_logged_in() ) : $laststep = 'Complete'; endif;
			?>
			<li class="active<?php if( empty($can_invite) || !$can_invite ) : echo ' no_invites'; endif; ?>"><?php echo $laststep ?></li>
		</ul>
		<?php 
			$leadcontent = 'Your sentiment ';
			if ($can_invite) : $leadcontent .= 'and invitations have '; else : $leadcontent .= 'has '; endif;
			$leadcontent .= 'been received.'; 
		?>
		<div class="generic">
			<?php if (has_post_thumbnail()) : ?>
			<div class="leftcol">
				<?php the_post_thumbnail(); ?>
			</div>
			<div class="rightcol">
				<h2><?php echo $leadcontent; ?></h2><br/>
				<?php the_content(); ?>
				<?php if ( !is_user_logged_in() ) : ?>
					<a href="<?php echo home_url(); ?>/create-an-account/" class="btn save">Create Your Account</a>
				<?php endif; ?>
			</div>
			<?php else : ?>
				<h2><?php echo $leadcontent; ?></h2><br/>
				<?php the_content(); ?>
				<?php if ( !is_user_logged_in() ) : ?>
					<a href="<?php echo home_url(); ?>/create-an-account/" class="btn save">Create Your Account</a>
				<?php endif; ?>
				<?php if ( $can_invite ) : ?>
					<a href="<?php echo home_url(); ?>/flatterer-create-account-and-invite/" class="btn save">Invite More Flatterers</a>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>		