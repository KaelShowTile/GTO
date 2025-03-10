<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $post;
global $product;

$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

// Get all product categories
$get_product_id = $product->get_id();
$terms = get_the_terms($get_product_id, 'product_cat');
$current_url = get_permalink($get_product_id);
$categories = array();

if ($terms && !is_wp_error($terms)) {
    
    // Organize categories by parent
    foreach ($terms as $term) {
        $categories[$term->parent][] = $term;
    }
}

if ( ! $short_description &&  ! $terms) {
	return;
}


?>
<div class="woocommerce-product-details__short-description">

	<div>

		<div class = "product-categories-list-container">

			<?php 

				if (isset($categories[0])){

					foreach ($categories[0] as $parent_category) 
					{						
						$parent_category_name = $parent_category->name;
						$parent_category_id = $parent_category->term_id;
						$parent_category_url = get_term_link($parent_category->term_id, 'product_cat');

						if ($parent_category_name == "Size") 
						{
							echo '<li>';
				        		echo '<a class="parent-categories-item" href="' . $parent_category_url . '">';
				        		echo esc_html($parent_category_name) . ':'; 
				        		echo '</a>';

				        		echo '<p>';

				        		if (isset($categories[$parent_category_id])) 
				        		{
				        			
				        			$cat_count = 0;

				        			foreach ($categories[$parent_category_id] as $child_category)
				        			{ 
				        				$child_category_url = get_term_link($child_category->term_id, 'product_cat');

				        				if ($cat_count !== 0)
				        				{
				        					echo ', ';
				        				}  

				        				echo '<a class="child-categories-item" href="' . $child_category_url . '">';

				        				$custom_size = get_field('size_mm', $product->get_id());
				        				if(empty($custom_size))
				        				{
				        					echo esc_html($child_category->name);
				        				}
				        				else
				        				{
				        					echo $custom_size;
				        				}

				        				  
				        				echo '</a>';

				        				$cat_count = $cat_count + 1;
				        			}

				        		} 

				        		echo '</p>';

				        	echo'</li>';
						}

					}


					foreach ($categories[0] as $parent_category) 
					{						
						$parent_category_name = $parent_category->name;
						$parent_category_id = $parent_category->term_id;
						$parent_category_url = get_term_link($parent_category->term_id, 'product_cat');

						if($parent_category_name !== "Tiles on sale" && $parent_category_name !== "Size")
			        	{ 
			        		
			        		echo '<li>';
				        		echo '<a class="parent-categories-item" href="' . $parent_category_url . '">';
				        		echo esc_html($parent_category_name). ':'; 
				        		echo '</a>';

				        		echo '<p>';

				        		if (isset($categories[$parent_category_id])) 
				        		{
				        			
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

				        		echo '</p>';

				        	echo'</li>';

			        	}
					}

					//ACF parameters

				    if(get_field('origin'))
				    {
				        echo '<li>';
				        	echo '<p class="parent-categories-item">Origin:</p>';
				        	echo '<p class="child-categories-item">';
				        	echo esc_html(the_field('origin'));
				        	echo '</p>';
				        echo'</li>';
				    }

				    if(get_field('finish'))
				    {
				        echo '<li>';
				        	echo '<p class="parent-categories-item">Finish:</p>';
				        	echo '<p class="child-categories-item">'; 
				        	echo esc_html(the_field('finish'));
				        	echo '</p>';
				        echo'</li>';
				    }

				    if(get_field('edge'))
				    {
				        echo '<li>';
				        	echo '<p class="parent-categories-item">Edge:</p>';
				        	echo '<p class="child-categories-item">'; 
				        	echo esc_html(the_field('edge'));
				        	echo '</p>';
				        echo'</li>';
				    }

				    if(get_field('thickness'))
				    {
				        echo '<li>';
				        	echo '<p class="parent-categories-item">Thickness:</p>';
				        	echo '<p class="child-categories-item">'; 
				        	echo esc_html(the_field('thickness'));
				        	echo '</p>';
				        echo'</li>';
				    }

				    if(get_field('package'))
				    {
				        echo '<li>';
				        	echo '<p class="parent-categories-item">Package:</p>';
				        	echo '<p class="child-categories-item">'; 
				        	echo esc_html(the_field('package'));
				        	echo '</p>';
				        echo'</li>';
				    }

				    if(get_field('slip_rate'))
				    {
				        echo '<li>';
				        	echo '<p class="parent-categories-item">Slip Rate:</p>';
				        	echo '<p class="child-categories-item">'; 
				        	echo esc_html(the_field('slip_rate'));
				        	echo '</p>';
				        echo'</li>';
				    }
				        			
				}

			?>

		</div>

	</div>

	<?php echo $short_description; // WPCS: XSS ok. ?>

	<div class="product-link-varaiation">

	<?php
		
		$attribute_item_list = [];

		$attribute_color = [];
		$attribute_size = [];
		$attribute_finish = [];
		$attribute_thickness = [];
		$attribute_depth = [];

		//define the object for variation product, attribute is array
		class attribute_item_obj {
		    public $slug;
		    public $thumbnail;
		    public $link;
		    public $colour;
		    public $size;
		    public $finish;
		    public $thickness;
		    public $depth;

		    public function __construct($slug, $thumbnail, $link, $colour, $size, $finish, $thickness, $depth) 
		    {
		        $this->slug = $slug;
		        $this->thumbnail = $thumbnail;
		        $this->link = $link;
		        $this->colour = $colour;
			    $this->size = $size;
			    $this->finish = $finish;
			    $this->thickness = $thickness;
			    $this->depth = $depth;
		    }
		}

		$active_attributes = get_field('active_attribute', $get_product_id); 
		$ifHasColours = 0;
		$ifHasSize = 0;
		$ifHasFinish = 0;
		$ifHasThickness = 0;
		$ifHasDepthSize = 0;

		$related_products = get_field('link_product_by_attribute', $get_product_id);
		
		$active_colours;
		$active_size;
		$active_finish;
		$active_thickness;
		$active_depth_size;

		if($related_products)
		{
			foreach($active_attributes as $active_attribute)
			{
				if($active_attribute == "pa_colours")
				{
					$active_colours = $product->get_attribute('pa_colours');
					$attribute_color[] = $active_colours;
					$ifHasColours = 1;
				}

				if($active_attribute == "pa_size")
				{
					$active_size = $product->get_attribute('pa_size');
					$attribute_size[] = $active_size;
					$ifHasSize = 1;
				}

				if($active_attribute == "pa_finish")
				{
					$active_finish = $product->get_attribute('pa_finish');
					$attribute_finish[] = $active_finish;
					$ifHasFinish = 1;
				}

				if($active_attribute == "pa_thickness")
				{
					$active_thickness = $product->get_attribute('pa_thickness');
					$attribute_thickness[] = $active_thickness;
					$ifHasThickness = 1;
				}

				if($active_attribute == "pa_depth-size")
				{
					$active_depth_size = $product->get_attribute('pa_depth-size');
					$attribute_depth[] = $active_depth_size;
					$ifHasDepthSize = 1;
				}

			}

			$attribute_item_list[] = new attribute_item_obj(
			    $product->get_title(), 
			    get_the_post_thumbnail_url($product_id, 'thumbnail'), 
			    $current_url, 
			    $active_colours,
			    $active_size,
			    $active_finish,
			    $active_thickness,
			    $active_depth_size
			);

			if($active_attributes && $related_products)
			{
				foreach($related_products as $related_product)
				{
					$related_product_ID = $related_product->ID;
					$related_product = wc_get_product($related_product_ID);
					
					if($related_product)
					{
						$related_colours = "";
						$related_size = "";
						$related_finish = "";
						$related_thickness = "";
						$related_depth_size = "";

						if($ifHasColours == 1)
						{
							$related_colours = $related_product->get_attribute('pa_colours');

							foreach($attribute_color as $color)
							{
								if($related_colours == $color)
								{
									$checkValue = 1;	
								}
							}

							if($checkValue == 0)
							{
								$attribute_color[] = $related_colours;
							}
							
						}

						if($ifHasSize == 1)
						{
							$related_size = $related_product->get_attribute('pa_size');

							$checkValue = 0;

							foreach($attribute_size as $size)
							{
								if($related_size == $size)
								{
									$checkValue = 1;
								}

							}

							if($checkValue == 0)
							{
								$attribute_size[] = $related_size;
							}

						}

						if($ifHasFinish == 1)
						{
							$related_finish = $related_product->get_attribute('pa_finish');

							$checkValue = 0;

							foreach($attribute_finish as $finish)
							{
								if($related_finish == $finish)
								{
									$checkValue = 1;
								}
							}

							if($checkValue == 0)
							{
								$attribute_finish[] = $related_finish;
							}

						}

						if($ifHasThickness == 1)
						{
							$related_thickness = $related_product->get_attribute('pa_thickness');

							$checkValue = 0;
						
							foreach($attribute_thickness as $thickness)
							{
								if($related_thickness == $thickness)
								{
									$checkValue = 1;
								}
							}

							if($checkValue == 0)
							{
								$attribute_thickness[] = $related_thickness;
							}
						}

						if($ifHasDepthSize == 1)
						{
							$related_depth_size = $related_product->get_attribute('pa_depth-size');

							$checkValue = 0;

							foreach($attribute_depth as $depth)
							{
								if($related_depth_size != $depth)
								{
									$checkValue = 1;
								}
							}

							if($checkValue == 0)
							{
								$attribute_depth[] = $related_depth_size;
							}
						}
				       
					}

					$attribute_item_list[] = new attribute_item_obj(
					    $related_product->get_title(),
					    get_the_post_thumbnail_url($related_product_ID, 'thumbnail'), 
					    $permalink = $related_product->get_permalink(),
					    $related_colours,
					    $related_size,
					    $related_finish,
					    $related_thickness,
					    $related_depth_size
					);
				}

				sort($attribute_color);
				sort($attribute_size);
				sort($attribute_finish);
				sort($attribute_thickness);
				sort($attribute_depth);
			}

			echo '<div class="clear-all">';
			

			echo'<div class="gto-link-products-container">';

				echo'<div class="gto-link-products-attribute attribute-color">';

					if($ifHasColours == 1)
					{
						echo'<p class="attribute-name">Colour: </p>';

						foreach($attribute_color as $color)
						{
							if($color == $active_colours)
							{
								echo'<span class="active">';
							}
							else
							{
								echo'<span>';
							}

							$ifCheckValue = 0;

							foreach($attribute_item_list as $attribute_item)
							{
								if($attribute_item->colour == $color && $attribute_item->size == $active_size && $attribute_item->finish == $active_finish && $attribute_item->thickness == $active_thickness && $attribute_item->depth == $active_depth_size)
								{
									echo'<a href="'. $attribute_item->link .'"><p><img src="' . $attribute_item->thumbnail . ' "></img>' . $color .'</p></a>';
									$ifCheckValue = 1;
								}
							}

							if($ifCheckValue == 0)
							{
								echo'<p class="not-available">' . $color .'</p>';
							}

							echo '</span>';

						}
					}

				echo '</div>';

				echo'<div class="gto-link-products-attribute">';

					if($ifHasSize == 1)
					{
						echo'<p class="attribute-name">Size: </p>';

						foreach($attribute_size as $size)
						{
							if($size == $active_size)
							{
								echo'<span class="active">';
							}
							else
							{
								echo'<span>';
							}

							$ifCheckValue = 0;

							foreach($attribute_item_list as $attribute_item)
							{
								if($attribute_item->colour == $active_colours && $attribute_item->size == $size && $attribute_item->finish == $active_finish && $attribute_item->thickness == $active_thickness && $attribute_item->depth == $active_depth_size)
								{
									echo'<a href="'. $attribute_item->link .'"><p>' . $size .'</p></a>';
									$ifCheckValue = 1;
								}
							}

							if($ifCheckValue == 0)
							{
								echo'<p class="not-available">' . $size .'</p>';
							}

							echo'</span>';						
						}
					}

				echo '</div>';

				echo'<div class="gto-link-products-attribute">';

					if($ifHasFinish == 1)
					{
						echo'<p class="attribute-name">Finish: </p>';

						foreach($attribute_finish as $finish)
						{
							if($finish == $active_finish)
							{
								echo'<span class="active">';
							}
							else
							{
								echo'<span>';
							}

							$ifCheckValue = 0;

							foreach($attribute_item_list as $attribute_item)
							{
								if($attribute_item->colour == $active_colours && $attribute_item->size == $active_size && $attribute_item->finish == $finish && $attribute_item->thickness == $active_thickness && $attribute_item->depth == $active_depth_size)
								{
									echo'<a href="'. $attribute_item->link .'"><p>' . $finish .'</p></a>';
									$ifCheckValue = 1;
								}
							}

							if($ifCheckValue == 0)
							{
								echo'<p class="not-available">' . $finish .'</p>';
							}

							echo '</span>';
						}
					}

				echo '</div>';

				echo'<div class="gto-link-products-attribute">';

					if($ifHasThickness == 1)
					{
						echo'<p class="attribute-name">Thickness: </p>';

						foreach($attribute_thickness as $thickness)
						{
							if($thickness == $active_thickness)
							{
								echo'<span class="active">'. $thickness .'</span>';
							}
							else
							{
								echo'<span>'. $thickness .'</span>';
							}

							$ifCheckValue = 0;

							foreach($attribute_item_list as $attribute_item)
							{
								if($attribute_item->colour == $active_colours && $attribute_item->size == $active_size && $attribute_item->finish == $active_finish && $attribute_item->thickness == $thickness && $attribute_item->depth == $active_depth_size)
								{
									echo'<a href="'. $attribute_item->link .'"><p>' . $thickness .'</p></a>';
									$ifCheckValue = 1;
								}
							}

							if($ifCheckValue == 0)
							{
								echo'<p class="not-available">' . $thickness .'</p>';
							}

							echo'</span>';
						}
					}

				echo '</div>';

				echo'<div class="gto-link-products-attribute">';

					if($ifHasDepthSize == 1)
					{
						echo'<p class="attribute-name">Depth Size: </p>';

						foreach($attribute_depth as $depth)
						{
							if($depth == $active_depth_size)
							{
								echo'<span class="active">'. $depth .'</span>';
							}
							else
							{
								echo'<span>'. $depth .'</span>';
							}

							$ifCheckValue = 0;

							foreach($attribute_item_list as $attribute_item)
							{
								if($attribute_item->colour == $active_colours && $attribute_item->size == $active_size && $attribute_item->finish == $active_finish && $attribute_item->thickness == $active_thickness && $attribute_item->depth == $depth)
								{
									echo'<a href="'. $attribute_item->link .'"><p>' . $depth .'</p></a>';
									$ifCheckValue = 1;
								}
							}

							if($ifCheckValue == 0)
							{
								echo'<p class="not-available">' . $depth .'</p>';
							}

							echo'</span>';
						}
					}

				echo '</div>';

			echo'</div>';
		}

		

	?>

	</div>
	
</div>
