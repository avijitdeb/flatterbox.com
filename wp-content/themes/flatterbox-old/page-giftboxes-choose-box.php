<?php
/* Template Name: FB Gift Boxes - Choose Box and Card */
get_header();
session_unset();
$args=array(
	'post_type' => 'box_types',
	'post_status' => 'publish',
	'order' => 'ASC',
	'posts_per_page' => 1 // Currently only acrylic is used - need to update this when we add "Box-Types" to the select
);
$box_type_query = new WP_Query($args);
$boxtype_desc = '';
if ( $box_type_query->have_posts() ) : while ( $box_type_query->have_posts() ) : $box_type_query->the_post(); 
	if ( get_field('descriptive_copy') ) : 
		$boxtype_desc = get_field('descriptive_copy');
	endif;
	$color_options = '';
	if ( have_rows('card_colors') ) : while(have_rows('card_colors')) : the_row();
		$color_options .= '<option value="'.str_replace(' ', '-', strtolower(get_sub_field('color_description'))).'||'.get_sub_field('long_description').'||'.get_sub_field('box_only_image').'">'.get_sub_field('color_description').'</option>';
	endwhile; endif;
endwhile; endif;
?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="lightbox boxes">
	<?php include('includes/productboxes.php'); ?>
</div>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<div class="product-holder">
			<div class="product-image">
				<h3>A preview of your Flatterbox</h3>
				<div class="description">
					<div class="arrow"></div>
					<div class="text"><?php echo $boxtype_desc; ?></div>
				</div>
			</div>
			<div class="product-info">
				<h2><?php if ($_REQUEST['productid'] == 703) { echo "The I Love You Collection"; } else if ($_REQUEST['productid'] == 698) { echo "The I'm Thankful For Collection"; } else if ($_REQUEST['productid'] == 661) { echo "The Grab-N-Go Collection"; } else { ?>Let's get started<?php } ?>.</h2>
				<div class="product-description">Design how you want your Flatterbox to look. Choose your box design, card color and the amount of sentiments to deliver from the drop down menus below.</div>
				<div class="product-description">Your <a href="#" id="boxsizes">box size</a> is dependant on the number of cards chosen</div>
				<div class="product-description" id="color-desc"><p>&nbsp;</p></div>
				<div class="form-holder">
					<form>
						<?php if (false) : ?>
						<select id="box-type">
							<option selected>Box Type</option>
							<?php
							$type = 'box_types';
							$args=array(
								'post_type' => $type,
								'post_status' => 'publish',
								'orderby' => 'menu_order',
								'order' => 'ASC',
								'posts_per_page' => -1,
								'caller_get_posts'=> 1
							);
							$my_query = new WP_Query($args);
							$i = 1;
							if( $my_query->have_posts() ) {
							while ($my_query->have_posts()) : $my_query->the_post(); ?>
							<option><?php the_title(); ?></option>
							<?php endwhile; 
							}
							wp_reset_query(); ?>
						</select>
						<?php endif; ?>
						<select id="card-color">
							<option value="||||<?php echo home_url(); ?>/wp-content/uploads/2014/12/navy-2.jpg" selected>Card Color</option>
							<?php if (false) : // Replaced with Dynamic code above with echo below ?>
							<option value="hot-pink">Hot Pink</option>
							<option value="navy">Navy</option>
							<option value="olive">Olive</option>
							<option value="pewter">Pewter</option>
							<option value="pink">Pink</option>
							<option value="pumpkin">Pumpkin</option>
							<option value="purple">Purple</option>
							<option value="scarlet">Scarlet</option>
							<option value="sea-foam">Sea Foam</option>
							<option value="sky-blue">Sky Blue</option>
							<option value="sunshine">Sunshine</option>
							<?php endif; ?>
							<?php echo $color_options; ?>
						</select>
						<select id="card-count">
							<option value="0" selected>Number of Cards</option>
							<option value="49">Up to 50 cards - included</option>
							<option value="59">Up to 100 cards + $10</option>
							<?php if (false) : ?><option value="79">Up to 200 cards + $20</option><?php endif; ?>
						</select>
						<?php // Hidden input since there's only one box type. ?>
						<input id="box-type" type="hidden" value="acrylic" />
						<input id="box_image_url" type="hidden" value="" />
						<input id="submitstep1" type="submit" value="submit" style="display:none;" />
					</form>
				</div>
				<div class="price-row">
					<div class="price"><sup>$</sup><span id="price-dollar">49</span>.<sup>00</sup></div>
				</div>
				<div class="action-row">
					<div class="note"></div>
					<a href="javascript:;" class="btn save orangebtn" onclick="goToStep3();">Next</a>
				</div>
			</div>
		</div><!-- end gallery-holder -->
	</div>
</main>
<?php endwhile; endif; ?>

<?php $loggedIn = is_user_logged_in(); ?>

<!-- SCRIPTS -->
<script type="text/javascript">
jQuery( "#boxsizes" ).click(function($) {
	event.preventDefault();
  	jQuery( ".lightbox.boxes" ).fadeToggle("fast");
});
</script>
<script>
// Set Values

var selectedColor = "";
var selectedBox = "";
var selectedBoxColor = "";
var selectedCardCount = "";
var selectedCardQty = 0
var selectedBoxImage = "";
var selectCount = 0;
var cardquantity = 0;
var productID = <?php echo $_REQUEST['productid']; ?>;
var variationID = "";
var totalSentimentCards = 0;
var selectedColorDesc = "";
var selectedColorImg = "";

function updateVals() {
	var colorobj = jQuery( "#card-color option:selected" ).val();
	var colorobjarr = colorobj.split('||')
	selectedColor = colorobjarr[0];
	selectedColorDesc = colorobjarr[1];
	selectedColorImg = colorobjarr[2];
	selectedBox = jQuery( "#box-type" ).val();
	selectedBoxColor = "clear";
	selectedCardCount = jQuery( "#card-count option:selected").val();

	cardquantity = selectedCardCount;

	console.log(cardquantity);

	if (cardquantity == "49") {
		variationID = <?php echo $_REQUEST['variation1']; ?>;
		totalSentimentCards = 50;
	} else if (cardquantity == "59") {
		variationID = <?php echo $_REQUEST['variation2']; ?>;
		totalSentimentCards = 100;
	} else if (cardquantity == "79") {
		variationID = <?php echo $_REQUEST['variation4']; ?>;
		totalSentimentCards = 200;
	}

	jQuery("#box_image_url").val(selectedColorImg);
}

// Change Image and Set Box Type and Card Color
jQuery( "#card-color" ).change(function() {
	
	updateVals();

	//jQuery( ".product-image" ).css("background-image", "url(<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/images/"+selectedBox+"-"+selectedBoxColor+"-"+selectedColor+".png)");  
	jQuery(".product-image" ).css("background-image", "url("+selectedColorImg+")");
	var tempBackground = jQuery(".product-image").css("background-image");
	selectedBoxImage = tempBackground.replace('url(','').replace(')','');
	jQuery("#color-desc").html(selectedColorDesc);

});

// Change and Set Price based on Card Quantity
jQuery( "#card-count" ).change(function() {
	
	updateVals();

	selectCount = jQuery( "#card-count option:selected" ).val();

	if (selectCount == "49") {
		selectedCardQty = 50;
	} else if (selectCount == "59") {
		selectedCardQty = 100;
	} else if (selectCount == "79") {
		selectedCardQty = 200;
	}

	jQuery("#price-dollar").html(selectCount);

});

// Set Value of redirect
<?php if (empty($loggedIn) || !$loggedIn) : $_SESSION["returnURL"] = curPageURL(); endif ?>
function goToStep3() {
	if((jQuery("#card-color").val() != '||||<?php echo home_url(); ?>/wp-content/uploads/2014/12/navy-2.jpg') && (jQuery("#card-count").val() != '0')) {
			var totalprice = 0;
			totalprice = Number(selectCount);	
			// document.location='<?php echo do_shortcode('[site_url]'); ?>/flatterbox-options/?boxtype=' + selectedBox + '&boxprice=' + totalprice + '&boxcolor=' + selectedBoxColor + '&cardcolor=' + selectedColor + '&cardquantity=' + selectedCardQty + '&cardquantityprice=' + totalprice + '&totalprice=' + totalprice + '&cardcolorimg=' + jQuery("#hiddencardcolorimg").val() + '&boxtypeimg=' + selectedBoxImage;
			document.location='<?php echo do_shortcode('[site_url]'); ?>/cart/?add-to-cart='+productID+'&variation_id='+variationID+'&attribute_pa_boxtype='+selectedBox+'&attribute_pa_boxcolor='+selectedBoxColor+'&attribute_pa_cardcolor='+selectedColor+'&attribute_pa_cardquantity='+selectedCardQty+'&attribute_pa_sentimentcount='+totalSentimentCards+'&box_image_url='+selectedColorImg;
	} else {
		alert('Please fill out all fields before continuing.');
	}
}

<?php
	if(isset($_SESSION["boxtype"])){ echo "jQuery('#box-type').val('" . $_SESSION["boxtype"] . "');updateVals();\n";  }
	if(isset($_SESSION["cardcolor"])){ echo "jQuery('#card-color').val('" . $_SESSION["cardcolor"] . "');updateVals();	jQuery( '.product-image' ).css('background-image', 'url(http://www.sbdcstage.com/flatterbox/wp-content/themes/flatterbox/images/" . $_SESSION["boxtype"] . "-" . $_SESSION["boxcolor"] . "-" . $_SESSION["cardcolor"] . ".png)');var tempBackground = jQuery('.product-image').css('background-image');selectedBoxImage = tempBackground.replace('url(','').replace(')','');\n";  }
	if(isset($_SESSION["boxprice"])){ echo "jQuery('#card-count').val('" . $_SESSION["boxprice"] . "');updateVals();selectCount = jQuery( '#card-count option:selected' ).val();jQuery('#price-dollar').html(selectCount);\n"; }
?>
</script>



<?php get_footer(); ?>