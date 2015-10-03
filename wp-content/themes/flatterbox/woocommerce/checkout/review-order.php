<?php
/**
 * Review order form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.8
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<?php
do_action( 'woocommerce_review_order_before_cart_contents' );

foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

	if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) { ?>

	<div class="product-holder">
		<?php 
			$productImg = get_field('box_image_url', $_SESSION['sentimentPID']); 
			if (isset($_SESSION['box_image_url']) && $productImg == '') : $productImg = $_SESSION['box_image_url']; endif; 
		?>

		<div class="product-image teaser <?php echo $cart_item["variation"]["attribute_pa_boxtype"]; ?> <?php echo $cart_item["variation"]["attribute_pa_boxcolor"]; ?> <?php echo $cart_item["variation"]["attribute_pa_cardcolor"]; ?>" style="background-image: url('<?php echo getSecureURLString($productImg); ?>');"></div>
		<div class="product-info teaser">
			<h2><?php if ($cart_item["variation"]["attribute_pa_flatterboxname"] != "") : 
					$occasionRerun = str_replace("s-","'s-",$cart_item["variation"]["attribute_pa_flatterboxoccasion"]);
					$occasionRun = str_replace("-"," ",$occasionRerun);
					echo ''.str_replace("-"," ",$cart_item["variation"]["attribute_pa_flatterboxname"]).'\'s '.$occasionRun.' Flatterbox';
				else : echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ); ?>
			<?php endif; ?></h2>
			<div class="product-description">
				<div class="info-row">
					<strong>Sentiments/Cards</strong><br>
					<?php echo $cart_item["variation"]["attribute_pa_sentimentcount"]; ?>/<?php echo $cardcolor = $cart_item["variation"]["attribute_pa_cardquantity"]; ?>
				</div>
				<div class="info-row">
					<strong>Card Color</strong><br>
					<span class="cardcolor"><?php echo $cardcolor = $cart_item["variation"]["attribute_pa_cardcolor"]; ?></span>
				</div>
				<div class="info-row">
					<strong>Price</strong><br>
					<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
				</div>
				<div class="info-row">
					<strong>Number of Flatterboxes</strong><br>
					<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', $cart_item['quantity'], $cart_item, $cart_item_key ); ?>
				</div>
				<div class="info-row">
					<strong>Subtotal</strong><br>
					<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); ?>
				</div>
			</div>
			<?php if ($cart_item["variation"]["attribute_pa_flatterboxname"] != "") : ?>
			<div class="box message-note">
				<ul class="woocommerce-error">
					<li>The Classic Flatterbox is a custom made product, <strong>one you should not purchase until all of your sentiments are collected.</strong>  Once your purchase is made no other sentiments can be collected.</li>
				</ul>
			</div>
			<?php endif; ?>
		</div>
	</div><!-- end gallery-holder -->
	
	<?php 
		}
	}
do_action( 'woocommerce_review_order_after_cart_contents' ); ?>

	<div class="box">
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
				<?php wc_cart_totals_shipping_html(); ?>
				<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
		<?php endif; ?>
		<div class="multiaddress">
			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
		</div>
	</div>

	<div class="box">
		<div class="one-third checkout-row cart-subtotal">
			<h3><?php _e( 'Cart Subtotal', 'woocommerce' ); ?></h3>
			<?php wc_cart_totals_subtotal_html(); ?>
		</div>
		<?php foreach ( WC()->cart->get_coupons( 'cart' ) as $code => $coupon ) : ?>
		<div class="one-third checkout-row cart-discount coupon-<?php echo esc_attr( $code ); ?>">
			<h3><?php wc_cart_totals_coupon_label( $coupon ); ?></h3>
			<?php wc_cart_totals_coupon_html( $coupon ); ?>
		</div>
		<?php endforeach; ?>

		<?php if ( false ) : // hidding?>
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<div class="one-third">
			<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
				<?php wc_cart_totals_shipping_html(); ?>
				<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
			</div>
		<?php endif; ?>
		<div class="shipaddresses">
			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
		</div>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="one-third checkout-row fee">
				<h3><?php echo esc_html( $fee->name ); ?></h3>
				<?php wc_cart_totals_fee_html( $fee ); ?>
			</div>
		<?php endforeach; ?>

		<?php if ( WC()->cart->tax_display_cart === 'excl' ) : ?>
			<?php if ( get_option( 'woocommerce_tax_total_display' ) === 'itemized' ) : ?>
				<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
					<div class="one-third checkout-row tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<h3><?php echo esc_html( $tax->label ); ?></h3>
						<?php echo wp_kses_post( $tax->formatted_amount ); ?>
					</div>
				<?php endforeach; ?>
			<?php else : ?>
				<div class="one-third checkout-row tax-total">
					<h3><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></h3>
					<?php echo wc_price( WC()->cart->get_taxes_total() ); ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_coupons( 'order' ) as $code => $coupon ) : ?>
			<div class="one-third checkout-row order-discount coupon-<?php echo esc_attr( $code ); ?>">
				<h3><?php wc_cart_totals_coupon_label( $coupon ); ?></h3>
				<?php wc_cart_totals_coupon_html( $coupon ); ?>
			</div>
		<?php endforeach; ?>

		<div class="one-third checkout-row order-total">
			<h3><?php _e( 'Order Total', 'woocommerce' ); ?></h3>
			<h2><?php wc_cart_totals_order_total_html(); ?></h2>
		</div>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</div>

	<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.matchHeight.js"></script>
	<script>
	jQuery(function() {
	    jQuery('.teaser').matchHeight();
	});
	</script>