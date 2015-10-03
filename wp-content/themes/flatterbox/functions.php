<?php
session_start();
if (isset($_GET['resetsession']) && $_GET['resetsession'] == 'reset') : session_unset(); endif;
if (isset($_GET['fb'])) : wp_redirect(site_url()."/sentiment/?fbox=".$_GET['fb']); exit; endif; // redirect for short URL

//include('includes/sentimentspanel.php');

add_action( 'after_setup_theme', 'flatterbox_setup' );

// Global Variables
$minusDeliveryDays = 7;
$minusSubmitDays = 7;
$minusApproveDays = 7;
$allowPasscode = false; // Passcode enabled for private Checks
$allowFlattererInvite = true; // Flatterers are allowed to send invites Check


/* Login Error Messages */
/*
function login_error_message( $error ){
	return 'TEST';
	global $errors;
	$err_codes = $errors->get_error_codes();

	// Invalid username.
	// Default: '<strong>ERROR</strong>: Invalid username. <a href="%s">Lost your password</a>?'
	if ( in_array( 'invalid_username', $err_codes ) ) {
		$error = '<strong>ERROR</strong>: Invalid username.';
	}

	// Incorrect password.
	// Default: '<strong>ERROR</strong>: The password you entered for the username <strong>%1$s</strong> is incorrect. <a href="%2$s">Lost your password</a>?'
	if ( in_array( 'incorrect_password', $err_codes ) ) {
		$error = '<strong>ERROR</strong>: The password you entered is incorrect.';
	}

	return $error;
}
add_filter( 'login_errors', 'login_error_message', 10, 3 );
*/
add_filter('authenticate', 'wp_my_auth', 20, 3);
function wp_my_auth($user, $username, $password) {
	if ( $user instanceof WP_User ) {
		return $user;
	}

	if ( empty($username) || empty($password) ) {
		if ( is_wp_error( $user ) )
			return $user;

		$error = new WP_Error();

		if ( empty($username) )
			$error->add('empty_username', __('<strong>ERROR</strong>: The username field is empty.'));

		if ( empty($password) )
			$error->add('empty_password', __('<strong>ERROR</strong>: The password field is empty.'));

		return $error;
	}

	$user = get_user_by('login', $username);

	if ( !$user )
		return new WP_Error( 'invalid_username', __( '<strong>ERROR</strong>: Invalid username or password' ) );

	/**
	 * Filter whether the given user can be authenticated with the provided $password.
	 *
	 * @since 2.5.0
	 *
	 * @param WP_User|WP_Error $user     WP_User or WP_Error object if a previous
	 *                                   callback failed authentication.
	 * @param string           $password Password to check against the user.
	 */
	$user = apply_filters( 'wp_authenticate_user', $user, $password );
	if ( is_wp_error($user) )
		return $user;

	if ( !wp_check_password($password, $user->user_pass, $user->ID) )
		return new WP_Error( 'incorrect_password', __( '<strong>ERROR</strong>: Invalid username or password' ) );

	return $user;
}

