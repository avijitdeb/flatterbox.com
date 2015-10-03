<?php 

/* Template Name: Invite More Confirm */

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

if ( count($_SESSION["flattereremails"]) == 0 ) : // Bypass if no emails
	wp_redirect( home_url().'/my-flatterbox/?invitePID='.$_GET["PID"] ); exit; //added as a bypass
endif;

$_SESSION["message_to_flatterers"] = $_POST["message_to_flatterers"];

get_header('steps'); ?>
<?php // $page_background_photo = getOccasionBGImg($_SESSION["occasion_id"]); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>

		<section class="sentiment-holder">
			<div class="compose">
				<h2>Confirm Invited Flatterers.</h2>
				Review the list of Flatterers.
				<?php the_content(); ?>
			<p>&nbsp;</p>
				
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
			
		</section>
		<div class="control-bar bottom">
			<a href="<?php echo do_shortcode('[site_url]'); ?>/invite-more/?PID=<?php echo $_GET["PID"] ?>" class="btn back">Back to Invite Flatterers</a>
			<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/?invitePID=<?php echo $_GET["PID"] ?>" class="btn save">Send Invitations</a>
		</div>
	</div>
</main>
<?php endwhile; endif; ?>
<?php get_footer(); ?>				