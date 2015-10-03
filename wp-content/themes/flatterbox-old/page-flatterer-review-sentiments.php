<?php 

/* Template Name: Flatterer - View Sentiments */

get_header();

$_SESSION["returnURL"] = do_shortcode('[site_url]') . "/flatterer-create-account-and-invite/?fromlist=1";

global $wpdb;

if(isset($_POST["sentimenttext"]) && $_POST["sentimenttext"] != '')
{
	if($_POST["updatesentiment"] == 1)
	{
		$wpdb->update( 
			'sentiments',
			array(
				'sentiment_text' => $_POST["sentimenttext"], 
				'sentiment_name' => $_POST["namefamily"]
			), 
			array(
				'SID' => $_SESSION["sentimentSID"]
			), 
			array(
				'%s',
				'%s'
			),
			array('%d')
		); 
	
	} elseif ( $_SESSION["sentimentPID"] > 0)  {

		if ($_SESSION["sentimentFID"] == -1) :
			$wpdb->insert(
				'flatterers',
				array(
					'PID' => $_SESSION["sentimentPID"],
					'flatterer_email' => $_POST["namefamily"],
					'flatterer_name' => $_POST["namefamily"],
					'responded' => '0',
					'passcode' => '',
					'approved' => '0',
				),
				array(
					'%d', 
					'%s',
					'%s',
					'%d',
					'%s',
					'%d'
				)
			);
			$_SESSION['sentimentFID'] = $wpdb->insert_id;
		endif;

		$wpdb->insert( 
			'sentiments', 
			array( 
				'FID' => $_SESSION["sentimentFID"], 
				'PID' => $_SESSION["sentimentPID"],
				'sentiment_text' => $_POST["sentimenttext"],
				'sentiment_name' => $_POST["namefamily"],
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

	} else {
		$errorMsg = 'There was a problem saving your sentiment, please close the browser and click the link again from your email.';
	}
}

//Get selected Flatterbox's data

$flatterbox_post = get_post($_SESSION["sentimentPID"]); 
$flatterer = $_SESSION["sentimentFID"];
$PID = $flatterbox_post->ID;

$can_invite = get_field("can_invite",$PID); // echo '!'.$can_invite.'!'.get_field("can_invite",$PID).'!';
?> 
<div class="lightbox choice">
	<?php include('includes/lightbox-choices.php'); ?>
</div>
<?php // $page_background_photo = getOccasionBGImg(0); ?>
<main id="main" role="main" class="flattererview">
<?php if (false) : ?><main id="main" role="main" style="background-image: url('<?php the_field('page_background_photo'); ?>');>"><?php endif; ?>
	<div class="container">
		<div class="heading">
			<?php
			foreach (get_the_terms($PID, 'flatterbox_type') as $cat) : 
				$catimage =  z_taxonomy_image_url($cat->term_id);
				$catname = $cat->name;
			endforeach;
			?>			
			<h1><?php the_field('who_is_this_for',$PID); ?>'s <?php echo $catname; ?></h1>
		</div>
		<ul id="status-bar">
			<li class="done<?php if( empty($can_invite) || !$can_invite ) : echo ' no_invites'; endif; ?>"><a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&newcard=1">Compose Sentiment</a></li>
			<li class="active<?php if( empty($can_invite) || !$can_invite ) : echo ' no_invites'; endif; ?>"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-review-sentiments/?listview=1">Review Sentiment</a></li>
			<?php if( $can_invite ) : ?>
			<li>Invite Flatterers</li>
			<li>Send Invitations</li>
			<?php endif; ?>
			<?php
				$laststep = 'Create Account';
				if ( is_user_logged_in() ) : $laststep = 'Complete'; endif;
			?>
			<li<?php if( empty($can_invite) || !$can_invite ) : echo ' class="no_invites"'; endif; ?>><?php echo $laststep ?></li>
		</ul>
		<?php if (isset($errorMsg)) : ?>
			<div id="filter-sentiments" class="control-bar">
				<h4><?php echo $errorMsg; ?></h4>
			</div>
		<?php endif; ?>
		<div id="filter-sentiments" class="control-bar">
			<h3>Review Your Sentiments</h3>
			<div class="options">
			<span>View As:</span>
				<?php if( $can_invite ) : ?>
				<a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-create-account-and-invite"  class="btn save orangebtn" id="confirm" style="display:block;margin-left:15px;">Save &amp; Continue</a>
				<?php else : ?>
				<a href="<?php echo do_shortcode('[site_url]'); ?>/thank-you" class="btn save orangebtn">Save &amp; Continue</a>
				<?php endif; ?>
				<a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-review-sentiments/"  class="btn save cardview <?php if ($listview != 1) : ?>active<?php endif; ?>" style="display:block;">Cards</a>
				<a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-review-sentiments/?listview=1" class="btn save listview <?php if ($listview == 1) : ?>active<?php endif; ?>" style="display:block;">List</a>&nbsp;&nbsp;
			</div>
		</div>
		<ul class="<?php if($_GET['listview']) { ?>card-list-stacked<?php } else { ?>card-list<?php } ?>">

		<?php
		
		//Loop through all sentiments in the flatterbox

		
		$sentiment_results = $wpdb->get_results( "SELECT * FROM sentiments WHERE FID = " . $flatterer . " AND PID = " . $PID, ARRAY_A);

		if ($sentiment_results) {

		foreach ($sentiment_results as $Row)
		
		  {
		
		?>
		  <?php if($_GET['listview']) { ?>
			<li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcardlist sentimentrow">

					<div class="sentimenttext">
						<span><?php echo stripslashes($Row["sentiment_text"]); ?></span>
					</div>
					<div class="sentimentauthor">
						<span><?php echo stripslashes($Row["sentiment_name"]); ?></span>
					</div>
					<div class="action">
						<form>
							<label><a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&SID=<?php echo $Row["SID"] ?>"  class="cornerbtn edit"></a><a href="javascript:;" onclick="sentimentAction('delete',<?php echo $Row["SID"] ?>);" class="cornerbtn reject"></a></label>
						</form>
					</div>					
		
			</li>		  
		  <?php } else { ?>
			<li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcard editsentiment">
				<div class="selectSentiment">
					<form>
						<label><a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&SID=<?php echo $Row["SID"] ?>" class="cornerbtn edit"></a><a href="javascript:;" onclick="sentimentAction('delete',<?php echo $Row["SID"] ?>);" class="cornerbtn reject"></a></label>
					</form>
				</div>
				<div class="frame">
					<p><?php echo nl2br( stripslashes($Row["sentiment_text"]) ); ?></p>
					<span class="name">- <?php echo stripslashes($Row["sentiment_name"]); ?></span>
				</div>
			</li>
		
		<?php
			} // if listview
		  }  // foreach

		} // if $results

		?>		
		
		<?php if($_GET['listview']) { ?>
			<li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcardlist addrow">

					<div class="title">
						Click on the + to Add Another Sentiment.
					</div>
					<div class="action">
						<form>
							<label><a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&newcard=1" class="cornerbtn add"></a></label>
						</form>
					</div>					
		
			</li>		
		<?php } else { ?>
			<li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcard addcard">
				<div class="selectSentiment">
					<form>
						<label><a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&newcard=1" class="cornerbtn add"></a></label>
					</form>
				</div>
				<div class="frame">
					<p>Click on the + to Add Another Sentiment.</p>
					<span class="name"></span>
				</div>
			</li>			
		<?php } ?>
		</ul>
		<div class="control-bar bottom">
			<?php if( $can_invite ) : ?>
			<a href="<?php echo do_shortcode('[site_url]'); ?>/flatterer-create-account-and-invite" class="btn save" id="confirm2" style="display:block;margin-left:15px;">Continue</a>
			<?php else : ?>
			<a href="<?php echo do_shortcode('[site_url]'); ?>/thank-you" class="btn save" id="confirm3">Continue</a>
			<?php endif; ?>
			<div class="divider"></div>
		</div>
		<script>
		jQuery( ".card-list > li" ).hover(function($) {
		  	jQuery(".hoverOptions", this).slideToggle("fast");
		});
		</script>
	</div>
</main>
<?php get_footer(); ?>		

<script>

function sentimentAction(action,sid)
{
	jQuery.ajax({
	
	url:"<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/sentiment_action.php",
	type:'post',
	data:{'action':action,'SID':sid},
	success: function(data, status) {
		if(data=="ok") {
			jQuery("#sentiment_"+sid).fadeOut();
		}
	},
	  error: function(xhr, desc, err) {
		console.log(xhr);
		console.log("Details: " + desc + "\nError:" + err);
	  }		
	
	});
}


</script>

<script>
function performGroupAction() {
	var checkboxes = document.getElementsByName('sentiment_id[]');
    var cboxLength = checkboxes.length;    

 	for ( i=0; i < cboxLength; i++ ) { 
    	if (checkboxes[i].checked == true) {
			sentimentAction(jQuery("#groupaction").val(),checkboxes[i].value);
		}
    }
}   
</script>

<script>
	<?php
	if($_GET["filter"])
	{
	?>
	jQuery("#sentiment-filter").val('<?php echo $_GET["filter"] ?>');
	<?php
	} else {
	?>
	jQuery("#sentiment-filter").val('0');
	<?php
	}
	?>
</script>

<script>
	jQuery( "#confirm" ).click(function(event) {
		mConfirm(event);
	});
	jQuery( "#confirm2" ).click(function(event) {
		mConfirm(event);
	});
	jQuery( "#confirm3" ).click(function(event) {
		mConfirm(event);
	});
	function mConfirm(e) {
		e.preventDefault();
		jQuery( ".lightbox.choice" ).fadeToggle("fast");
	}
	jQuery( "#confirm" ).trigger('click');
</script>