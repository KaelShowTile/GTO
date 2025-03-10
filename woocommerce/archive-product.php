<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header( 'shop' );

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */

?>

<div id="fliter-loading-icon" style="display: none;">
    <img src="/wp-content/uploads/2025/01/system-regular-719-spinner-circle-loop-snake-resize.gif" alt="Loading..." />
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/js/round-price.js"></script>
</div>

<?php 


do_action( 'woocommerce_before_main_content' ); 

/**
 * Hook: woocommerce_shop_loop_header.
 *
 * @since 8.6.0
 *
 * @hooked woocommerce_product_taxonomy_archive_header - 10   
 */

woocommerce_show_messages();

do_action( 'woocommerce_shop_loop_header' );


if ( woocommerce_product_loop() ) {

	/**
	 * Hook: woocommerce_before_shop_loop.
	 *
	 * @hooked woocommerce_output_all_notices - 10
	 * @hooked woocommerce_result_count - 20
	 * @hooked woocommerce_catalog_ordering - 30
	 */
	//do_action( 'woocommerce_before_shop_loop' );

	$current_term = get_queried_object();

	// Check if it's a valid term and display the description
	if ($current_term && !is_wp_error($current_term)) {
	    // Output the term description
	    echo '<div class="taxonomy-description">';
	    echo '<p>' .wp_kses_post($current_term->description) . '</p>';
	    echo '</div>';
	}

	//load filter data from setting page
	$search_colors = get_field('colors_filter', 28905);
	$search_sizes = get_field('size_filter', 28905);
	$search_price_row = get_field('price_range', 28905);

	// Get current category or attribute
    $current_url = (isset($_SERVER['HTTPS']) ? "https://" : "http://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $path = parse_url($current_url, PHP_URL_PATH);
    $segments = explode('/', trim($path, '/'));
    $current_cate;
    $current_attr;
    $parent_category_slug = 'categroy';

    if (count($segments) == 1)
    {
        $current_cate = $segments[count($segments) - 1];
        $parent_category_slug = $current_cate;
    }
    elseif (count($segments) == 2)
    {
        $parent_category_slug = $segments[count($segments) - 2];
        $current_cate = $segments[count($segments) - 1];
    }

    //Create filters
	echo'<div class = "gto-custom-product-filter">';

	if($search_price_row)
	{
		echo '<div class="archive-fliter-dropdown">';

			echo '<button class="archive-fliter-dropdown-btn">Price</button>';

			echo '<div class="archive-fliter-dropdown-content">';

				foreach( $search_price_row as $price )
				{
					echo '<label><input type="checkbox" id="fliter-price-option" value="' . $price['minimum_price'] . '-' . $price['maximum_price'] . ' ">$' . $price['minimum_price'] . ' - $' . $price['maximum_price'] . ' </label>';
				}	

			echo '</div>';

		echo '</div>';
	}

	if($search_sizes)
	{
		if($parent_category_slug == 'size')
		{
			echo '<div class="archive-fliter-dropdown hide-this-area">';
		}
		else
		{
			echo '<div class="archive-fliter-dropdown">';
		}
				echo '<button class="archive-fliter-dropdown-btn">Size</button>';

				echo '<div class="archive-fliter-dropdown-content">';

					foreach( $search_sizes as $size )
					{
						echo '<label><input type="checkbox" id="fliter-size-option" value="' . esc_attr($size->term_id) . '">' . esc_html($size->name) . '</label>';
					}	

				echo '</div>';

			echo '</div>';
	}

	if($search_colors)
	{
		
		if($parent_category_slug == 'colours')
		{
			echo '<div class="archive-fliter-dropdown hide-this-area">';
		}
		else
		{
			echo '<div class="archive-fliter-dropdown">';
		}
				echo '<button class="archive-fliter-dropdown-btn">Colour</button>';

				echo '<div class="archive-fliter-dropdown-content">';

					foreach( $search_colors as $color )
					{
						echo '<label><input type="checkbox" id="fliter-color-option" value="' . esc_attr($color->term_id) . '">' .esc_html($color->name) . '</label>';
					}

				echo '</div>';	

			echo '</div>';
	}

		echo '<div class="archive-fliter-dropdown hide-this-area">';

			echo '<p id="current-parent-slug">' . $parent_category_slug . '<p>';
			
			if($current_cate)
			{
				echo '<p id="current-child-slug">' . $current_cate . '<p>';
			}
			
		echo '</div>';

	echo '</div>';

	echo '<div id = "product-list">';

	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();

			/**
			 * Hook: woocommerce_shop_loop.
			 */
			do_action( 'woocommerce_shop_loop' );

			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

	echo '</div>';

	/**
	 * Hook: woocommerce_after_shop_loop.
	 *
	 * @hooked woocommerce_pagination - 10
	 */
	do_action( 'woocommerce_after_shop_loop' );

	if ( ! is_tax( 'product_cat' ) ) { ?>
		
		<script type="text/javascript">
		    var ajaxfilter = {
		        ajaxurl: "<?php echo admin_url('admin-ajax.php'); ?>"
		    };
		</script>
		<script type="text/javascript" src="https://www.gettilesonline.com.au/wp-content/themes/enfold-child/js/ajax-filter.js" id="ajax-filter-js"></script>

		<?php

		$current_category = get_queried_object();
    
	    // Get the custom field value
	    $below_category_content = get_field('below_category_content', 'product_cat_' . $current_category->term_id);
	    
	    // Check if the custom field has a value and display it
	    if ($below_category_content) 
	    {
	        echo '<div class="container">';
		        echo '<div class="below-category-content">';
		        echo $below_category_content;
		        echo '</div>';
	        echo '</div>';
	    }
	}
	else
	{
		$current_category = get_queried_object();
    
	    // Get the custom field value
	    $below_category_content = get_field('below_category_content', 'product_cat_' . $current_category->term_id);
	    
	    // Check if the custom field has a value and display it
	    if ($below_category_content) 
	    {
	        echo '<div class="container">';
		        echo '<div class="below-category-content">';
		        echo $below_category_content;
		        echo '</div>';
	        echo '</div>';
	    }
	}

} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}


/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'woocommerce_after_main_content' );

/**
 * Hook: woocommerce_sidebar.
 *
 * @hooked woocommerce_get_sidebar - 10
 */
do_action( 'woocommerce_sidebar' );

get_footer( 'shop' );
