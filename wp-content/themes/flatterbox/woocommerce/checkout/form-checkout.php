<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>



<main id="main" role="main" class="page-order-preview-order">
	<div class="container cart">
		<div class="heading">	
			<h1>Billing and Shipping</h1>
		</div>
		<ul id="status-bar" class="status-cart">
			<li class="done">View Sentiments</li>
			<?php if ( false ) : ?><li class="done">Add Sentiments</li><?php endif; ?>
			<li class="done">Preview Order</li>
			<li class="active">Billing and Shipping</li>
			<li>Order Confirmation</li>
		</ul>
		<?php wc_print_notices(); ?>
<?php
do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', WC()->cart->get_checkout_url() ); ?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $get_checkout_url ); ?>" enctype="multipart/form-data">

	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<div class="box">

			<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

			<div class="col2-set" id="customer_details">
				<div class="col-1">
					<?php do_action( 'woocommerce_checkout_billing' ); ?>
				</div>

				<div class="col-2">
					<?php do_action( 'woocommerce_checkout_shipping' ); ?>
				</div>
			</div>

			<?php if (has_action('woocommerce_checkout_after_customer_details')) : ?>
				<h2>Order Extras</h2>
				<div class="box">
					<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
					<span style="display:none;color:red;" id="add10text"><br/>PLEASE NOTE: Because your order's sentiments exceed the box limit within 10 cards, the additional cards option is required.</span>
				</div>
			<?php endif; ?>
		</div>

		<h2 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h2>

	<?php endif; ?>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<?php 
if($_SESSION["add10"] == 1)
{
	?>
	<script>
		jQuery("#wc_checkout_add_ons_3").prop('checked', true);
		jQuery("#wc_checkout_add_ons_3").attr('onclick', 'return false');
		jQuery("#add10text").css("display","inline");
	</script>
	<?php
}
?>
