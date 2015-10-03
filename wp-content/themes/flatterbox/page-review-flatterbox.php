<?php
/* Template Name: Review Flatterbox */
get_header('');


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
		$selected = ''; if ( str_replace(' ', '-', strtolower(get_sub_field('color_description'))) == get_field("card_style",$_GET["PID"]) ) : $selected = ' selected'; endif;
		$color_options .= '<option value="'.str_replace(' ', '-', strtolower(get_sub_field('color_description'))).'||'.get_sub_field('long_description').'||'.get_sub_field('color_image').'"'.$selected.'>'.get_sub_field('color_description').'</option>';
	endwhile; endif;

endwhile; endif;
?>
<?php // $page_background_photo = getOccasionBGImg(0); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
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
				<h2>Select Your Changes</h2>
				<div class="product-description"><?php the_content(); ?></div>
				<div class="form-holder">
					<form id="choose_card_form">
						<?php
							$args = array(
									  'order'		=> 'none',
									  'hide_empty'	=> false
									);
							$iterms = get_terms('flatterbox_type', $args);
							$cat_list = '';
							foreach($iterms as $iterm) :
								if ($iterm->name == get_field('occasion',$_GET["PID"])) : $selected = ' selected'; else : $selected = ''; endif;
								$cat_list .= '<option value="'.$iterm->name.'"'.$selected.'>'.$iterm->name.'</option>';
							endforeach;
						?>
						<select id="occasion">
							<option value="Select Occasion" selected="selected">Select Occasion</option>
							<?php echo $cat_list; ?>
						</select>
						<select id="card-color">
							<option>Card Color</option>
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
							<option value="69">Up to 50 cards - included</option>
							<option value="84">Up to 100 cards + $15</option>
							<option value="104">Up to 200 cards + $35</option>
						</select>
						<div class="fullwidth">
							<?php

							?>
							<input type="checkbox" name="add10" id="add10" value="add10"/><label for="add10">&nbsp;10 Additional Cards</label>
						</div>
						<?php // Hidden input since there's only one box type. ?>
						<input id="box-type" type="hidden" value="acrylic" />
						<input id="submitstep1" type="submit" value="submit" style="display:none;" />
					</form>
				</div>
				<div class="price-row">
					<div class="price"><sup>$</sup><span id="price-dollar">75</span>.<sup>00</sup></div>
				</div>
				<div class="action-row">
					<div class="note">Having trouble deciding? Don’t worry, you can change your mind later!</div>
					<a href="javascript:;" class="btn save orangebtn" onclick="goToStep3();">Save</a>
					<div class="clearing"></div>
					<a href="#" class="btn save orangebtn" onclick="goToStep3(true);">Edit More Options</a>
				</div>
			</div>
		</div><!-- end gallery-holder -->
		<div class="control-bar bottom">
			<br/>
			<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox" class="btn back">Back to My Flatterbox</a>
		</div>
	</div>
