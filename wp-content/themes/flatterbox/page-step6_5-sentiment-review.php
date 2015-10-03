<?php 

/* Template Name: FB Step 6_5 View Sentiments */

get_header('steps');

global $wpdb;

if(isset($_POST["sentimenttext"]))
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
	
	} else {

	$wpdb->insert( 
		'sentiments', 
		array( 
			'FID' => 0, 
			'PID' => $_SESSION["new_flatterbox_id"],
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
	$inserted = $wpdb->insert_id;
	}	
}

//Get selected Flatterbox's data

$flatterbox_post = get_post($_SESSION["new_flatterbox_id"]); 
$flatterer = 0;
$PID = $flatterbox_post->ID;

?> 
<div class="lightbox confirm">
	<?php include('includes/lightbox-confirm.php'); ?>
</div>
<main id="main" role="main">
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
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/choose-card/?preservesession=1">Create It</a></li>
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/flatterbox-options/">Personalize It</a></li>
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/invitations/">Invite Flatterers</a></li>
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/title-card/">Title Card</a></li>
			<li class="clearpadding active">Write the First One!</li>
			<li>Complete</li> <?php // Changed from "What's Next" ?>
		</ul>
		
		<div id="filter-sentiments" class="control-bar">
			<h3>View Your Sentiments</h3>
			<div class="options">
			<span>View As:</span>
				<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/"  class="btn save orangebtn" id="confirm" style="display:block;margin-left:15px;">Save & Continue</a>
				<a href="<?php echo do_shortcode('[site_url]'); ?>/review-sentiments/"  class="btn save cardview <?php if ($listview != 1) : ?>active<?php endif; ?>" id="confirm" style="display:block;">Cards</a>
				<a href="<?php echo do_shortcode('[site_url]'); ?>/review-sentiments/?listview=1" class="btn save listview <?php if ($listview == 1) : ?>active<?php endif; ?>" id="confirm" style="display:block;">List</a>&nbsp;&nbsp;
			</div>
		</div>
		
		
		<ul class="<?php if($_GET['listview']) { ?>card-list-stacked<?php } else { ?>card-list<?php } ?>">
		<!-- Title Card Card/Row -->
		<?php if($_GET['listview']) { ?>

		  <li id="sentiment_<?php echo '000' ?>" class="status_<?php echo '111' ?> sentimentcardlist sentimentrow titlecard">

					<div class="sentimenttext">
						<span>Title Card : <?php echo stripslashes(get_field('title_card_headline', $_SESSION["new_flatterbox_id"])); ?></span>
					</div>
					<div class="sentimentauthor">
						<span><?php echo stripslashes(get_field('title_card_name', $_SESSION["new_flatterbox_id"])); ?></span>
					</div>
					<div class="action">
						<form>
							<label><?php if($Row["FID"] == 0) { ?><a href="<?php echo do_shortcode('[site_url]'); ?>/title-card/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo '222'; ?>&SID=<?php echo '000' ?>" class="cornerbtn edit"></a><?php } ?><a href="javascript:;" onclick="titleAction('delete',<?php echo '000' ?>);" class="cornerbtn reject"></a></label>
						</form>
					</div>					
		
			</li>		  
		  <?php } else { ?>
			<li id="sentiment_<?php echo '000' ?>" class="status_<?php echo '111' ?> sentimentcard editsentiment titlecard">
				<div class="selectSentiment">
					<form>
						<label><?php if($Row["FID"] == 0) { ?><a href="<?php echo do_shortcode('[site_url]'); ?>/title-card/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo '222'; ?>&SID=<?php echo '000' ?>" class="cornerbtn edit"></a><?php } ?><a href="javascript:;" onclick="titleAction('delete',<?php echo '000' ?>);" class="cornerbtn reject"></a></label>
					</form>
				</div>
				<div class="frame">
					<p><?php echo stripslashes(get_field('title_card_headline', $_SESSION["new_flatterbox_id"])); ?></p>
					<span class="name"><?php echo stripslashes(get_field('title_card_name', $_SESSION["new_flatterbox_id"])); ?></span>
					<div class="fb_squ_logo"><img src="<?php echo get_template_directory_uri(); ?>/images/squarelogo.png" alt="<?php echo stripslashes(''); ?>"></div>
				</div>
			</li>
		
		<?php
			} // End Title Card ?>

		<?php if($_GET['listview']) { ?>
			<li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcardlist addrow">

					<div class="title">
						Click on the + to Add Another Sentiment.
					</div>
					<div class="action">
						<form>
							<label><a href="<?php echo do_shortcode('[site_url]'); ?>/compose-your-sentiment" class="cornerbtn add"></a></label>
						</form>
					</div>					
		
			</li>		
		<?php } else { ?>
			<li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcard addcard">
				<div class="selectSentiment">
					<form>
						<label><a href="<?php echo do_shortcode('[site_url]'); ?>/compose-your-sentiment" class="cornerbtn add"></a></label>
					</form>
				</div>
				<div class="frame">
					<p>Click on the + to Add Another Sentiment.</p>
					<span class="name"></span>
				</div>
			</li>			
		<?php } ?>

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
							<label><a href="<?php echo do_shortcode('[site_url]'); ?>/compose-your-sentiment/?SID=<?php echo $Row["SID"] ?>"  class="cornerbtn edit"></a>
							<a href="javascript:;" onclick="sentimentAction('delete',<?php echo $Row["SID"] ?>);" class="cornerbtn reject"></a></label>
						</form>
					</div>					
		
			</li>		  
		  <?php } else { ?>
			<li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcard editsentiment">
				<div class="selectSentiment">
					<form>
						<label><a href="<?php echo do_shortcode('[site_url]'); ?>/compose-your-sentiment/?SID=<?php echo $Row["SID"] ?>" class="cornerbtn edit"></a><a href="javascript:;" onclick="sentimentAction('delete',<?php echo $Row["SID"] ?>);" class="cornerbtn reject"></a></label>
					</form>
				</div>
				<div class="frame">
					<p><?php echo stripslashes(nl2br($Row["sentiment_text"])); ?></p>
					<span class="name">- <?php echo stripslashes($Row["sentiment_name"]); ?></span>
				</div>
			</li>
		
		<?php
			} // if listview
		  }  // foreach

		} // if $results

		?>		
		
		
		</ul>		
		
		<div class="control-bar bottom">
			<?php if ( false ) : ?><a href="<?php echo do_shortcode('[site_url]'); ?>/whats-next" class="btn save" style="display:block;margin-left:15px;">Continue</a><?php endif; ?>
			<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox" class="btn save" style="display:block;margin-left:15px;">Continue</a> 
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
function titleAction(action,pid)
{
	if(action=='delete')
	{
		var confirmdelete = confirm("Are you sure you want to delete the Title Card?");
		if(confirmdelete) 
		{
			jQuery.ajax({
			
			url:"<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/title_action.php",
			type:'post',
			data:{'action':action,'PID':pid},
			success: function(data, status) {
				if(data=="ok") {
					jQuery("#titlecard_"+pid).fadeOut(1000,function() 
					{ 
					jQuery(this).remove(); 
					});
					
					if(action=='delete')
					{
						newcardcount = parseInt(jQuery('#sentimentcount').html()) - 1;
						jQuery('#sentimentcount').html(newcardcount);
					}
				}
			},
			  error: function(xhr, desc, err) {
				console.log(xhr);
				console.log("Details: " + desc + "\nError:" + err);
			  }		
			
			});			
		}
	} else {

		jQuery.ajax({
		
		url:"<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/title_action.php",
		type:'post',
		data:{'action':action,'PID':pid},
		success: function(data, status) {
			if(data=="ok") {
				jQuery("#titlecard_"+pid).fadeOut(1000,function() 
				{ 
				jQuery(this).remove(); 
				});
				
				if(action=='delete')
				{
					newcardcount = parseInt(jQuery('#sentimentcount').html()) - 1;
					jQuery('#sentimentcount').html(newcardcount);
				}
			}
		},
		  error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		  }		
		
		});
	}
}

	function gotoStep6() {
		document.location = '<?php echo do_shortcode('[site_url]'); ?>/compose-your-sentiment/?SID=<?php echo $inserted; //Edit last inserted ?>';
	}
	jQuery(document).ready(function($) {

		if (window.history && window.history.pushState) {

			$(window).on('popstate', function() {
				var hashLocation = location.hash;
				var hashSplit = hashLocation.split("#!/");
				var hashName = hashSplit[1];

				if (hashName !== '') {
					var hash = window.location.hash;
					if (hash === '') {
						// Call Back...
						doConfirm("Clicking the Browser Back Button may cause you to lose information. Please use the Back button link at the bottom of the page instead. Are you sure you want to leave this page?", function yes()
						{
							gotoStep6();
						}, function no() {
						    // do nothing
						});
					}
				}
			});

			window.history.pushState('forward', null, './#forward');
		}

	});

	function doConfirm(msg, yesFn, noFn)
	{
	  	jQuery( ".lightbox.confirm" ).fadeToggle("fast");
	    var confirmBox = jQuery("#confirmBox");
	    confirmBox.find(".message").text(msg);
	    confirmBox.find(".yes,.no").unbind().click(function()
	    {
	        confirmBox.hide();
	    });
	    confirmBox.find(".yes").click(yesFn);
	    confirmBox.find(".no").click(noFn);
	    confirmBox.show();
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