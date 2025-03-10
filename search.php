<?php
get_header(); 

// Check if it's a search query
if (is_search()) {
    // Modify the main query to only return products
    add_action('pre_get_posts', 'filter_product_search');

    function filter_product_search($query) {
        if ($query->is_search() && !is_admin()) {
            $query->set('post_type', 'product'); // Only search products
        }
    }
}

?>


<div class="container search-result-container">

    <?php if (have_posts()) : ?>
        <header class="woocommerce-products-header">
            <h1 class="woocommerce-products-header__title"><?php printf(__('Search Results for: %s', 'woocommerce'),  get_search_query() ); ?></h1>
        </header>

        <?php woocommerce_product_loop_start(); ?>

        <?php while (have_posts()) : the_post(); ?>
            <?php
            // Output product content
            wc_get_template_part('content', 'product');
            ?>
        <?php endwhile; ?>

        <?php woocommerce_product_loop_end(); ?>

        <?php
        // Pagination
        the_posts_pagination();
        ?>
    <?php else : ?>
        <p><?php _e('No products found matching your search criteria.', 'woocommerce'); ?></p>
    <?php endif; ?>

</div>

<?php
get_footer();