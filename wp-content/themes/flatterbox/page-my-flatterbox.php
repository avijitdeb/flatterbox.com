<?php 

/* Template Name: My Flatterbox */
// Redirect if not logged in
if ( !is_user_logged_in() ) : wp_redirect(home_url()); endif;

global $wpdb;
	
// Check for initial Sentiment creation from step 6 and insert with the flatterrer id 0.
if(isset($_POST["sentimenttext"]))
{
	
	$wpdb->insert( 
		'sentiments', 
		array( 
			'FID' => 0,
			'PID' => $_SESSION["new_flatterbox_id"], 
			'sentiment_text' => $_POST["sentimenttext"],
			'sentiment_name' => $_POST["namefamily"],
			'private' => 0,
			'approved' => 0
		), 
		array( 
			'%d',
			'%d', 
			'%s',
			'%s',
			'%d',
			'%d'
		) 
	);	

}

if(isset($_GET["modifyPID"]))
{
	__update_post_meta( $_GET["modifyPID"], 'box_type', $value = $_GET["boxtype"]);
	__update_post_meta( $_GET["modifyPID"], 'total_price', $value = $_GET["boxprice"]);
	__update_post_meta( $_GET["modifyPID"], 'box_color', $value = $_GET["boxcolor"]);
	__update_post_meta( $_GET["modifyPID"], 'box_style', $value = $_GET["boxtype"]);
	__update_post_meta( $_GET["modifyPID"], 'card_style', $value = $_GET["cardcolor"]);
	__update_post_meta( $_GET["modifyPID"], 'box_image_url', $value = $_GET["boxtypeimg"]);
	__update_post_meta( $_GET["modifyPID"], 'card_quantity', $value = $_GET["cardquantity"]);
	__update_post_meta( $_GET["modifyPID"], 'occasion', $value = $_GET["occasion"]);
	__update_post_meta( $_GET["modifyPID"], 'add10', $value = $_GET["add10"]);
}


	include 'flatterer-invitation.php';

if (isset($_GET['editOptions']) && $_GET['editOptions'] == 1 && isset($_GET["modifyPID"])) :
	wp_redirect(site_url()."/flatterbox-options/?PID=".$_GET["modifyPID"]); exit;
endif;

