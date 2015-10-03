<?php
session_start();
if (isset($_GET['resetsession']) && $_GET['resetsession'] == 'reset') : session_unset(); endif;
add_action( 'after_setup_theme', 'flatterbox_setup' );

// Global Variables
$minusDeliveryDays = 7;
$minusSubmitDays = 7;
$minusApproveDays = 7;
$allowPasscode = false; // Passcode enabled for private Checks
$allowFlattererInvite = true; // Flatterers are allowed to send invites Check

function flatterbox_setup() {
	load_theme_textdomain( 'flatterbox', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );

	global $content_width;

	if ( ! isset( $content_width ) ) $content_width = 640;

	register_nav_menus(
		array( 
			'main-menu' => __( 'Main Menu', 'flatterbox' ),
			'utility-menu' => __( 'Utility Menu', 'flatterbox' ),
			'footer-col-1' => __( 'Footer Create / Learn Menu', 'flatterbox' ),
			'footer-col-2' => __( 'Footer Support Menu', 'flatterbox' ),
			'footer-col-3' => __( 'Footer Your Account Menu', 'flatterbox' ),
			'footer-col-4' => __( 'Footer About Us Menu', 'flatterbox' ),
			'footer-col-5' => __( 'Footer Connect Menu', 'flatterbox' )
		)
	);
}

add_action( 'wp_enqueue_scripts', 'flatterbox_load_scripts' );

function flatterbox_load_scripts() {
	wp_enqueue_script( 'jquery' );
}

add_action( 'comment_form_before', 'flatterbox_enqueue_comment_reply_script' );

function flatterbox_enqueue_comment_reply_script() {
	if ( get_option( 'thread_comments' ) ) { wp_enqueue_script( 'comment-reply' ); }
}
add_filter( 'the_title', 'flatterbox_title' );

function flatterbox_title( $title ) {
	if ( $title == '' ) {
		return '&rarr;';
	} else {
		return $title;
	}
}
add_filter( 'wp_title', 'flatterbox_filter_wp_title' );

function flatterbox_filter_wp_title( $title ) {
	return $title . esc_attr( get_bloginfo( 'name' ) );
}

add_action( 'widgets_init', 'flatterbox_widgets_init' );

