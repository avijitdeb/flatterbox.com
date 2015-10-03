<?php
	function add_new_flatterboxes_columns($flatterboxes_columns) {
	    $new_columns['cb'] = '<input type="checkbox" />';
	    
	    $new_columns['title'] = _x('Flatterbox Titles', 'column name');
	    $new_columns['card_quantity'] = __('Card QTY');
	    $new_columns['box_status'] = __('Flatterbox Status');
	    $new_columns['date_of_delivery'] = __('Delivery Date');
	    $new_columns['sentiment_count'] = __('Sentiment Count');
	    $new_columns['invite_count'] = __('Invites Sent');
	    $new_columns['unique_url'] = __('Unique URL');
	    $new_columns['order_count'] = __('Order Numbers');
	    $new_columns['author_email'] = __('Author &amp; Email');
	    
	    $new_columns['date'] = _x('Date', 'column name');
	 
	    return $new_columns;
	}
	add_filter('manage_edit-flatterboxes_columns', 'add_new_flatterboxes_columns');
	
	function manage_flatterboxes_columns($column_name, $id) {
	    global $wpdb;
	    switch ($column_name) {
	    case 'id':
	        echo $id;
	        break;
	    case 'card_quantity':
	        echo get_field('card_quantity',$id);
	        break;
	    case 'box_status':
	        if (strlen(get_field('order_count',$id)) == 0 ) :
	        	echo '<div style="width:100%;text-align:center;color:#fff;font-size:14px;padding:5px 0;background-color:green">Flatterbox is Open</div>';
	        else :
	        	echo '<div style="width:100%;text-align:center;color:#fff;font-size:14px;padding:5px 0;background-color:red">Flatterbox is Closed</div>';
	        endif;
	        break;
	    case 'date_of_delivery':
	        echo date('F j, Y',strtotime(get_field('date_of_delivery')));
	        break;
	    case 'sentiment_count':
	    	$numberofsentiments = 0;
	    	$sentiment_count = $wpdb->get_results( "SELECT count(*) AS Sentiments FROM sentiments WHERE PID = " . $id, ARRAY_A);
	    	if ($sentiment_count) :
					foreach ($sentiment_count as $row) :
						$numberofsentiments = $row["Sentiments"];
						//$flatterername = $row["flatterer_name"];
					endforeach;
				endif;
	        echo '<a href="'.home_url().'/wp-admin/admin.php?page=sentiments&pid='.$id.'">'.$numberofsentiments.'</a>';
	        break;
	    case 'invite_count':
	    	$numberofinvitations = 0;
	    	$flatterer_count = $wpdb->get_results( "SELECT count(*) AS flatterers FROM flatterers WHERE invalid = 0 AND PID = " . $id, ARRAY_A);
	    	if ($flatterer_count) :
					foreach ($flatterer_count as $row) :
						$numberofinvitations = $row["flatterers"];
						//$flatterername = $row["flatterer_name"];
					endforeach;
				endif;
	        echo $numberofinvitations;
	        break;
	    case 'unique_url':
	        echo '<a href="'.home_url().'/?fb='.get_field('unique_url',$id).'" target="_blank">'.home_url().'/?fb='.get_field('unique_url',$id).'</a>';
	        break;
	    case 'order_count':
	        echo get_field('order_count',$id);
	        break;
       case 'author_email':
	       $post_author_id = get_post_field( 'post_author', $id );
	       echo '<a href="edit.php?post_type=flatterboxes&author='.$post_author_id.'">'.get_the_author_meta('display_name',$post_author_id).'</a><br/>'.get_the_author_meta('email',$post_author_id);
	       break;
	    default:
	        break;
	    } // end switch
	}   
	add_action('manage_flatterboxes_posts_custom_column', 'manage_flatterboxes_columns', 10, 2);

	function flatterboxes_sort($columns) {
		$custom = array(
			'card_quantity'		=> 'card_quantity',
			'date_of_delivery'	=> 'date_of_delivery',
			'order_count'	=> 'order_count'
		);
		return wp_parse_args($custom, $columns);
		/* or this way
			$columns['concertdate'] = 'concertdate';
			$columns['city'] = 'city';
			return $columns;
		*/
	}
	add_filter("manage_edit-flatterboxes_sortable_columns", 'flatterboxes_sort');

	function flatterboxes_column_orderby( $vars ) {
		if ( isset( $vars['orderby'] ) && 'date_of_delivery' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'date_of_delivery',
				//'orderby' => 'meta_value_num', // does not work
				'orderby' => 'meta_value'
				//'order' => 'asc' // don't use this; blocks toggle UI
			) );
		}
		if ( isset( $vars['orderby'] ) && 'card_quantity' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'card_quantity',
				//'orderby' => 'meta_value_num', // does not work
				'orderby' => 'meta_value'
				//'order' => 'asc' // don't use this; blocks toggle UI
			) );
		}
		if ( isset( $vars['orderby'] ) && 'order_count' == $vars['orderby'] ) {
			$vars = array_merge( $vars, array(
				'meta_key' => 'order_count',
				//'orderby' => 'meta_value_num', // does not work
				'orderby' => 'meta_value'
				//'order' => 'asc' // don't use this; blocks toggle UI
			) );
		}
		return $vars;
	}
	add_filter( 'request', 'flatterboxes_column_orderby' );
?>