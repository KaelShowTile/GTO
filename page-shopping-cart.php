<?php
	if ( ! defined( 'ABSPATH' ) ){ die(); }

	global $avia_config, $wp_query;

	/*
	 * get_header is a basic wordpress function, used to retrieve the header.php file in your theme directory.
	 * Template Name: Shopping Cart Template
	 */
	get_header();

	/**
	 * @used_by				enfold\config-wpml\config.php				10
	 * @since 4.5.1
	 */
	do_action( 'ava_page_template_after_header' );

	if( get_post_meta(get_the_ID(), 'header', true) != 'no')
	{
		echo avia_title();
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

			<div class='container'>

				<main class='template-page content  <?php avia_layout_class( 'content' ); ?> units <?php echo $main_class; ?>' <?php avia_markup_helper(array('context' => 'content','post_type'=>'page'));?>>

					<?php
					/* Run the loop to output the posts.
					* If you want to overload this in a child theme then include a file
					* called loop-page.php and that will be used instead.
					*/

					$avia_config['size'] = avia_layout_class( 'main' , false) == 'fullsize' ? 'entry_without_sidebar' : 'entry_with_sidebar';

					echo '<div class="free-sample-list">';
		
						display_sample_list();
									
					echo'</div>';

					get_template_part( 'includes/loop', 'page' );
					?>

					

				<!--end content-->
				</main>

				<?php

				//get the sidebar
				$avia_config['currently_viewing'] = 'page';
				get_sidebar();

				?>

			</div><!--end container-->

		</div><!-- close default .container_wrap element -->

<?php
		get_footer();
