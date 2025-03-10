<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;
$get_product_id = $product->get_id();
$gallery_image_ids = $product->get_gallery_image_ids();
$thumbnail_id = get_post_thumbnail_id( $product_id );
$gallery_shortcode = "";
$gallery_count = 0;
$gallery_pre_count = 0;
$gallery_btn_code = "";
$gallery_slider_code = "";

if(! empty($thumbnail_id))
{	
	$gallery_count = $gallery_count + 1;
	$gallery_pre_count = $gallery_count - 1;

	$gallery_btn_code = "<button type='button' data-bs-target='#carouselIndicators' data-bs-slide-to='" . $gallery_pre_count. "' class='active' aria-current='true' aria-label='Slide ". $gallery_count ."'><img src=" . wp_get_attachment_image_url($thumbnail_id, 'thumbnail') ."></button>";
	$gallery_slider_code = "<div class='carousel-item active'><a href=" . wp_get_attachment_image_url($thumbnail_id, 'full') . " rel='lightbox'><img src=" . wp_get_attachment_image_url($thumbnail_id, 'full') . " class='d-block w-100'></a></div>";

	if(! empty( $gallery_image_ids ))
	{
		$gallery_shortcode = "[av_slideshow img_copyright='' size='medium' control_layout='av-control-default' slider_navigation='av-navigate-arrows av-navigate-dots' nav_visibility_desktop='' nav_arrow_color='' nav_arrow_bg_color='' nav_dots_color='' nav_dot_active_color='' img_copyright_font='' av-desktop-font-img_copyright_font='' av-medium-font-img_copyright_font='' av-small-font-img_copyright_font='' av-mini-font-img_copyright_font='' img_copyright_color='' img_copyright_bg='' animation='slide' transition_speed='' autoplay='false' interval='5' img_scale='' img_scale_end='10' img_scale_direction='' img_scale_duration='3' img_scale_opacity='1' conditional_play='' img_scrset='' lazy_loading='disabled' alb_description='' id='' custom_class='' template_class='' element_template='' one_element_template='' av_uid='av-m6ydukis' sc_version='1.0'][av_slide id='" . $thumbnail_id . "' element_template='' title='' video='' slide_type='' mobile_image='' fallback_link='https://' video_ratio='16:9' video_autoplay='' video_controls='' video_mute='' video_loop='' custom_title_size='' av-desktop-font-size-title='' av-medium-font-size-title='' av-small-font-size-title='' av-mini-font-size-title='' custom_size='' av-desktop-font-size='' av-medium-font-size='' av-small-font-size='' av-mini-font-size='' font_color='' custom_title='' custom_content='' heading_tag='' heading_class='' link_apply='' link='' link_dynamic='' link_target='' one_element_template=''  av_uid='' sc_version='1.0'][/av_slide]";

		foreach ( $gallery_image_ids as $image_id ) 
		{
			$gallery_count = $gallery_count + 1;
			$gallery_pre_count = $gallery_count - 1;

			$gallery_shortcode = $gallery_shortcode . "[av_slide id='" . $image_id . "' element_template='' title='' video='' slide_type='' mobile_image='' fallback_link='https://' video_ratio='16:9' video_autoplay='' video_controls='' video_mute='' video_loop='' custom_title_size='' av-desktop-font-size-title='' av-medium-font-size-title='' av-small-font-size-title='' av-mini-font-size-title='' custom_size='' av-desktop-font-size='' av-medium-font-size='' av-small-font-size='' av-mini-font-size='' font_color='' custom_title='' custom_content='' heading_tag='' heading_class='' link_apply='' link='' link_dynamic='' link_target='' one_element_template=''  av_uid='' sc_version='1.0'][/av_slide]";

			$gallery_btn_code = $gallery_btn_code . "<button type='button' data-bs-target='#carouselIndicators' data-bs-slide-to='" . $gallery_pre_count. "' aria-label='Slide ". $gallery_count ."'><img src=" . wp_get_attachment_image_url($image_id, 'thumbnail') ."></button>";
			$gallery_slider_code = $gallery_slider_code . "<div class='carousel-item'><a href=" . wp_get_attachment_image_url($image_id, 'full') . " rel='lightbox'><img src=" . wp_get_attachment_image_url($image_id, 'full') . " class='d-block w-100'></a></div>";
	    }

	    $gallery_shortcode = $gallery_shortcode . "[/av_slideshow]";
	}
	else
	{
		$gallery_shortcode = "[av_slideshow img_copyright='' size='no scaling' control_layout='av-control-default' slider_navigation='av-navigate-arrows av-navigate-dots' nav_visibility_desktop='' nav_arrow_color='' nav_arrow_bg_color='' nav_dots_color='' nav_dot_active_color='' img_copyright_font='' av-desktop-font-img_copyright_font='' av-medium-font-img_copyright_font='' av-small-font-img_copyright_font='' av-mini-font-img_copyright_font='' img_copyright_color='' img_copyright_bg='' animation='slide' transition_speed='' autoplay='false' interval='5' img_scale='' img_scale_end='10' img_scale_direction='' img_scale_duration='3' img_scale_opacity='1' conditional_play='' img_scrset='' lazy_loading='disabled' alb_description='' id='' custom_class='' template_class='' element_template='' one_element_template='' av_uid='av-m6ydukis' sc_version='1.0'][av_slide id='" . $thumbnail_id . "' element_template='' title='' video='' slide_type='' mobile_image='' fallback_link='https://' video_ratio='16:9' video_autoplay='' video_controls='' video_mute='' video_loop='' custom_title_size='' av-desktop-font-size-title='' av-medium-font-size-title='' av-small-font-size-title='' av-mini-font-size-title='' custom_size='' av-desktop-font-size='' av-medium-font-size='' av-small-font-size='' av-mini-font-size='' font_color='' custom_title='' custom_content='' heading_tag='' heading_class='' link_apply='' link='' link_dynamic='' link_target='' one_element_template=''  av_uid='' sc_version='1.0'][/av_slide][/av_slideshow]"; 
	}
}
else
{
	$gallery_shortcode = "[av_slideshow img_copyright='' size='no scaling' control_layout='av-control-default' slider_navigation='av-navigate-arrows av-navigate-dots' nav_visibility_desktop='' nav_arrow_color='' nav_arrow_bg_color='' nav_dots_color='' nav_dot_active_color='' img_copyright_font='' av-desktop-font-img_copyright_font='' av-medium-font-img_copyright_font='' av-small-font-img_copyright_font='' av-mini-font-img_copyright_font='' img_copyright_color='' img_copyright_bg='' animation='slide' transition_speed='' autoplay='false' interval='5' img_scale='' img_scale_end='10' img_scale_direction='' img_scale_duration='3' img_scale_opacity='1' conditional_play='' img_scrset='' lazy_loading='disabled' alb_description='' id='' custom_class='' template_class='' element_template='' one_element_template='' av_uid='av-m6ydukis' sc_version='1.0'][av_slide id='29495' element_template='' title='' video='' slide_type='' mobile_image='' fallback_link='https://' video_ratio='16:9' video_autoplay='' video_controls='' video_mute='' video_loop='' custom_title_size='' av-desktop-font-size-title='' av-medium-font-size-title='' av-small-font-size-title='' av-mini-font-size-title='' custom_size='' av-desktop-font-size='' av-medium-font-size='' av-small-font-size='' av-mini-font-size='' font_color='' custom_title='' custom_content='' heading_tag='' heading_class='' link_apply='' link='' link_dynamic='' link_target='' one_element_template=''  av_uid='' sc_version='1.0'][/av_slide][/av_slideshow]";

	$gallery_btn_code = "<button type='button' data-bs-target='#carouselIndicators' data-bs-slide-to='" . $gallery_pre_count. "' class='active' aria-current='true' aria-label='Slide ". $gallery_count ."'><img src='/wp-content/uploads/2025/02/no-photo.jpg'></button>"; 
	$gallery_slider_code = "<div class='carousel-item active'><img src='/wp-content/uploads/2025/02/no-photo.jpg' class='d-block w-100'> </div>";
}


