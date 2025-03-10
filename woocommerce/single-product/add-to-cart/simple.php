<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

//load SQM

$product_id = $product->get_id();
$product_name = $product->get_name();
$product_permalink = get_permalink($product_id);
$product_thumbnail = wp_get_attachment_image_src($product->get_image_id(), 'thumbnail');
$if_allow_sample = esc_html (get_field('sample_available_check', $product_id));

$step_value = get_post_meta($product_id, '_advanced-qty-step', true); 
$box_price = 0;
$product_suffix = get_post_meta($product_id, '_advanced-qty-price-suffix', true); 

$m2_price = get_post_meta($product_id, '_sale_price', true); 

if (empty($m2_price)) {
	$m2_price = get_post_meta($product_id, '_regular_price', true); 
}


if (empty($step_value)) {
	$step_value = 1; // Default step value
	$box_price = $m2_price;
}
else
{
	$step_value = get_post_meta($product_id, '_advanced-qty-step', true); 
	$box_price = $step_value * $m2_price;
}

?>

<script>
	jQuery(document).ready(function($) {
	    var boxSize = <?php echo json_encode($step_value); ?>;
	    var boxprice = <?php echo json_encode($box_price); ?>; 
	    var suffix = <?php echo json_encode($product_suffix); ?>; 

	    const squareMetersInput = document.getElementById('square-meter-needed');	    

	    $('#square-meter-needed').on('input', function() 
	    {
	        var squareMeters = parseFloat($(this).val());
	        var boxesNeeded = Math.ceil(squareMeters / boxSize);
	        var actualsquareMeters = (boxSize * boxesNeeded).toFixed(2);
	        var totalPrice = (boxesNeeded * boxprice).toFixed(2);
	        $('input[name="quantity"]').val(squareMeters);

	        if(boxesNeeded == 0 )
	        {
	        	$('.boxes-explaination').text( boxSize + ' m2 per box');
	        	$('input[name="quantity"]').val('0');
	        	$('#output-total-price').text('$0');
	        }
	        else if(boxesNeeded == 1)
	        {
	        	$('.boxes-explaination').text( 'Round to ' + actualsquareMeters + ' m2 (' + boxesNeeded + ' box)');
	        	$('input[name="quantity"]').val(actualsquareMeters);
	        	$('#output-total-price').text('$' + totalPrice);
	        }
	        else if(boxesNeeded > 1)
	        {
	        	$('.boxes-explaination').text( 'Round to ' + actualsquareMeters + ' m2 (' + boxesNeeded + ' boxes)');
	        	$('input[name="quantity"]').val(actualsquareMeters);
	        	$('#output-total-price').text('$' + totalPrice);
	        }
	        else
	        {
	        	$('.boxes-explaination').text( boxSize + ' m2 per box');
	        	$('input[name="quantity"]').val('0');
	        	$('#output-total-price').text('$0');
	        }

	    });
	});
</script>


