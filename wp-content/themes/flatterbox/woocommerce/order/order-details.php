<?php
/**
 * Order details
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$order = wc_get_order( $order_id );
?>
<h2><?php _e( 'Order Details', 'woocommerce' ); ?></h2>


		<?php
		if ( sizeof( $order->get_items() ) > 0 ) {

			foreach( $order->get_items() as $item ) {
				$_product     = apply_filters( 'woocommerce_order_item_product', $order->get_product_from_item( $item ), $item );
				$item_meta    = new WC_Order_Item_Meta( $item['item_meta'], $_product );

				?>
				<div class="product-holder">
				<div class="product-image teaser <?php echo $cardcolor = $item["pa_boxtype"]; ?> <?php echo $cardcolor = $item["pa_boxcolor"]; ?> <?php echo $cardcolor = $item["pa_cardcolor"]; ?>" style="background-image: url('<?php echo do_shortcode('[site_url]'); ?>/wp-content/themes/flatterbox/images/<?php echo $cardcolor = $item["pa_boxtype"]; ?>-<?php echo $cardcolor = $item["pa_boxcolor"]; ?>-<?php echo $cardcolor = $item["pa_cardcolor"]; ?>.png');"></div>
				<div class="product-info teaser">
					<h2>
					<?php 
					/*
					
					JMD: below is the old code to get the title based on an if statement to determine if it is a product or a custom flatterbox
					
					if ($item["pa_flatterboxname"] != "default" || $item["pa_flatterboxname"] != "") : 
						$occasionRerun = str_replace("s-","'s-",$item["pa_flatterboxoccasion"]);
						$occasionRun = str_replace("-"," ",$occasionRerun);
						echo ''.str_replace("-"," ",$item["pa_flatterboxname"]).'\'s '.$occasionRun.' Flatterbox';
					else : echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
					endif;
					
					*/
					
					//Below is a new if statement to determine if the flatterbox title comes from a product or a custom flatterbox
					
					if (apply_filters( 'woocommerce_order_item_name', $item['name'], $item ) == "My Custom Flatterbox") : 
						$occasionRerun = str_replace("s-","'s-",$item["pa_flatterboxoccasion"]);
						$occasionRun = str_replace("-"," ",$occasionRerun);
						echo ''.str_replace("-"," ",$item["pa_flatterboxname"]).'\'s '.$occasionRun.' Flatterbox';
					else : echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
					endif;					
					
					?>
					</h2>
					<div class="product-description">
						<div class="info-row">
							<strong>Sentiments/Cards</strong><br>
							<?php echo $item["pa_sentimentcount"] ?>/<?php echo $item["pa_cardquantity"]; ?>
						</div>
						<div class="info-row">
							<strong>Card Color</strong><br>
							<span class="cardcolor"><?php $item["pa_cardcolor"]; ?></span>
						</div>
						<div class="info-row">
							<strong>Price</strong><br>
							<?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?>
						</div>
						<div class="info-row">
							<strong>Number of Flatterboxes</strong><br>
							<?php echo $item['qty']; ?>
						</div>
						<div class="info-row">
							<strong>Subtotal</strong><br>
							<?php echo $order->get_formatted_line_subtotal( $item ); ?>
						</div>
					</div>
				</div>
			</div><!-- end gallery-holder -->











			<?php if (false) : ?>
				<!-- OLD -->
				<h3><?php echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item ); ?></h3>

				<div class="preview">
					<div class="sample">
						<div class="box cardcolor <?php echo $cardcolor = $item["pa_cardcolor"]; ?>">
						</div>
					</div>
					<div class="sample">
					<div class="box thumbnail <?php echo $cardcolor = $item["pa_boxtype"]; ?> <?php echo $cardcolor = $item["pa_boxcolor"]; ?>">
						<?php

								if (false) :
								$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

								if ( ! $_product->is_visible() )
									echo $thumbnail;
								else
									printf( '<a href="%s">%s</a>', $_product->get_permalink(), $thumbnail );

								endif;
						?>
					</div>
				</div>
				<div class="sample">
					<div class="sentiment">
						<div class="frame">
							<h2><?php echo $cardcolor = $item["pa_cardquantity"]; ?></h2>
							<p><strong>Sentiments</strong></p>
							<div class="details">
								<table>
									<tr class="headerrow">
										<td>Price</td>
										<td class="textcenter">Quantity</td>
										<td class="textright">Subtotal</td>
									</tr>
									<tr>
										<td><?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); ?></td>
										<td class="textcenter"><?php echo $item['qty'];
										?>
										</td>
										<td class="textright"><?php echo $order->get_formatted_line_subtotal( $item ); ?></td>
									</tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>

			<?php endif; ?>

				<?php if (false) : ?>

				<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
					<td class="product-name">
						<?php
							if ( $_product && ! $_product->is_visible() )
								echo apply_filters( 'woocommerce_order_item_name', $item['name'], $item );
							else
								echo apply_filters( 'woocommerce_order_item_name', sprintf( '<a href="%s">%s</a>', get_permalink( $item['product_id'] ), $item['name'] ), $item );

							echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times; %s', $item['qty'] ) . '</strong>', $item );

							$item_meta->display();

							if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {

								$download_files = $order->get_item_downloads( $item );
								$i              = 0;
								$links          = array();

								foreach ( $download_files as $download_id => $file ) {
									$i++;

									$links[] = '<small><a href="' . esc_url( $file['download_url'] ) . '">' . sprintf( __( 'Download file%s', 'woocommerce' ), ( count( $download_files ) > 1 ? ' ' . $i . ': ' : ': ' ) ) . esc_html( $file['name'] ) . '</a></small>';
								}

								echo '<br/>' . implode( '<br/>', $links );
							}
						?>
					</td>
					<td class="product-total">
						<?php echo $order->get_formatted_line_subtotal( $item ); ?>
					</td>
				</tr>
				<?php

				if ( $order->has_status( array( 'completed', 'processing' ) ) && ( $purchase_note = get_post_meta( $_product->id, '_purchase_note', true ) ) ) {
					?>
					<tr class="product-purchase-note">
						<td colspan="3"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
					</tr>
					<?php
				}

				endif;
			}
		}

		do_action( 'woocommerce_order_items_table', $order );
		?>

		<h2>Shipping Overview</h2>
		<div class="box order-shipping-address">
			
