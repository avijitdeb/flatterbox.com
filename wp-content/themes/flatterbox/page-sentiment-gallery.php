<?php 

/* Template Name: Prep Order Step 2 - Sentiment Gallery */

get_header(); 

global $wpdb;

//Get the number of sentiments remaining in an order

$_SESSION["sentimentPID"] = $_GET["PID"];

$totalcards = get_field('card_quantity',$_GET["PID"]);
$boxtype = get_field('box_style',$_GET["PID"]);
$boxcolor = get_field('box_color',$_GET["PID"]);
$cardcolor = get_field('card_style',$_GET["PID"]);
$currentcards = $wpdb->get_var("SELECT COUNT(SID) FROM sentiments WHERE sentiment_text <> '' AND approved = 1 AND PID = " . $_GET["PID"]);
$flatterboxName = get_field('who_is_this_for',$_GET["PID"]);

foreach (get_the_terms($_GET["PID"], 'flatterbox_type') as $cat) : 
	$catimage =  z_taxonomy_image_url($cat->term_id);
	$catname = $cat->name;
endforeach;

foreach (get_the_terms($_GET["PID"], 'flatterbox_type') as $cat) : 
	$catid =  $cat->term_id;
endforeach;
					

$remainingcards = $totalcards - $currentcards;

?>
<?php // $page_background_photo = getOccasionBGImg(0); ?>
<main id="main" role="main">
<?php if (false) : ?><main id="main" role="main" style="background-image: url('<?php the_field('page_background_photo'); ?>');>"><?php endif; ?>
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<ul id="status-bar">
			<li class="done">View Sentiments</li>
			<li class="active">Add Sentiments</li>
			<li>Preview Order</li>
			<li>Billing and Shipping</li>
			<li>Order Confirmation</li>
		</ul>
		<div id="filter-sentiments" class="control-bar">
			<div class="options">
				<a href="javascript:;" class="btn save orangebtn" onclick="save_and_continue();" style="display:block;margin-left:15px;">Save & Continue</a>
			</div>
			<h3>Select <span id="remainingcount" style="font-size: 25px !important;"><?php echo $remainingcards ?></span> Cards or Continue to Shipping</h3>
		</div>
		<ul class="card-list">
			<li id="blankcards" class="sentimentcard addblank">
				<div class="selectSentiment">

					<form>
						<label><a href="javascript:;" class="cornerbtn add" onclick="addSentiment(0,jQuery('#blankcountcard').val());"></a></label>
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
					<p>Add blank cards to fill out later.</p>
					<span class="name"></span>
				</div>
			</li>
			<?php
				$args = array(
				'posts_per_page'   => -1,
				'post_type'        => 'sentence_gallery'
				); 			
			
				$premade_sentiments = get_posts($args);
				
			foreach ( $premade_sentiments as $post ) : setup_postdata( $post ); ?>

			<?php if(in_array($catid, get_field('flatterbox_type',get_the_ID()))) { ?>
			<li id="sentiment_<?php the_ID(); ?>" class="cannedsentiment">
				<div class="selectSentiment">
					<form>
						<label><a href="javascript:;" class="cornerbtn add" onclick="addSentiment(<?php the_ID() ?>,0);"></a></label>
					</form>
				</div>
				<div class="frame">
					<p><?php the_field('sentence'); ?></p>
				</div>
			</li>
			<?php } ?>

			
			<?php endforeach; 
			wp_reset_postdata();
			?>			

		</ul>
		<div class="control-bar bottom">
			<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/" class="back">Back to My Flatterbox</a>
			<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/order-shipping-information" class="btn save" id="addToCartBTN">Save &amp; Continue</a>
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

	var currentcount = parseInt(jQuery("#remainingcount").html(),10);

	var totalSentimentCards = <?php echo $totalcards; ?> - currentcount;


	function addSentiment(sentence_id,cardcount) {

		currentcount = parseInt(jQuery("#remainingcount").html(),10);
		
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
				}
				totalSentimentCards = <?php echo $totalcards; ?> - currentcount;
			}
		},
		  error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		  }		
		
		});
	}

	jQuery("#addToCartBTN").click(function(e) {
		save_and_continue();
	});

	jQuery("#addToCartBTN").click(function(e) {
		e.preventDefault();
		save_and_continue();
	});

	function save_and_continue() {

		var productID = 781;
		var variationID = "0";
		var boxtype = "<?php echo $boxtype; ?>";
		var boxcolor = "<?php echo $boxcolor; ?>";
		var cardcolor = "<?php echo $cardcolor; ?>";
		var cardquantity = <?php echo $totalcards; ?>;
		var flatterboxname = "<?php echo $flatterboxName; ?>";
		var flatterboxoccasion = "<?php echo $catname; ?>";

		if (cardquantity == 50) {
			variationID = 782;
		} else if (cardquantity == 100) {
			variationID = 783;
		} else if (cardquantity == 200) {
			variationID = 784;
		}

		window.location.href = "<?php echo site_url(); ?>/cart/?add-to-cart="+productID+"&variation_id="+variationID+"&attribute_pa_boxtype="+boxtype+"&attribute_pa_boxcolor="+boxcolor+"&attribute_pa_cardcolor="+cardcolor+"&attribute_pa_cardquantity="+cardquantity+"&attribute_pa_sentimentcount="+totalSentimentCards+"&attribute_pa_flatterboxname="+flatterboxname+"&attribute_pa_flatterboxoccasion="+flatterboxoccasion;
		return false;

	}

</script>