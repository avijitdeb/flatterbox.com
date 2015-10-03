<?php 

/* Template Name: Prep Order Step 1 - View Sentiments */

get_header();

global $woocommerce;

$woocommerce->cart->empty_cart(); 

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
	

	}	
}

//Get the number of sentiments remaining in an order

$_SESSION["sentimentPID"] = $_GET["PID"];

$totalcards = get_field('card_quantity',$_GET["PID"]);
$boxtype = get_field('box_style',$_GET["PID"]);
$boxcolor = get_field('box_color',$_GET["PID"]);
$cardcolor = get_field('card_style',$_GET["PID"]);
$currentcards = $wpdb->get_var("SELECT COUNT(SID) FROM sentiments WHERE approved = 1 AND PID = " . $_GET["PID"]);
$flatterboxName = get_field('who_is_this_for',$_GET["PID"]);

$totalcards = get_field('card_quantity',$_GET["PID"]);
$currentcards = $wpdb->get_var("SELECT COUNT(SID) FROM sentiments WHERE approved = 1 AND PID = " . $_GET["PID"]);

$remainingcards = $totalcards - $currentcards;

if(get_field('add10',$_GET["PID"]) == 1)
{
	$_SESSION["add10"] = 1;
} else {
	$_SESSION["add10"] = 0;
}

//Get selected Flatterbox's data
 
$flatterbox_post = get_post($_GET["PID"]); 
$PID = $flatterbox_post->ID;
$listview = $_REQUEST['listview'];

