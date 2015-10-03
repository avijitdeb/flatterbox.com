<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<main id="main" role="main" class="page-order-preview-order">
	<div class="container cart">
		<div class="heading">	
			<h1>Order Confirmation</h1>
		</div>
		<ul id="status-bar" class="status-cart">
			<li class="done">View Sentiments</li>
			<?php if ( false ) : ?><li class="done">Add Sentiments</li><?php endif; ?>
			<li class="done">Preview Order</li>
			<li class="done">Billing and Shipping</li>
			<li class="active">Order Confirmation</li>
		</ul>

<?php if ( $order ) : ?>

	<div class="box order-status">

	<?php if ( $order->has_status( 'failed' ) ) : ?>

		<h2><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce' ); ?></h2>

		<p><?php
			if ( is_user_logged_in() )
				_e( 'Please attempt your purchase again or go to your account page.', 'woocommerce' );
			else
				_e( 'Please attempt your purchase again.', 'woocommerce' );
		?></p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
			<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>" class="button pay"><?php _e( 'My Account', 'woocommerce' ); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

		<h2><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received. You will receive an email confirmation shortly. Please visit My Flatterbox to review the status of your order.', 'woocommerce' ), $order ); ?></h2>

		<ul class="order_details">
			<li class="order">
				<?php _e( 'Order:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li class="date">
				<?php _e( 'Date:', 'woocommerce' ); ?>
				<strong><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></strong>
			</li>
			<li class="total">
				<?php _e( 'Total:', 'woocommerce' ); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ( $order->payment_method_title ) : ?>
			<li class="method">
				<?php _e( 'Payment method:', 'woocommerce' ); ?>
				<strong><?php echo $order->payment_method_title; ?></strong>
			</li>
			<?php endif; ?>
		</ul>
		<div class="clear"></div>

	<?php endif; ?>

	</div>

	<div class="box payment-details">
	<?php do_action( 'woocommerce_thankyou_' . $order->payment_method, $order->id ); ?>
	</div>
	<?php do_action( 'woocommerce_thankyou', $order->id ); ?>

<?php else : ?>

	<div class="box">
		<p><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received. You will receive an email confirmation shortly. Please visit My Flatterbox to review the status of your order.', 'woocommerce' ), null ); ?></p>
	</div>

<?php endif; ?>

</div>
</main>