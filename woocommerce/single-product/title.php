<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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

?>

<div>

	<h1 class="product_title entry-title"><?php echo $firstPart; ?> </h1>
	<p class="product_title_code">Code: <?php echo $product_code; ?></p>

</div>

<div class="hide-this-area"><?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?></div>

<?php
