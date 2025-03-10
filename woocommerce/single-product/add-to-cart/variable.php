<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

$check_product_type;
$if_prices_differnt = 0;
$box_price = 0;
$step_value = 0;
$product_suffix = get_post_meta($product->get_id(), '_advanced-qty-price-suffix', true); 

if ($product->is_type('simple')) 
{
    $check_product_type = 1;
} 
elseif ($product->is_type('variable')) 
{
    $check_product_type = 2;
} 
else 
{
    $check_product_type = 0;
}

?>


<?php if($check_product_type == 1) 
{ 

	$m2_price = get_post_meta($product->get_id(), '_sale_price', true); 
	if (empty($m2_price)) {
		$m2_price = get_post_meta($product->get_id(), '_regular_price', true); 
	}

	$step_value = get_post_meta($product->get_id(), '_advanced-qty-step', true); 
	if (empty($step_value)) 
    {
		$step_value = 1;
        $box_price = $m2_price;
	}
    else
    {
        $box_price = $step_value * $m2_price;
    }

}
else if ($check_product_type == 2) 
{
	$prices = $product->get_variation_prices();

    // Extract the prices array
    $variation_prices = $prices['price'];

    // Get the highest and lowest prices
    $lowest_price = min($variation_prices);
    $highest_price = max($variation_prices);

    $variation_ids = $product->get_children();

    // Initialize variables for storing the custom meta
    $lowest_box_price = null;
    $highest_box_price = null;

    foreach ($variation_ids as $variation_id) {
        // Get the price of the current variation
        $variation_price = get_post_meta($variation_id, '_price', true);
        
        // Check if it matches the lowest price
        if ($variation_price == $lowest_price) 
        {
            $step_value = get_post_meta($variation_id, '_advanced-qty-step', true);
            if (empty($step_value)) 
            {
				$step_value = 0;
                $lowest_box_price = $lowest_price;
			}
            else
            {
                $lowest_box_price = $lowest_price * $step_value;
            }

			
        }

        // Check if it matches the highest price
        if ($variation_price == $highest_price) 
        {
            $step_value = get_post_meta($variation_id, '_advanced-qty-step', true);
            if (empty($step_value)) 
            {
				$step_value = 0; 
                $highest_box_price = $highest_price;
			}
            else
            {
                $highest_box_price = $highest_price * $step_value;
            }

			
        }

        //check if lowest price equal highest price
        if($lowest_box_price == $highest_box_price)
        {
        	$box_price = $lowest_box_price;

        }else
        {
        	$if_prices_differnt = 1;
        	$box_price = $lowest_box_price . ' ~ ' . $highest_box_price;
        }

    }
}
else
{

}



do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="variations_form cart" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo $variations_attr; // WPCS: XSS ok. ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); ?></p>
	<?php else : ?>
		<table class="variations" cellspacing="0" role="presentation">
			<tbody>
				<?php foreach ( $attributes as $attribute_name => $options ) : ?>
					<tr>
						<th class="label"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></th>
						<td class="value">
							<?php
								wc_dropdown_variation_attribute_options(
									array(
										'options'   => $options,
										'attribute' => $attribute_name,
										'product'   => $product,
									)
								);
								echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
							?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php do_action( 'woocommerce_after_variations_table' ); ?>

		<div class="single_variation_wrap">

			<div id="tile-box-calculator-container">

				<?php
					$step_value = get_post_meta($product->get_id(), '_advanced-qty-step', true); 

					if ($product->is_type('variable')) 
					{
				    // Get all variation IDs
				    $variation_ids = $product->get_children();



				    foreach ($variation_ids as $variation_id) 
				    { 
				        $advanced_variation_name = get_post_meta($variation_id, 'attribute_pa_size', true);
				        
				        // Debug output: Check if the value is retrieved
				        if ($step_value) {
				            echo '<div class="variation-qty-step" data-variation-id="' . esc_attr($advanced_variation_name) . '" >'; ?>

								<p class="boxes-explaination" style="display: none;">(<span id="box-quantity-<?php echo $advanced_variation_name ?>"><?php echo $step_value; ?></span> m2 per box)</p>

				            <?php

				            echo '</div>';
				        } 
				        else 
				        {

				        }
				    }
				}

				if($product_suffix == "/m2" || $product_suffix == "/ m2" || $product_suffix == "m2"){ ?>

					<h5>Required quantity:</h5>

					<div class="m2-quantity">
						<input type="number" id="square-meter-needed" min="0" max="999" step="<?php echo $step_value ?>" placeholder="0" />
						<p>m2</p>
					</div>

					<p class="boxes-explaination"> (<?php echo $step_value; ?> m2 per box)</p>
					<p id="output-total-price-label">Total Price:</p> 
					<h3 id="output-total-price">0</h3>

				</div>

				<?php }else{ 

					$product_suffix = str_replace("/", "", $product_suffix);

					?>

					<h5>Required quantity:</h5>

					<div class="m2-quantity">
						<input type="number" id="square-meter-needed" min="0" max="999" step="<?php echo $step_value ?>" placeholder="0" />
						<p><?php echo $product_suffix ?></p>
					</div>

					<p id="output-total-price-label">Total Price:</p> 
					<h3 id="output-total-price">0</h3>

				</div>

				<?php }

				/**
				 * Hook: woocommerce_before_single_variation.
				 */
				do_action( 'woocommerce_before_single_variation' );?>

				<?php
				/**
				 * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
				 *
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * Hook: woocommerce_after_single_variation.
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>
	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<script type="text/javascript">

	jQuery(document).ready(function($) {
	    // Initially hide all qty steps
	    $('.variation-qty-step').hide();

	    // Listen for change on the variation select dropdowns
	    $('form.variations_form').on('change', 'select', function() {
	        // Get the selected variation name
	        var selectedVariationId = $(this).val(); // This gets the value of the selected option, attribute_pa_size

	        // Hide all qty steps
	        $('.variation-qty-step').hide();
	        
	        // Show the qty step for the selected variation
	        if (selectedVariationId ) {
	            $('.variation-qty-step[data-variation-id="' + selectedVariationId + '"]').show();
	        }
	    });
	});

	jQuery(document).ready(function($) {
	    
		var ifpricediff = <?php echo json_encode($if_prices_differnt); ?>; // Pass the PHP variable to JavaScript
		var boxSize = <?php echo json_encode($step_value); ?>; // Pass the PHP variable to JavaScript
	    var boxprice = 0;
	    var suffix = <?php echo json_encode($product_suffix); ?>; 

	    if (ifpricediff == 0) 
	    {
	    	boxprice = <?php echo json_encode($box_price); ?>; // Pass the PHP variable to JavaScript
	    }
	    else if(ifpricediff == 1) 
	    {
			$('select').on('change', function() 
			{
		        setTimeout(() => {
			        const GetCurrentPrice = $('.woocommerce-variation-price .price .woocommerce-Price-amount bdi').text();
			    	boxprice = GetCurrentPrice.replace('$', '').trim();

			    	const currentquntity = parseFloat($('#square-meter-needed').val()) || 0;
			    	if(currentquntity != 0)
			    	{
			    		var changeTotalPrice = (boxprice * currentquntity).toFixed(2);
			    		$('#output-total-price').text('$ ' + changeTotalPrice );
			    	}

			    },100)
		    });
	    }
	    else
	    {

	    }

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

<?php


