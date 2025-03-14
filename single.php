<?php
	if( ! defined( 'ABSPATH' ) )	{ die(); }

	global $avia_config;

	/**
	 * get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
	 */
	get_header();


	$title = __( 'Blog - Latest News', 'avia_framework' ); //default blog title
	$t_link = home_url( '/' );
	$t_sub = '';

	if( avia_get_option( 'frontpage' ) && $new = avia_get_option( 'blogpage' ) )
	{
		$title = get_the_title( $new ); //if the blog is attached to a page use this title
		$t_link = get_permalink( $new );
		$t_sub = avia_post_meta( $new, 'subtitle' );
	}

	if( get_post_meta( get_the_ID(), 'header', true ) != 'no' )
	{
		echo avia_title( array( 'heading' => 'strong', 'title' => $title, 'link' => $t_link, 'subtitle' => $t_sub ) );
	}

	do_action( 'ava_after_main_title' );

	/**
	 * @since 5.6.7
	 * @param string $main_class
	 * @param string $context					file name
	 * @return string
	 */
	$main_class = apply_filters( 'avf_custom_main_classes', 'av-main-' . basename( __FILE__, '.php' ), basename( __FILE__ ) );

	?>

		<div class='container_wrap container_wrap_first main_color <?php avia_layout_class( 'main' ); ?>'>

			<div class='container template-blog template-single-blog '>

				<main class='content units <?php avia_layout_class( 'content' ); ?> <?php echo avia_blog_class_string(); ?> <?php echo $main_class; ?>' <?php avia_markup_helper( array( 'context' => 'content', 'post_type' => 'post' ) );?>>

					<?php
					/* Run the loop to output the posts.
					* If you want to overload this in a child theme then include a file
					* called loop-index.php and that will be used instead.
					*
					*/
					get_template_part( 'includes/loop', 'index' );

					$blog_disabled = ( avia_get_option('disable_blog') == 'disable_blog' ) ? true : false;

					if( ! $blog_disabled )
					{
						//show related posts based on tags if there are any
						get_template_part( 'includes/related-posts' );

					}
					?>

					<div class="blog-author-container">
						<div class="author-avatar">
							<img src="/wp-content/uploads/2025/02/author-avatar.jpg">
						</div>

						<div class="author-description">
							<h2>Jimmy Moon</h2>
							<span>Tile Expert </span>
							<p>Jimmy is a seasoned professional in the tile industry with over 10 years of experience, he's known for his passion to help builders and homeowners find their dream tiles for their projects. </p>
						</div>
					</div>

				<!--end content-->
				</main>

				<?php

				$avia_config['currently_viewing'] = 'blog';
				//get the sidebar
				get_sidebar();

				?>

			</div><!--end container-->

		</div><!-- close default .container_wrap element -->

<?php
		get_footer();

