<?php
/**
 * Empty cart page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

?>

<main id="main" role="main" class="page-order-preview-order">
	<div class="container cart">
		<div class="heading">	
			<h1>Preview Order</h1>
		</div>
		<?php // wc_print_notices(); ?>

		<div class="box">

		<h2 style="margin-left: 0;"><?php _e( 'There are no Flatterboxes in your order.', 'woocommerce' ) ?></h2>

		<?php do_action( 'woocommerce_cart_is_empty' ); ?>

		<p class="return-to-shop"><a class="button wc-backward" id="returnback" href="<?php echo do_shortcode('[site_url]'); ?>">Back to Homepage</a></p>
		
		</div>
	</div>
</main>