</main>
<?php endwhile; endif; ?>
<?php $loggedIn = is_user_logged_in(); ?>
<!-- SCRIPTS -->
<script>
// Set Values
var selectedColor = "";
var selectedBox = "";
var selectedBoxColor = "";
var selectedCardCount = "";
var selectedCardQty = 0;
var selectedBoxImage = "";
var selectCount = 0;
var selectedColorDesc = "";
var selectedColorImg = "";
var selectedOccasion = "";
var selectedAdd10 = 0;
function updateVals() {
	var colorobj = jQuery( "#card-color option:selected" ).val();
	var colorobjarr = colorobj.split('||')
	selectedColor = colorobjarr[0];
	selectedColorDesc = colorobjarr[1];
	selectedColorImg = colorobjarr[2];
	if (selectedColorDesc.length == 0) { selectedColorDesc = '<p>&nbsp;</p>'; }
	selectedBox = jQuery( "#box-type" ).val();
	selectedBoxColor = "clear";
	selectedCardCount = jQuery( "#card-count option:selected").val();
	selectedOccasion = jQuery( "#occasion option:selected").val();
}
jQuery( "#occasion" ).change(function() {
	updateVals();
});
// Change Image and Set Box Type and Card Color
jQuery( "#card-color" ).change(function() {
	
	updateVals();
	//jQuery( ".product-image" ).css("background-image", "url(<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/images/"+selectedBox+"-"+selectedBoxColor+"-"+selectedColor+".png)");  
	jQuery(".product-image" ).css("background-image", "url("+selectedColorImg+")");
	var tempBackground = jQuery(".product-image").css("background-image");
	selectedBoxImage = tempBackground.replace('url(','').replace(')','');
});
// Change and Set Price based on Card Quantity
jQuery( "#card-count" ).change(function() {
	
	updateVals();
	selectCount = jQuery( "#card-count option:selected" ).val();
	if (selectCount == "69") {
		selectedCardQty = 50;
	} else if (selectCount == "84") {
		selectedCardQty = 100;
	} else if (selectCount == "104") {
		selectedCardQty = 200;
	}
	jQuery("#price-dollar").html(selectCount);
});
// Set Value of redirect
<?php if (empty($loggedIn) || !$loggedIn) : $_SESSION["returnURL"] = curPageURL(); endif ?>
function goToStep3(editOptions) {
	editOptions = typeof editOptions !== 'undefined' ? editOptions : false;

	if (jQuery('#add10').prop( "checked" ) == true) { selectedAdd10 = 1; }

	updateVals();
	selectedBoxImage = selectedBoxImage.replace('"', '');
	selectCount = jQuery( "#card-count option:selected" ).val();
	if (selectCount == "69") {
		selectedCardQty = 50;
	} else if (selectCount == "84") {
		selectedCardQty = 100;
	} else if (selectCount == "104") {
		selectedCardQty = 200;
	}
	if((jQuery("#card-color").val() != 'Card Color') && (jQuery("#card-count").val() != '0'))
	{
			var totalprice = 0;
			totalprice = Number(selectCount);
			var docLoc = '<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/?modifyPID=<?php echo $_GET["PID"]; ?>&boxtype=' + selectedBox + '&boxprice=' + totalprice + '&boxcolor=' + selectedBoxColor + '&cardcolor=' + selectedColor + '&cardquantity=' + selectedCardQty + '&cardquantityprice=' + totalprice + '&totalprice=' + totalprice + '&cardcolorimg=' + jQuery("#hiddencardcolorimg").val() + '&boxtypeimg=' + selectedColorImg + '&occasion=' + selectedOccasion;
			docLoc = docLoc + '&add10=' + selectedAdd10;
			if (editOptions) { docLoc = docLoc + '&editOptions=1';}
			document.location = docLoc;
	} else {
		alert('Please fill out all fields before continuing.');
	}
}
<?php
	echo "jQuery('#box-type').val('" . get_field("box_style",$_GET["PID"]) . "');updateVals();\n";
	//echo "jQuery('#card-color').val('" . get_field("card_style",$_GET["PID"]) . "');updateVals();\n";
	//echo "jQuery( '.product-image' ).css('background-image', 'url(" . do_shortcode('[site_url]') . "/wp-content/themes/flatterbox/images/" . get_field("box_style",$_GET["PID"]) . "-" . get_field("box_color",$_GET["PID"]) . "-" . get_field("card_style",$_GET["PID"]) . ".png)');\n";
	echo "var tempBackground = jQuery('.product-image').css('background-image');selectedBoxImage = tempBackground.replace('url(','').replace(')','');\n";
	echo "jQuery('#card-count').val('" . get_field("total_price",$_GET["PID"]) . "');updateVals();selectCount = jQuery( '#card-count option:selected' ).val();jQuery('#price-dollar').html(selectCount);\n";
	echo "jQuery('#add10').prop('checked','" . get_field("add10",$_GET["PID"]) . "');updateVals();\n";
	// See if should disable unclicking due to too many cards.
	$received_count = $wpdb->get_var("SELECT COUNT(SID) FROM sentiments WHERE sentiment_text <> '' AND PID = " . $_GET["PID"]); //FID != 0 AND 
	$cardcount = intval(get_field("card_quantity",$_GET["PID"]));
	if (strlen(trim(get_field('title_card_headline',$_GET["PID"]))) > 0) :
		$received_count = $received_count + 1;
	endif;
	if ( $received_count > $cardcount &&  $received_count-$cardcount <=10 ) : echo "jQuery('#add10').attr('onclick', 'return false');"; endif;
	?>
jQuery(".product-image" ).css("background-image", "url("+selectedColorImg+")");
</script>
<?php get_footer(); ?>