function flatterbox_widgets_init() {
	register_sidebar( array (
		'name' => __( 'Sidebar Widget Area', 'flatterbox' ),
		'id' => 'primary-widget-area',
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => "</li>",
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}

function flatterbox_comments_number( $count ) {
	if ( !is_admin() ) {
		global $id;
		$comments_by_type = &separate_comments( get_comments( 'status=approve&post_id=' . $id ) );
		return count( $comments_by_type['comment'] );
	} else {
		return $count;
	}
}

/* POST THUMBNAIL SIZES */
add_image_size( 'blog-header', 875, 250, false );
add_image_size( 'occasion-square', 290, 290, true );

/* PAGE SLUG BODY CLASS */
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

/* DIVIDER SHORTCODE */
function divider_func( $atts ){
	return "<div class='divider'></div>";
}
add_shortcode( 'divider', 'divider_func' );


/* Custom Post Types */

function custom_post_types() {
	$labels = array(
		'name'               => _x( 'Testimonials', 'post type general name' ),
		'singular_name'      => _x( 'Testimonial', 'post type singular name' ),
		'add_new'            => _x( 'Add New Testimonial', 'book' ),
		'add_new_item'       => __( 'Add New Testimonial' ),
		'edit_item'          => __( 'Edit Testimonial' ),
		'new_item'           => __( 'New Testimonial' ),
		'all_items'          => __( 'All Testimonials' ),
		'view_item'          => __( 'View Testimonial' ),
		'search_items'       => __( 'Search Testimonials' ),
		'not_found'          => __( 'No Testimonials found' ),
		'not_found_in_trash' => __( 'No Testimonials found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Testimonials'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'What People Are Saying',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom fields' ),
		'has_archive'   => true,
	);
	register_post_type( 'testimonials', $args );

	$labels = array(
		'name'               => _x( 'Flatterboxes', 'post type general name' ),
		'singular_name'      => _x( 'Flatterbox', 'post type singular name' ),
		'add_new'            => _x( 'Add New Flatterbox', 'book' ),
		'add_new_item'       => __( 'Add New Flatterbox' ),
		'edit_item'          => __( 'Edit Flatterbox' ),
		'new_item'           => __( 'New Flatterbox' ),
		'all_items'          => __( 'All Flatterboxes' ),
		'view_item'          => __( 'View Flatterbox' ),
		'search_items'       => __( 'Search Flatterboxes' ),
		'not_found'          => __( 'No Flatterboxes found' ),
		'not_found_in_trash' => __( 'No Flatterboxes found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Flatterboxes'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Flatterboxes',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom fields' ),
		'has_archive'   => true,
	);
	register_post_type( 'flatterboxes', $args );

	$labels = array(
		'name'               => _x( 'Box Types', 'post type general name' ),
		'singular_name'      => _x( 'Box Type', 'post type singular name' ),
		'add_new'            => _x( 'Add New Box Type', 'book' ),
		'add_new_item'       => __( 'Add New Box Type' ),
		'edit_item'          => __( 'Edit Box Type' ),
		'new_item'           => __( 'New Box Type' ),
		'all_items'          => __( 'All Box Types' ),
		'view_item'          => __( 'View Box Type' ),
		'search_items'       => __( 'Search Box Types' ),
		'not_found'          => __( 'No Box Types found' ),
		'not_found_in_trash' => __( 'No Box Types found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Box Types'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Box Types',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom fields', 'page-attributes' ),
		'has_archive'   => true,
	);
	register_post_type( 'box_types', $args );

	$labels = array(
		'name'               => _x( 'Coupon Codes', 'post type general name' ),
		'singular_name'      => _x( 'Coupon Code', 'post type singular name' ),
		'add_new'            => _x( 'Add New Coupon Code', 'book' ),
		'add_new_item'       => __( 'Add New Coupon Code' ),
		'edit_item'          => __( 'Edit Coupon Code' ),
		'new_item'           => __( 'New Coupon Code' ),
		'all_items'          => __( 'All Coupon Codes' ),
		'view_item'          => __( 'View Coupon Code' ),
		'search_items'       => __( 'Search Coupon Codes' ),
		'not_found'          => __( 'No Coupon Codes found' ),
		'not_found_in_trash' => __( 'No Coupon Codes found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Coupon Codes'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Coupon Codes',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom fields' ),
		'has_archive'   => true,
	);
	register_post_type( 'coupon_codes', $args );

	$labels = array(
		'name'               => _x( 'Sentiment Gallery', 'post type general name' ),
		'singular_name'      => _x( 'Sentiment Gallery', 'post type singular name' ),
		'add_new'            => _x( 'Add New Sentiment', 'book' ),
		'add_new_item'       => __( 'Add New Sentiment' ),
		'edit_item'          => __( 'Edit Sentiment' ),
		'new_item'           => __( 'New Sentiment' ),
		'all_items'          => __( 'All Sentiments' ),
		'view_item'          => __( 'View Sentiment' ),
		'search_items'       => __( 'Search Sentiments' ),
		'not_found'          => __( 'No Sentiments found' ),
		'not_found_in_trash' => __( 'No Sentiments found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Sentiment Gallery'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Sentiment Gallery',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom fields' ),
		'has_archive'   => true,
	);
	register_post_type( 'sentence_gallery', $args );

	$labels = array(
		'name'               => _x( 'Sentence Starters', 'post type general name' ),
		'singular_name'      => _x( 'Sentence Starter', 'post type singular name' ),
		'add_new'            => _x( 'Add New Sentence', 'book' ),
		'add_new_item'       => __( 'Add New Sentence' ),
		'edit_item'          => __( 'Edit Sentence' ),
		'new_item'           => __( 'New Sentence' ),
		'all_items'          => __( 'All Sentences' ),
		'view_item'          => __( 'View Sentence' ),
		'search_items'       => __( 'Search Sentences' ),
		'not_found'          => __( 'No Sentences found' ),
		'not_found_in_trash' => __( 'No Sentences found in the Trash' ),
		'parent_item_colon'  => '',
		'menu_name'          => 'Sentence Starters'
	);
	$args = array(
		'labels'        => $labels,
		'description'   => 'Sentence Starter',
		'public'        => true,
		'menu_position' => 5,
		'supports'      => array( 'title', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom fields' ),
		'has_archive'   => true,
	);
	register_post_type( 'sentence_starter', $args );

  register_taxonomy( 'flatterbox_type',
    array('flatterboxes'), /* This is the name of your custom post type, I used "Images" */
    array('hierarchical' => true,     /* if this is true it acts like categories */
        'labels' => array(
             /* OPTIONS */
             'name' => 'Types'
        ),
        'show_ui' => true,
        'query_var' => true,

    )
  );
  
  register_taxonomy( 'card_quantity',
    array('box_types'), /* This is the name of your custom post type, I used "Images" */
    array('hierarchical' => true,     /* if this is true it acts like categories */
        'labels' => array(
             /* OPTIONS */
             'name' => 'Card Quantities'
        ),
        'show_ui' => true,
        'query_var' => true,

    )
  );  

}
add_action( 'init', 'custom_post_types' );


function gform_column_splits($content, $field, $value, $lead_id, $form_id) {
	if(!IS_ADMIN) { // only perform on the front end

		// target section breaks
		if($field['type'] == 'section') {
			$form = RGFormsModel::get_form_meta($form_id, true);

			// check for the presence of multi-column form classes
			$form_class = explode(' ', $form['cssClass']);
			$form_class_matches = array_intersect($form_class, array('two-column', 'three-column'));

			// check for the presence of section break column classes
			$field_class = explode(' ', $field['cssClass']);
			$field_class_matches = array_intersect($field_class, array('gform_column'));

			// if field is a column break in a multi-column form, perform the list split
			if(!empty($form_class_matches) && !empty($field_class_matches)) { // make sure to target only multi-column forms

				// retrieve the form's field list classes for consistency
				$form = RGFormsModel::add_default_properties($form);
				$description_class = rgar($form, 'descriptionPlacement') == 'above' ? 'description_above' : 'description_below';

				// close current field's li and ul and begin a new list with the same form field list classes
				return '</li></ul><ul class="gform_fields '.$form['labelPlacement'].' '.$description_class.' '.$field['cssClass'].'"><li class="gfield gsection empty">';

			}
		}
	}

	return $content;
}
add_filter('gform_field_content', 'gform_column_splits', 10, 5);


// Order information submission

add_action("gform_after_submission_3", "set_post_content_3", 10, 2);
function set_post_content_3($entry, $form){

	global $wpdb;
	
	if($entry["8"] == 0)
	{
		$wpdb->insert( 
			'orderinfo', 
			array( 
				'PID' => $_SESSION["sentimentPID"],
				'FNAME' => $entry["2.3"],
				'LNAME' => $entry["2.6"],
				'ADDRESS' => $entry["3.1"],
				'ADDRESS2' => $entry["3.2"],
				'CITY' => $entry["3.3"],
				'STATE' => $entry["3.4"],
				'ZIP' => $entry["3.5"],
				'COUNTRY' => $entry["3.6"],
				'QUANTITY' => $entry["5"],
				'COMMENTS' => $entry["4"]
			), 
			array( 
				'%d',
				'%s', 
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%s',
				'%d',
				'%s'
			) 
		);
	
	} else {
	
			$wpdb->update( 
				'orderinfo',
				array(
					'FNAME' => $entry["2.3"],
					'LNAME' => $entry["2.6"],
					'ADDRESS' => $entry["3.1"],
					'ADDRESS2' => $entry["3.2"],
					'CITY' => $entry["3.3"],
					'STATE' => $entry["3.4"],
					'ZIP' => $entry["3.5"],
					'COUNTRY' => $entry["3.6"],
					'QUANTITY' => $entry["5"],
					'COMMENTS' => $entry["4"]
				), 
				array(
					'OID' => $entry["8"]
				), 
				array(
					'%s', 
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%s',
					'%d',
					'%s'
				),
				array('%d')
			); 	
	
	}
	
	
	if($entry["7"] == "1")
	{		
	
		wp_redirect(site_url()."/order-shipping-information/?adding=1");
		
	} else {
		
		wp_redirect(site_url()."/order-preview-order/");
		
	}

}

// Flatterbox Options submission
add_action("gform_after_submission_34", "set_post_content_34", 10, 2);
function set_post_content_34($entry, $form){
// Create flatterbox post object
	//Get the current user info to insert into flatterbox information
	$current_user = wp_get_current_user();
	$current_user_name = $current_user->user_login;

	//Set up the flatterbox information to insert
	$my_flatterbox = array(
	  'post_title'    => $entry["1"] . '\'s ' . $entry["26"] . ' Flatterbox',
	  'post_type'	  => 'flatterboxes',
	  'post_content'  => '',
	  'post_status'   => 'publish',
	  'post_author'   => get_current_user_id()
	);
	// Insert the post into the database
	if(!isset($_SESSION["new_flatterbox_id"]))
	{
	$new_post_id = wp_insert_post( $my_flatterbox );
	$newpid = $new_post_id;
	} else {
	$newpid = $_SESSION["new_flatterbox_id"];
	}
	
	$_SESSION["occassion_name"] = $entry["26"];
	
	wp_set_object_terms($new_post_id, $entry["26"], 'flatterbox_type', 0);
	
	//Convert date to ACF's format
	$date = new DateTime($entry["2"]);
	$newdate = $date->Format(Ymd);	
	
	$boxtheme = '';
	
	switch($entry["26"]) {
		case 'Birthday':
			$boxtheme = $entry["27"];
			break;
			
		case 'Anniversary':
			$boxtheme = $entry["28"];
			break;		

		case 'Military Gift':
			$boxtheme = $entry["53"];
			break;		
			
		case 'Get Well':
			$boxtheme = $entry["52"];
			break;		

		case 'Bar Mizvah':
			$boxtheme = $entry["51"];
			break;		
			
		case 'New Baby/Parents':
			$boxtheme = $entry["50"];
			break;		

		case 'Engagement':
			$boxtheme = $entry["49"];
			break;	

		case 'Wedding':
			$boxtheme = $entry["48"];
			break;				
			
		case 'Graduation':
			$boxtheme = $entry["47"];
			break;	

		case 'Bridal Shower':
			$boxtheme = $entry["46"];
			break;
			
		case "Boss' Gift":
			$boxtheme = $entry["45"];
			break;	

		case 'Holiday':
			$boxtheme = $entry["44"];
			break;	

		case "Valentine's Day":
			$boxtheme = $entry["43"];
			break;		

		case "Father's Day":
			$boxtheme = $entry["42"];
			break;	

		case 'Sweet 16':
			$boxtheme = $entry["41"];
			break;	

		case 'Love You Because...':
			$boxtheme = $entry["40"];
			break;	

		case 'Retirement':
			$boxtheme = $entry["39"];
			break;		

		case 'Hanukkah':
			$boxtheme = $entry["38"];
			break;	

		case "Mother's Day":
			$boxtheme = $entry["37"];
			break;		

		case 'Funeral':
			$boxtheme = $entry["36"];
			break;			

		case 'New Year Encouragement':
			$boxtheme = $entry["35"];
			break;		

		case 'Just Because...':
			$boxtheme = $entry["34"];
			break;				
			
		case 'Divorce Encouragement':
			$boxtheme = $entry["33"];
			break;		

		case 'Corporate Meeting':
			$boxtheme = $entry["32"];
			break;	

		case "Teacher's Gift":
			$boxtheme = $entry["31"];
			break;		

		case 'Christmas':
			$boxtheme = $entry["30"];
			break;	

		case "Coach's Gift":
			$boxtheme = $entry["29"];
			break;					
			
		}
			
	
	__update_post_meta( $newpid, 'occasion', $value = $entry["26"]);
	$_SESSION["occasion"] =  $entry["26"];
	__update_post_meta( $newpid, 'box_theme', $value = $boxtheme);	
	$_SESSION["box_theme"] =  $boxtheme;
	__update_post_meta( $newpid, 'who_is_this_for', $value = $entry["1"]);
	$_SESSION["who_is_this_for"] =  $entry["1"];
	__update_post_meta( $newpid, 'who_is_this_from', $value = $entry["65"]);
	$_SESSION["who_is_this_from"] =  $entry["65"];
	__update_post_meta( $newpid, 'date_of_birthday', $value = $newdate);
	$_SESSION["date_of_birthday"] =  $newdate;
	__update_post_meta( $newpid, 'birthday_occassion', $value = $entry["3"]);
	$_SESSION["birthday_occassion"] =  $entry["3"];
	__update_post_meta( $newpid, 'card_style', $value = $_SESSION["cardcolor"]);
	__update_post_meta( $newpid, 'card_quantity', $value = $_SESSION["cardquantity"]);
	__update_post_meta( $newpid, 'box_style', $value = $_SESSION["boxtype"]);
	__update_post_meta( $newpid, 'introductory_card_message', $value = $entry["4"]);
	$_SESSION["introductory_card_message"] =  $entry["4"];
	__update_post_meta( $newpid, 'special_instructions_to_flatterers', $value = $entry["5"]);
	$_SESSION["special_instructions_to_flatterers"] =  $entry["5"];
	__update_post_meta( $newpid, 'private', $value = $entry["8"]); // Used for Passcode
	$_SESSION["private"] =  $entry["8"];
	__update_post_meta( $newpid, 'can_invite', $value = $entry["69"]); // Used for Flaterers to be able to Invite
	$_SESSION["can_invite"] =  $entry["69"];
	__update_post_meta( $newpid, 'allow_to_see_eachother', $value = $entry["25"]);
	$_SESSION["allow_to_see_eachother"] =  $entry["25"];
	__update_post_meta( $newpid, 'allow_to_share', $value = $entry["7"]);
	$_SESSION["allow_to_share"] =  $entry["7"];
	__update_post_meta( $newpid, 'allow_profanity', $value = $entry["9"]);
	$_SESSION["allow_profanity"] =  $entry["9"];
	__update_post_meta( $newpid, 'notification_frequency', $value = $entry["10"]);
	$_SESSION["notification_frequency"] =  $entry["10"];
	__update_post_meta( $newpid, 'total_price', $value = $_SESSION["totalprice"]);
	__update_post_meta( $newpid, 'box_image_url', $value = $_SESSION["boxtypeimg"]); //$_SESSION["boxtypeimg"]
	__update_post_meta( $newpid, 'box_color', $value = $_SESSION["boxcolor"]);

	// Dates for Deadlines
	__update_post_meta( $newpid, 'date_of_delivery', $value = $newdate);
	__update_post_meta( $newpid, 'date_of_project_complete', $value = $newdate);
	__update_post_meta( $newpid, 'date_sentiments_complete', $value = $newdate);
	
	// Dates for Deadlines
	//Convert dates to ACF's format
	$date = new DateTime($entry["2"]);
	$delivery = $date->Format(Ymd);
	$date = new DateTime($entry["19"]);
	$project = $date->Format(Ymd);
	$date = new DateTime($entry["54"]);
	$sentiment = $date->Format(Ymd);

	__update_post_meta( $newpid, 'date_of_delivery', $value = $delivery);
	$_SESSION["date_of_delivery"] =  $delivery;
	__update_post_meta( $newpid, 'date_of_project_complete', $value = $project);
	$_SESSION["date_of_project_complete"] =  $project;
	__update_post_meta( $newpid, 'date_sentiments_complete', $value = $sentiment);
	$_SESSION["date_sentiments_complete"] =  $sentiment;

	$_SESSION["new_flatterbox_id"] = $newpid;
}


add_action("gform_after_submission_13", "set_post_content_13", 10, 2);
function set_post_content_13($entry, $form){
	if (isset($_SESSION['returnURL']) && !empty($_SESSION['returnURL'])) :
		$url = $_SESSION['returnURL'];
		$_SESSION['returnURL'] == '';
		header('Location: '.$url);
	endif;
}


function __update_post_meta( $post_id, $field_name, $value = '' )
{
    if ( empty( $value ) OR ! $value ) :
        delete_post_meta( $post_id, $field_name );
    elseif ( ! get_post_meta( $post_id, $field_name ) ) :
        add_post_meta( $post_id, $field_name, $value );
    else :
        update_post_meta( $post_id, $field_name, $value );
    endif;
}

// Disable Admin Bar for everyone but administrators
if (!function_exists('df_disable_admin_bar')) {

	function df_disable_admin_bar() {
		
		if (!current_user_can('manage_options')) {
		
			// for the admin page
			remove_action('admin_footer', 'wp_admin_bar_render', 1000);
			// for the front-end
			remove_action('wp_footer', 'wp_admin_bar_render', 1000);
			
			// css override for the admin page
			function remove_admin_bar_style_backend() { 
				echo '<style>body.admin-bar #wpcontent, body.admin-bar #adminmenu { padding-top: 0px !important; }</style>';
			}	  
			add_filter('admin_head','remove_admin_bar_style_backend');
			
			// css override for the frontend
			function remove_admin_bar_style_frontend() {
				echo '<style type="text/css" media="screen">
				html { margin-top: 0px !important; }
				* html body { margin-top: 0px !important; }
				</style>';
			}
			add_filter('wp_head','remove_admin_bar_style_frontend', 99);
			
		}
  	}
}
add_action('init','df_disable_admin_bar');


function curPageURL() {
    $pageURL = 'http';
    if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
        $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

/* Order Complete Actions */
/*
add_action( 'woocommerce_order_status_completed', 'send_to_printer' );
function send_to_printer($order_id) {

	echo '<script>window.alert("HELLO");</script>';

	// order object (optional but handy)
	$order = new WC_Order( $order_id );
	echo 'ORDERSTARTED -- '.$order_id;
	print_r($order);
	$file = 'people.txt';
	//$current = file_get_contents($file);
	$current = '';
	$current .= "John Smith\n";
	file_put_contents($file, $current);
	echo 'ORDERENDED';

	if ( count( $order->get_items() ) > 0 ) :
		foreach( $order->get_items() as $item ) :
			// Add element
		endforeach;
	endif;

}
*/

add_action('add_to_cart_redirect', 'resolve_dupes_add_to_cart_redirect');
 
function resolve_dupes_add_to_cart_redirect($url = false) {
 
     // If another plugin beats us to the punch, let them have their way with the URL
     if(!empty($url)) { return $url; }
 
     // Redirect back to the original page, without the 'add-to-cart' parameter.
     // We add the `get_bloginfo` part so it saves a redirect on https:// sites.
     return get_bloginfo('wpurl').add_query_arg(array(), remove_query_arg('add-to-cart'));
 
}

add_action( 'woocommerce_thankyou_order_id', 'send_to_printer2' );
//add_action( 'woocommerce_payment_complete', 'send_to_printer2' );
function send_to_printer2($order_id) {

	global $wpdb;
	$order = wc_get_order( $order_id );
	
	$orderAddOns = wc_checkout_add_ons()->get_order_add_ons( $order_id );
	$gift = 'No'; $extracards = 0;
	if ( $orderAddOns[2]['value'] == 1 ) : $gift = 'Yes'; endif;
	if ( $orderAddOns[3]['value'] == 1 ) : $extracards = 10; endif;
	
	//print_r($orderAddOns);
	$order_count = 1;
	//print_r($order);
	//print_r($_SESSION);
	$PID = $_SESSION['sentimentPID'];
	do_action( 'woocommerce_order_details_after_customer_details', $order );
	$_customer = new WC_Customer($order_id);

	$shipping_address = $order->get_formatted_shipping_address();
	$name = explode('<br/>', $shipping_address);
	$name = $name[0];
	// echo $shipping_address;
	// print_r($_customer);

	$blankcolor = '';

	$xmlstring = '<?xml version="1.0" encoding="UTF-8"?>';
	// Open Order
$xmlstring .= '
<order>';
	if ( count( $order->get_items() ) > 0 ) :
		foreach( $order->get_items() as $item ) :
			// Add element
			$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
			//print_r($_product);
			//print_r($item);
			//$_item = $order->get_item_meta(784); // 781 784
			/*
			echo '------------';
			echo $_item;
			print_r($_item);
			echo '------------';
			*/
	// Start Box
$xmlstring .= '
    <orderId>'.$order_id.'-'.str_pad($order_count, 3, '0', STR_PAD_LEFT).'</orderId>
    <orderDate><![CDATA['.date('n/j/y',strtotime( $order->order_date )).']]></orderDate>
    <shipTo><![CDATA['.$name.']]></shipTo>
    <shipAdd1><![CDATA['.$_customer->get_shipping_address().']]></shipAdd1>
    <shipAdd2><![CDATA['.$_customer->get_shipping_address_2().']]></shipAdd2>
    <shipCity><![CDATA['.$_customer->get_shipping_city().']]></shipCity>
    <shipState><![CDATA['.$_customer->get_shipping_state().']]></shipState>
    <shipZip><![CDATA['.$_customer->get_shipping_postcode().']]></shipZip>
    <giftWrapped><![CDATA['.$gift.']]></giftWrapped>
    <shipVia>NotAvailable</shipVia>
    <box>
        <shipQty>'.$item["qty"].'</shipQty>
        <sku><![CDATA['.$_product->get_sku().'_'.$item["pa_cardquantity"].']]></sku>
        <font><![CDATA[Unknown]]></font>
        <substrate><![CDATA[Unknown]]></substrate>
        <boxType><![CDATA['.$item["pa_boxtype"]; if ( strlen($item["pa_boxcolor"]) > 0 ) : $xmlstring .= '-'.$item["pa_boxcolor"]; endif; $xmlstring .= ']]></boxType>';

        $sentiment_results = $wpdb->get_results( "SELECT * FROM sentiments WHERE approved = 1 AND PID = " . $PID, ARRAY_A);

        // Start Card
		if ($sentiment_results) :
			foreach ($sentiment_results as $row) : 
$xmlstring .= '
        <card>
            <note><![CDATA['.$row["sentiment_text"].']]></note>
            <author><![CDATA['.$row["sentiment_name"].']]></author>
            <image><![CDATA['.$item["pa_cardcolor"].']]></image>
        </card>';
        		$blankcolor = $item["pa_cardcolor"];
        	endforeach;
        endif;
        // End Card
    	$order_count++; // Increase Count
		endforeach;
		// Extra Cards
		for ($i=0; $i < $extracards; $i++) : 
$xmlstring .= '
        <card>
            <note><![CDATA[]]></note>
            <author><![CDATA[]]></author>
            <image><![CDATA['.$blankcolor.']]></image>
        </card>';
		endfor;
		// End Extra Cards
$xmlstring .= '
    </box>';
    // Close Box
	endif;
    // Close Order
$xmlstring .= '
</order>';

	// Save File
	$file = $order_id.'.xml';
	file_put_contents('orderlist/'.$file, $xmlstring);

	$response = sftp_printer($file);

	//echo '<script>window.alert("HELLO -- '.$order_id.' -- '.$response.'");</script>';
}
/*
add_filter ('woocommerce_payment_complete_order_status', 'my_change_status_function');
function my_change_status_function ($order_id) { 
	echo '<script>window.alert("HELLO 3 -- '.$order_id.'");</script>';
	$order = new WC_Order($order_id);
	//Do whatever additional logic you like before….
	print_r($order);
	return 'completed';
}
*/

/* SFTP TO PRINTER */
function sftp_printer($xml_file) {

	if ( strrpos(get_include_path(), "phpseclib0.3.0") === false ) :
		set_include_path(get_include_path() . PATH_SEPARATOR . './phpseclib0.3.0');
	endif;

	include('Net/SFTP.php');

	$rtn = false;

	$sftp = new Net_SFTP('ftp.tginc.com', 2222);
	if (!$sftp->login('flatterbox', 'T&!Fl@!!er')) :
		$rtn ='Login Failed';
	endif;

	$local_directory = './orderlist/'; // Set in previous function -- orderlist/';
	$remote_directory = '/incoming/';

	//echo $local_directory.'<br/>';
	//echo $remote_directory.'<br/>';

	$files_to_upload = array();

	if ( $xml_file != "." && $xml_file != ".." ) :
		$files_to_upload[] = $xml_file;
	endif;

	if( !empty($files_to_upload) && $rtn == false ) :
		foreach($files_to_upload as $file) :
			/*
			echo dirname(__FILE__).'<br/>';
			echo $file.'<br/>';
			echo $local_directory . $file.'<br/>';
			*/
			//$rtn = $sftp->put($remote_directory . $file, $local_directory . $file, NET_SFTP_LOCAL_FILE);

			$data = file_get_contents($local_directory . $file);
			$rtn = $sftp->put($remote_directory . $file, $data);
		endforeach;
	endif;

	return $rtn;
/*
	$conn = ssh2_connect('ftp.tginc.com', 2222);
	ssh2_auth_password($conn, 'flatterbox', 'T&!Fl@!!er');

	// send a file
	return ssh2_scp_send($conn, $local_directory.$xml_file, $remote_directory.$xml_file, 0644);
*/

}

/* OPTIONS PAGE */
if (function_exists('acf_add_options_page')) {
acf_add_options_page('Site Settings');
}

/* select2 Issues in checkout */
add_action( 'wp_enqueue_scripts', 'child_manage_woocommerce_styles', 99 );
function child_manage_woocommerce_styles() {
	//remove_action( 'wp_head', array( $GLOBALS['woocommerce'], 'generator' ) );
	if ( is_page('checkout') ) :
		wp_dequeue_style( 'select2' );
		wp_dequeue_script( 'select2' );
	endif;
}

?>