add_filter('query_vars', 'parameter_queryvars' );
function parameter_queryvars( $qvars ) {
	$qvars[] = 'boxtype';
	$qvars[] = 'boxprice';
	$qvars[] = 'boxcolor';
	$qvars[] = 'cardcolor';
	$qvars[] = 'cardquantity';
	$qvars[] = 'cardquantityprice';
	$qvars[] = 'totalprice';
	$qvars[] = 'cardcolorimg';
	$qvars[] = 'boxtypeimg';
	$qvars[] = 'occasion';
	$qvars[] = 'FID';
	$qvars[] = 'PID';
	$qvars[] = 'SID';
	$qvars[] = 'resetsession';
	$qvars[] = 'fb';
	$qvars[] = 'fbox';
	$qvars[] = 'modifyPID';
	$qvars[] = 'action';
	$qvars[] = 'order_id';
	$qvars[] = 'initialinvite';
	$qvars[] = 'flattererinvite';
	$qvars[] = 'invitePID';
	$qvars[] = 'creatingbox';
	$qvars[] = 'listview';
	$qvars[] = 'filter';
	$qvars[] = 'fromlist';
	$qvars[] = 'fromPopup';
	$qvars[] = 'adding';
	$qvars[] = 'OID';
	$qvars[] = 'realtime';
	$qvars[] = 'preservesession';
	$qvars[] = 'allowPasscode';
	$qvars[] = 'allowFlattererInvite';
	$qvars[] = 'summary';
	$qvars[] = 'whoto';
	$qvars[] = 'whofrom';
	$qvars[] = 'theme';
	$qvars[] = 'profanity';
	$qvars[] = 'multi';
	$qvars[] = 'invite';
	$qvars[] = 'title_card_headline';
	$qvars[] = 'redirect';
	$qvars[] = 'remind';
	$qvars[] = 'type';
	$qvars[] = 'typename';
	$qvars[] = 'dyear';
	$qvars[] = 'month';
	$qvars[] = 'list_day';
	$qvars[] = 'showme';
	$qvars[] = 'exportcsv';
	$qvars[] = 'box_image_url';
	$qvars[] = 'modified';
	return $qvars;
}

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
		'supports'      => array( 'title', 'author', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom fields' ),
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
		'supports'      => array( 'title', 'author', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom fields' ),
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
		'supports'      => array( 'title', 'author', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom fields', 'page-attributes' ),
		'has_archive'   => true,
	);
	register_post_type( 'box_types', $args );
	/*
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
		'supports'      => array( 'title', 'author', 'editor', 'thumbnail', 'excerpt', 'comments', 'custom fields' ),
		'has_archive'   => true,
	);
	//register_post_type( 'coupon_codes', $args );
	*/
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
		'supports'      => array( 'title','author' , 'editor', 'thumbnail', 'excerpt', 'comments', 'custom fields' ),
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
             'name' => 'Occasions' /* was Types */
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
		$my_flatterbox = array(
			'ID'			=> $_SESSION["new_flatterbox_id"],
			'post_title'	=> $entry["1"] . '\'s ' . $entry["26"] . ' Flatterbox',
			'post_content'	=> ''
		);
		wp_update_post( $my_flatterbox ); // To update the title
		$newpid = $_SESSION["new_flatterbox_id"];
	}
	
	$_SESSION["occassion_name"] = $entry["26"];
	
	wp_set_object_terms($newpid, $entry["26"], 'flatterbox_type', 0);
	
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
		case 'Bar/Bat Mizvah':
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
			$boxtheme = $entry["80"];
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
		case "Coach's Gift":
			$boxtheme = $entry["29"];
			break;
		case "Baby Shower":
			$boxtheme = $entry["81"];
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
	__update_post_meta( $newpid, 'special_instructions_to_flatterers', $value = $entry["77"]);
	$_SESSION["special_instructions_to_flatterers"] =  $entry["77"];
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
	__update_post_meta( $newpid, 'notification_frequency', $value = $entry["75"]); // Was 10
	$_SESSION["notification_frequency"] =  $entry["75"];
	__update_post_meta( $newpid, 'total_price', $value = $_SESSION["totalprice"]);
	__update_post_meta( $newpid, 'box_image_url', $value = $_SESSION["boxtypeimg"]); //$_SESSION["boxtypeimg"]
	__update_post_meta( $newpid, 'box_color', $value = $_SESSION["boxcolor"]);
	//__update_post_meta( $newpid, 'title_card_headline', $value = $entry["73"]); // Removed from form
	__update_post_meta( $newpid, 'live_event', $value = $entry["78"]);

	// Dates for Deadlines
	__update_post_meta( $newpid, 'date_of_delivery', $value = $newdate);
	__update_post_meta( $newpid, 'date_of_project_complete', $value = $newdate);
	__update_post_meta( $newpid, 'date_sentiments_complete', $value = $newdate);
	
	//$current_user = wp_get_current_user();
	//$uniquestart = $current_user->user_email.$newpid;
	//__update_post_meta( $newpid, 'unique_url', $value = md5(uniqid($uniquestart, true)));

	//Order Items
	__update_post_meta( $newpid, 'order_count', $value = '');
	__update_post_meta( $newpid, 'add10', $value = '0');

	// Only Update if new
	if(!isset($_SESSION["new_flatterbox_id"]))
	{
		__update_post_meta( $newpid, 'unique_url', $value = getURLToken(10));
	}

	// Dates for Deadlines
	//Convert dates to ACF's format
	$date = new DateTime($entry["2"]);
	$delivery = $date->Format(Ymd);
	$date = new DateTime($entry["19"]); // Was 19? Which doesnt exist
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

	if ($entry["84"] == "1") : 
		wp_redirect(site_url()."/my-flatterbox/"); exit;
	endif;
}


