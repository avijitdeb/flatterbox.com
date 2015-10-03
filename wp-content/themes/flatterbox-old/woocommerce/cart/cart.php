<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if($_GET["add10"] == 1)
{
	$_SESSION["add10"] = 1;
} else {
	$_SESSION["add10"] = 0;
}

do_action( 'woocommerce_before_cart' ); ?>

<form action="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<main id="main" role="main" class="page-order-preview-order">
	<div class="container cart">
		<div class="heading">	
			<h1>Preview Order</h1>
		</div>
		<ul id="status-bar" class="status-cart">
			<li class="done">View Sentiments</li>
			<?php if ( false ) : ?><li>Add Sentiments</li><?php endif; ?>
			<li class="active">Preview Order</li>
			<li>Billing and Shipping</li>
			<li>Order Confirmation</li>
		</ul>
		<?php wc_print_notices(); ?>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>
		<?php
		//print_r($_SESSION);

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			
		 //print_r($cart_item);
			$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) { ?>
			<div class="product-holder">
			
			<?php
			//added by JMD to pull the correct card color image in the cart when multiple products exist
			$args=array(
				'post_type' => 'box_types',
				'post_status' => 'publish',
				'order' => 'ASC',
				'posts_per_page' => 1 // Currently only acrylic is used - need to update this when we add "Box-Types" to the select
			);
			$box_type_query = new WP_Query($args);
			$boxtype_desc = '';
			if ( $box_type_query->have_posts() ) : while ( $box_type_query->have_posts() ) : $box_type_query->the_post(); 
				if ( have_rows('card_colors') ) : while(have_rows('card_colors')) : the_row();
					if(strtolower($cart_item["variation"]["attribute_pa_cardcolor"])==str_replace(' ','-',strtolower(get_sub_field('color_description'))))
					{
						$productImg = get_sub_field('color_image');
					}
				endwhile; endif;
			endwhile; endif;
			//end code added by JMD
			
			?>			
				<?php 
					//$productImg = get_field('box_image_url', $_SESSION['sentimentPID']);
					if (isset($_GET['box_image_url']) && $productImg == '') : $productImg = $_GET['box_image_url']; endif;
					$_SESSION['box_image_url'] = $productImg;
				?>
				<div class="product-image teaser <?php echo $cart_item["variation"]["attribute_pa_boxtype"]; ?> <?php echo $cart_item["variation"]["attribute_pa_boxcolor"]; ?> <?php echo $cart_item["variation"]["attribute_pa_cardcolor"]; ?>" style="background-image: url('<?php echo $productImg; ?>');"></div>
				<div class="product-info teaser">
					<h2><?php if ($cart_item["variation"]["attribute_pa_flatterboxname"] != "") : 
							$occasionRerun = str_replace("s-","'s-",$cart_item["variation"]["attribute_pa_flatterboxoccasion"]);
							$occasionRun = str_replace("-"," ",$occasionRerun);
							echo ''.str_replace("-"," ",$cart_item["variation"]["attribute_pa_flatterboxname"]).'\'s<br/>'.$occasionRun.' Flatterbox';
						else : echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ); ?>
					<?php endif; ?></h2>
					<div class="product-description">
						<div class="info-row">
							<strong>Sentiments/Cards</strong><br>
							<?php echo $cart_item["variation"]["attribute_pa_sentimentcount"]; ?>/<?php echo $cardcolor = $cart_item["variation"]["attribute_pa_cardquantity"]; ?>
						</div>
						<div class="info-row">
							<strong>Card Color</strong><br>
							<span class="cardcolor"><?php echo $cardcolor = strtoupper($cart_item["variation"]["attribute_pa_cardcolor"]); ?></span>
						</div>
						<div class="info-row">
							<strong>Price</strong><br>
							<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
							<?php
							if($_GET["modified"] == 1) 
							{
							?>
							<br/><span style="color:red;font-weight:bold;">PLEASE NOTE:  Your Flatterbox size and price were upgraded to accommodate a surplus in sentiment cards in your order.</span>
							<?php
							}
							?>
						</div>
						<div class="info-row">
							<strong>Number of Flatterboxes</strong><br>
							<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
								} else {
									$product_quantity = woocommerce_quantity_input( array(
										'input_name'  => "cart[{$cart_item_key}][qty]",
										'input_value' => $cart_item['quantity'],
										'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
										'min_value'   => '0'
									), $_product, false );
								}
								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key ); ?>
						</div>
						<div class="info-row">
							<strong>Subtotal</strong><br>
							<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
						</div>
						<?php if ($cart_item["variation"]["attribute_pa_flatterboxname"] != "") : ?>
						<div class="box message-note">
							<ul class="woocommerce-error">
								<li>Please do not purchase your box until all of your sentiments are collected.</li>
							</ul>
						</div>
						<?php endif; ?>
					</div>
					<div class="action-row">
						<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="btn save orangebtn" title="%s">Remove from Cart</a>', esc_url( WC()->cart->get_remove_url( $cart_item_key ) ), __( 'Remove from Preview', 'woocommerce' ) ), $cart_item_key );?>
					</div>
				</div>
			</div><!-- end gallery-holder -->

			<?php
				}
			}
			do_action( 'woocommerce_cart_contents' ); ?>

			<div class="preview">
				<ul class="woocommerce-error">
					<li>Currently we only ship in the continental United States. Our products will ship approximately 2 days after purchase.</li>
				
					<li><br>Printing takes 2-3 business days before it will ship.</li>
				</ul>
				<div class="leftcol matchCol">
					<?php if ( WC()->cart->coupons_enabled() ) { ?>
						<div class="coupon">

							<?php if (false) : ?><label class="couponlabel" for="coupon_code"><?php _e( 'Coupon Code', 'woocommerce' ); ?>:</label><?php endif; ?>
							<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php _e( 'Coupon code', 'woocommerce' ); ?>" />
							<input type="submit" class="btn" name="apply_coupon" value="<?php _e( 'Apply Coupon', 'woocommerce' ); ?>" />

							<?php do_action('woocommerce_cart_coupon'); ?>

						</div>
					<?php } ?>

					<?php wp_nonce_field( 'woocommerce-cart' ); ?>

					<?php do_action( 'woocommerce_after_cart_contents' ); ?>
				</div>
				<div class="rightcol matchCol">
					<?php do_action( 'woocommerce_cart_collaterals' ); ?>

					<?php //woocommerce_cart_totals(); // Removed duplication?>

					<?php woocommerce_shipping_calculator(); ?>
				</div>
			</div>
			<div class="preview textright">

				<a href="<?php echo do_shortcode('[site_url]'); ?>/my-flatterbox/" class="btn back checkout-button orangebtn" style="color: #fff;">Back to My Flatterbox</a> <?php //astopani ?>

				<input type="submit" class="button btn" name="update_cart" value="<?php _e( 'Update Cart', 'woocommerce' ); ?>" />

				<input type="submit" class="checkout-button button alt wc-forward btn orangebtn" name="proceed" value="<?php _e( 'Proceed to Checkout', 'woocommerce' ); ?>" />

			</div>

			<?php do_action( 'woocommerce_after_cart_table' ); ?>

			</form>

			<?php if (false) : ?>

			<div class="cart-collaterals">

				<?php do_action( 'woocommerce_cart_collaterals' ); ?>

				<?php woocommerce_cart_totals(); ?>

				<?php woocommerce_shipping_calculator(); ?>

			</div>

			<?php endif; ?>

			<?php do_action( 'woocommerce_after_cart' ); ?>

		</div>

	</main>

	<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.matchHeight.js"></script>
	<script>
	jQuery(function() {
	    jQuery('.teaser').matchHeight();
	});
	</script>
