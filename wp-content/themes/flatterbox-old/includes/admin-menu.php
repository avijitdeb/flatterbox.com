<?php
// CHANGE MENU ICONS
// Icons: https://developer.wordpress.org/resource/dashicons/#welcome-learn-more
function replace_admin_menu_icons_css() {
    ?>
    <style>
        #adminmenu .menu-icon-plugins div.wp-menu-image:before,
		#adminmenu .menu-icon-appearance div.wp-menu-image:before,
		#adminmenu .menu-icon-tools div.wp-menu-image:before, 
		#adminmenu .menu-icon-settings div.wp-menu-image:before,
		#adminmenu #toplevel_page_edit-post_type-acf div.wp-menu-image:before {
    		content: '\f158' !important; /* X */
	}
	#adminmenu  #menu-posts-flatterboxes div.wp-menu-image:before {
		content: "\f307" !important; /* People */
	}
	#adminmenu #menu-posts-sentence_starter div.wp-menu-image:before {
		content: "\f478" !important; /* Words */
	}
	#adminmenu #menu-posts-sentence_gallery div.wp-menu-image:before {
		content: "\f479" !important; /* Words */
	}
	#adminmenu #menu-posts-box_types div.wp-menu-image:before {
		content: "\f480" !important; /* Box */
	}
	#adminmenu #menu-posts-testimonials div.wp-menu-image:before {
		content: "\f526" !important; /* Person in Thought */
	}
    </style>
    <?php
}

add_action( 'admin_head', 'replace_admin_menu_icons_css' );

// CHANGE MENU ORDER
function custom_menu_order($menu_ord) {
    if (!$menu_ord) return true;
     
    return array(
        'index.php', // Dashboard
        'separator1', // First separator
        'edit.php', // Posts
		'edit.php?post_type=page', // Pages
		'edit.php?post_type=flatterboxes', // Flatterboxes
		'edit.php?post_type=sentence_starter', // Sentance Starters
		'edit.php?post_type=sentence_gallery', // Sentance Gallery
		'edit.php?post_type=box_types', // Box Types
		'edit.php?post_type=testimonials', // Testimonials
		'separator2', // Second separator
        'upload.php', // Media
        'edit-comments.php', // Comments
		'users.php', // Users
        'separator3', // Third separator
        'themes.php', // Appearance
        'plugins.php', // Plugins
        'tools.php', // Tools
        'options-general.php', // Settings
		'edit.php?post_type=acf',
        'separator-last', // Last separator
    );
}
add_filter('custom_menu_order', 'custom_menu_order'); // Activate custom_menu_order
add_filter('menu_order', 'custom_menu_order');

?>