<?php if($product_suffix == "/m2" || $product_suffix == "/ m2" || $product_suffix == "m2"){ ?>

	<div id="tile-box-calculator-container">

		<div class="tile-box-calculator-inner grid-view">

			<div class="tile-box-calculator-qty">
				<h5>Required quantity:</h5>

				<div class="m2-quantity">
					<input type="number" id="square-meter-needed" min="0" max="999" step="<?php echo $step_value ?>" placeholder="0" />
					<p>m2</p>
				</div>

				<p class="boxes-explaination"> (<?php echo $step_value; ?> m2 per box)</p>
			</div>

			<div class="tile-box-total-price-container">
				<p id="output-total-price-label">Total Price:</p> 
				<h3 id="output-total-price">0</h3>
			</div>

			<div class="tile-box-calculator-btn">
				<?php

				$backorder_status;

				if ( method_exists( $product, 'get_stock_status' ) ) 
				{
		            $backorder_status = $product->get_stock_status(); // For version 3.0+
		        } else 
		        {
		            $backorder_status = $product->stock_status; // Older than version 3.0
		        }

		        echo '<p class="hide-this-area">' . $backorder_status . '</p>';

				if ($backorder_status === 'onbackorder') 
				{
					echo '<p class="tile-box-backorder">Please Contact Us For Backorder.</p>';
				}
				else
				{
					if ( $product->is_in_stock() )
					{
						do_action( 'woocommerce_before_add_to_cart_form' ); ?>

						<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
							<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

							<?php
							do_action( 'woocommerce_before_add_to_cart_quantity' );

							woocommerce_quantity_input(
								array(
									'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
									'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
									'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
								)
							);

							do_action( 'woocommerce_after_add_to_cart_quantity' );

							?>

							<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

							<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

						</form>

					<?php }else{

						echo '<p class="tile-box-out-of-stock">Out Of Stock</p>'; 
					}

				}

				?>

			</div>

		</div>	

		<div class="single-product-sample-container grid-view">

			<div class = "sample-delivery-explaination">

				<p>Sample size is 100x100mm and the sample is free.</p>
				<p>Only postage is $15 per one set, Australia wide.</p>
				<p>In one set of sample, you can order up to 5 different tiles.</p>

			</div>

			<?php if($if_allow_sample != "No"){ ?>

				<div class="sample-delivery-btn">
					<button class="sample-button" id="sample-button-notification" data-product-name="<?php echo $product_name; ?>" data-product-thumbnail="<?php echo esc_url($product_thumbnail[0]); ?>" data-product-permalink="<?php echo esc_url($product_permalink); ?>" rel="nofollow">Get Free Sample</button>
				</div> 
				
			<?php } ?>

		</div>

	</div>

<?php }else{ 

	$product_suffix = str_replace("/", "", $product_suffix);

	?>

	<div id="tile-box-calculator-container">

		<div class="tile-box-calculator-inner grid-view">

			<div class="tile-box-calculator-qty">
				<h5>Required quantity:</h5>

				<div class="m2-quantity">
					<input type="number" id="square-meter-needed" min="0" max="999" step="<?php echo $step_value ?>" placeholder="0" />
					<p><?php echo $product_suffix ?></p>
				</div>
			</div>

			<div class="tile-box-total-price-container">
				<p id="output-total-price-label">Total Price:</p> 
				<h3 id="output-total-price">0</h3>
			</div>

			<div class="tile-box-calculator-btn">
				<?php

				$backorder_status;

				if ( method_exists( $product, 'get_stock_status' ) ) 
				{
		            $backorder_status = $product->get_stock_status(); // For version 3.0+
		        } 
		        else 
		        {
		            $backorder_status = $product->stock_status; // Older than version 3.0
		        }

				if ($backorder_status === 'onbackorder') 
				{
					echo '<p class="tile-box-backorder">Please Contact Us For Backorder.</p>';
				}
				else
				{
					if ( $product->is_in_stock() )
					{
						do_action( 'woocommerce_before_add_to_cart_form' ); ?>

						<form class="cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data'>
							<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

							<?php
							do_action( 'woocommerce_before_add_to_cart_quantity' );

							woocommerce_quantity_input(
								array(
									'min_value'   => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
									'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
									'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
								)
							);

							do_action( 'woocommerce_after_add_to_cart_quantity' );
							?>

							<button type="submit" name="add-to-cart" rel="nofollow" value="<?php echo esc_attr( $product->get_id() ); ?>" class="single_add_to_cart_button button alt<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>

							<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

						</form>

					<?php 
					}else
					{
						echo '<p class="tile-box-out-of-stock">Out Of Stock</p>'; 
					}
				}

				?>

			</div>

		</div>	

		<div class="single-product-sample-container grid-view">

			<div class = "sample-delivery-explaination">

				<p>Sample size is 100x100mm and the sample is free.</p>
				<p>Only postage is $15 per one set, Australia wide.</p>
				<p>In one set of sample, you can order up to 5 different tiles.</p>

			</div>

			<?php if($if_allow_sample != "No"){ ?>

				<div class="sample-delivery-btn">
					<button class="sample-button" id="sample-button-notification" data-product-name="<?php echo $product_name; ?>" data-product-thumbnail="<?php echo esc_url($product_thumbnail[0]); ?>" data-product-permalink="<?php echo esc_url($product_permalink); ?>" rel="nofollow">Get Free Sample</button>
				</div> 
				
			<?php } ?>

		</div>

	</div>

<?php }

echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>

