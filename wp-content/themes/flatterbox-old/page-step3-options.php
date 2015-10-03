<?php

/* Template Name: FB Step 3 Options */
get_header('steps'); ?>
<?php $loggedIn = is_user_logged_in(); ?>
<?php

global $allowPasscode;
global $allowFlattererInvite;

$_GET['allowPasscode'] = false; // $allowPasscode;
$_GET['allowFlattererInvite'] = true; // $allowFlattererInvite;

// Write step 2 pertinent data to session
if( $_GET["boxtype"] ) : $_SESSION["boxtype"] = cleanData($_GET["boxtype"]); endif;
if( $_GET["boxprice"] ) : $_SESSION["boxprice"] = cleanData($_GET["boxprice"]); endif;
if( $_GET["boxcolor"] ) : $_SESSION["boxcolor"] = cleanData($_GET["boxcolor"]); endif;
if( $_GET["cardcolor"] ) : $_SESSION["cardcolor"] = cleanData($_GET["cardcolor"]); endif;
if( $_GET["cardquantity"] ) : $_SESSION["cardquantity"] = cleanData($_GET["cardquantity"]); endif;
if( $_GET["cardquantityprice"] ) : $_SESSION["cardquantityprice"] = cleanData($_GET["cardquantityprice"]); endif;
if( $_GET["totalprice"] ) : $_SESSION["totalprice"] = cleanData($_GET["totalprice"]); endif;
if( $_GET["cardcolorimg"] ) : $_SESSION["cardcolorimg"] = cleanData($_GET["cardcolorimg"]); endif;
if( $_GET["boxtypeimg"] ) : $_SESSION["boxtypeimg"] = cleanData($_GET["boxtypeimg"]); endif;
if( $_GET["occasion"] ) : $_SESSION["occasion"] = cleanData($_GET["occasion"]); endif;
if( $_GET["realtime"] ) : $_SESSION["realtime"] = cleanData($_GET["realtime"]); endif;

$_SESSION['boxtypeimg'] = str_replace('\\"', '', $_SESSION['boxtypeimg']);

$isModify = 0;
if (isset($_GET['PID'])) : 
	$_SESSION['new_flatterbox_id'] = $_GET['PID']; 
	$isModify = 1; 

	// SET OTHER SESSIONS (INCASE LOST)
	$_SESSION["boxtype"] = cleanData(get_field('box_style',$_GET['PID']));
	$_SESSION["boxprice"] = cleanData(get_field('total_price',$_GET['PID']));
	$_SESSION["boxcolor"] = cleanData(get_field('box_color',$_GET['PID']));
	$_SESSION["cardcolor"] = cleanData(get_field('card_style',$_GET['PID']));
	$_SESSION["cardquantity"] = cleanData(get_field('card_quantity',$_GET['PID']));
	$_SESSION["cardquantityprice"] = cleanData(get_field('total_price',$_GET['PID']));
	$_SESSION["totalprice"] = cleanData(get_field('total_price',$_GET['PID']));
	$_SESSION["cardcolorimg"] = ''; // Dont think this is used
	$_SESSION["boxtypeimg"] = cleanData(get_field('box_image_url',$_GET['PID']));
	$_SESSION['occasion'] = cleanData(get_field('occasion',$_GET['PID'])); 
	$_SESSION["realtime"] = cleanData(get_field('live_event',$_GET['PID']));
endif;

// Get Image?
$args=array(
	'post_type' => 'box_types',
	'post_status' => 'publish',
	'order' => 'ASC',
	'posts_per_page' => 1 // Currently only acrylic is used - need to update this when we add "Box-Types" to the select
);
$box_type_query = new WP_Query($args);
$color_options = '';
if ( $box_type_query->have_posts() ) : while ( $box_type_query->have_posts() ) : $box_type_query->the_post(); 
	if ( have_rows('card_colors') ) : while(have_rows('card_colors')) : the_row();
		if ( str_replace(' ', '-', strtolower(get_sub_field('color_description'))) == $_SESSION["cardcolor"] ) :
			$color_options = get_sub_field('silhouette_image');
		endif;
	endwhile; endif;
endwhile; endif;
if (strlen($color_options) == 0) : $color_options = $_SESSION["boxtypeimg"]; endif;

$sel_occasion = '';
if (isset($_SESSION['occasion'])) : $sel_occasion = $_SESSION['occasion']; endif;
$sel_realtime = '';
if (isset($_SESSION['realtime'])) : $sel_realtime = $_SESSION['realtime']; endif;

//echo $_SESSION['occasion'].' !! '.$sel_occasion;
?>

