<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<th class="product-remove"><span class="screen-reader-text"><?php esc_html_e( 'Remove item', 'woocommerce' ); ?></span></th>
				<th class="product-thumbnail"><span class="screen-reader-text"><?php esc_html_e( 'Thumbnail image', 'woocommerce' ); ?></span></th>
				<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
				<th class="checkout-product-box-quantity"><?php esc_html_e( 'sqm_per_box', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				/**
				 * Filter the product name.
				 *
				 * @since 2.1.0
				 * @param string $product_name Name of the product in the cart.
				 * @param array $cart_item The product in the cart.
				 * @param string $cart_item_key Key for the product in the cart.
				 */
				$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<td class="product-remove">
							<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										/* translators: %s is the product name */
										esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
							?>
						</td>

						<td class="product-thumbnail">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

						if ( ! $product_permalink ) {
							echo $thumbnail; // PHPCS: XSS ok.
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
						}
						?>
						</td>

						<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
						<?php
						if ( ! $product_permalink ) {
							echo wp_kses_post( $product_name . '&nbsp;' );
						} else {
							/**
							 * This filter is documented above.
							 *
							 * @since 2.1.0
							 */
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
						}

						do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

						// Meta data.
						echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

						// Backorder notification.
						if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
						}
						?>
						</td>

						<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>

						<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">

							<div class="checkout-box-quantity-container">

								<div class="checkout-box-quantity-inner">
							
								<?php

									$product_sqm_per_box= get_post_meta($product_id, '_advanced-qty-step', true); 
									$_product = $cart_item['data'];
		        					$product_quantity = $cart_item['quantity']; 
		        					$product_box = $product_quantity/$product_sqm_per_box;
		        					$product_suffix = get_post_meta($product_id, '_advanced-qty-price-suffix', true); 

									if ( $_product->is_sold_individually() ) {
										$min_quantity = 1;
										$max_quantity = 1;
									} else {
										$min_quantity = 0;
										$max_quantity = $_product->get_max_purchase_quantity();
									}

									$product_quantity = woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $max_quantity,
											'min_value'    => $min_quantity,
											'step'   	   => $min_quantity,
											'product_name' => $product_name,
										),
										$_product,
										false
									);

								?>


								<?php echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok. ?>

								<?php 
								//if box rate has not been setup
								if($product_suffix == "/m2" || $product_suffix == "/ m2" || $product_suffix == "m2"){ ?>

									<p class="M2-mark checkout-M2-mark">m2</p>

								<?php }else{ 

									$product_suffix = str_replace("/", "", $product_suffix);

									?>
									
									<p class="M2-mark checkout-M2-mark"><?php echo $product_suffix ?></p>

								<?php } ?>

								</div>

								<?php 
								//if box rate has not been setup
								if($product_suffix == "/m2" || $product_suffix == "/ m2" || $product_suffix == "m2")
								{ ?>

									<p class="checkout-product-box-quantity"><?php echo $product_box; ?> Boxes, <?php echo $product_sqm_per_box; ?> m2/Box</p>

								<?php }else{  

								} ?>

							</div>

						</td>

						<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
							<?php
								$_product = $cart_item['data'];
        						$quantity = $cart_item['quantity'];

        						$product_price = $_product->get_price();
        						$original_subtotal = $product_price * $quantity;;
        						$discounted_subtotal = $original_subtotal;

        						if ( WC()->cart->has_discount() ) 
        						{
						            $total_discount = 0;

						            // Loop through each applied coupon
						            foreach ( WC()->cart->get_coupons() as $code => $coupon ) {
						                
						                $coupon_obj = new WC_Coupon($code);// Get the coupon object

						                if ($coupon_obj->is_valid() && $coupon_obj->is_valid_for_product($_product)) 
						                {
						                    // Example: Flat discount
						                    $discount_amount = $coupon_obj->get_discount_amount($_product->get_price() * $quantity);
						                    //echo $discount_amount;
						                    $total_discount += $discount_amount;
						                }
						            }

						            // Calculate the new discounted subtotal
						            $discounted_subtotal = ($original_subtotal - $total_discount);

						            if($original_subtotal  > $discounted_subtotal)
						            {
						            	echo '<del><p class="coupon-original-price">$' . $original_subtotal . '</p></del>';
        								echo '<span class="woocommerce-Price-amount amount coupon-price">';
							        	echo '<bdi><span class="woocommerce-Price-currencySymbol">$</span>' . $discounted_subtotal . '</bdi>';
							        	echo '</span>';
						            }
						            else
						            {
										echo '<span class="woocommerce-Price-amount amount coupon-price">';
							        	echo '<bdi><span class="woocommerce-Price-currencySymbol">$</span>' . $original_subtotal . '</bdi>';
							        	echo '</span>';
						            }						            
						        }
						        else
						        {
						        	echo '<span class="woocommerce-Price-amount amount coupon-price">';
						        	echo '<bdi><span class="woocommerce-Price-currencySymbol">$</span>' . $original_subtotal . '</bdi>';
						        	echo '</span>';
						        }
							?>
						</td>
					</tr>
					<?php
				}

			}
		
			?>

			<?php do_action( 'woocommerce_cart_contents' );  ?>

			<tr>
				<td colspan="6" class="actions">

					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply coupon', 'woocommerce' ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>

					<button type="submit" class="button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</td>
			</tr>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>


	<?php do_action( 'woocommerce_after_cart_table' ); 

	//coupon

	if ( WC()->cart->has_discount() )
	{
		$auto_apply_code = get_field('auto_apply_coupon_code');

		if($auto_apply_code)
		{ 
			echo '<div class="coupon-explaination">';
			echo '<h5>A coupon(<b>' . $auto_apply_code . '</b>) is auto applied.</h5>';
			echo '<p>' . the_field('coupon_explanation') . '</p>';
			echo '</div>';
		}
		else
		{ 
			foreach ( WC()->cart->get_coupons() as $code => $coupon ) 
			{		                
				$coupon_obj = new WC_Coupon($code);
				$coupon_description = $coupon_obj->get_description();
				echo '<div class="coupon-explaination">';
				echo '<h5>A Coupon(<b>' . $code . '</b>) is applied.</h5>';
				echo '<p>' . $coupon_description . '</p>';
				echo '</div>';
			}
		} 
	}
	
	?>

</form>

	



<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
	<?php
		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
