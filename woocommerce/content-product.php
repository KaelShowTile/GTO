<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if ( ! is_a( $product, WC_Product::class ) || ! $product->is_visible() ) {
	return;
}
?>
<li <?php wc_product_class( '', $product ); ?>>
	<?php
	/**
	 * Hook: woocommerce_before_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item' );

	if ( $product->is_on_sale() ) 
	{
	    $regular_price = $product->get_regular_price();
        $sale_price = $product->get_sale_price();
        $percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
	    echo '<span class="product-discount-rate"> '. $percentage .'% OFF</span>'; 
	}

	/**
	 * Hook: woocommerce_before_shop_loop_item_title.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item_title' );

	/**
	 * Hook: woocommerce_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 * 
	 * do_action( 'woocommerce_shop_loop_item_title' );
	 */
	
	//split tile and code
	$full_title = get_the_title();
	$firstPart = $full_title;
	$delimiter;
	$product_code = " ";

	if (strpos($full_title, "(Code:") !== false)
	{
		$delimiter = "(Code:";
	}
	else if(strpos($full_title, "(code:") !== false)
	{
		$delimiter = "(code:";
	}
	else if(strpos($full_title, "(code :") !== false)
	{
		$delimiter = "(code :";
	}
	else if(strpos($full_title, "(Code :") !== false)
	{
		$delimiter = "(Code :";
	}

	if($delimiter !== null)
	{
		$title_parts = explode($delimiter, $full_title, 2); // Limit to 2 parts
		$firstPart = $title_parts[0];
		$product_code = str_replace($firstPart, "", $full_title);
		$product_code = str_replace($delimiter, "", $product_code);
		$product_code = str_replace(")", "", $product_code);
	}
	
	$product_url = get_permalink($product->get_id());

	//get ranking
	$rating_score = get_field('tile_rate');
	if( !$rating_score ) 
	{
		$rating_score = 5;
	} 
	
	$full_stars = floor($rating_score);
	$half_star = ($rating_score - $full_stars) >= 0.5 ? 1 : 0; 
	$empty_stars = 5 - $full_stars - $half_star;

	// get categories
	$product_id = $product->get_id();
	$terms = get_the_terms($product->get_id(), 'product_cat');
	$categories = array();

	if ($terms && !is_wp_error($terms)) 
	{    
	    // Organize categories by parent
	    foreach ($terms as $term) {
	        $categories[$term->parent][] = $term;
	    }
	}

	?>

	<div class="woocommerce-catepage-product-title">

		<a href="<?php echo $product_url ?>" class="woocommerce-catepage-product-title-link">
			<h2><?php echo $firstPart; ?></h2>
		</a>

		<div class="woocommerce-LoopProduct-meta">

			<div class="rating-stars-container cate-page-rating">

			    <?php for ($i = 1; $i <= $full_stars; $i++): ?>
			        <span class="rating-stars filled">&starf;</span> <!-- Full star -->
			    <?php endfor; ?>
			    
			    <?php if ($half_star): ?>
			        <span class="rating-stars half">&starf;</span> <!-- Half star -->
			    <?php endif; ?>
			    
			    <?php for ($i = 1; $i <= $empty_stars; $i++): ?>
			        <span class="rating-stars">&starf;</span> <!-- Empty star -->
			    <?php endfor; ?>

			</div>
			
			<div class="product_title_size">

				<?php 

					$if_is_swimming_pool_wall_cladding = 0;

					if ( has_term( 'Swimming Pool Tiles', 'product_cat', $product->get_id() ) )
					{
						$if_is_swimming_pool_wall_cladding = 1;
						echo '<a class="child-categories-item" href="/area/swimming-pool-tiles/">Swimming Pool Tiles</a>';
					}

					if ( has_term( 'Wall Cladding Tiles', 'product_cat', $product->get_id() ) )
					{
						$if_is_swimming_pool_wall_cladding = 1;
						echo '<a class="child-categories-item" href="/area/wall-cladding/">Wall Cladding Tiles</a>';
					}

					if($if_is_swimming_pool_wall_cladding == 0)
					{
						if (isset($categories[0]))
						{
							foreach ($categories[0] as $parent_category) 
							{						
								$parent_category_name = $parent_category->name;
								$parent_category_id = $parent_category->term_id;
								
								if($parent_category_name == "Size")
					        	{  
					        		if (isset($categories[$parent_category_id])) 
						        	{
						        		$if_has_size_category = 1;

						        		$cat_count = 0;

						        		foreach ($categories[$parent_category_id] as $child_category)
						        		{ 
						        			$child_category_url = get_term_link($child_category->term_id, 'product_cat');

						        			if ($cat_count !== 0)
						        			{
						        				echo ', ';
						        			}  

						        			echo '<a class="child-categories-item" href="' . $child_category_url . '">';
						        			echo esc_html($child_category->name);
						        			echo '</a>';

						        			$cat_count = $cat_count + 1;
						        		}

						        	}  
						        }
							}

						}
					}
				?>

			</div>

			<p class="product_title_code">Code: <?php echo $product_code; ?></p>

		</div>

	</div>

	<?php

	/**
	 * Hook: woocommerce_after_shop_loop_item_title.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */

	echo '<div class="woocommerce-LoopProduct-bottom-container">';
	
	echo '<div class="woocommerce-LoopProduct-price-container">';
	do_action( 'woocommerce_after_shop_loop_item_title' );
	echo '</div>';

	echo '<div class="woocommerce-LoopProduct-cart-btn">';
	echo '<a href="'. esc_url( $product->add_to_cart_url() ) .'" class="category-add-cart-btn" rel="nofollow" data-product_id="' . esc_attr( $product->get_id() ) . '" aria-label="' . esc_attr( $product->add_to_cart_description() ) . '">' . esc_html( $product->add_to_cart_text() ) . '</a>';
	echo '</div>';

	echo '</div>';

	//do_action( 'woocommerce_after_shop_loop_item_title' );

	/**
	 * Hook: woocommerce_after_shop_loop_item.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
