<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( ! empty( $product_tabs ) ) : ?>

	<script type="text/javascript">
		
		document.addEventListener('DOMContentLoaded', function() {
		    // Function to switch tabs
		    function showTab(tabId) {
		        const tabs = document.querySelectorAll('.woocommerce-Tabs-panel'); // Select all tab panels
		        const tabListItems = document.querySelectorAll('.tablist-item'); // Select all tab list items

		        tabs.forEach(tab => {
		            tab.style.display = 'none'; // Hide all tab panels
		        });

		        const selectedTab = document.getElementById(tabId);
		        if (selectedTab) {
		            selectedTab.style.display = 'block'; // Show selected tab
		        }

		        // Remove 'active' class from all tab list items
		        tabListItems.forEach(item => {
		            item.classList.remove('active'); // Remove 'active' class
		        });

		        // Add 'active' class to the currently selected tab item
		        const activeTabItem = document.querySelector(`.tablist-item a[href="#${tabId}"]`).parentElement;
		        if (activeTabItem) {
		            activeTabItem.classList.add('active'); // Add 'active' class to the selected tab item
		        }
		    }

		    // Event listener for tab links
		    document.querySelectorAll('.tablist-item a').forEach(link => {
		        link.addEventListener('click', function(e) {
		            e.preventDefault(); // Prevent default anchor behavior
		            const tabId = this.getAttribute('href').substring(1); // Remove the '#' to get the ID
		            showTab(tabId);
		        });
		    });

		    // Scroll to target when the Tile Calculator button is clicked
		    document.querySelectorAll('#go-to-tile-calculator').forEach(anchor => {
		        anchor.addEventListener('click', function(e) {
		            e.preventDefault(); // Prevent default anchor behavior
		            const targetId = 'product-page-tile-calculator-container'; // Get target ID
		            const tabId = 'tile-calculator-tab'; // ID of the tab containing the target div
		            
		            // Show the tab containing the target div
		            showTab('tile-calculator-tab-control'); // Ensure this matches the correct tab ID
		            
		            // Scroll to the target div after a short delay to ensure the tab is displayed
		            setTimeout(() => {
		                const targetElement = document.getElementById(targetId);
		                if (targetElement) {
		                    targetElement.scrollIntoView({ behavior: 'smooth' });
		                }
		            }, 100); // Delay for content to render
		        });
		    });

		    // Optionally, show the first tab by default
		    showTab('tab-0'); // Change this to the ID of your default tab
		});

	</script>


	<div class="woocommerce-tabs wc-tabs-wrapper">
		<div class="wc-product-tabs-container">
			<ul class="tabs wc-tabs" role="tablist">
				<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
					<li class="tablist-item <?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
						<a href="#tab-<?php echo esc_attr( $key ); ?>" class="tablist-item">
							<?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
						</a>
					</li>
				<?php endforeach; ?>

					<li class="tablist-item" id="tile-calculator-tab" role="tab" aria-controls="tile-calculator-tab-control" >
						<a href="#tile-calculator-tab-control">Tile Calculator</a>
					</li>

					<li class="tablist-item" id="delivery-pickup-tab" role="tab" aria-controls="delivery-pickup-tab-control" >
						<a href="#delivery-pickup-tab-control">Delivery & Pick up</a>
					</li>

					<li class="tablist-item" id="batching-info-tab" role="tab" aria-controls="batching-info-tab-control" >
						<a href="#batching-info-tab-control">Batching Info</a>
					</li>
			</ul>
		</div>

		<?php 
		global $product;

		if ( ! is_a( $product, 'WC_Product' ) ) {
			$product = wc_get_product( get_the_id() );
		}
		
		$product_name = $product->get_name();

		$pattern = '/\(\Code:([^\)]+)\)/'; // Regex pattern to match "(Code:XXXX)"
    	$product_name = preg_replace( $pattern, '', $product_name ); // Replace matched pattern with empty string
		?>

		<div class="wc-product-tabs-panel-container">

		<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
			<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
				<h2 class="description-tab-product-name"><?php echo $product_name; ?></h2>
				<?php
				if ( isset( $product_tab['callback'] ) ) 
				{
					call_user_func( $product_tab['callback'], $key, $product_tab );
				}
				?>
			</div>
		<?php endforeach; ?>

			
			<div class="woocommerce-Tabs-panel tile-calculator-tab-content entry-content wc-tab" id="tile-calculator-tab-control" role="tabpanel">

				<div id="product-page-tile-calculator-container">

					<div class="omni-calculator" data-calculator="construction/tile" data-width="100%" data-config='{}' data-currency="AUD" data-show-row-controls="false" data-version="3" data-t="1696554125357">

					  <?php echo do_shortcode('[CP_CALCULATED_FIELDS id="6"]'); ?>

					  <script src="/wp-content/themes/enfold-child/calculator-generator.js"></script>

					</div>

					<script async src="https://cdn.omnicalculator.com/sdk.js"></script>

				</div>

			</div>


			<div class="woocommerce-Tabs-panel delivery-pickup-tab-content entry-content wc-tab" id="delivery-pickup-tab-control" role="tabpanel">

				<div id="product-page-delivery-pickup-container">

					<?php 
					$delivery_page_id = 6058; 
					$page = get_post($delivery_page_id);

					if ($page) 
					{
					    echo apply_filters('the_content', $page->post_content);
					} 
					?>

				</div>

			</div>

			<div class="woocommerce-Tabs-panel batching-info-tab-content entry-content wc-tab" id="batching-info-tab-control" role="tabpanel">

				<div id="product-page-batching-info-container">

					<?php 
					$delivery_page_id = 27911; 
					$page = get_post($delivery_page_id);

					if ($page) 
					{
					    echo apply_filters('the_content', $page->post_content);
					} 
					?>

				</div>

			</div>

		</div>

		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>



<?php endif; ?>
