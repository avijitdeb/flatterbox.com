<?php 

/* Template Name: Flatterer Step 4 Invitation Review */

if ( false ) :
	$_SESSION["flatterernames"] = $_POST['flatterername'];
	$_SESSION["flattereremails"] = $_POST["flattereremail"];
else :
	$arrNames = array();
	$arrEmails = array();

	//$strings = explode(",", $_POST['flatterers']);
	$strings = preg_split( '/(\s|;|,)/', $_POST['flatterers'] );

	foreach ($strings as $value) {
		$entry = explode(" ", trim($value));
		$theemail = array_pop($entry); // removes last entry in array
		$theemail = strtolower(trim($theemail)); // Make lower case and trim white space
		$theemail = str_replace(';', '', $theemail); // from tag issues
		$theemail = str_replace('\\\'', '', $theemail); // from tag issues
		$theemail = str_replace('\'', '', $theemail); // from tag issues
		if (strpos($theemail,'<')) : $theemail = get_string_between($theemail,'<','>'); endif;// from tag issues

		$thename = trim(str_replace($theemail, '', trim($value)));
		//if (!$thename) : $thename = 'Flatterer'; endif; // Just incase only email is used, we need a name.
		if ( filter_var($theemail, FILTER_VALIDATE_EMAIL) && !in_array($theemail, $arrEmails) ) :
			array_push($arrEmails, $theemail); // adds to emails array
			array_push($arrNames, $thename); // replaces the email with empty string and adds to the names array
		endif;
	}

	$_SESSION["flatterernames"] = $arrNames;
	$_SESSION["flattereremails"] = $arrEmails;
endif;

if ( $_POST["redirectback"] == "1" ) :
	wp_redirect( home_url().'/flatterer-create-account-and-invite/' ); exit;
endif;

get_header(); 

// Redirect if not allowed to show.
if ( get_field('can_share') ) : wp_redirect( home_url() ); exit; endif;

foreach (get_the_terms($_SESSION["sentimentPID"], 'flatterbox_type') as $cat) : 
	$catid =  $cat->term_id;
endforeach;
?>		
<?php // $page_background_photo = getOccasionBGImg($catid); ?>
<main id="main" role="main" class="flattererview">
<?php if (false) : ?><main id="main" role="main" style="background-image: url('<?php the_field('page_background_photo'); ?>');>"><?php endif; ?>
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<ul id="status-bar">
				<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&newcard=1">Compose Sentiment</a></li>
				<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-review-sentiments/?listview=1">Review Sentiment</a></li>
				<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-create-account-and-invite/">Invite Flatterers</a></li>
				<li class="active"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-invitation-review/">Send Invitations</a></li>
				<?php
					$laststep = 'Create Account';
					if ( is_user_logged_in() ) : $laststep = 'Complete'; endif;
				?>
				<li><?php echo $laststep ?></li>
			</ul>
		<section class="sentiment-holder">
			<div class="compose">
				<h2>Confirm Invited Flatterers.</h2>
			</div>
			<div class="example">
				<div class="img-holder">
				<?php 
				
				// Converted to Session
				$nCount = 0;
				for ($i = 0; $i < count($_SESSION['flattereremails']); $i++)
				{
					if ($_SESSION['flattereremails'][$i] != "")
					{
						if ($nCount % 2 == 0) {
							if ($nCount == 0) {
								echo "<div class=\"list_row\">";
							} else {
								echo "</p></div><div class=\"list_row\">";
							}
							echo "<p class=\"list_left\">";

						} else {
							echo "</p><p class=\"list_right\">";
						}
						echo $_SESSION['flattereremails'][$i];
						// echo "<p>" . $_SESSION['flattereremails'][$i] . "</p>"; // Old layout -- remove rest inside the if flattereremails check
						$nCount++;
					}
				}
				echo "</p></div>" // Close it off - Remove if returning to old layout
				?>
				
				</div>
			</div>
		</section>
		<div class="control-bar bottom">
			<a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-create-account-and-invite/" class="btn back">Edit My Invitations</a>
			<a href="<?php echo do_shortcode('[site_url]'); ?>/thank-you?initialinvite=1" class="btn save">Send Invitations</a>
		</div>
	</div>
</main>
<?php get_footer(); ?>		