?> 
<?php // $page_background_photo = getOccasionBGImg(0); ?>
<main id="main" role="main">
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
		<ul id="status-bar" class="status-cart">
			<li class="active">View Sentiments</li>
			<?php if ( false ) : ?><li>Add Sentiments</li><?php endif; ?>
			<li>Preview Order</li>
			<li>Billing and Shipping</li>
			<li>Order Confirmation</li>
		</ul>
		<div id="filter-sentiments" class="control-bar">
			<?php $approve_count = $wpdb->get_var("SELECT COUNT(SID) FROM sentiments WHERE PID = " . $PID); ?>
			<?php if (get_field('title_card_headline', $PID)) : $approve_count = $approve_count +1; endif; ?>
			<h3>You Have <span id="sentimentcount"><?php echo $approve_count; ?></span> Sentiment<?php if ($approve_count != 1) : echo 's'; endif; ?></h3> <?php // echo '/'.get_field('card_quantity',$PID); ?>
			<div class="options">
				<span>View As:</span> 
				<a href="javascript:;" class="btn save orangebtn" onclick="continue_and_save();" style="display:block;margin-left:15px;">Check Out</a>
				<?php if (false) : ?><a href="javascript:;" class="btn save orangebtn" onclick="removeBlank(<?php echo $_GET["PID"]; ?>);" style="display:block;margin-left:15px;">Clear Blank Cards</a><?php endif; ?>
				<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/review-sentiments/?PID=<?php echo $PID ?>" class="btn save cardview <?php if ($listview != 1) : ?>active<?php endif; ?>" id="confirm" style="display:block;">Cards</a>
				<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/review-sentiments/?PID=<?php echo $PID ?>&listview=1" class="btn save listview <?php if ($listview == 1) : ?>active<?php endif; ?>" id="confirm" style="display:block;">List</a>&nbsp;&nbsp;
			</div>
		</div>
		<ul class="<?php if($_GET['listview']) { ?>card-list-stacked<?php } else { ?>card-list<?php } ?>">

		<?php
		
		//Loop through all sentiments in the flatterbox
		$sentiment_filter = 0;
		
		if($_GET["filter"])
		{
			$sentiment_filter = $_GET["filter"];
		}
		
		$sentiment_results = $wpdb->get_results( "SELECT * FROM sentiments WHERE sentiment_text <> '' AND PID = " . $PID . " ORDER BY sentimentdate DESC", ARRAY_A);
		$currentpending = $wpdb->get_var("SELECT COUNT(SID) FROM sentiments WHERE sentiment_text <> '' AND PID = " . $_GET["PID"]);

		?>
		<!-- Title Card Card/Row -->
		<?php if (get_field('title_card_headline', $PID)) : ?>
		<?php if($_GET['listview']) { ?>

		  <li id="titlecard_<?php echo $PID ?>" class="status_<?php echo '111' ?> sentimentcardlist sentimentrow titlecard">

					<div class="sentimenttext">
						<span>Title Card : <?php echo stripslashes(get_field('title_card_headline', $PID)); ?></span>
					</div>
					<div class="sentimentauthor">
						<span><?php echo stripslashes(get_field('title_card_name', $PID)); ?></span>
					</div>
					<div class="action">
						<form>
							<label><?php if($Row["FID"] == 0) { ?><a href="<?php echo do_shortcode('[site_url]'); ?>/title-card/?PID=<?php echo $PID; ?>&FID=<?php echo '222'; ?>&SID=<?php echo '000' ?>" class="cornerbtn edit" title="Edit Title Card"></a><?php } ?><a href="javascript:;" onclick="titleAction('delete',<?php echo $PID ?>);" class="cornerbtn reject" title="Delete Title Card"></a></label>
						</form>
					</div>					
		
			</li>		  
		  <?php } else { ?>
			<li id="titlecard_<?php echo $PID ?>" class="status_<?php echo '111' ?> sentimentcard editsentiment titlecard">
				<div class="selectSentiment">
					<form>
						<label><?php if($Row["FID"] == 0) { ?><a href="<?php echo do_shortcode('[site_url]'); ?>/title-card/?PID=<?php echo $PID; ?>&FID=<?php echo '222'; ?>&SID=<?php echo '000' ?>" class="cornerbtn edit" title="Edit Title Card"></a><?php } ?><a href="javascript:;" onclick="titleAction('delete',<?php echo $PID ?>);" class="cornerbtn reject" title="Delete Title Card"></a></label>
					</form>
				</div>
				<div class="frame">
					<p><?php echo stripslashes(get_field('title_card_headline', $PID)); ?></p>
					<span class="name"><?php echo stripslashes(get_field('title_card_name', $PID)); ?></span>
					<div class="fb_squ_logo"><img src="<?php echo get_template_directory_uri(); ?>/images/squarelogo.png" alt="<?php echo stripslashes('titlecard'); ?>"></div>
				</div>
			</li>
		
		<?php } ?>
		<?php else : // Need to Create?>
		<?php if($_GET['listview']) { ?>

		  <li id="titlecard_<?php echo $PID ?>" class="status_<?php echo '111' ?> sentimentcardlist sentimentrow titlecard">

					<div class="sentimenttext">
						<span>Title Card : <?php echo stripslashes('Add Title Card Headline'); ?></span>
					</div>
					<div class="sentimentauthor">
						<span><?php echo stripslashes('Add Title Card Name'); ?></span>
					</div>
					<div class="action">
						<form>
							<label><?php if($Row["FID"] == 0) { ?><a href="<?php echo do_shortcode('[site_url]'); ?>/title-card/?PID=<?php echo $PID; ?>&FID=<?php echo '222'; ?>&SID=<?php echo '000' ?>" class="cornerbtn add" title="Add Title Card"></a><?php } ?></label>
						</form>
					</div>					
		
			</li>		  
		  <?php } else { ?>
			<li id="titlecard_<?php echo $PID ?>" class="status_<?php echo '111' ?> sentimentcard editsentiment titlecard">
				<div class="selectSentiment">
					<form>
						<label><?php if($Row["FID"] == 0) { ?><a href="<?php echo do_shortcode('[site_url]'); ?>/title-card/?PID=<?php echo $PID; ?>&FID=<?php echo '222'; ?>&SID=<?php echo '000' ?>" class="cornerbtn add" title="Add Title Card"></a><?php } ?></label>
					</form>
				</div>
				<div class="frame">
					<p><?php echo stripslashes('Add Title Card Headline'); ?></p>
					<span class="name"><?php echo stripslashes('Add Title Card Name'); ?></span>
					<div class="fb_squ_logo"><img src="<?php echo get_template_directory_uri(); ?>/images/squarelogo.png" alt="<?php echo stripslashes('titlecard'); ?>"></div>
				</div>
			</li>
		
		<?php } ?>
		<?php endif; // End Title Card ?>

		<!-- Add New Sentiment Card/Row -->
		<?php if($_GET['listview']) { ?>
		
			<li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcardlist addrow">

					<div class="title">
						Add A New Sentiment!
					</div>
					<div class="action">
						<form>
							<label><a href="<?php echo do_shortcode('[site_url]'); ?>/add-sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&newcard=1" class="cornerbtn add" title="Add a New Sentiment Card"></a></label>
						</form>
					</div>					
		
			</li>	
				
		<?php } else { ?>
		
			<li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcard">
				<div class="selectSentiment">
					<form>
						<label><a href="<?php echo do_shortcode('[site_url]'); ?>/add-sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&newcard=1" class="cornerbtn add" title="Add a New Sentiment Card"></a></label>
					</form>
				</div>
				<div class="frame">
					<p>Add a new sentiment</p>
					<span class="name"></span>
				</div>
			</li>		
				
		<?php } // End Add New ?>

		<?php 

		if ($sentiment_results) {

		foreach ($sentiment_results as $Row)
		
		  {
		
		?>
		  
		  <?php if($_GET['listview']) { ?>

		  <!-- Sentiment Cards/Rows -->
		  <li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcardlist sentimentrow">

					<div class="sentimenttext">
						<span><?php echo stripslashes($Row["sentiment_text"]); ?></span>
					</div>
					<div class="sentimentauthor">
						<span><?php echo stripslashes($Row["sentiment_name"]); ?></span>
					</div>
					<div class="action">
						<form>
							<label><?php if($Row["FID"] == 0) { ?><a href="<?php echo do_shortcode('[site_url]'); ?>/add-sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $Row["FID"]; ?>&SID=<?php echo $Row["SID"] ?>" class="cornerbtn edit" title="Edit Sentiment Card"></a><?php } ?><a href="javascript:;" onclick="sentimentAction('delete',<?php echo $Row["SID"] ?>);" class="cornerbtn reject" title="Delete Sentiment Card"></a></label>
						</form>
					</div>					
		
			</li>		  
		  <?php } else { ?>
			<li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcard editsentiment">
				<div class="selectSentiment">
					<form>
						<label><?php if($Row["FID"] == 0) { ?><a href="<?php echo do_shortcode('[site_url]'); ?>/add-sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $Row["FID"]; ?>&SID=<?php echo $Row["SID"] ?>" class="cornerbtn edit" title="Edit Sentiment Card"></a><?php } ?><a href="javascript:;" onclick="sentimentAction('delete',<?php echo $Row["SID"] ?>);" class="cornerbtn reject" title="Delete Sentiment Card"></a></label>
					</form>
				</div>
				<div class="frame">
					<p><?php echo stripslashes(nl2br($Row["sentiment_text"])); ?></p>
					<span class="name">-<?php echo stripslashes($Row["sentiment_name"]); ?></span>
				</div>
			</li>
		
		<?php
			} // if listview
		  }  // foreach

		} // if $results

		?>		
		<?php if (false) : //Not including Blank Cards at the moment ?>
		<!-- Add Blank Cards Card/Row -->
		<?php if($_GET['listview']) { ?>
			<li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcardlist addrow addblank">

					<div class="title">
						Add blank cards to fill out later. 
						<?php $currentblank = $wpdb->get_var("SELECT COUNT(SID) FROM sentiments WHERE sentiment_text = '' AND PID = " . $_GET["PID"]); ?>
						<br/><br/><b>CURRENT BLANK CARDS: <span id="currentblank" class="blankcardnum"><?php echo $currentblank; ?></span></b>
					</div>
					<div class="action">
					<!--
						<form>
							<label><a href="<?php echo do_shortcode('[site_url]'); ?>/sentiment/?PID=<?php echo $_SESSION["sentimentPID"]; ?>&FID=<?php echo $_SESSION["sentimentFID"]; ?>&newcard=1" class="cornerbtn add"></a></label>
							<label>
								<select>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
								</select>
							</label>
						</form>
						-->
						
						<form>
							<label><a href="javascript:;" onclick="removeBlank('<?php echo $_GET["PID"] ?>');" class="cornerbtn reject"></a></label>
							<label><a href="javascript:;" class="cornerbtn add" onclick="addSentiment(0,jQuery('#blankcount').val());"></a></label>
							
							<select id="blankcount" name="blankcount">
							<?php 
							for ($i = 0; $i <= $remainingcards; $i++) {
							
							echo "<option value='" . $i . "'>" . $i . "</option>";
								
							} 
							?>						
							</select>
						</form>						
						
					</div>					
		
			</li>		
		<?php } else { ?>
			<li id="sentiment_<?php echo $Row["SID"] ?>" class="status_<?php echo $Row["approved"] ?> sentimentcard addblank">
				<div class="selectSentiment" style="width:260px;">

					<form>
					    
						<label><a href="javascript:;" onclick="removeBlank('<?php echo $_GET["PID"] ?>');" class="cornerbtn reject" style="float:right;"></a><a href="javascript:;" class="cornerbtn add" onclick="addSentiment(0,jQuery('#blankcountcard').val());"></a></label>
						<select id="blankcountcard" name="blankcountcard">
						<?php 
						for ($i = 0; $i <= $remainingcards; $i++) {
						
						echo "<option value='" . $i . "'>" . $i . "</option>";
							
						} 
						?>						
						</select>
						
					</form>						
					
				</div>
				<div class="frame">
					<p>Add blank cards to fill out later.
					<?php $currentblank = $wpdb->get_var("SELECT COUNT(SID) FROM sentiments WHERE sentiment_text = '' AND PID = " . $_GET["PID"]); ?>
					<br/><br/><b>CURRENT BLANK CARDS: <span id="currentblank" class="blankcardnum"><?php echo $currentblank; ?></span></b>
					</p>
					
					
					<span class="name"></span>
				</div>
			</li>			
		<?php } ?>	
	<?php endif; // End not including blank cards ?>
		</ul>
		<input type="hidden" id="pendingcardcount" value="<?php echo $currentpending; ?>" />
		<div class="control-bar bottom">
			<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/" class="btn back">Save &amp; Back to My Flatterbox</a>
			<a href="javascript:;" class="btn save" onclick="continue_and_save();">Check Out</a>
		</div>
		<script>
		jQuery( ".card-list > li" ).hover(function($) {
		  	jQuery(".hoverOptions", this).slideToggle("fast");
		});
		</script>
	</div>