add_action("gform_after_submission_13", "set_post_content_13", 10, 2);
function set_post_content_13($entry, $form){

	//login, set cookies, and set current user
	$user_pass = md5($entry['2']); 
    wp_login($entry['3'], $user_pass, true);
    wp_setcookie($entry['3'], $entry['2'], true);
    wp_set_current_user($user->ID, $user_login);

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

//add_action( 'woocommerce_thankyou_order_id', 'send_to_printer2' );
add_action( 'woocommerce_payment_complete', 'send_to_printer2' );
function send_to_printer2($order_id, $PID = -1, $reqfrom = "woocommerce") {

	global $wpdb;
	$order = wc_get_order( $order_id );
	if ($PID == -1 && $reqfrom == "woocommerce") : $PID = $_SESSION['sentimentPID']; endif;
	
	// Update Order So that it is stored in the flatterbox
	$currentOrders = get_field('order_count', $PID);
	if ( strlen($currentOrders) > 0 ) : $currentOrders .= ','; endif;
	$currentOrders .= $order_id;
	__update_post_meta( $PID, 'order_count', $value = $currentOrders);

	$orderAddOns = wc_checkout_add_ons()->get_order_add_ons( $order_id );
	$gift = 'No'; $extracards = 0;
	if ( $orderAddOns[2]['value'] == 1 ) : $gift = 'Yes'; endif;
	if ( $orderAddOns[3]['value'] == 1 ) : $extracards = 10; endif; // Not needed I dont think
	
	//print_r($orderAddOns);
	$order_count = 1;
	//print_r($order);
	//print_r($_SESSION);
	do_action( 'woocommerce_order_details_after_customer_details', $order );
	$_customer = new WC_Customer($order_id);

	$shipping_address = $order->get_formatted_shipping_address();
	$name = explode('<br/>', $shipping_address);
	$name = $name[0];
	// echo $shipping_address;
	// print_r($_customer);

	$blankcolor = '';

	$shipping_method = $order->get_shipping_method();

	switch (strtoupper($shipping_method)) {
		case 'FEDEX EXPRESS SAVER':
			$shipping_method = 'FDEX_EXPRESSSAVER';
			break;
		case 'FEDEX 2DAY':
			$shipping_method = 'FDEX_FEDEX2DAY';
			break;
		case 'FEDEX PRIORITY OVERNIGHT':
			$shipping_method = 'FDEX_PRIORITYOVERNIGHT';
			break;
		default:
			$shipping_method = 'FDXG_FEDEXGROUND';
			break;
	}


	$xmlstring = '<?xml version="1.0" encoding="UTF-8"?>';
	// Open Order
$xmlstring .= '
<order xmlns="http://fbns/">';
	if ( count( $order->get_items() ) > 0 ) :
		foreach( $order->get_items() as $item ) :
			// Reset Additional Count
			if ( $orderAddOns[3]['value'] == 1 ) : $extracards = 10; else : $extracards = 0; endif;

			// Add element
			$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
			$qty = intval($item["pa_cardquantity"]) + $extracards;
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
<subOrder>
    <orderId>'.$order_id.'-'.str_pad($order_count, 3, '0', STR_PAD_LEFT).'</orderId>
    <orderDate><![CDATA['.date('n/j/y',strtotime( $order->order_date )).']]></orderDate>
    <shipTo><![CDATA['.$name.']]></shipTo>
    <shipAdd1><![CDATA['.$_customer->get_shipping_address().']]></shipAdd1>
    <shipAdd2><![CDATA['.$_customer->get_shipping_address_2().']]></shipAdd2>
    <shipCity><![CDATA['.$_customer->get_shipping_city().']]></shipCity>
    <shipState><![CDATA['.$_customer->get_shipping_state().']]></shipState>
    <shipZip><![CDATA['.$_customer->get_shipping_postcode().']]></shipZip>
    <giftWrapped><![CDATA['.$gift.']]></giftWrapped>';
    /* <specialInstructions><![CDATA['.htmlspecialchars_decode(stripslashes(get_field('special_instructions_to_flatterers', $PID))).']]></specialInstructions> */
$xmlstring .= '
    <shipVia><![CDATA['.$shipping_method.']]></shipVia>
    <box>
        <shipQty>'.$item["qty"].'</shipQty>
        <sku><![CDATA['.$_product->get_sku().'_'.$qty.']]></sku>
        <font><![CDATA[Unknown]]></font>
        <substrate><![CDATA[Unknown]]></substrate>
        <boxType><![CDATA['.$item["pa_boxtype"]; if ( strlen($item["pa_boxcolor"]) > 0 ) : $xmlstring .= '-'.$item["pa_boxcolor"]; endif; $xmlstring .= ']]></boxType>
		<cardcolor><![CDATA['.xmlPrepare($item["pa_cardcolor"]).']]></cardcolor>';
		$blankcolor = xmlPrepare($item["pa_cardcolor"]);
        
        if (get_field('title_card_headline', $PID)) :
$xmlstring .= '
        <titlecard>
            <heading><![CDATA['.xmlPrepare(get_field('title_card_headline', $PID)).']]></heading>
            <to><![CDATA['.xmlPrepare(get_field('who_is_this_for', $PID)).']]></to>
            <from><![CDATA['.xmlPrepare(get_field('title_card_name', $PID)).']]></from>
        </titlecard>';
        else : // For the blank parts
$xmlstring .= '
        <titlecard>
            <heading><![CDATA[]]></heading>
            <to><![CDATA[]]></to>
            <from><![CDATA[]]></from>
        </titlecard>';
        endif;

        $sentiment_results = $wpdb->get_results( "SELECT * FROM sentiments WHERE approved = 1 AND PID = " . $PID, ARRAY_A);
        $sentiment_count = $wpdb->num_rows;
        // Start Cards
		if ($sentiment_results) :
			foreach ($sentiment_results as $row) : 
$xmlstring .= '
        <card>
            <note><![CDATA['.xmlPrepare(preg_replace('/\r/', '/R',preg_replace('/\n(\s*\n)+/', '/R', preg_replace('/\r\n/', '/R',$row["sentiment_text"])))).']]></note>
            <author><![CDATA['.xmlPrepare($row["sentiment_name"]).']]></author>
            <image><![CDATA['.xmlPrepare($item["pa_cardcolor"]).']]></image>
        </card>';
        	endforeach;
        endif;
        // End Card
    	$order_count++; // Increase Count
		// Extra Cards
		$addCards = intval($item["pa_cardquantity"])-$sentiment_count;
		if ($addCards > 0) : $extracards = $extracards + $addCards; endif;
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
    </box>
</subOrder>';
		endforeach;
    // Close Box
	endif;
    // Close Order
$xmlstring .= '
</order>';

	// GET DATE
	$thedate = date("Ymd_Hi");

	// Save File
	$file = $order_id.'_'.$thedate.'.xml';
	//$myfile = fopen('orderlist/'.$file, "w");
	//echo '<script>window.alert("HELLO -- '.$order_id.' -- '.getcwd().'");</script>';
	if ($reqfrom == 'ajaxfunction') :
		file_put_contents('../../../orderlist/'.$file, $xmlstring); // Staging requires the ../ to be removed
	else :
		file_put_contents('../orderlist/'.$file, $xmlstring); // Staging requires the ../ to be removed
	endif;
	if (false) : // removed as they are presently pulling
		$response = sftp_printer($file);
	endif; 

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
		set_include_path(get_include_path() . PATH_SEPARATOR . '../phpseclib0.3.0'); // Staging requires the ../ to be ./
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

function xmlPrepare($string) {
	$string = htmlspecialchars_decode(stripslashes($string));
	$stringQuoteCount = substr_count($string,'"');
	if (($stringQuoteCount % 2 == 0) == false) : $string .= '"'; endif; // Add another slash so XML is not broken for Printer
	return $string;
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

/* WooCommerce Support */
add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

/* Gravity Forms Updates */
//add_filter( 'gform_other_choice_value', __( 'Or write your own theme here' , 'gravityforms' ) );
add_action( 'get_other_choice_value', 'get_other_choice_value_theme' );
function get_other_choice_value_theme() {
		$value = apply_filters( 'gform_other_choice_value', __( 'Or write your own theme here' , 'gravityforms' ) );

		return $value;
	}
/* Share This Function Shortcode */
function st_customized_buttons_shortcode() {
	global $post;

	$widgetTag= stripslashes(get_option('st_widget'));
	$publisher_id= get_option('st_pubid');
	if(empty($publisher_id)) {
		$toShow="";
		// Re-generate new random publisher key
		$publisher_id=trim(makePkey());
	} else $toShow= $widgetTag;
	$content= $toShow;
	$services= get_option('st_services');
	if(empty($services)) $services="facebook,twitter,linkedin,email,sharethis,fblike,plusone,pinterest";
	$tags= stripslashes(get_option('st_tags'));
	if(empty($tags)) {
		foreach(explode(',', $services) as $svc) {
			$tags.= "<span class='st_".$svc."_large' st_title='".get_the_title($post->ID)."' st_url='".get_permalink($post->ID)."' displayText='".$svc."'></span>";
		}
	} else {
		$tagsArray= explode('<?php the_title(); ?>', $tags);
		$myTitle= get_the_title($post->ID);
		$tags= implode($myTitle, $tagsArray);
		$tagsArray= explode('<?php the_permalink(); ?>', $tags);
		$myPermalink= get_permalink($post->ID);
		$tags= implode($myPermalink, $tagsArray);
	}
	$content.= $tags;
	return $content;
}
add_shortcode('st_buttons', 'st_customized_buttons_shortcode');

// Admin Menu & Columns Updates
include('includes/admin-menu.php');
include('includes/admin-tables.php');
// Custom Buttons
add_action( 'post_submitbox_misc_actions', 'custom_flatterbox_buttons' );

function custom_flatterbox_buttons(){
	if (get_post_type($post) == 'flatterboxes') :
		$PID = get_the_ID();
		//$ordered = false;
		if (strlen(get_field('order_count',$PID)) > 0 || strlen(get_field('order_archive',$PID)) > 0) : //$ordered = true;
	        $html  = '<div id="major-publishing-actions" style="overflow:hidden">';
	        $html .= '<div id="publishing-action">';
			$orderArr = explode(",", trim(get_field('order_count',$PID))); 
	        //print_r($orderArr);
			for ($i=0; $i < count($orderArr); $i++) : 
				if (strlen($orderArr[$i]) > 0) :
					$html .= '<input type="button" accesskey="p" tabindex="5" value="Create XML '.$orderArr[$i].'" class="button-primary" id="xml'.$orderArr[$i].'" name="publish" onclick="flatterboxAction(\'createxml\','.$PID.','.$orderArr[$i].');" style="margin-left:5px;">';
					$html .= '<input type="button" accesskey="p" tabindex="5" value="Reopen '.$orderArr[$i].'" class="button-primary" id="reopen'.$orderArr[$i].'" name="publish" onclick="flatterboxAction(\'reopen\','.$PID.','.$orderArr[$i].');" style="margin-left:5px;">';
				endif;
			endfor; 
	        $html .= '</div>';
	        $html .= '</div>';
	        $html .= '<div id="major-publishing-actions" style="overflow:hidden">';
	        $html .= '<div id="publishing-action">';
	        $orderArchArr = explode(",", trim(get_field('order_archive',$PID)));
	        //print_r($orderArchArr);
			for ($y=0; $y < count($orderArchArr); $y++) :
				if (strlen($orderArchArr[$y]) > 0) :
					$html .= '<input type="button" accesskey="p" tabindex="5" value="Close and Create XML '.$orderArchArr[$y].'" class="button-primary" id="xml'.$orderArchArr[$y].'" name="publish" onclick="flatterboxAction(\'createxmlclose\','.$PID.','.$orderArchArr[$y].');">';
	        	endif;
	        endfor; 
	        $html .= '</div>';
	        $html .= '<script type="text/javascript">function flatterboxAction(action,pid,order_id) {
	var r = false;
	if (action == "reopen") {
		r = confirm("Are you sure you want to re-open this Flatterbox?");
	} else if (action == "createxmlclose") {
		r = confirm("Are you sure you want to close this Flatterbox and create the XML?");
	} else {
		r = confirm("Are you sure you want to create this Flatterbox XML?");
	}
	if (r == true) {
		jQuery.ajax({

		url:"'.home_url().'/wp-content/themes/flatterbox/flatterbox_action.php",
		type:"post",
		data:{"action":action,"PID":pid,"order_id":order_id},
		success: function(data, status) {
			if(data=="ok") { 
				if (action == "reopen") {
					location.reload(true);
				} else {
					window.alert("XML Created!");
					if (action == "createxmlclose") {
						location.reload(true);
					}
				}
			}
		},
		  error: function(xhr, desc, err) {
		  	console.log(data);
			console.log(xhr);
			console.log("Details: " + desc + "\nError:" + err);
		  }		
		});
	} 	

}</script>';
	        $html .= '</div>';
        	echo $html;
        endif;
    endif;
}
add_action( 'edit_form_top', 'top_form_edit' );

function top_form_edit( $post ) {
    if( 'flatterboxes' == $post->post_type ) :
    	$boxstatusopen = true;
        if (strlen(trim(get_field('order_count',$post->ID))) > 0) : $boxstatusopen = false; endif;
        if ($boxstatusopen == true) :
        	$boxstatuscolor = "green";
        	$boxstatusmsg = 'Flatterbox is open';
       	else :
        	$boxstatuscolor = "red";
        	$boxstatusmsg = 'Flatterbox is closed';
       	endif;
        echo '<div id="boxstatus" style="width:100%;text-align:center;color:#fff;font-size:14px;padding:5px 0;background-color:'.$boxstatuscolor.'">'.$boxstatusmsg.'</div>';
    endif;
}

include('includes/emailFunctions.php');

function crypto_rand_secure($min, $max) {
        $range = $max - $min;
        if ($range < 0) return $min; // not so random...
        $log = log($range, 2);
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
}

function getURLToken($length){
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    for($i=0;$i<$length;$i++){
        $token .= $codeAlphabet[crypto_rand_secure(0,strlen($codeAlphabet))];
    }
    return $token;
}

function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

function get_string_between($string, $start, $end){
    $string = " ".$string;
    $ini = strpos($string,$start);
    if ($ini == 0) return "";
    $ini += strlen($start);
    $len = strpos($string,$end,$ini) - $ini;
    return substr($string,$ini,$len);
}

function get_preview_email($PID = 0, $isReminder = false) {
	$strRtn = '';

	// Removed to be a passed variable
	//$PID = 0;
	//if(isset($_SESSION['sentimentPID'])) : $PID = $_SESSION["sentimentPID"]; endif;
	//if(isset($_SESSION['new_flatterbox_id'])) : $PID = $_SESSION["new_flatterbox_id"]; endif;
	//if(isset($_GET['PID'])) : $PID = $_GET["PID"]; endif;

	//$sentimentneeded = date_create(get_field("date_sentiments_complete",$PID));
	$date = DateTime::createFromFormat('Ymd', get_field('date_sentiments_complete', $PID));
	if( $date ) :
		$sentimentneeded = $date->format('m/d/Y');
	endif;
	if (strpos(get_field("box_theme",$PID), '(name)') > 0) :
		$box_theme = str_replace('(name)', get_field("who_is_this_for",$PID), get_field("box_theme",$PID));
	else :
		// Hidding as I think it is a duplicate
		$box_theme = get_field("box_theme",$PID); //.' '.get_field("who_is_this_for",$PID);
	endif;
	$box_theme = trim($box_theme);
	$sentimentneeded = trim($sentimentneeded);

	// Variables for the string
	if (strlen(get_field("who_is_this_from",$PID)) > 0) : $strFrom = get_field("who_is_this_from",$PID); else : $strFrom =  '<i>&lt;from&gt;</i>'; endif;
	if (strlen(get_field("who_is_this_for",$PID)) > 0) : $strFor = get_field("who_is_this_for",$PID); else : $strFor = '<i>&lt;Flatterbox Recipient&gt;</i>'; endif;
	$strOccasion = get_field("occasion",$PID);
	if(strlen($box_theme) > 0) : $strTheme = $box_theme; else : $strTheme = '&lt;Sentiment Box Theme&gt;';endif;
	if (strpos(get_field("box_theme",$PID), '(name)') > 0) : $strThemeFor = ''; else : $strThemeFor = $strFor; endif;
	if ($sentimentneeded) : $strDateneeded = $sentimentneeded; else : $strDateneeded = '&lt;Date Needed&gt;'; endif;
	if(strlen(get_field("special_instructions_to_flatterers",$PID)) > 0) : $strSpecInst = nl2br(get_field("special_instructions_to_flatterers",$PID)); else : $strSpecInst = '&lt;Personal Message&gt;'; endif;
	if($isReminder) : 
		if(strlen(get_field("reminder_instructions",$PID)) > 0) : $strRemind = nl2br(get_field("reminder_instructions",$PID)); else : $strRemind = '&lt;Reminder Instructions&gt;'; endif;
	endif;
	$strRtn .= '
<center>
<table id="Table_01" width="100%" border="0" cellpadding="0" cellspacing="0" style="background-color:white;">
<!--
	<tr>
		<td width="650" height="107" colspan="5" align="center" valign="top"><img src="'.home_url().'/emails/sentiment_invite/images/fb_invite_header_logo.png" width="370" height="80" alt="Your Flatterbox® Invite"></td>
	</tr>
-->
	<tr>
		<td width="100%" height="307" colspan="5" align="center" valign="top" style="font-family: Arial, Helvetica, sans-serif; font-size: 15px; color: #0e2240; margin:0;"><p style="font-weight: bold;">You have been invited by <span id="who_from">'.$strFrom.'</span> to participate in a gift for </p>
			<p style="font-size: 40px; font-weight: bold; margin:25px 0; line-height: 40px;" id="who_for">'.$strFor.'</p>
		  	<p style="font-size: 18px; margin:5px 0 10px 0;">The occasion: <strong id="the_occasion">'.$strOccasion.'</strong></p>
		  	<p style="margin:0;">The gift is called a Flatterbox and we need just<br/>one minute of your time to make it happen.</p>
		  	<div style="padding: 10px; width: 310px; background-color: #f38707; color: #fff; font-size: 19px; font-weight: bold; margin:20px 0;"><em><a href="#" onclick="window.alert(\'This is a sample of your email.  The link will be enabled when your Flatterers receive their invitation.\'); return false;" style="color: #fff;">Click here</a></em> to share<br><span id="the_theme">'.$strTheme.'</span> <span id="theme_name">'.$strThemeFor.'</span></div>
        	<!--
        	<p style="font-size: 14px; color: #797979; font-weight: bold; margin:0;">SENTIMENT NEEDED BY: <span id="date_needed">'.$strDateneeded.'</span></p>
      		<p style="font-size: 28px; color: #f38707; font-weight: bold; margin-top: 20px; margin-bottom: 20px;">Creating a sentiment is as simple as...</p>
      		-->
  			<p style="margin:20px 55px;"><strong id="special_inst">'.$strSpecInst.'</strong></p>
  			';
  			if($isReminder) : $strRtn .= '<p style="margin:20px 55px;"><strong id="reminder_inst">'.$strRemind.'</strong></p>'; endif; 
			$strRtn .= '
  		</td>
	</tr>

	<tr>
		<td colspan="5"><img src="'.home_url().'/emails/sentiment_invite/images/Flatterbox_Eblast_final_08.jpg" width="100%" alt="Your sentiment will appear on a card like this. Click on the link  share '.$strTheme.'. - Flatterbox"></td>
	</tr>
	<tr>
		<td width="100%" height="28" colspan="5" align="center" valign="middle" style="font-size: 11px; background-color: #0D2065; color: #fff;">Thank you for participating in this special gift! | <a href="http://www.flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">flatterbox.com</a> | <a href="mailto:info@flatterbox.com" target="_blank" style="color:#fff; text-decoration:none;">info@flatterbox.com</a></td>
	</tr>
</table>
</center>
';
	return $strRtn;
}
/* Number items for english Numbers */
$ones = array(
			"",
			" one",
			" two",
			" three",
			" four",
			" five",
			" six",
			" seven",
			" eight",
			" nine",
			" ten",
			" eleven",
			" twelve",
			" thirteen",
			" fourteen",
			" fifteen",
			" sixteen",
			" seventeen",
			" eighteen",
			" nineteen"
			);

$tens = array(
			"",
			"",
			" twenty",
			" thirty",
			" forty",
			" fifty",
			" sixty",
			" seventy",
			" eighty",
			" ninety"
			);

$triplets = array(
			"",
			" thousand",
			" million",
			" billion",
			" trillion",
			" quadrillion",
			" quintillion",
			" sextillion",
			" septillion",
			" octillion",
			" nonillion"
			);

// recursive fn, converts three digits per pass
function convertTri($num, $tri) {
	global $ones, $tens, $triplets;

	// chunk the number, ...rxyy
	$r = (int) ($num / 1000);
	$x = ($num / 100) % 10;
	$y = $num % 100;

	// init the output string
	$str = "";

	// do hundreds
	if ($x > 0)
		$str = $ones[$x] . " hundred";

	// do ones and tens
	if ($y < 20)
		$str .= $ones[$y];
	else
		$str .= $tens[(int) ($y / 10)] . $ones[$y % 10];

	// add triplet modifier only if there
	// is some output to be modified...
	if ($str != "")
		$str .= $triplets[$tri];

	// continue recursing?
	if ($r > 0)
		return convertTri($r, $tri+1).$str;
	else
		return $str;
}
function convertNum($num) {
	$num = (int) $num;    // make sure it's an integer

	if ($num < 0)
		return "negative".convertTri(-$num, 0);

	if ($num == 0)
		return "zero";

	return convertTri($num, 0);
}
/* End Number functions */

function getSecureURLString($strURL) {
	if(isset($_SERVER['HTTPS'])) {
    	if ($_SERVER['HTTPS'] == "on") {
	        $strURL = str_replace('http://', 'https://', $strURL);
	    }
	}
	return $strURL;
}

add_action( 'woocommerce_cart_calculate_fees','woocommerce_custom_surcharge' );
function woocommerce_custom_surcharge() {
	global $woocommerce; // Dont think this is needed, but it works so yay
	//print_r($woocommerce->cart->cart_contents_count);
	$extra_note = '';
	if ($_SESSION["add10"] == 1 && is_cart()) : // Only add the line item if on the cart page...
		$ten_add = 4.99;
		$additional = $ten_add * $woocommerce->cart->cart_contents_count;
		if ($woocommerce->cart->cart_contents_count > 1) : $extra_note = ' ('.$woocommerce->cart->cart_contents_count.')'; endif;
		WC()->cart->add_fee(__('Additional 10 Cards'.$extra_note, 'woocommerce'), $additional, true, ''); 
	endif;
	}

function check_for_mobile() { // Returns true if mobile device
	if ( wp_is_mobile() ) // Internal WP Check
		return true;

	// Catch any others
	$ua = $_SERVER['HTTP_USER_AGENT'];

	$iPod    = stripos($ua,"iPod");
	$iPhone  = stripos($ua,"iPhone");
	$iPad    = stripos($ua,"iPad");
	$Android = stripos($ua,"Android");
	$webOS   = stripos($ua,"webOS");
	$runMe	 = false;
	//do something with this information
	if( $iPod || $iPhone ){
	    //browser reported as an iPhone/iPod touch -- do something here
	    $runMe = true;
	}else if($iPad){
	    //browser reported as an iPad -- do something here
	    $runMe = true;
	}else if($Android){
	    //browser reported as an Android device -- do something here
	    $runMe = true;
	}else if($webOS){
	    //browser reported as a webOS device -- do something here
	}

	return $runMe;
}

?>