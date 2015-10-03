<?php
/* Template Name: FB NEW Step 2 Choose Card & Box */
get_header('steps');
if(!$_GET["preservesession"])
{
	session_unset();
}

//For Real Time Handling (Live Event)
$realtime = '';
if (isset($_GET['realtime'])) : $realtime = '&realtime='.$_GET['realtime']; endif;

// For BoxColor input
if (isset($_GET['cardcolor'])) : $_SESSION['cardcolor'] = $_GET['cardcolor']; endif;

// For Occasion
if( isset($_GET["occasion"]) ) : $_SESSION["occasion"] = cleanData($_GET["occasion"]); endif;

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
	$cardcolor ='';
	 if (isset($_SESSION['cardcolor']) && $_SESSION['cardcolor'] != ''){
								$cardcolorArr = explode('||||',$_SESSION['cardcolor']);
								//print_r($cardcolorArr);exit;
								$cardcolor = $cardcolorArr[0];
							} 
	$color_options = '';
	if ( have_rows('card_colors') ) : while(have_rows('card_colors')) : the_row();
		$selected = ''; if ( str_replace(' ', '-', strtolower(get_sub_field('color_description'))) == $cardcolor ) : $selected = ' selected'; endif;
		$color_options .= '<option value="'.str_replace(' ', '-', strtolower(get_sub_field('color_description'))).'||'.get_sub_field('long_description').'||'.get_sub_field('color_image').'"'.$selected.'>'.get_sub_field('color_description').'</option>';
	endwhile; endif;
endwhile; endif;
?>
<?php // $page_background_photo = getOccasionBGImg(0); ?>
<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<div class="lightbox boxes">
	<?php include('includes/productboxes.php'); ?>
</div>
<main id="main" role="main">
	<div class="container">
		<div class="heading steps">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<ul id="status-bar">
			<li class="active">Create It</li>
			<li>Personalize It</li>
			<li>Invite Flatterers</li>
			<li>Title Card</li>
			<li class="clearpadding">Write the First One!</li>
			<li>Complete</li>
		</ul>
		<div class="product-holder">
			<div class="product-image">
				<h3>A preview of your Flatterbox</h3>
				<?php if ( $boxtype_desc ) : // Again may need to add JS to hide / unhide this on boxtype change ?>
				<div class="description">
					<div class="arrow"></div>
					<div class="text"><?php echo $boxtype_desc; ?></div>
				</div>
				<?php endif; ?>
			</div>
			<div class="product-info">
				<h2>Let's get started.</h2>
				<div class="product-description"><?php the_content(); ?></div>
				<div class="product-description">Your <a href="#" id="boxsizes">box size</a> is dependant on the number of cards chosen</div>
				<div class="product-description" id="color-desc"><p>&nbsp;</p></div>
				<div class="product-description">You don't have to pay for this until you're 100% satisfied!</div>
				<div class="form-holder">
					<form id="choose_card_form">
						<?php if (false) : ?>
						<select id="box-type"  name="boxtype">
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
						<?php
							$args = array(
									  'order'		=> 'none',
									  'hide_empty'	=> false
									);
							$iterms = get_terms('flatterbox_type', $args);
							$cat_list = '';
							foreach($iterms as $iterm) :
								$selected = '';
								if (false && strtolower($_SESSION['occasion']) == strtolower($iterm->name)) : $selected = 'selected'; endif;
								$cat_list .= '<option value="'.$iterm->name.'" '.$selected.'>'.$iterm->name.'</option>';
							endforeach;
						?>
						<select id="occasion"  name="occasion">
							<option value="Select Occasion" >Select Occasion</option>
							<?php echo $cat_list; ?>
						</select>
						<?php if (isset($_SESSION['cardcolor']) && $_SESSION['cardcolor'] != ''){
								$cardcolorArr = explode('||||',$_SESSION['cardcolor']);
								//print_r($cardcolorArr);exit;
								$cardcolor = $cardcolorArr[0];
							} ?>
						<select id="card-color"  name="cardcolor">
							<option value="||||<?php echo home_url(); ?>/wp-content/uploads/2014/12/navy-2.jpg" selected>Card Color</option>
							<?php if (false) : // Replaced with Dynamic code above with echo below ?>
							

							<option value="hot-pink" <?php if($cardcolor == 'hot-pink'){ echo 'selected';}?>>Hot Pink</option>
							<option value="navy" <?php if($cardcolor == 'navy'){ echo 'selected';}?>>Navy</option>
							<option value="olive" <?php if($cardcolor == 'olive'){ echo 'selected';}?>>Olive</option>
							<option value="pewter" <?php if($cardcolor == 'pewter'){ echo 'selected';}?>>Pewter</option>
							<option value="pink" <?php if($cardcolor == 'pink'){ echo 'selected';}?>>Pink</option>
							<option value="pumpkin" <?php if($cardcolor == 'pumpkin'){ echo 'selected';}?>>Pumpkin</option>
							<option value="purple" <?php if($cardcolor == 'purple'){ echo 'selected';}?>>Purple</option>
							<option value="scarlet" <?php if($cardcolor == 'scarlet'){ echo 'selected';}?>>Scarlet</option>
							<option value="sea-foam" <?php if($cardcolor == 'sea-foam'){ echo 'selected';}?>>Sea Foam</option>
							<option value="sky-blue" <?php if($cardcolor == 'sky-blue'){ echo 'selected';}?>>Sky Blue</option>
							<option value="sunshine" <?php if($cardcolor == 'sunshine'){ echo 'selected';}?>>Sunshine</option>
							<?php endif; ?>
							<?php echo $color_options; ?>
						</select>
						<select id="card-count"  name="boxprice">
							<option value="0" selected>Number of Cards</option>
							<option value="69">Up to 50 cards - included</option>
							<option value="84">Up to 100 cards + $15</option>
							<option value="104">Up to 200 cards + $35</option>
						</select>
						<?php // Hidden input since there's only one box type. ?>
						<input id="box-type" type="hidden" value="acrylic" />
						<input id="submitstep1" type="submit" value="submit" style="display:none;" />
					</form>
				</div>
				<div class="price-row">
					<div class="price"><sup>$</sup><span id="price-dollar">69</span>.<sup>00</sup></div>
				</div>
				<div class="action-row">
					<div class="note">Having trouble deciding? Don’t worry, you can change your mind later!</div>
					<a href="javascript:;" id="choose_card_btn" class="btn save orangebtn" onclick="goToStep3();">Next</a>
				</div>
			</div>
		</div><!-- end gallery-holder -->
	</div>
