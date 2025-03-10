<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$check_product_type;
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

<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?></p>

<?php if($check_product_type == 1) 
{ 

	$m2_price = get_post_meta($product->get_id(), '_sale_price', true); 
	if (empty($m2_price)) {
		$m2_price = get_post_meta($product->get_id(), '_regular_price', true); 
	}

	$step_value = get_post_meta($product->get_id(), '_advanced-qty-step', true); 
	if (empty($step_value)) 
    {
		$step_value = 0;
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
    $step_value = get_post_meta($product->get_id(), '_advanced-qty-step', true);

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
        if ($variation_price == $lowest_price) {
            $variation_step_value = get_post_meta($variation_id, '_advanced-qty-step', true);
            if (empty($variation_step_value)) {
                $lowest_box_price = $lowest_price * $step_value;
			}
            else
            {
                $lowest_box_price = $lowest_price * $variation_step_value;
            }
        }

        // Check if it matches the highest price
        if ($variation_price == $highest_price) {
            $variation_step_value = get_post_meta($variation_id, '_advanced-qty-step', true);
            if (empty($variation_step_value)) {

                $highest_box_price = $highest_price * $step_value;
			}
            else
            {
                $highest_box_price = $highest_price * $variation_step_value;
            }
        }

        //check if lowest price equal highest price
        if($lowest_box_price == $highest_box_price)
        {
        	$box_price = $lowest_box_price;

        }else
        {
        	$box_price = $lowest_box_price . ' ~ ' . $highest_box_price;
        }

    }
}
else
{

}

?>

<?php 
//if box rate has not been setup
if($product_suffix == "/m2" || $product_suffix == "/ m2" || $product_suffix == "m2"){ ?>

    <div class="box-price-container">
        <p>= $<?php echo $box_price; ?>/box</p>
    </div>

<?php }else{  

} 

if ( $product->is_on_sale() ) 
{
    $regular_price = $product->get_regular_price();
    $sale_price = $product->get_sale_price();
    $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
    echo '<span class="product-discount-rate"> '. $percentage .'% OFF</span>'; 
}

?>

<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/round-price-single.js"></script>