get_header("steps");
?>
<main id="main" role="main">
	<div class="container">
		<div class="heading">
			<h1><?php the_field('page_title'); ?></h1>
		</div>
		<section class="event-holder">
		
		<?php

			//Get all flatterboxes (custom posts) of the currently logged in user
			
			global $current_user;
			get_currentuserinfo();                      
			//$current_userID = $current_user->ID, // I could also use $user_ID, right?
			$current_userID = get_current_user_id();

			$args = array(
				'author'        =>  $current_userID,
				'orderby'       =>  'post_date',
				'order'         =>  'DESC',
				'post_type'		=>	'flatterboxes',
				'posts_per_page' => -1
				);

			// get his posts 'ASC'
			$current_user_posts = get_posts( $args );
		
			$i = 1;			
			
			foreach ( $current_user_posts as $post ) : setup_postdata( $post );

			// Check for URL variable -- Can be removed once legacy items are covered
			if ( strlen(get_field('unique_url')) == 0 ) :
				//$current_user = wp_get_current_user();
				//$uniquestart = $current_user->user_email.get_the_ID();
				//__update_post_meta( get_the_ID(), 'unique_url', $value = md5(uniqid($uniquestart, true)));
				__update_post_meta(get_the_ID(), 'unique_url', $value = getURLToken(10));
			endif;
			
		?>
		
			<div class="event anchor-<?php the_id(); ?>" id="flatterbox-<?php echo $i; ?>">
				<div class="detail">
					<div class="img-holder">
						<?php
						foreach (get_the_terms(get_the_ID(), 'flatterbox_type') as $cat) : 
							$catimage =  z_taxonomy_image_url($cat->term_id);
							$catname = $cat->name;
						endforeach;
						?>
						<img src="<?php echo getSecureURLString(get_field('box_image_url')); ?>" alt="img description">
						<?php if ( strlen(get_field('order_count')) == 0 ) : ?>
						<a class="btn small actions edit" id="edit_options_button_<?php echo get_the_ID(); ?>" href="#" onclick="toggleEdit(<?php echo get_the_ID(); ?>);">Edit Flatterbox<span id="up"> &#x25BC;</span><span id="down" style="display:none;"> &#x25B2;</span></a>
						<div id="edit_options_<?php echo get_the_ID(); ?>" class="edit_options" style="display:none;">
							<a class="btn small actions" href="<?php echo do_shortcode('[site_url]'); ?>/review-flatterbox/?PID=<?php the_id(); ?>&preservesession=1">Flatterbox Design</a>
							<a class="btn small actions" href="<?php echo do_shortcode('[site_url]'); ?>/flatterbox-options/?PID=<?php the_id(); ?>">Flatterbox Options</a>
						</div>
						<?php endif; ?>
					</div>
					<div class="holder">
						<h2><?php the_field('who_is_this_for'); ?>'s<br/><?php echo $catname; ?></h2>
						<span class="more delete"><a href="javascript:;" onclick="flatterboxAction('delete',<?php the_id(); ?>);">Delete</a></span>
						<div class="time-holder">
							<?php

							$date = DateTime::createFromFormat('Ymd', get_field('date_of_delivery'));
							if ($date !== FALSE) :
								$datedelivery = $date->format('m/d/Y');
							else : 
								$date = DateTime::createFromFormat('d/m/Y', get_field('date_of_delivery'));
								if( $date ) :
									$datedelivery = $date->format('m/d/Y');
								else :
									$datedelivery = '  TBD  ';
								endif;
							endif;

							$dateDelivery = $date;

							$date = DateTime::createFromFormat('Ymd', get_field('date_sentiments_complete'));
							if ($date !== FALSE) :
								$datesentiment = $date->format('m/d/Y');
							else : 
								$date = DateTime::createFromFormat('d/m/Y', get_field('date_sentiments_complete'));
								if( $date ) :
									$datesentiment = $date->format('m/d/Y');
								else :
									$datesentiment = '  TBD  ';
								endif;
							endif;

							$date1 = DateTime::createFromFormat('Ymd', get_field('date_sentiments_complete'));
							$date = date('m/d/Y');
							$date2 = DateTime::createFromFormat('m/d/Y', $date);

							$remainingDays = date_diff($date2,$dateDelivery); // Days until delivery

							if ($date1 !== FALSE && $date2 !== FALSE) : 
								$diff = date_diff($date2,$date1);
								$days = $diff->format('%a days');
							else :
								$date1 = DateTime::createFromFormat('d/m/Y', get_field('date_sentiments_complete'));
								if( $date1 !== FALSE ) :
									$diff = date_diff($date2,$date1);
									$days = $diff->format('%a days');
								else :
									$days = '  TBD  ';
								endif;
							endif;							
							
							
							//Get count for sentiment approvals and responses
							
							//$card_count = $wpdb->get_var("SELECT COUNT(SID) FROM sentiments WHERE sentiment_text <> '' AND PID = " . get_the_ID());
							$invite_count = $wpdb->get_var("SELECT COUNT(DISTINCT flatterer_email) FROM flatterers WHERE invalid = 0 AND PID = " . get_the_ID());
							$received_count = $wpdb->get_var("SELECT COUNT(SID) FROM sentiments WHERE sentiment_text <> '' AND PID = " . get_the_ID()); //FID != 0 AND 
							$people_reponded = $wpdb->get_var("SELECT COUNT(DISTINCT sentiment_name) FROM sentiments WHERE sentiment_text <> '' AND   PID = " . get_the_ID());
							
							$cardamount = get_field("card_quantity");

							// Account for Title Card
							if (strlen(trim(get_field('title_card_headline'))) > 0) :
								//$card_count = $card_count + 1;
								$received_count = $received_count + 1;
							endif;
							
							$needed_count = $cardamount - $received_count;							
							
							// Check for ordering
							$toosoon = true;
							$msg = '';
							$rem = 10; // Remaining Days
							$perc = 0.2; // Percentage
							$percAmt = floor($cardamount*$perc);
							if ( false && intval($remainingDays->format('%a')) > $rem ) : // Less than X days remaining // Removed for now at the request of Leslie 5/30/2015
								$msg = 'Orders may be placed '.$rem.' days before your Delivery Date of '.$datedelivery;
							elseif( $received_count < $percAmt ) : // Over X% Sentiments here
								$msg = 'You must have a minimum of '.$percAmt.' sentiments in order to purchase a custom Flatterbox';
							else :
								$toosoon = false;
							endif;

							?>
							<time class="orderby" datetime="2014-05-12">FINALIZE ORDER BY: <span><?php echo $datesentiment; ?></span></time>
							<time class="dateneeded" datetime="2014-12-12">DATE NEEDED: <span><?php echo $datedelivery; ?></span></time>
						</div>
						<?php if ( strtolower(get_field('live_event')) == 'yes' || get_field('live_event') == 1) : ?>
							<div class="order-now">
								<a class="btn small orng" href="<?php echo do_shortcode('[site_url]'); ?>/present-sentiment/?PID=<?php the_id(); ?>">Start Event</a>
							</div>
						<?php endif; ?>
						<?php if ( strlen(get_field('order_count')) == 0 ) : ?>
							<div class="order-now">
								<a class="btn small orng<?php if($toosoon) : echo ' grey'; endif; ?>" href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/review-sentiments/?PID=<?php the_id(); ?>"<?php if($toosoon) : echo ' onclick="window.alert(\''.$msg.'\'); return false;"'; endif; ?>><?php if($toosoon) : echo 'ORDER PENDING'; else : echo 'ORDER NOW'; endif; ?></a>
							</div>
						<?php else : ?>
							<div class="order-now">
								<a class="btn small orng" href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/review-sentiments/?PID=<?php the_id(); ?>&oc=<?php get_field('order_count'); ?>">RE-ORDER NOW</a>
							</div>
							<div class="re-order-now">
								<?php
								$orderArr = explode(",", get_field('order_count'));
								$viewOrderURL = do_shortcode('[site_url]');
								for ($i=0; $i < count($orderArr); $i++) :
									echo '<a class="btn small order"  href="'.$viewOrderURL.'/my-account/view-order/'.$orderArr[$i].'">VIEW ORDER #'.$orderArr[$i].'</a>';
								endfor; 
								?>
							</div>
						<?php endif;?>
						<div class="order-alert">Please do not purchase your box until all of your sentiments are collected.</div>

						<?php if ( false ) : ?><a class="btn small actions" href="<?php echo do_shortcode('[site_url]'); ?>/add-sentiment/?PID=<?php the_id(); ?>&FID=0&newcard=1">Add Another Sentiment</a><?php endif; ?>
						<div class="extra-actions">
							<?php if ( strlen(get_field('order_count')) == 0 ) : // Hiding so they cannot add more ?> 
								<a class="btn small actions fleft" href="<?php echo do_shortcode('[site_url]'); ?>/invite-more/?PID=<?php the_id(); ?>">Invite Flatterers</a>
								<a class="btn small actions fright" href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/review-sentiments/?PID=<?php the_id(); ?>">View Sentiments</a>
								<a class="btn small actions fullwidth" href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/remind-flatterers/?PID=<?php the_id(); ?>">Send Reminder Email to Flatterers</a>
							<?php endif; ?>
						</div>
						<?php if (false && strlen(get_field('unique_url')) > 0 ) : ?>
						<div class="extra-actions">
							Share your Flatterbox with Flatterers using your unique link : <?php echo home_url().'/sentiment/?fb='.get_field('unique_url'); ?>
						</div>
						<?php endif; ?>
					</div>
				</div>		
				
				<aside class="meta">
					<?php if (false) : ?><span class="heart"><a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/review-sentiments/?PID=<?php the_id(); ?>"><em class="counter"><?php echo $card_count ?></em><strong>Sentiments Received</strong></a></span><?php endif; ?>
					<?php 
						$the_final_count = strtoupper(get_field("card_quantity"));
						$upgradeVariation = 0;
						$upgradecost = 0;
						$totalPrice = intval(get_field('total_price'));
						$upgrade_plus10 = '4.99'; $addupgrade_cost = ''; $auto10 = 0;

						if ( !$received_count ||  $received_count < 0 ) : 
							$received_count = 0; 
							$sentimentPercent = 0;
							$upgradeamount = 0;
							$upgradedb = 0; // Updating the Flatterbox DB value -- Only needed if upgraded
						else :
							$upgradeamount = $cardamount;
							$sentimentPercent = 0;
							if ($cardamount > 0 ) :	
								// If needed to add Auto 20 - dupe counts in switch case statements in order and add the required items to make all math right
								$sentimentPercent = round((intval($received_count)/intval($cardamount))*100);
								if ( $received_count > $cardamount && $received_count-$cardamount <= 10 ) : // 10 Card update
									$upgradeamount = $cardamount+10;
									$upgradecost = $upgrade_plus10;
									$auto10 = 1;
								elseif ($received_count > $cardamount) :
										$updated_fun = false;
									if ($received_count > $cardamount+10) :
										$updated_fun = true;
										switch (true) :
											case ($received_count<=60) :
												$upgradeamount = 60;
												// No variation update needed
												// Auto 10
												$addupgrade_cost = $upgrade_plus10;
												$auto10 = 1;
												break;
											case ($received_count<=100) :
												$upgradeamount = 100;
												$upgradedb = 100; // Updating the Flatterbox DB value
												$upgradeVariation = 783;
												break;
											case ($received_count<=110) :
												$upgradeamount = 110;
												$upgradedb = 100; // Updating the Flatterbox DB value
												$upgradeVariation = 783;
												// Auto 10
												$addupgrade_cost = $upgrade_plus10;
												$auto10 = 1;
												break;
											case ($received_count<=200) :
												$upgradeamount = 200;
												$upgradedb = 200; // Updating the Flatterbox DB value
												$upgradeVariation = 784;
												break;
											case ($received_count<=210) :
												$upgradeamount = 210;
												$upgradedb = 200; // Updating the Flatterbox DB value
												$upgradeVariation = 784;
												// Auto 10
												$addupgrade_cost = $upgrade_plus10;
												$auto10 = 1;
												break;
											default :
												$upgradeamount = -1; // Need to handle the top item with contact us.
												$upgradedb = -1; // Updating the Flatterbox DB value
												$upgradeVariation = -1; // Need to handle unknown cost...
												break;
										endswitch;
									endif;
									
									if (!$updated_fun) :
										switch ($cardamount) :
											case 50 :
												$upgradeamount = 100;
												$upgradeVariation = 783;
												break;
											case 100 :
												$upgradeamount = 200;
												$upgradeVariation = 784;
												break;
											case 200 :
												$upgradeamount = -1; // Need to handle the top item with contact us.
												$upgradeVariation = -1; // Need to handle unknown cost...
												break;
										endswitch;
									endif;
									
									// Update for manual processing Add10 (10 )
									if ($auto10 == 0 && get_field('add10', get_the_ID()) == 1 && $upgradeamount >= 0) : $addupgrade_cost = $upgrade_plus10; $upgradeamount = $upgradeamount+10; endif; // This does the math for the cost - only if not auto upgraded

									if( $upgradeVariation >= 0 ) :
										$_product = new WC_Product_Variation( $upgradeVariation );
										$basecost = $_product->get_price();
										$upgradecost = intval($basecost)-$totalPrice+$addupgrade_cost;
									else :
										$basecost = -1;
										$upgradecost = -1; // Handling the overages
									endif;
								endif;
							endif;
							//Setting the counts
							if ($upgradeamount >= 0) : $the_final_count = strtoupper($upgradeamount); endif;

							// Updating the DB
							if ($upgradedb > 0) : 
								__update_post_meta( get_the_ID(), 'card_quantity', $value = $upgradedb); 
								__update_post_meta( get_the_ID(), 'total_price', $value = intval($basecost)); 
							endif;
							if ( $auto10 == 1 ) : __update_post_meta( get_the_ID(), 'add10', $value = 1); endif;

						endif;
					?>
					<?php if (false) : // Old layout for counter ?><em class="counter"><?php  echo $received_count; ?>/<?php echo $cardamount; ?></em><?php endif; ?>
					<span class="response"><em class="counter"><?php echo $sentimentPercent; ?>%</em><strong>Complete</strong></span>
					<?php if ( strlen(get_field('order_count')) == 0 ) : ?>
						<?php if( $sentimentPercent > 79 && $sentimentPercent <= 100 ) : ?>
							<span class="update">You have <em class="counter" style="color:#1a3667;"><?php echo $received_count; ?></em>sentiments, you may want to <a href="<?php echo do_shortcode('[site_url]'); ?>/review-flatterbox/?PID=<?php the_id(); ?>"><strong>upgrade</strong></a> your order.</span>
						<?php elseif ($sentimentPercent > 100) : ?>
							<?php if ( $upgradeamount > 0 ) : ?>
								<span class="update">You have <em class="counter" style="color:#1a3667;"><?php echo $received_count; ?></em>sentiments. Your order has been upgraded to <strong><?php echo $upgradeamount; ?></strong> sentiments automatically. The total cost of your order has been updated. </span>
							<?php else : // handle odd case of more than the biggest box can handle ?>
								<span class="update">You have <em class="counter" style="color:#1a3667;"><?php echo $received_count; ?></em>sentiments. Your order has more sentiments than our largest Flatterbox. Please contact <a href="mailto:info@flatterbox.com"><strong>info@flatterbox.com</strong></a> and we will work with you to make sure all your sentiments are included. </span>
							<?php endif; ?>
							<span class="update"><a href="<?php echo do_shortcode('[site_url]'); ?>/faq/?faq_tag=auto_upgrade" target="_blank"><strong>More information about upgrades.</strong></a></span>
						<?php endif; ?>
					<?php endif; ?>
					<span class="more"><a href="#" class="panelmore">Details</a></span>
				</aside>
				
				<div class="panel" id="flatterbox-<?php echo $i; ?>-panel">
					<div class="one-third text-center">
						<h3 class="box-count">Schedule</h3>
						<table tableborder="0" cellspacing="5" cellpadding="5">
							<?php if (false) : // Hiding 7/13/2015 ?>
							<tr>
								<td><strong>Days Left to Complete</strong></td>
								<td><a href="<?php echo home_url().'/flatterbox-options/?PID='.get_the_ID(); ?>"><?php echo $days ?></a></td>
							</tr>
							<?php endif; ?>
							<tr>
								<td><strong>Sentiments Due By</strong></td>
								<td><a href="<?php echo home_url().'/flatterbox-options/?PID='.get_the_ID(); ?>"><?php echo $datesentiment; ?></a></td>
							</tr>
							<tr>
								<td><strong>Gift Delivery Date</strong></td>
								<td><a href="<?php echo home_url().'/flatterbox-options/?PID='.get_the_ID(); ?>"><?php echo $datedelivery; ?></a></td>
							</tr>
						</table>
						<?php if (false) : ?><a class="btn small" href="#">EDIT</a><?php endif; ?>
					</div>		
					<div class="one-third text-center">
						<h3 class="box-count">Sentiment Summary</h3>
						<table tableborder="0" cellspacing="5" cellpadding="5">
							<tr>
								<td><strong>Invitations Sent</strong></td>
								<td><a href="<?php echo home_url().'/invite-more/?PID='.get_the_ID(); ?>"><?php if ( !$invite_count ||  $invite_count < 0 ) : $invite_count = 0; endif; echo $invite_count; ?></a></td>
							</tr>
							<?php if (false) : // Hiding 7/13/2015 ?>
							<tr>
								<td><strong>People Responded</strong></td>
								<td><a href="<?php echo home_url().'/invite-more/?PID='.get_the_ID(); ?>"><?php if ( !$people_reponded ||  $people_reponded < 0 ) : $people_reponded = 0; endif; echo $people_reponded; ?></a></td>
							</tr>
							<?php endif; ?>
							<tr>
								<td><strong>Sentiments Received</strong></td>
								<td><a href="<?php echo home_url().'/my-flatterbox/review-sentiments/?PID='.get_the_ID(); ?>"><?php if ( !$received_count ||  $received_count < 0 ) : $received_count = 0; endif; echo $received_count; ?></a></td>
							</tr>
							<?php if ( false ) : ?>
							<tr>
								<td><strong>Sentiments Needed</strong></td>
								<td><a href="<?php echo home_url().'/invite-more/?PID='.get_the_ID(); ?>"><?php if ( !$needed_count ||  $needed_count < 0 ) : $needed_count = 0; endif; echo $needed_count; ?></a></td>
							</tr>
							<?php endif; ?>
						</table>
						</div>
					<div class="one-third text-center">
						<h3 class="box-count">Box Design</strong></h3>
						<table tableborder="0" cellspacing="5" cellpadding="5">
							<?php if (false) : // Hiding 7/13/2015 ?>
							<tr>
								<td><strong>Box Material</strong></td>
								<td><a href="<?php echo home_url().'/review-flatterbox/?PID='.get_the_ID().'&preservesession=1'; ?>"><?php echo strtoupper(get_field("box_style")); ?></a></td>
							</tr>
							<?php endif; ?>
							<tr>
								<td><strong>Card Color</strong></td>
								<td><a href="<?php echo home_url().'/review-flatterbox/?PID='.get_the_ID().'&preservesession=1'; ?>"><?php echo strtoupper(get_field("card_style")); ?></a></td>
							</tr>
							<tr>
								<td><strong>Card Quantity</strong></td>
								<td><a href="<?php echo home_url().'/review-flatterbox/?PID='.get_the_ID().'&preservesession=1'; ?>"><?php echo $the_final_count; ?></a></td>
							</tr>							
							<tr>
								<td><strong>Total Cost</strong></td>
								<?php 
									//$tmp = $totalPrice+$upgradecost;
									//$upgradecost = intval($upgradecost);
									if ($upgradecost >= 0) : 
										$totalPrice = $totalPrice+$upgradecost;
										if (strpos($totalPrice,'.') && strpos($totalPrice,'.') <= 0 ) : $totalPrice .= '.00'; endif;
									else :
										$totalPrice = '<a href="mailto:info@flatterbox.com"><strong>Contact Us</strong></a>';
									endif;
									//echo '!!'.$upgradecost.'!!'.$totalPrice.'!!'.$tmp.'!!'.strpos($tmp,'.').'!!';

								?>
								<td><?php if (intval($totalPrice)) : echo '$'; endif; echo $totalPrice; ?></td>
							</tr>
						</table>
						<?php if (false) : ?><a class="btn small" href="#">EDIT</a><?php endif; ?>
					</div>										
				</div>
				
			</div>

		<?php $i++;
		endforeach; 
		wp_reset_postdata();?>			
		
		</section>
	</div>
</main>

<script>
jQuery( ".panelmore" ).click(function() {
	event.preventDefault();
	jQuery(this).parent().parent().siblings(".panel").slideToggle("fast");
	jQuery(this).parent().toggleClass("minus");
	if (jQuery(this).parent().hasClass("minus")) {
		jQuery(this).html("Close");
	} else {
		jQuery(this).html("Details");
	}
});

jQuery( document ).ready(function() {
	jQuery("#flatterbox-1-panel").slideToggle("fast");
	jQuery("#flatterbox-1 > .meta > .more").toggleClass("minus");
	jQuery("#flatterbox-1 > .meta > .more > .panelmore").html("Close");
});

function toggleEdit(id) {
	event.preventDefault();
	jQuery('#edit_options_button_'+id+' #up').toggle();
	jQuery('#edit_options_button_'+id+' #down').toggle();
	jQuery('#edit_options_'+id+'').toggle();
	return false;
}

function flatterboxAction(action,pid)
{
	
	var r = confirm("Are you sure you want to delete this Flatterbox?");
	
	if (r == true) {
		jQuery.ajax({

		url:"<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/flatterbox_action.php",
		type:'post',
		data:{'action':action,'PID':pid},
		success: function(data, status) {
			if(data=="ok") {
				jQuery(".anchor-"+pid).fadeOut(1000,function() 
				{ 
				jQuery(this).remove(); 
				});
			}
		},
		  error: function(xhr, desc, err) {
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		  }		
		
		});
	} 	

}




</script>

<?php get_footer(); ?>		