</main>
<span id="remainingcount" style="font-size: 25px !important;display:none;"><?php echo $remainingcards ?></span>
<?php get_footer(); ?>

<script>

function addSentiment(sentence_id,cardcount)
{
	var currentcount = parseInt(jQuery("#remainingcount").html(),10);
	var currentblank = parseInt(jQuery("#currentblank").html(),10);
	
	jQuery.ajax({
	url:"<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/add_sentiment.php",
	type:'post',
	data:{'sentence_id':sentence_id,'cardcount':cardcount,'PID':'<?php echo $_GET["PID"]; ?>'},
	success: function(data, status) {
		if(data=="ok") {
			if(sentence_id != 0)
			{
			jQuery("#sentiment_"+sentence_id).fadeOut();
			currentcount = currentcount - 1;
			jQuery("#remainingcount").html(currentcount);
			} else {
				alert(cardcount + " blank cards have been added to your flatterbox.");
				currentcount = currentcount - cardcount;
				jQuery("#remainingcount").html(currentcount);	
				newcardcount = parseInt(jQuery('#sentimentcount').html()) + parseInt(cardcount);
				jQuery('#sentimentcount').html(newcardcount);	
				newblank = currentblank + parseInt(cardcount);
				jQuery('.blankcardnum').html(newblank);
			}
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

function sentimentAction(action,sid)
{
	if(action=='delete')
	{
		var confirmdelete = confirm("Are you sure you want to delete this sentiment?");
		if(confirmdelete) 
		{
			jQuery.ajax({
			
			url:"<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/sentiment_action.php",
			type:'post',
			data:{'action':action,'SID':sid},
			success: function(data, status) {
				if(data=="ok") {
					jQuery("#sentiment_"+sid).fadeOut(250,function() 
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
		
		url:"<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/sentiment_action.php",
		type:'post',
		data:{'action':action,'SID':sid},
		success: function(data, status) {
			if(data=="ok") {
				jQuery("#sentiment_"+sid).fadeOut(1000,function() 
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
var $window = jQuery(window),
$stickyEl = jQuery('#filter-sentiments'),
$stickyPad = jQuery('#status-bar'),
elTop = $stickyEl.offset().top;

$window.scroll(function() {
	$stickyEl.toggleClass('sticky', $window.scrollTop() > elTop);
	$stickyPad.toggleClass('stickypad', $window.scrollTop() > elTop);
});
</script>

<script>

function removeBlank(pid)
{
	jQuery.ajax({
	
	url:"<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/remove-blank.php",
	type:'post',
	data:{'PID':pid},
	success: function(data, status) {
		if(data=="ok") {

			alert('All blank cards are now removed from the Flatterbox.');
			location.reload();
			
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

var currentcount = parseInt(jQuery("#remainingcount").html(),10);

var totalSentimentCards = <?php echo $totalcards; ?> - currentcount;

function continue_and_save()
{

		var productID = 781;
		var variationID = "0";
		var boxtype = "<?php echo $boxtype; ?>";
		var boxcolor = "<?php echo $boxcolor; ?>";
		var cardcolor = "<?php echo $cardcolor; ?>";
		var cardquantity = <?php echo $totalcards; ?>;
		var flatterboxname = "<?php echo addslashes($flatterboxName); ?>";
		var flatterboxoccasion = "<?php echo addslashes($catname); ?>";
		var pid = <?php echo $_GET['PID']; ?>;
		
		cardcount = <?php echo $approve_count; ?>;
		//cardcount = 75;
		//JMD - New way of assigning variation ids based on actual number of sentiments received
		modified=0;
		
		if(cardcount > cardquantity)
		{
			modified=1;
		}
		
		addon = 0; // Tracking addon

		if (cardcount <= 60) {
			variationID = 782;
			cardquantity = 50;
			if (cardcount > 50) { addon = 1; }
		} else if (cardcount >= 61 && cardcount <= 110) { //60
			variationID = 783;
			cardquantity = 100;
			if (cardcount > 100) { addon = 1; }
		} else if (cardcount > 110 && cardcount <= 210) {//210
			variationID = 784;
			cardquantity = 200;
			if (cardcount > 200) { addon = 1; }
		} else {
			variationID = -1;
			cardquantity = -1;
		} // to handle the off case - and force a phone call.
		
		// end of new code
		// Adjusted based on not working with the 10 additional


		// Added Manual 10
		<?php $add10_chk = get_field('add10',$_GET["PID"]); ?>
		//window.alert(<?php echo $add10_chk; ?> + ' -- ' + addon);
		if (addon == 0 && <?php if ( empty($add10_chk) ) : echo '0'; else : echo '1'; endif; ?> == 1) { addon = 1; } 

		//JMD - Old variation assignment that doesn't account for surplus amount of cards
		//if (cardquantity == 50) {
			//variationID = 782;
		//} else if (cardquantity == 100) {
			//variationID = 783;
		//} else if (cardquantity == 200) {
			//variationID = 784;
		//}
		var locHREF = "<?php echo site_url(); ?>/cart/?add-to-cart="+productID+"&variation_id="+variationID+"&attribute_pa_boxtype="+boxtype+"&attribute_pa_boxcolor="+boxcolor+"&attribute_pa_cardcolor="+cardcolor+"&attribute_pa_cardquantity="+cardquantity+"&attribute_pa_sentimentcount="+totalSentimentCards+"&attribute_pa_flatterboxname="+flatterboxname+"&attribute_pa_flatterboxoccasion="+flatterboxoccasion+"&modified="+modified+"&attribute_pa_flatterboxid="+pid+"&add10="+addon;

		window.location.href = locHREF;
		return false;
			

}

</script>	