</main>
<?php endwhile; endif; ?>
<?php $loggedIn = is_user_logged_in(); ?>
<!-- SCRIPTS -->
<script type="text/javascript">
/* Commenting out As Not sure what this is... Generates an error though cannot find "sessionSet" -- Andy
	jQuery(document).ready( function() {
   jQuery("#occasion").change( function() {
		sessionSet("choose_card_form");
   })
    jQuery("#card-color").change( function() {
		sessionSet("choose_card_form");
   })
	 jQuery("#card-count").change( function() {
		sessionSet("choose_card_form");
   })
	 jQuery("#choose_card_btn").click( function() {
		sessionSet("choose_card_form");
   })
	 

})
	*/
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
var selectedCardQty = 0;
var selectedBoxImage = "";
var selectCount = 0;
var selectedColorDesc = "";
var selectedColorImg = "";
var selectedOccasion = "";
var realtime = '<?php echo $realtime; ?>';
  
function updateVals() {
	var colorobj = jQuery( "#card-color option:selected" ).val();
	var colorobjarr = colorobj.split('||');
	selectedColor = colorobjarr[0];
	selectedColorDesc = colorobjarr[1];
	selectedColorImg = colorobjarr[2];
	if (selectedColorDesc.length == 0) { selectedColorDesc = '<p>&nbsp;</p>'; }
	selectedBox = jQuery( "#box-type" ).val(); // To be used in Updating desc of box type
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
	selectedBoxImage = tempBackground.replace('url("','').replace('")','');
	selectedBoxImage = tempBackground.replace('url(','').replace(')','');
	console.log('!!'+selectedBoxImage+'!!');
	jQuery("#color-desc").html(selectedColorDesc);
});
// Change and Set Price based on Card Quantity
jQuery( "#card-count" ).change(function() {
	
	updateVals();
	selectCount = jQuery( "#card-count option:selected" ).val();
	//console.log(selectCount);
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
function goToStep3() {
	if(jQuery("#occasion").val() != 'Select Occasion' && jQuery("#occasion").val() != '' && jQuery("#card-color").val() != '||||<?php echo home_url(); ?>/wp-content/uploads/2014/12/navy-2.jpg' && jQuery("#card-count").val() != '0')
	{
		if (<?php if (false && ( empty($loggedIn) || !$loggedIn) ): echo 'true'; else : echo 'false'; endif; ?>) {
			// Set Lightbox visible
			jQuery("#redirect-step2").val('<?php echo do_shortcode('[site_url]'); ?>/flatterbox-options/?boxtype=' + selectedBox + '&boxprice=' + totalprice + '&boxcolor=' + selectedBoxColor + '&cardcolor=' + selectedColor + '&cardquantity=' + selectedCardQty + '&cardquantityprice=' + totalprice + '&totalprice=' + totalprice + '&cardcolorimg=' + jQuery("#hiddencardcolorimg").val() + '&boxtypeimg=' + selectedBoxImage + '&occasion=' + selectedOccasion + realtime);
			extraURL = jQuery("#createaccountlink").attr("href")+'?creatingbox=1&boxtype=' + selectedBox + '&boxprice=' + totalprice + '&boxcolor=' + selectedBoxColor + '&cardcolor=' + selectedColor + '&cardquantity=' + selectedCardQty + '&cardquantityprice=' + totalprice + '&totalprice=' + totalprice + '&cardcolorimg=' + jQuery("#hiddencardcolorimg").val() + '&boxtypeimg=' + selectedBoxImage + '&occasion=' + selectedOccasion + realtime;
			//window.alert(extraURL);
			jQuery("#createaccountlink").attr("href",extraURL);
			jQuery("#createaccountlink-step2").val(extraURL);
			jQuery( ".lightbox-step2.login-step2" ).fadeToggle("fast");
			jQuery('html, body').animate({scrollTop : 0},800);
		} else {
			var totalprice = 0;
			totalprice = Number(selectCount);
			var addURL = '';
			if (getParameterByName('includestep3')) {
				addURL = getQry();
			}
			document.location='<?php echo do_shortcode('[site_url]'); ?>/flatterbox-options/?boxtype=' + selectedBox + addURL + '&boxprice=' + totalprice + '&boxcolor=' + selectedBoxColor + '&cardcolor=' + selectedColor + '&cardquantity=' + selectedCardQty + '&cardquantityprice=' + totalprice + '&totalprice=' + totalprice + '&cardcolorimg=' + jQuery("#hiddencardcolorimg").val() + '&boxtypeimg=' + selectedBoxImage + '&occasion=' + selectedOccasion + realtime;
		}
	} else {
		alert('Please fill out all fields before continuing.');
	}
}

//window.alert('<?php echo $_GET["occasion"]; ?>\n<?php echo $_SESSION["occasion"]; ?>\n<?php echo addslashes($_SESSION["occasion"]); ?>');
<?php
	if(isset($_SESSION["boxtype"])){ echo "jQuery('#box-type').val('" . $_SESSION["boxtype"] . "');updateVals();\n";  }
	if(isset($_SESSION["occasion"])){ echo "jQuery('#occasion').val(\"" . encodeData($_SESSION["occasion"]) . "\");updateVals();\n";  }
	//if(isset($_SESSION["cardcolor"])){ echo "jQuery('#card-color').val('" . $_SESSION["cardcolor"] . "');updateVals();	jQuery( '.product-image' ).css('background-image', 'url(" . do_shortcode('[site_url]') . "/wp-content/themes/flatterbox/images/" . $_SESSION["boxtype"] . "-" . $_SESSION["boxcolor"] . "-" . $_SESSION["cardcolor"] . ".png)');var tempBackground = jQuery('.product-image').css('background-image');selectedBoxImage = tempBackground.replace('url(','').replace(')','');\n";  }
	if(isset($_SESSION['cardcolor'])){ echo 'updateVals();jQuery(".product-image" ).css("background-image", "url("+selectedColorImg+")");';}
	if(isset($_SESSION["boxprice"])){ echo "jQuery('#card-count').val('" . $_SESSION["boxprice"] . "');updateVals();selectCount = jQuery( '#card-count option:selected' ).val();jQuery('#price-dollar').html(selectCount);\n"; }
?>

function getQry(obj) {
	var queryString = unescape(location.search);

	if (!queryString) {
		return '';
	}

	//remove the ?
	queryString = queryString.substring(1);

	//split querystring into key/value pairs
	var pairs = queryString.split("&");
	var qry = "";

	for (var i = 0; i < pairs.length; i++) {
		var keyValuePair = pairs[i].split("=");
		//keyValuePair[0] = key
		//keyValuePair[1] = value
		qry = qry + "&" + keyValuePair[0] + "=" + keyValuePair[1];
	}
	return qry;
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

</script>
<?php 
function cleanData($str = '') {
	return trim(stripslashes(htmlspecialchars_decode($str)));	
}
function encodeData($str = '') {
	$str = cleanData($str);
	return trim(addslashes($str));
}
?>
<?php get_footer(); ?>