/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.  
	return;
}
?>

<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/bootstrap.bundle.js"></script>
<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/css/bootstrap.css">
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/css/bootstrap.min.css">

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<div class="gto-product-warpper">

		<div class="gto-product-gallery-container">

			<div class="gto-gallery-wishlist-container"><?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?></div>

			<div class="gto-product-gallery-container-inner  desktop-only">

				<div class="carousel-wrapper">
					<div id="carouselIndicators" class="carousel slide" data-bs-ride="carousel">
					  <div class="carousel-indicators">
					    <?php echo $gallery_btn_code; ?>
					  </div>
					  <div class="carousel-inner">

					  	<div class="carousel-control-container">

					  		<button class="carousel-control-prev" type="button" data-bs-target="#carouselIndicators" data-bs-slide="prev">
							    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
							    <span class="visually-hidden">Previous</span>
						    </button>

						    <button class="carousel-control-next" type="button" data-bs-target="#carouselIndicators" data-bs-slide="next">
							    <span class="carousel-control-next-icon" aria-hidden="true"></span>
							    <span class="visually-hidden">Next</span>
						    </button>

					  	</div>
					    <?php echo $gallery_slider_code; ?>
					  </div>
					</div>
				</div>

			</div>

			
			<div class="gto-gallery-inner-container desktop-only hide-this-area"><?php echo do_shortcode($gallery_shortcode); ?></div>

			<div class="gto-gallery-inner-container mobile-only"><?php echo do_shortcode($gallery_shortcode); ?></div>
		</div>

		
		<?php //do_action( 'woocommerce_before_single_product_summary' ); ?>


		<div class="summary entry-summary gto-product-des-container">
			
			<div class="gto-product-des-inner">
				<?php

				// Product summary container
				echo '<div class="product-summary">';

				// Load product title
				the_title( '<h1 class="product_title entry-title">', '</h1>' );

				// Load product price
				wc_get_template_part( 'single-product/price' );

				// Load short description
				wc_get_template_part( 'single-product/short-description' );

				// Load Add to Cart button
				woocommerce_template_single_add_to_cart();

				// Load product meta data (like SKU, categories, etc.)
				wc_get_template_part( 'single-product/meta' );

				// Load sharing buttons
				wc_get_template_part( 'single-product/sharing' );

				echo '</div>'; // End product summary

				?>

				<div class="end-fix"></div>

			</div>

		</div>

	</div>

</div>


	<?php
	/**
	 * Hook: woocommerce_after_single_product_summary.  
	 *
	 * @hooked woocommerce_output_product_data_tabs - 10  
	 * @hooked woocommerce_upsell_display - 15
	 * @hooked woocommerce_output_related_products - 20
	 */
	do_action( 'woocommerce_after_single_product_summary' );
	?>


<?php do_action( 'woocommerce_after_single_product' ); ?>