<?php
	// Additional customer details hook
	do_action( 'woocommerce_order_details_after_customer_details', $order );
?>
</dl>

<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

<div class="col2-set addresses">

	<div class="col-1">

<?php endif; ?>

		<header class="title">
			<h3><?php _e( 'Billing Address', 'woocommerce' ); ?></h3>
		</header>
		<address>
			<?php
				if ( ! $order->get_formatted_billing_address() ) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_billing_address();
			?>
		</address>
		<br>
		<p><?php if ( $order->billing_email ) : echo __( 'Email:', 'woocommerce' ); echo ' '.$order->billing_email; endif; ?><br>
		<?php if ( $order->billing_phone ) : echo __( 'Phone:', 'woocommerce' ); echo ' '.$order->billing_phone; endif; ?></p>

<?php if ( ! wc_ship_to_billing_address_only() && $order->needs_shipping_address() && get_option( 'woocommerce_calc_shipping' ) !== 'no' ) : ?>

	</div><!-- /.col-1 -->

	<div class="col-2">

		<header class="title">
			<h3><?php _e( 'Shipping Address', 'woocommerce' ); ?></h3>
		</header>
		<address>
			<?php
				if ( ! $order->get_formatted_shipping_address() ) _e( 'N/A', 'woocommerce' ); else echo $order->get_formatted_shipping_address();
			?>
		</address>

	</div><!-- /.col-2 -->

</div><!-- /.col2-set -->

<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>


</div><!-- end shipping -->

<h2>Order Total</h2>
<div class="box grand-total">
	<?php
		if ( $totals = $order->get_order_item_totals() ) foreach ( $totals as $total ) :
			?>
			<div class="one-third text-center">
				<h3 class="box-count"><?php echo $total['label']; ?></h3>
				<?php if ($total['label'] == "Order Total:") : ?>
				<h2><?php echo $total['value']; ?></h2>
				<?php else : ?>
				<p><?php echo $total['value']; ?></p>
				<?php endif; ?>
			</div>
			<?php
		endforeach;
	?>
</div>

<?php endif; ?>

<div class="clear"></div>


<script src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.matchHeight.js"></script>
<script>
jQuery(function() {
    jQuery('.teaser').matchHeight();
});
</script>