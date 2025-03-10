<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>

<div class="product-rating-container">

	<?php
		if ( comments_open() || get_comments_number() )
		{
		    comments_template();
		}
	?>
	
</div>

<?php 

$rating_score = get_field('tile_rate');
$full_stars = floor($rating_score);
$half_star = ($rating_score - $full_stars) >= 0.5 ? 1 : 0; 
$empty_stars = 5 - $full_stars - $half_star;

if( have_rows('tile_reviews') ){ ?>

<div class = "google-review-container hide-this-area">

	<div class = "customer-review-container related products">

		<div class="customer-rating-container">

			<h5>Customer Reviews</h5>

			<div class="rating-stars-container">

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

		</div>

		<div class ="rating-container">

			<?php if( have_rows('tile_reviews') ){


				while( have_rows('tile_reviews') ) : the_row();

					$review_name = get_sub_field('tile_reviewers_name');
					$review_comment = get_sub_field('tile_reviewers_comment'); ?>

					<div class="rating-inner-container">
						<p class="reviewers_name"><?php echo $review_name ?></p>
						<p class="reviewers_comment"><?php echo $review_comment ?></p>
					</div>

				<?php endwhile; 
			} 

		echo '</div>';

	echo '</div>';

echo '</div>';

}else{  ?>

<div class = "google-review-container hide-this-area">

	<section class="related products google-review-inner">

		<?php echo do_shortcode('[trustindex no-registration=google]'); ?>

	</section>

</div>

<?php } 

$product_id = get_the_ID();
$product_name = get_the_title();
$product_link = get_permalink();

?>

<div class = "gto-product-review-container">

	<div class="review-score-headline mobile-only">
		<p class = "rating-title">Review & Rating of</p>
		<h5><?php echo $product_name ?></h5>
	</div>

	<div class="gto-product-review-list">

		<?php 

			$reviews = get_product_reviews($product_id);
			$total_review = 0;
			$total_score = 0;

			if (!empty($reviews))
			{
				foreach ($reviews as $review)
				{
					if(esc_html($review->show_review == 1))
					{
						$rating_score = $review->product_rating;
						$full_stars = floor($rating_score);
						$half_star = ($rating_score - $full_stars) >= 0.5 ? 1 : 0; 
						$empty_stars = 5 - $full_stars - $half_star;

						$total_review ++;
						$total_score = $total_score + $rating_score;

						?>

						<li class="gto-product-review-item">

							<h5 class="reviewr-name"><?php echo esc_html($review->customer_name); ?></h5>
							<p class="review-date"><?php echo date('F j, Y', strtotime($review->review_date)); ?></p>

							<div class="rating-stars-container">

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

							<?php 
								if(!empty($review->review_imgs))
								{
									$images = explode(',', $review->review_imgs);

									echo '<div class="review-images">';

							            foreach ($images as $image) 
							            {
							                echo '<a href="/submit-review/' . $image . '" lightbox-added><img src="/submit-review/' . $image . '" alt="Review Image" "></a>';
							            }

						            echo '</div>';
								}
							?>

							<p class= "review-content"><?php echo esc_html($review->review_content); ?></p>

						</li>

					<?php }
				}
			}
			else
			{
				echo '<p class="no-review">No Review Found.</p>';
			}

		?>

	</div>

	<div class="review-score-container">
		
		<div class="review-score-headline desktop-only">
			<p class = "rating-title">Review & Rating of</p>
			<h5><?php echo $product_name ?></h5>
		</div>

		<div class="rating-stars-container desktop-only">

			<?php 

			$average_score;

			if($total_review > 0)
			{
				$average_score = $total_score / $total_review;
			}
			else
			{
				$average_score = 0 ;
			}

			
			$average_full_stars = floor($average_score);
			$average_half_star = ($average_score - $average_full_stars) >= 0.5 ? 1 : 0; 
			$average_empty_stars = 5 - $average_full_stars - $average_half_star;

			for ($i = 1; $i <= $average_full_stars; $i++): ?>
				<span class="rating-stars filled">&starf;</span> <!-- Full star -->
			<?php endfor; ?>
							    
			<?php if ($average_half_star): ?>
				<span class="rating-stars half">&starf;</span> <!-- Half star -->
			<?php endif; ?>
							    
			 <?php for ($i = 1; $i <= $average_empty_stars; $i++): ?>
				<span class="rating-stars">&starf;</span> <!-- Empty star -->
			<?php endfor; ?>

		</div>
		
		<a href="/submit-review/submit-form.html#product_id=<?php echo $product_id; ?>&product_name=<?php echo $product_name; ?>&product_link=<?php echo $product_link ?>" target="_blank" rel="nofollow">Post Your Review</a>

	</div>

</div>



<?php if ( $related_products ) : ?>

	<section class="related products">

		<?php
		$heading = apply_filters( 'woocommerce_product_related_products_heading', __( 'Related products', 'woocommerce' ) );

		if ( $heading ) :
			?>
			<h2><?php echo esc_html( $heading ); ?></h2>
		<?php endif; ?>
		
		<?php woocommerce_product_loop_start(); ?>

			<?php foreach ( $related_products as $related_product ) : ?>

					<?php
					$post_object = get_post( $related_product->get_id() );

					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

					wc_get_template_part( 'content', 'product' );
					?>

			<?php endforeach; ?>

		<?php woocommerce_product_loop_end(); ?>

	</section>
	<?php
endif;

wp_reset_postdata();