<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
<main id="main" role="main">
	<div class="container">
		<div class="heading steps">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<?php if (!isset($_GET['PID'])) : ?>
		<ul id="status-bar">
			<li class="done"><a href="<?php echo do_shortcode('[site_url]'); ?>/choose-card/?preservesession=1">Create It</a></li>
			<li class="active">Personalize It</li>
			<li>Invite Flatterers</li>
			<li>Title Card</li>
			<li class="clearpadding">Write the First One!</li>
			<li>Complete</li>
		</ul>
		<?php endif; ?>
		<div class="box">
			<div class="direction options">
				<?php the_content(); ?>
				<?php 
					unset($field_vals);
					$field_vals = array('sel_occasion' => $sel_occasion, 
									'sel_realtime' => $sel_realtime
									); // Remember to update form
					//print_r($field_vals);
				?>
				<?php gravity_form(34, $display_title=false, $display_description=false, $display_inactive=false, $field_values=$field_vals, $ajax=false, $tabindex); ?>

				<div class="styles previewstyles pinned" id="preview-email">
					<?php if (false) : ?>
					<div class="hold">
						<h2>Card Color</h2>
						<div class="choose">
							<div class="selected"><img src="<?php echo $_SESSION["cardcolorimg"]; ?>" /></div>
						</div>
					</div>
					<?php endif; ?>
					<div class="hold add">
						<h2>Your Flatterbox Invite - Preview</h2>
						<div id="flatterer-preview" class="flatterer-preview-options">
							<?php 
								//include ("preview-email.php"); // Changed to the Functions
								echo get_preview_email($_SESSION["new_flatterbox_id"]);
							?>
						</div>
						<?php if (false) : ?>
						<div class="choose">
							<div class="selected"><img src="<?php echo $color_options; ?>" /></div>
						</div>
						<?php endif; ?>
						<div class="choose data">
							<p class="pleft"><strong>Occasion : </strong></p><p class="pright"><?php echo $_SESSION['occasion']; ?></p>
							<div class="divider light"></div>
							<p class="pleft"><strong>Box Type : </strong></p><p class="pright"><?php echo $_SESSION['boxtype']; ?></p>
							<div class="divider light"></div>
							<p class="pleft"><strong>Box Color : </strong></p><p class="pright"><?php echo $_SESSION['boxcolor']; ?></p>
							<div class="divider light"></div>
							<p class="pleft"><strong>Card Color : </strong></p><p class="pright"><?php echo $_SESSION['cardcolor']; ?></p>
							<div class="divider light"></div>
							<p class="pleft"><strong>Card Quanity : </strong></p><p class="pright"><?php echo $_SESSION['cardquantity']; ?></p>
							<div class="divider light"></div>
							<p class="pleft"><strong>Price : </strong></p><p class="pright">$ <?php echo $_SESSION['cardquantityprice']; ?>.00</p>
						</div>
					</div>
				</div>

			</div>

		</div>
		<div class="control-bar bottom">
			<input type="hidden" name="auto_submit" id="auto_submit" value="0" />
			<?php if ($isModify == 0) : ?>
			<a href="javascript:;" onclick="gotoStep2();" class="btn back">Back to Create It</a>
			<a href="javascript:;" class="btn save" onclick="gotoStep4();">Next</a>
			<?php else : ?>
			<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/" class="btn back">Back My Flatterbox</a>
			<a href="javascript:;" class="btn save" onclick="gotoStep4(true);">Save</a>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php $iForm = "34" ?>

