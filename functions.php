<?php

//child theme setting
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);
function enqueue_child_theme_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

//add no-index
add_action('wp_head', 'add_noindex_for_filtered_pages');
function add_noindex_for_filtered_pages() 
{
    if (isset($_GET['mini-cart'])) 
    {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
    else if (isset($_GET['/?add-to-cart'])) 
    {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
    else if (isset($_GET['/cart'])) 
    {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
    else if(isset($_GET['avia_extended_shop_select'])) 
    {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
    else if (isset($_GET['quantity='])) 
    {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
    else if (isset($_GET['?yith_wcan='])) 
    {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
    else if (isset($_GET['wishlist/view'])) 
    {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
    else if (isset($_GET['feed'])) 
    {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
    else if (isset($_GET['?wpf=categories_filters'])) 
    {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
    else if (isset($_GET['product-category'])) 
    {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
    else if (isset($_GET['/?s='])) 
    {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
    else if (isset($_GET['/?filter'])) 
    {
        echo '<meta name="robots" content="noindex, nofollow" />';
    }
}

// Disable WooCommerce product feeds
add_action( 'init', function() {
    remove_action( 'do_feed_rss2', 'do_feed_rss2', 10, 1 );
    remove_action( 'do_feed_rss2_comments', 'do_feed_rss2_comments', 10, 1 );
    remove_action( 'do_feed_atom', 'do_feed_atom', 10, 1 );
    remove_action( 'do_feed_rdf', 'do_feed_rdf', 10, 1 );
    remove_action( 'do_feed_rss', 'do_feed_rss', 10, 1 );
});


//acf auto update post object field
add_action('acf/save_post', 'sync_acf_post_object', 20);
function sync_acf_post_object($post_id) 
{
    // Check if it's a product
    if (get_post_type($post_id) !== 'product') {
        return;
    }

    // Get the current post object field value (related products)
    $related_products = get_field('link_product_by_attribute', $post_id); 
    $actived_attribute = get_field('active_attribute', $post_id);

    // Initialize an array to hold all related product IDs, including the original product ID
    $all_related_ids = [$post_id];

    // collect all ids firstly
    if ($related_products) {
        foreach ($related_products as $related_product) 
        {
            if ($related_product->ID !== $post_id) 
            {
                $all_related_ids[] = $related_product->ID;
            }
        }
    }

    // Loop through each related product and update their fields
    if ($related_products) 
    {
        foreach ($related_products as $related_product) 
        {
            $current_loop_id = $related_product->ID;
            $updated_ids = array_diff($all_related_ids, [$current_loop_id]);

            // Update the related products field for the related product with new IDs only
            update_field('link_product_by_attribute', $updated_ids, $related_product->ID); 
            update_field('active_attribute', $actived_attribute, $related_product->ID); 
        }
    }
}

//load filter ajax file 
function enqueue_filter_scripts() {
    // Only enqueue on product archive pages
    if (is_product_category() || is_product_tag() || is_post_type_archive('product')) {
        wp_enqueue_script('ajax-filter', get_stylesheet_directory_uri() . '/js/ajax-filter.js', array('jquery'), null, true);
        
        // Localize the script with the ajaxurl
        wp_localize_script('ajax-filter', 'ajaxfilter', array('ajaxurl' => admin_url('admin-ajax.php')));
    }
}
add_action('wp_enqueue_scripts', 'enqueue_filter_scripts');

//load free sample ajax file 
function enqueue_free_sample_scripts() {
    wp_enqueue_script('sample-script', get_stylesheet_directory_uri() . '/js/ajax-free-sample.js', array('jquery'), null, true);
    wp_enqueue_script('generate-sample-script', get_stylesheet_directory_uri() . '/js/ajax-generate-sample-set.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_free_sample_scripts');


//fliter main function
function filter_products() {

    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array('relation' => 'AND'), // Default meta query
        'tax_query' => array('relation' => 'AND'), // Default tax query
        'orderby' => 'meta_value_num', // Sort by numeric value
        'order' => 'ASC', // Ascending order
        'meta_key' => '_price', // Specify the meta key for price
    );

    //Get current cate
    $currentParent = isset($_POST['current_parent']) ? sanitize_text_field($_POST['current_parent']) : '';
    $currentChild = isset($_POST['current_child']) ? sanitize_text_field($_POST['current_child']) : '';

    if($currentParent == 'colours')
    {
        $args['tax_query'][] = array(
            'taxonomy' => 'pa_colours', 
            'field' => 'slug',
            'terms' => $currentChild,
        );
    }
    else
    {
        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat', 
            'field' => 'slug',
            'terms' => $currentChild,
        );
    }
    
    // Handle price range
    if (!empty($_POST['price_range'])) {
        $prices = array_map('sanitize_text_field', $_POST['price_range']); // Sanitize the price range strings

        // Prepare meta query for price ranges
        $meta_query = array('relation' => 'OR');
        foreach ($prices as $price) {
            list($min_price, $max_price) = explode('-', $price);
            $meta_query[] = array(
                'key' => '_price',
                'value' => array($min_price, $max_price),
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC',
            );
        }
        $args['meta_query'] = array_merge($args['meta_query'], $meta_query);
    }
    // Handle size (subcategory)
    if (!empty($_POST['size'])) {
        $size = array_map('sanitize_text_field', $_POST['size']); // Sanitize the size values

        $args['tax_query'][] = array(
            'taxonomy' => 'product_cat', 
            'field' => 'id',
            'terms' => $size,
            'operator' => 'IN', // Use the 'IN' operator for multiple values
        );
    }

    // Handle color (multiple selections)
    if (!empty($_POST['color'])) {
        $color = array_map('sanitize_text_field', $_POST['color']); // Sanitize the color IDs

        $args['tax_query'][] = array(
            'taxonomy' => 'pa_colours', 
            'field' => 'term_id',
            'terms' => $color,
            'operator' => 'IN', // Use the 'IN' operator for multiple values
        );
    }

    $query = new WP_Query($args);

    if ($query->have_posts()) {
        ob_start(); // Start output buffering
        while ($query->have_posts()) : $query->the_post();
            wc_get_template_part('content', 'product');
        endwhile;
        $output = ob_get_clean(); 
        echo $output;
    } else {
        echo '<p>No products found</p>';
    }
    wp_die();
}
add_action('wp_ajax_filter_products', 'filter_products');
add_action('wp_ajax_nopriv_filter_products', 'filter_products');

//load sample list
function display_sample_list() {

    $sample_tile_product_id = get_field('free_sample_product', 29314);
    $tiles_in_one_set = get_field('tiles_in_one_set', 29314);

    ?>
    <div class="sample-list-header">
        <h2>Sample Tiles</h2>
        <p class="free-tile-explaination">1 set = 5 tile samples. Postage $15/ set (Australia-wide). If you order more than 5 tiles, it will be considered a 2nd sample set, incurring an additional postage fee.</p>
    </div>
    <div id="sample-list"></div>
    <script>
        jQuery(document).ready(function($) {
            // Function to update and render the sample list
            function updateSampleList() {
                let sampleList = JSON.parse(localStorage.getItem('sampleList')) || [];
                var sampleProductID = "<?php echo $sample_tile_product_id; ?>";
                var oneSet = "<?php echo $tiles_in_one_set; ?>";
                if (sampleList.length > 0) 
                {
                    let sampleTileQuantity = Math.ceil(sampleList.length / oneSet);
                    $('#sample-list').html('<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0"><tbody>' + sampleList.map((item, index) => 
                        '<tr class="woocommerce-cart-form__cart-item cart_item"><td class="product-remove"><span class="delete-icon remove" data-index="' + index + '">×</span></td><td class="product-thumbnail"><img src="' + item.thumbnail + '"></td><td class="product-name"><a href="' + item.permalink + '">' + item.name + ' </a></td></tr>').join('') + '</tbody></table><div id="free-sample-btn-container"><a href="/cart/?add-to-cart=' + sampleProductID + '&quantity=' + sampleTileQuantity + '" id="generate-tile-set" rel="nofollow">Add to cart</a></div>');
                }
                else
                {
                    const HeaderDIV = document.querySelector('.sample-list-header');
                    const ListDIV = document.querySelector('.free-sample-list');
                    HeaderDIV.classList.add('hide-this-area');
                    ListDIV.classList.add('hide-this-area');
                }
            }

            function refreshCart() {
                // Use AJAX to refresh the cart content  
                $.get('/?wc-ajax=get_refreshed_fragments', function(response) {
                    if (response && response.fragments) {
                        $.each(response.fragments, function(key, value) {
                            $(key).replaceWith(value);
                        });
                    }
                });
            }

            // Initial load of the sample list   
            updateSampleList();

            // Event delegation for delete icons
            $('#sample-list').on('click', '.delete-icon', function() {
                const index = $(this).data('index');
                let sampleList = JSON.parse(localStorage.getItem('sampleList')) || [];
                sampleList.splice(index, 1); // Remove item from array
                localStorage.setItem('sampleList', JSON.stringify(sampleList)); // Update localStorage
                updateSampleList(); // Re-render the list
            });
        });
    </script>
    <?php
}


// Add a textarea for sample tile name in checkout field
add_filter('woocommerce_checkout_fields', 'add_sample_tile_names_field');

function add_sample_tile_names_field($fields) {
    $fields['billing']['sample_tile_names_string'] = array(
        'type'        => 'textarea',
        'id'          => 'sample-tile-names-string',
        'label'       => __('Sample Tile Names'),
        'required'    => false,
    );
    return $fields;
}

//load sample tile names on checkout page 
function display_sample_list_for_checkout() {

    $sample_tile_product_id = get_field('free_sample_product', 29314);
    $tiles_in_one_set = get_field('tiles_in_one_set', 29314);

    ?>
    <textarea id="sample-tile-names-string"></textarea>
    <script>
        jQuery(document).ready(function($) {
            // Function to update and render the sample list
            function getSampleList() {
                let sampleList = JSON.parse(localStorage.getItem('sampleList')) || [];
                let passName = localStorage.getItem('passName') || '';
                $('#sample-tile-names-string').val(passName);
            }
            // Initial load of the sample list   
            getSampleList();

        });
    </script>
    <?php
}


//save sample tiles name to order's ACF field 
add_action('woocommerce_checkout_create_order', 'save_sample_tile_names_to_order', 10, 2);

function save_sample_tile_names_to_order($order, $data) {
    // Get the sample tile names from the posted data
    if (isset($_POST['sample_tile_names_string'])) {
        $sample_tile_names = sanitize_textarea_field($_POST['sample_tile_names_string']);
        
        // Check if the order contains the sample tile product
        $sample_tile_product_id = get_field('free_sample_product', 29314);
        $has_sample_tile_product = false;

        foreach ($order->get_items() as $item) {
            if ($item->get_product_id() == $sample_tile_product_id) {
                $has_sample_tile_product = true;
                break;
            }
        }

        // If the sample tile product is in the order, save the textarea value  
        if ($has_sample_tile_product) {
            $order->update_meta_data('sample_tiles', $sample_tile_names);
        } ?>

        <script>
        jQuery(document).ready(function($) {
            localStorage.clear();
        });
        </script>

    <?php }
}


//disable enfold extra divs & sorting function for WooCommerce pages  
add_action( 'init', 'remove_enfold_action' );

function remove_enfold_action() {
    remove_action( 'woocommerce_before_shop_loop_item_title', 'avia_shop_overview_extra_header_div', 20 );
    remove_action( 'woocommerce_after_shop_loop_item_title',  'avia_close_div', 1000 );
    remove_action( 'woocommerce_after_shop_loop_item_title',  'avia_close_div', 1001 );
    remove_action( 'woocommerce_after_shop_loop_item_title',  'avia_close_div', 1002 );
    //remove_action( 'woocommerce_before_shop_loop', 'avia_woocommerce_frontend_search_params', 20);
    remove_action( 'woocommerce_before_single_product_summary', 'avia_add_summary_div', 25 );
    remove_action( 'woocommerce_after_single_product_summary',  'avia_close_div', 3 );
}

// Add a custom column to the product categories list
add_filter('manage_edit-product_cat_columns', 'add_category_id_column');

function add_category_id_column($columns) {
    $columns['category_id'] = __('ID', 'your-text-domain'); // Adds the ID column
    return $columns;
}

// Populate the custom column with the category ID
add_action('manage_product_cat_custom_column', 'show_category_id_column', 10, 3);

function show_category_id_column($content, $column_name, $term_id) {
    if ($column_name === 'category_id') {
        return $term_id; // Return the category ID
    }
    return $content;
}

// Remove the product meta from single product page
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);

// Remove "Additional Information" tab from single product page
add_filter( 'woocommerce_product_tabs', 'remove_additional_information_tab', 98 );
function remove_additional_information_tab( $tabs ) {
    unset( $tabs['additional_information'] ); // Removes the additional information tab
    return $tabs;
}


// Show shipping class on checkout
add_action('woocommerce_review_order_before_shipping', 'display_shipping_classes_on_checkout');

function display_shipping_classes_on_checkout() {
    $shipping_methods = WC()->session->get('chosen_shipping_methods');
    $shipping_class = '';

    if ($shipping_methods) {
        foreach ($shipping_methods as $method) {
            $shipping_class = $method;
        }
    }

    if ($shipping_class) {
        echo '<div class="shipping-class-option">';
        echo '<h3>' . __('Shipping Class', 'your-text-domain') . '</h3>';
        echo '<p>' . esc_html($shipping_class) . '</p>';
        echo '</div>';
    }
}


// Setup the length of excerpt
add_filter('avf_postgrid_excerpt_length','avia_change_postgrid_excerpt_length', 10, 1);
function avia_change_postgrid_excerpt_length($length)
{
   $length = 100;
   return $length;
}

// Remove the sorting dropdown from the WooCommerce shop page
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

//auto apply coupon
add_action('woocommerce_before_cart', 'apply_coupon_automatically');
function apply_coupon_automatically() 
{  
    $coupon_code = get_field('auto_apply_coupon_code'); 

    if($coupon_code)
    {
        if ( ! WC()->cart->is_empty() && ! WC()->cart->has_discount( $coupon_code ) ) {
            WC()->cart->apply_coupon( $coupon_code );
        }
    }
}


//change the caps of backend menu - tools
function change_redirection_menu_capability() {

    global $submenu;
    
    if (isset($submenu['tools.php'])) {
        foreach ($submenu['tools.php'] as $key => $item) {
            $submenu['tools.php'][$key][1] = 'edit_shop_orders'; // Change capability
        }
    }
}
add_action('admin_menu', 'change_redirection_menu_capability', 999);

//enable lightbox for product gallery
function woocommerce_product_gallery_setup() 
{
    add_theme_support( 'wc-product-gallery-lightbox' );
}

add_action( 'after_setup_theme', 'woocommerce_product_gallery_setup' );


/*put sales price in front of original price 
add_filter('woocommerce_get_price_html', 'custom_price_display', 10, 2);

function custom_price_display($price, $product) {
    if ($product->is_on_sale()) 
    {
        $product_id = $product->get_id();
        $regular_price = $product->get_regular_price();
        $product_suffix = get_post_meta($product_id, '_advanced-qty-price-suffix', true);
        $sale_price = $product->get_sale_price();
        $price = '<span class="sale-price">' . wc_price($sale_price) . '' . $product_suffix . '</span> <del><span class="regular-price">' . wc_price($regular_price) . '</span></del>';
    }
    return $price;
}
*/

//add ACF field "size_mm" on order email
add_action('woocommerce_order_item_meta_end', 'add_custom_field_to_order_email', 10, 3);

function add_custom_field_to_order_email($item_id, $item, $order) {
    // Get the product ID from the order item
    $product_id = $item->get_product_id();

    // Get the ACF custom field value
    $size_mm = get_field('size_mm', $product_id);

    // Check if the field has a value
    if ($size_mm) {
        echo '<p><strong>Size:</strong> ' . esc_html($size_mm) . '</p>';
    }
}

//add ACF field "sample_tile" on order email
add_action( 'woocommerce_email_after_order_table', 'add_sample_tiles_to_email', 10, 4 );

function add_sample_tiles_to_email( $order, $sent_to_admin, $plain_text, $email ) {
    // Get the order ID
    $order_id = $order->get_id();

    // Retrieve the custom field value
    $sample_tiles = get_post_meta( $order_id, 'sample_tiles', true );

    // Display the custom field in the email
    if ( ! empty( $sample_tiles ) ) {
        echo '<h2>' . esc_html__( 'Sample Tiles', 'your-text-domain' ) . '</h2>';
        echo '<p>' . esc_html( $sample_tiles ) . '</p>';
    }
}


//Put Recipients into BCC list for new order email   
add_filter('woocommerce_email_headers', 'add_bcc_to_new_order_email', 10, 3);

function add_bcc_to_new_order_email($headers, $id, $object) {
    if ($id === 'new_order') {
        $bcc = 'sales@showtile.com.au, ken@showtile.com.au, marketing@showtile.com.au'; // Replace with your BCC email addresses
        $headers .= 'Bcc: ' . $bcc . "\r\n";
    }
    return $headers;
}


//Showing total product price below cart icon on header  
add_action('ava_main_header', 'custom_cart_info_in_header', 10);

function custom_cart_info_in_header() {
    
    if ( WC()->cart )
    {
        $cart_count = WC()->cart->get_cart_contents_count();
        $cart_total = WC()->cart->get_cart_total();
        echo '<a href="/cart" aria-label="shopping-cart"><div id="header-cart-detail-info">';
        echo ' <span class="cart-total">' . wp_kses_post($cart_total) . '</span>';
        echo '</div></a>';
    }
    else
    {
        // If cart is not available, display a default message or empty state
        echo '<a href="/cart"><div id="header-cart-detail-info">';
        echo ' <span class="cart-total">0.00</span>';
        echo '</div></a>';
    }
    
}

//Showing a woocommerce style notification on single product page after adding a sample      
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

function enqueue_custom_scripts() {
    if (is_product()) {
        wp_enqueue_script('custom-notification', get_stylesheet_directory_uri() . '/js/add-sample-notification.js', array('jquery'), null, true);
        wp_localize_script('custom-notification', 'customData', array(
            'productName' => get_the_title(),
        ));
    }
}


//load external review(GTO Review)
function get_product_reviews($product_id) {
    global $wpdb;

    $table_name = $wpdb->prefix . 'gto_review';

    // Prepare the SQL query
    $query = $wpdb->prepare(
        "SELECT * FROM $table_name WHERE post_id = %d ORDER BY review_date DESC",
        $product_id
    );

    // Fetch the results
    $reviews = $wpdb->get_results($query);

    return $reviews;
}