<script>
	jQuery( document ).ready(function($) {
		
	    $('#input_<?php echo $iForm; ?>_2').bind("change",updateDatesDel); // For delivery Date
	    $('#input_<?php echo $iForm; ?>_54').bind("change",updateDatesSent); // For Sentiment Due Date
		/* sessionSet is not defined.... ??? 
		jQuery("#card-count").change( function() {
			sessionSet("choose_card_form");
	   })
		 jQuery("#gform_34 input").blur( function() {
			sessionSet("gform_34");
	   })
		jQuery("#gform_34 select").change( function() {
			sessionSet("gform_34");
	   })
		jQuery("#gform_34 radio").click( function() {
			sessionSet("gform_34");
	   })
		jQuery("#gform_34 checkbox").click( function() {
			sessionSet("gform_34");
	   })
	 */
	});
	
	function updateDatesDel() {
		d = new Date(jQuery('#input_<?php echo $iForm; ?>_2').val());
		sentiment = new Date(d);
		sentiment.setDate(sentiment.getDate() - <?php echo intval(get_field('leadtime_for_boxes', 'option')); ?>);

		sm = sentiment.getMonth() + 1;

		jQuery('#input_<?php echo $iForm; ?>_54').val(sm + '/'+ sentiment.getDate() + '/'+ sentiment.getFullYear());
	} 
	function updateDatesSent(){
		d = new Date(jQuery('#input_<?php echo $iForm; ?>_54').val());
		sentiment = new Date(d);
		sentiment.setDate(sentiment.getDate() + <?php echo intval(get_field('leadtime_for_boxes', 'option')); ?>);

		sm = sentiment.getMonth() + 1;

		jQuery('#input_<?php echo $iForm; ?>_2').val(sm + '/'+ sentiment.getDate() + '/'+ sentiment.getFullYear());
	}

	// Variables
	var selectedBox = '<?php echo $_SESSION["boxtype"]; ?>';
	var selectedBoxColor = '<?php echo $_SESSION["boxcolor"]; ?>';
	var selectedColor = '<?php echo $_SESSION["cardcolor"]; ?>';
	var selectedCardQty = '<?php echo $_SESSION["cardquantity"]; ?>';
	var totalprice = '<?php echo $_SESSION["totalprice"]; ?>';
	var selectedBoxImage = '<?php echo $_SESSION["boxtypeimg"]; ?>';
	<?php 
		if (!isset($_GET['occasion'])) :
		$selectedOccasion = encodeData($_SESSION['occasion']);
		else :
		$selectedOccasion = encodeData($_GET['occasion']);
		endif;
	?>
	var selectedOccasion = '<?php echo $selectedOccasion; ?>';

	// Step 2
	var selectedWhoTo = jQuery('#input_<?php echo $iForm; ?>_1').val();
	var selectedWhoFrom = jQuery('#input_<?php echo $iForm; ?>_65').val();

	var selectedTheme = ''; 
	var nid = 0;

	var selectedRealTime 	 = jQuery('input:radio[name=input_78]:checked').val(); //jQuery('#input_<?php echo $iForm; ?>_78').val();
	var selectedDelivery 	 = jQuery('#input_<?php echo $iForm; ?>_2').val();
	var selectedFinalize 	 = jQuery('#input_<?php echo $iForm; ?>_54').val();
	var selectedInstructions = jQuery('#input_<?php echo $iForm; ?>_77').val();
	var selectedMultiple 	 = jQuery('input:radio[name=input_7]:checked').val(); //jQuery('#input_<?php echo $iForm; ?>_7').val();
	var selectedSentiments 	 = jQuery('input:radio[name=input_25]:checked').val(); //jQuery('#input_<?php echo $iForm; ?>_25').val();
	var selectedProfanity 	 = jQuery('input:radio[name=input_9]:checked').val(); //jQuery('#input_<?php echo $iForm; ?>_9').val();
	var selectedInvite		 = jQuery('input:radio[name=input_69]:checked').val(); //jQuery('#input_<?php echo $iForm; ?>_69').val();
	var selectedSummary 	 = jQuery('#input_<?php echo $iForm; ?>_75').val();

	function setVars() {
		selectedBox = '<?php echo $_SESSION["boxtype"]; ?>';
		selectedBoxColor = '<?php echo $_SESSION["boxcolor"]; ?>';
		selectedColor = '<?php echo $_SESSION["cardcolor"]; ?>';
		selectedCardQty = '<?php echo $_SESSION["cardquantity"]; ?>';
		totalprice = '<?php echo $_SESSION["totalprice"]; ?>';
		selectedBoxImage = '<?php echo $_SESSION["boxtypeimg"]; ?>';
		//selectedOccasion = '<?php echo addslashes($_SESSION["occasion"]); ?>';
		selectedOccasion = jQuery("#input_<?php echo $iForm; ?>_26").val(); 
		//window.alert(selectedOccasion);
		// Step 2
		selectedWhoTo = jQuery('#input_<?php echo $iForm; ?>_1').val();
		selectedWhoFrom = jQuery('#input_<?php echo $iForm; ?>_65').val();

		selectedTheme = ''; 
		nid = 0;
		switch(selectedOccasion) {
		    case 'Birthday': // 27
		    	nid = 27;
		        break;
		    case 'Anniversary': // 28
		    	nid = 28;
		        break;
		    case 'Coach\'s Gift': // 29
		    	nid = 29;
		        break;
		    case 'Christmas': // 30
		    	nid = 30;
		        break;
		    case 'Teacher\'s Gift': // 31
		    	nid = 31;
		        break;
		    case 'Corporate Meeting': // 32
		    	nid = 32;
		        break;
		    case 'Divorce Encouragement': // 33
		    	nid = 33;
		        break;
		    case 'Just Because...': // 34
		    	nid = 34;
		        break;
		    case 'XXXXX': // 35
		    	nid = 35;
		        break;
		    case 'Funeral': // 36
		    	nid = 36;
		        break;
		    case 'Mother\'s Day': // 37
		    	nid = 37;
		        break;
		    case 'XXXXX': // 37
		    	nid = 37;
		        break;
		    case 'Hanukkah': // 38
		    	nid = 38;
		        break;
		    case 'Retirement': // 39
		    	nid = 39;
		        break;
		    case 'Love You Because...': // 40
		    	nid = 40;
		        break;
		    case 'Sweet 16': // 41
		    	nid = 41;
		        break;
		    case 'Father\'s Day': // 42
		    	nid = 42;
		        break;
		    case 'Valentine\'s Day': // 43
		    	nid = 43;
		        break;
		    case 'XXXXX': // 43
		    	nid = 43;
		        break;
		    case 'Holiday':
		    case 'Other Holiday': // 44
		    	nid = 44;
		        break;
		    case 'Boss\' Gift': // 45
		    	nid = 45;
		        break;
		    case 'Bridal Shower': // 46
		    	nid = 46;
		        break;
		    case 'Graduation': // 47
		    	nid = 47;
		        break;
		    case 'Wedding': // 48
		    	nid = 48;
		        break;
		    case 'Engagement': // 49
		    	nid = 49;
		        break;
		    case 'New Baby / Parents': // 50
		    	nid = 50;
		        break;
		    case 'Bar/Bat Mizvah ': // 51
		    	nid = 51;
		        break;
		    case 'Get Well': // 52
		    	nid = 52;
		        break;
		    case 'Military Gift': // 53
		    	nid = 53;
		        break;
		    case 'New Year Encouragement': // 80
		    	nid = 80;
		        break;
		    case 'Baby Shower': // 81
		    	nid = 81;
		        break;
		    case 'Quinceañera': // 82
		    	nid = 82;
		        break;
		    case 'Goodbye / Farewell': // 83
		    	nid = 83;
		        break;
		}
        selectedTheme = jQuery('input:radio[name=input_'+nid+']:checked').val();
        if(selectedTheme == 'gf_other_choice') {
        	selectedTheme = jQuery('input[name=input_'+nid+'_other]').val();
        }
        selectedTheme = htmlEncode(selectedTheme);
        /*
		if (jQuery('input:radio[name=input_27]').prop("checked") == 'true') { selectedTheme = '27'+jQuery('input:radio[name=input_27]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_27').val(); }
		if (jQuery('input:radio[name=input_28]').prop("checked") == 'true') { selectedTheme = '28'+jQuery('input:radio[name=input_28]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_28').val(); }
		if (jQuery('input:radio[name=input_29]').prop("checked") == 'true') { selectedTheme = '29'+jQuery('input:radio[name=input_29]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_29').val(); }
		if (jQuery('input:radio[name=input_30]').prop("checked") == 'true') { selectedTheme = '30'+jQuery('input:radio[name=input_30]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_30').val(); }
		if (jQuery('input:radio[name=input_31]').prop("checked") == 'true') { selectedTheme = '31'+jQuery('input:radio[name=input_31]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_31').val(); }
		if (jQuery('input:radio[name=input_32]').prop("checked") == 'true') { selectedTheme = '32'+jQuery('input:radio[name=input_32]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_32').val(); }
		if (jQuery('input:radio[name=input_33]').prop("checked") == 'true') { selectedTheme = '33'+jQuery('input:radio[name=input_33]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_33').val(); }
		if (jQuery('input:radio[name=input_34]').prop("checked") == 'true') { selectedTheme = '34'+jQuery('input:radio[name=input_34]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_34').val(); }
		if (jQuery('input:radio[name=input_35]').prop("checked") == 'true') { selectedTheme = '35'+jQuery('input:radio[name=input_35]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_35').val(); }
		if (jQuery('input:radio[name=input_36]').prop("checked") == 'true') { selectedTheme = '36'+jQuery('input:radio[name=input_36]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_36').val(); }
		if (jQuery('input:radio[name=input_37]').prop("checked") == 'true') { selectedTheme = '37'+jQuery('input:radio[name=input_37]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_37').val(); }
		if (jQuery('input:radio[name=input_38]').prop("checked") == 'true') { selectedTheme = '38'+jQuery('input:radio[name=input_38]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_38').val(); }
		if (jQuery('input:radio[name=input_39]').prop("checked") == 'true') { selectedTheme = '39'+jQuery('input:radio[name=input_39]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_39').val(); }
		if (jQuery('input:radio[name=input_40]').prop("checked") == 'true') { selectedTheme = '40'+jQuery('input:radio[name=input_40]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_40').val(); }
		if (jQuery('input:radio[name=input_41]').prop("checked") == 'true') { selectedTheme = '41'+jQuery('input:radio[name=input_41]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_41').val(); }
		if (jQuery('input:radio[name=input_42]').prop("checked") == 'true') { selectedTheme = '42'+jQuery('input:radio[name=input_42]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_42').val(); }
		if (jQuery('input:radio[name=input_43]').prop("checked") == 'true') { selectedTheme = '43'+jQuery('input:radio[name=input_43]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_43').val(); }
		if (jQuery('input:radio[name=input_44]').prop("checked") == 'true') { selectedTheme = '44'+jQuery('input:radio[name=input_44]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_44').val(); }
		if (jQuery('input:radio[name=input_45]').prop("checked") == 'true') { selectedTheme = '45'+jQuery('input:radio[name=input_45]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_45').val(); }
		if (jQuery('input:radio[name=input_46]').prop("checked") == 'true') { selectedTheme = '46'+jQuery('input:radio[name=input_46]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_46').val(); }
		if (jQuery('input:radio[name=input_47]').prop("checked") == 'true') { selectedTheme = '47'+jQuery('input:radio[name=input_47]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_47').val(); }
		if (jQuery('input:radio[name=input_48]').prop("checked") == 'true') { selectedTheme = '48'+jQuery('input:radio[name=input_48]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_48').val(); }
		if (jQuery('input:radio[name=input_49]').prop("checked") == 'true') { selectedTheme = '49'+jQuery('input:radio[name=input_49]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_49').val(); }
		if (jQuery('input:radio[name=input_50]').prop("checked") == 'true') { selectedTheme = '50'+jQuery('input:radio[name=input_50]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_50').val(); }
		if (jQuery('input:radio[name=input_51]').prop("checked") == 'true') { selectedTheme = '51'+jQuery('input:radio[name=input_51]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_51').val(); }
		if (jQuery('input:radio[name=input_52]').prop("checked") == 'true') { selectedTheme = '52'+jQuery('input:radio[name=input_52]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_52').val(); }
		if (jQuery('input:radio[name=input_53]').prop("checked") == 'true') { selectedTheme = '53'+jQuery('input:radio[name=input_53]:checked').val(); } //jQuery('#input_<?php echo $iForm; ?>_53').val(); }
		*/
		selectedRealTime 	 = jQuery('input:radio[name=input_78]:checked').val(); //jQuery('#input_<?php echo $iForm; ?>_78').val();
		selectedDelivery 	 = jQuery('#input_<?php echo $iForm; ?>_2').val();
		selectedFinalize 	 = jQuery('#input_<?php echo $iForm; ?>_54').val();
		selectedInstructions = jQuery('#input_<?php echo $iForm; ?>_77').val(); 
		selectedMultiple 	 = jQuery('input:radio[name=input_7]:checked').val(); //jQuery('#input_<?php echo $iForm; ?>_7').val();
		selectedSentiments 	 = jQuery('input:radio[name=input_25]:checked').val(); //jQuery('#input_<?php echo $iForm; ?>_25').val();
		selectedProfanity 	 = jQuery('input:radio[name=input_9]:checked').val(); //jQuery('#input_<?php echo $iForm; ?>_9').val();
		selectedInvite		 = jQuery('input:radio[name=input_69]:checked').val(); //jQuery('#input_<?php echo $iForm; ?>_69').val();
		selectedSummary 	 = jQuery('#input_<?php echo $iForm; ?>_75').val();
	}

	function htmlEncode(value){
		value = typeof value !== 'undefined' ? value : '';
		value = specialChars(value);
		return jQuery('<div/>').text(value).html();
	}

	function htmlDecode(value){
		return jQuery('<div/>').html(value).text();
	}
	function specialChars(value) {
		//value = rfc3986EncodeURIComponent(value);
		value = value.replace(/&/g, "&amp;");
		value = value.replace(/>/g, "&gt;");
		value = value.replace(/</g, "&lt;");
		value = value.replace(/…/g, "&hellip;");
		return value;
	}
	function rfc3986EncodeURIComponent (str) {  
	    return encodeURIComponent(str).replace(/[!'()*]/g, escape);  
	}
	function gotoStep2(){
		setVars();
		extraURL2 = '&includestep3=1&occasion=' + selectedOccasion + '&whoto=' + selectedWhoTo + '&whofrom=' + selectedWhoFrom + '&theme=' + selectedTheme + '&realtime=' + selectedRealTime + '&delivery=' + selectedDelivery + '&finalize=' + selectedFinalize + '&instuc=' + selectedInstructions + '&multi=' + selectedMultiple + '&sentim=' + selectedSentiments + '&profanity=' + selectedProfanity + '&invite=' + selectedInvite + '&summary=' + selectedSummary;
			
		document.location = '<?php echo do_shortcode('[site_url]'); ?>/choose-card/?preservesession=1'+extraURL2;
	}

	function gotoStep4(saveEditOptions) {
		saveEditOptions = typeof saveEditOptions !== 'undefined' ? saveEditOptions : false;
		if (saveEditOptions) { jQuery('#input_<?php echo $iForm; ?>_84').val(1); }

		setVars();
		//window.alert(jQuery('#auto_submit').val());
		if ( <?php if (empty($loggedIn) || !$loggedIn) : echo 'true'; else : echo 'false'; endif; ?> && jQuery('#auto_submit').val() == 0 ) {
			// Set Lightbox visible
			jQuery("#redirect-step2").val('<?php echo do_shortcode('[site_url]'); ?>/invitations/?boxtype=' + selectedBox + '&boxprice=' + totalprice + '&boxcolor=' + selectedBoxColor + '&cardcolor=' + selectedColor + '&cardquantity=' + selectedCardQty + '&cardquantityprice=' + totalprice + '&totalprice=' + totalprice + '&cardcolorimg=' + jQuery("#hiddencardcolorimg").val() + '&boxtypeimg=' + selectedBoxImage + '&occasion=' + selectedOccasion
				+ '&whoto=' + selectedWhoTo + '&whofrom=' + selectedWhoFrom + '&theme=' + selectedTheme + '&realtime=' + selectedRealTime + '&delivery=' + selectedDelivery + '&finalize=' + selectedFinalize + '&instuc=' + selectedInstructions + '&multi=' + selectedMultiple + '&sentim=' + selectedSentiments + '&profanity=' + selectedProfanity + '&invite=' + selectedInvite + '&summary=' + selectedSummary);
			extraURL = jQuery("#createaccountlink-step2").val()+'?creatingbox=1&boxtype=' + selectedBox + '&boxprice=' + totalprice + '&boxcolor=' + selectedBoxColor + '&cardcolor=' + selectedColor + '&cardquantity=' + selectedCardQty + '&cardquantityprice=' + totalprice + '&totalprice=' + totalprice + '&cardcolorimg=' + jQuery("#hiddencardcolorimg").val() + '&boxtypeimg=' + selectedBoxImage + '&occasion=' + selectedOccasion
				+ '&whoto=' + selectedWhoTo + '&whofrom=' + selectedWhoFrom + '&theme=' + selectedTheme + '&realtime=' + selectedRealTime + '&delivery=' + selectedDelivery + '&finalize=' + selectedFinalize + '&instuc=' + selectedInstructions + '&multi=' + selectedMultiple + '&sentim=' + selectedSentiments + '&profanity=' + selectedProfanity + '&invite=' + selectedInvite + '&summary=' + selectedSummary;
			//window.alert(extraURL);
			//console.log(jQuery("#redirect-step2").val());
			jQuery("#createaccountlink").attr("href",extraURL);
			jQuery("#createaccountlink-step2").val(extraURL);
			jQuery('#process_check').val(1);
			jQuery( ".lightbox-step2.login-step2" ).fadeToggle("fast");
			jQuery('html, body').animate({scrollTop : 0},800);
		} else {
			jQuery( '#gform_submit_button_34' ).trigger( 'click' );
		}
	}
	var direct = true;
	jQuery(document).ready(function($) {

		if (window.history && window.history.pushState) {

			$(window).on('popstate', function() {
				var hashLocation = location.hash;
				var hashSplit = hashLocation.split("#!/");
				var hashName = hashSplit[1];
				//window.alert (direct);
				if (hashName !== '') {
					var hash = window.location.hash;
					if (hash === '' && direct) {
						// Call Back...
						gotoStep2();
					}
				}
			});

			window.history.pushState('forward', null, './#forward');
		}

	});
</script>

<?php endwhile; endif; ?>


<?php
	if(isset($_SESSION["new_flatterbox_id"]))
	{
	$populatePID = $_SESSION["new_flatterbox_id"];
?>

<script>
	setVars(); 
	jQuery("#input_<?php echo $iForm; ?>_1").val("<?php echo encodeData(get_field('who_is_this_for',$populatePID)); ?>");
	jQuery("#input_<?php echo $iForm; ?>_65").val("<?php echo encodeData(get_field('who_is_this_from',$populatePID)); ?>");
	<?php if (!isset($_GET['occasion'])) : ?>
	jQuery("#input_<?php echo $iForm; ?>_26").val("<?php echo encodeData(get_field('occasion',$populatePID)); ?>");
	<?php else : ?>
	jQuery("#input_<?php echo $iForm; ?>_26").val("<?php echo encodeData($_GET['occasion']); ?>");
	<?php endif; ?>
	//jQuery("#input_<?php echo $iForm; ?>_73").val("<?php the_field('title_card_headline',$populatePID); ?>");
	<?php
		$date = new DateTime(get_field('date_of_delivery',$populatePID));
		$delivery = $date->Format("m/d/Y");
	?>
	jQuery("#input_<?php echo $iForm; ?>_2").val('<?php echo $delivery; ?>');
	<?php
		$date = new DateTime(get_field('date_sentiments_complete',$populatePID));
		$sentimentdate = $date->Format("m/d/Y");
	?>	
	jQuery("#input_<?php echo $iForm; ?>_54").val('<?php echo $sentimentdate; ?>');
	jQuery("#input_<?php echo $iForm; ?>_7 input[value=<?php if(get_field('allow_to_see_eachother',$populatePID)){ the_field('allow_to_see_eachother',$populatePID); } else { echo "0"; } ?>]").prop("checked", true);
	//jQuery("#input_<?php echo $iForm; ?>_8 input[value=<?php if(get_field('allow_to_share',$populatePID)){ the_field('allow_to_share',$populatePID); } else { echo "0"; } ?>]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_69 input[value=<?php if(get_field('allow_to_share',$populatePID)){ the_field('allow_to_share',$populatePID); } else { echo "0"; } ?>]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_9 input[value=<?php if(get_field('allow_profanity',$populatePID)){ the_field('allow_profanity',$populatePID); } else { echo "0"; } ?>]").prop("checked", true);
	//jQuery("#input_<?php echo $iForm; ?>_10 input[value=<?php the_field('notification_frequency',$populatePID); ?>]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_75 input[value=<?php the_field('notification_frequency',$populatePID); ?>]").prop("checked", true);

	jQuery("#input_<?php echo $iForm; ?>_27 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_28 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_29 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_30 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_31 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_32 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_33 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_34 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_35 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_36 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_37 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_38 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_39 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_40 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_41 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_42 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_43 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_44 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_45 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_46 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_47 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_48 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_49 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_50 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_51 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_52 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_53 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_80 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_81 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_82 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_83 input[value=\"<?php echo encodeData(get_field('box_theme',$populatePID)); ?>\"]").prop("checked", true);
	
	jQuery("#input_<?php echo $iForm; ?>_78 input[value=<?php if(get_field('live_event',$populatePID)){ the_field('live_event',$populatePID); } else { echo "0"; } ?>]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_77").val('<?php echo preg_replace("/\r?\n/m", "<br />", nl2br(encodeData(get_field('special_instructions_to_flatterers',$populatePID)))); ?>');
	
	if (typeof jQuery('input:radio[name=input_'+nid+']:checked').val() === "undefined" ) {
		jQuery("#input_<?php echo $iForm; ?>_"+nid+" input[value='gf_other_choice']").prop("checked", true);
		jQuery('input[name=input_'+nid+'_other]').val('<?php echo encodeData(get_field('box_theme',$populatePID)); ?>');
	}

	jQuery.noConflict();

	  jQuery(document).ready(function($) {

			$( "#gforms_calendar_icon_input_34_2" ).datepicker({  buttonImage: 'images/calendar.gif', minDate: '+<?php echo intval(get_field('wait_time_for_boxes', 'option')); ?>d' });



	  });
	
</script>

<?php
	} elseif( isset($_GET['summary']) || isset($_GET['whoto']) ) {
		?>
<script>
	setVars();
	jQuery("#input_<?php echo $iForm; ?>_1").val("<?php echo encodeData($_GET['whoto']); ?>");
	jQuery("#input_<?php echo $iForm; ?>_65").val("<?php echo encodeData($_GET['whofrom']); ?>");
	jQuery("#input_<?php echo $iForm; ?>_26").val("<?php echo encodeData($_GET['occasion']); ?>");
	//jQuery("#input_<?php echo $iForm; ?>_73").val("<?php echo $_GET['title_card_headline']; ?>");
	<?php
	if( strlen($_GET['delivery']) > 0 ) :
		$date = new DateTime($_GET['delivery']);
		$delivery = $date->Format("m/d/Y");
	else :
		$delivery = '';
	endif;
	?>
	jQuery("#input_<?php echo $iForm; ?>_2").val('<?php echo $delivery; ?>');
	<?php
	if( strlen($_GET['finalize']) > 0 ) :
		$date = new DateTime($_GET['finalize']);
		$sentimentdate = $date->Format("m/d/Y");
	else :
		$sentimentdate = '';
	endif;
	?>	
	jQuery("#input_<?php echo $iForm; ?>_54").val('<?php echo $sentimentdate; ?>');
	jQuery("#input_<?php echo $iForm; ?>_7 input[value=<?php if($_GET['multi']){ echo $_GET['multi']; } else { echo "0"; } ?>]").prop("checked", true);
	//jQuery("#input_<?php echo $iForm; ?>_8 input[value=<?php if($_GET['invite']){ echo $_GET['invite']; } else { echo "0"; } ?>]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_69 input[value=<?php if($_GET['invite']){ echo $_GET['invite']; } else { echo "0"; } ?>]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_9 input[value=<?php if($_GET['profanity']){ echo $_GET['profanity']; } else { echo "0"; } ?>]").prop("checked", true);
	//jQuery("#input_<?php echo $iForm; ?>_10 input[value=<?php echo $_GET['summary']; ?>]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_75 input[value=<?php echo $_GET['summary']; ?>]").prop("checked", true);

	jQuery("#input_<?php echo $iForm; ?>_27 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_28 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_29 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_30 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_31 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_32 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_33 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_34 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_35 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_36 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_37 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_38 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_39 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_40 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_41 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_42 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_43 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_44 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_45 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_46 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_47 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_48 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_49 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_50 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_51 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_52 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_53 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_80 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_81 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_82 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_83 input[value=\"<?php echo encodeData2($_GET['theme']); ?>\"]").prop("checked", true);
	//console.log(nid);
	//console.log(selectedOccasion);
	if (typeof jQuery('input:radio[name=input_'+nid+']:checked').val() === "undefined" ) {
		jQuery("#input_<?php echo $iForm; ?>_"+nid+" input[value='gf_other_choice']").prop("checked", true);
		jQuery('input[name=input_'+nid+'_other]').val('<?php echo encodeData($_GET['theme']); ?>');
	}

	jQuery("#input_<?php echo $iForm; ?>_78 input[value=<?php if($_GET['realtime']){ echo $_GET['realtime']; } else { echo "0"; } ?>]").prop("checked", true);
	jQuery("#input_<?php echo $iForm; ?>_77").val('<?php echo encodeData($_GET['instuc']); ?>');

	jQuery.noConflict();

	jQuery(document).ready(function($) {
		$( "#gforms_calendar_icon_input_34_2" ).datepicker({  buttonImage: 'images/calendar.gif', minDate: '+<?php echo intval(get_field('wait_time_for_boxes', 'option')); ?>d' });
	});
	
</script>
		<?php
	}

?>

<script type="text/javascript">
	jQuery(document).ready(function($) {
		// Who For
		$('#input_<?php echo $iForm; ?>_1').keyup(function() { update_preview($); });
		$('#input_<?php echo $iForm; ?>_1').blur(function() { update_preview($); });
		// Who From
		$('#input_<?php echo $iForm; ?>_65').keyup(function() {	update_preview($); });
		$('#input_<?php echo $iForm; ?>_65').blur(function() {	update_preview($); });
		// Occasion
		$('#input_<?php echo $iForm; ?>_26').change(function(){ update_preview($); });
		<?php if (!isset($_GET['occasion'])) : ?>
		$('#the_occasion').html('<?php echo encodeData($_SESSION["occasion"]); ?>');
		<?php else : ?>
		$('#the_occasion').html('<?php echo encodeData($_GET["occasion"]); ?>');
		<?php endif; ?>
		// Themes
		<?php 
		for ($i=27; $i < 54; $i++) {
			echo '
			$(\'input:radio[name=input_'.$i.']\').change(function(){
				if ($(\'input:radio[name=input_'.$i.']:checked\').val() == \'gf_other_choice\') {
					$(\'#the_theme\').html($(\'input:radio[name=input_'.$i.'_other]\').val());
				} else {
					$(\'#the_theme\').html($(\'input:radio[name=input_'.$i.']:checked\').val());
				} 
				theme_update();
			});
			$(\'#input_'.$iForm.'_'.$i.'_other\').keyup(function() { $(\'#the_theme\').html($(\'#input_'.$iForm.'_'.$i.'_other\').val()); });
			$(\'#input_'.$iForm.'_'.$i.'_other\').focus(function() { $(\'#the_theme\').html($(\'#input_'.$iForm.'_'.$i.'_other\').val()); });
			$(\'#input_'.$iForm.'_'.$i.'_other\').blur(function() { $(\'#the_theme\').html($(\'#input_'.$iForm.'_'.$i.'_other\').val()); });
			';
		}
		?>
		// May need to convert 80-83 if we get more to the same as the loop above.
		$('input:radio[name=input_80]').change(function(){
			if ($('input:radio[name=input_80]:checked').val() == 'gf_other_choice') {
				$('#the_theme').html($('input:radio[name=input_80_other]').val());
			} else {
				$('#the_theme').html($('input:radio[name=input_80]:checked').val());
			} 
			theme_update();
		});
		$('#input_<?php echo $iForm; ?>_80_other').keyup(function() { $('#the_theme').html($('#input_<?php echo $iForm; ?>_80_other').val()); theme_update(); });
		$('#input_<?php echo $iForm; ?>_80_other').focus(function() { $('#the_theme').html($('#input_<?php echo $iForm; ?>_80_other').val()); theme_update(); });
		$('#input_<?php echo $iForm; ?>_80_other').blur(function() { $('#the_theme').html($('#input_<?php echo $iForm; ?>_80_other').val()); theme_update(); });
		
		$('input:radio[name=input_81]').change(function(){
			if ($('input:radio[name=input_81]:checked').val() == 'gf_other_choice') {
				$('#the_theme').html($('input:radio[name=input_81_other]').val());
			} else {
				$('#the_theme').html($('input:radio[name=input_81]:checked').val());
			} 
			theme_update();
		});
		$('#input_<?php echo $iForm; ?>_81_other').keyup(function() { $('#the_theme').html($('#input_<?php echo $iForm; ?>_81_other').val()); theme_update(); });
		$('#input_<?php echo $iForm; ?>_81_other').focus(function() { $('#the_theme').html($('#input_<?php echo $iForm; ?>_81_other').val()); theme_update(); });
		$('#input_<?php echo $iForm; ?>_81_other').blur(function() { $('#the_theme').html($('#input_<?php echo $iForm; ?>_81_other').val()); theme_update(); });
		
		$('input:radio[name=input_82]').change(function(){
			if ($('input:radio[name=input_82]:checked').val() == 'gf_other_choice') {
				$('#the_theme').html($('input:radio[name=input_82_other]').val());
			} else {
				$('#the_theme').html($('input:radio[name=input_82]:checked').val());
			} 
			theme_update();
		});
		$('#input_<?php echo $iForm; ?>_82_other').keyup(function() { $('#the_theme').html($('#input_<?php echo $iForm; ?>_82_other').val()); theme_update(); });
		$('#input_<?php echo $iForm; ?>_82_other').focus(function() { $('#the_theme').html($('#input_<?php echo $iForm; ?>_82_other').val()); theme_update(); });
		$('#input_<?php echo $iForm; ?>_82_other').blur(function() { $('#the_theme').html($('#input_<?php echo $iForm; ?>_82_other').val()); theme_update(); });
		
		$('input:radio[name=input_83]').change(function(){
			if ($('input:radio[name=input_83]:checked').val() == 'gf_other_choice') {
				$('#the_theme').html($('input:radio[name=input_83_other]').val());
			} else {
				$('#the_theme').html($('input:radio[name=input_83]:checked').val());
			} 
			theme_update();
		});
		$('#input_<?php echo $iForm; ?>_83_other').keyup(function() { $('#the_theme').html($('#input_<?php echo $iForm; ?>_83_other').val()); theme_update(); });
		$('#input_<?php echo $iForm; ?>_83_other').focus(function() { $('#the_theme').html($('#input_<?php echo $iForm; ?>_83_other').val()); theme_update(); });
		$('#input_<?php echo $iForm; ?>_83_other').blur(function() { $('#the_theme').html($('#input_<?php echo $iForm; ?>_83_other').val()); theme_update(); });

		// Date Needed
		$('#input_<?php echo $iForm; ?>_2').change(function() { update_preview($); });
		$('#input_<?php echo $iForm; ?>_2').blur(function() { update_preview($); });
		$('#input_<?php echo $iForm; ?>_54').change(function() { update_preview($); });
		$('#input_<?php echo $iForm; ?>_54').blur(function() { update_preview($); });
		// Special Instructions
		$('#input_<?php echo $iForm; ?>_77').keyup(function() { update_preview($); });
		$('#input_<?php echo $iForm; ?>_77').blur(function() { update_preview($); });

		<?php if(isset($_GET['whoto'])) : ?>
		// Initial Set
		update_preview($);
		<?php endif; ?>

		// Sticky
		/*
		var $window = jQuery(window),
		$stickyEl = jQuery('#preview-email'),
		$stickyCont = jQuery('.page-flatterbox-options .box .direction'),
		$stickyStop = jQuery('#preview-email.sticky.stop'),
		//$stickyPad = jQuery('#status-bar'),
		elTop = $stickyEl.offset().top;
		elBottom = $stickyEl.offset().top + $stickyEl.height();
		elContBottom = $stickyCont.offset().top + $stickyCont.height();

		$window.scroll(function() {
			var scrollBottom = $(window).scrollTop() + $(window).height();
			console.log($window.scrollTop() + ' -- ' + scrollBottom + ' -- ' + elTop + ' -- ' + elBottom + ' -- ' + elContBottom + ' -- ' + ($window.scrollTop()+333));
			$stickyEl.toggleClass('sticky', $window.scrollTop() > elTop);
			$stickyEl.toggleClass('stop', scrollBottom > elBottom);
			$('#preview-email.sticky.stop').css({'top': -1*(400)}, scrollBottom >= );
			//$('#preview-email.sticky.stop').css({'top': 0}, $window.scrollTop() < scrollBottom);
			//$stickyPad.toggleClass('stickypad', $window.scrollTop() > elTop);
		});
*/
		// jQuery('#preview-email').stick_in_parent({inner_scrolling: true, recalc_every: 1});
	});

	function theme_update() {
		if(jQuery('#the_theme').html().indexOf("(name)") >= 0) {
			jQuery('#the_theme').html(jQuery('#the_theme').html().replace("(name)", jQuery('#theme_name').html()));
			jQuery('#theme_name').hide();
		} else {
			jQuery('#theme_name').show();
		}
	}

	function update_preview($){
		// Who For
		$('#who_for').html($('#input_<?php echo $iForm; ?>_1').val());
		$('#theme_name').html($('#input_<?php echo $iForm; ?>_1').val());

		// Who From
		$('#who_from').html($('#input_<?php echo $iForm; ?>_65').val());

		// Occasion
		$('#the_occasion').html($('#input_<?php echo $iForm; ?>_26').val());

		// Theme
		theme_update();

		// Date Needed
		$('#date_needed').html($('#input_<?php echo $iForm; ?>_54').val());
		// Special Instructions
		$('#special_inst').html($('#input_<?php echo $iForm; ?>_77').val().replace(/(?:\r\n|\r|\n)/g, '<br>')); 

	}
</script>
<?php if ( false ) : // Removed at the request of the client 7/20/2015?>
<script src="<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/js/jquery.pin.js" type="text/javascript"></script>
<script>
	
	jQuery(".pinned").pin({
      containerSelector: ".options"
	});
</script>
<?php endif; ?>
<?php
	if (false) :
	$ses_arr = $_SESSION;
	foreach($ses_arr as $key => $val){
		
		?>
		<script>
		if (jQuery('[name="<?php echo $key;?>"]').length) {
			console.log('<?php echo $key;?>');
			jQuery('[name="<?php echo $key;?>"]').val('<?php echo addslashes($val);?>');
		}
		
		</script>
		<?php
	}
	endif;

function cleanData($str = '') {
	return stripslashes(htmlspecialchars_decode($str));	
}
function encodeData($str = '') {
	$str = cleanData($str);
	return addslashes($str);
}
function encodeData2($str = '') {
	return addslashes(encodeData($str)); // adding double
}
?>
<?php get_footer(); ?>
