<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo( 'charset' );?>"/>
	<?php

		if ( Codevz_Theme::option( 'disable_responsive' ) ) {
			echo '<meta name="viewport" content="width=1140"/>';
		} else {
			echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0"/>';
		}

		wp_head();

	?>
</head>

<body id="intro" <?php body_class(); ?> <?php echo Codevz_Theme::intro_attrs(); ?>>

<?php 

	wp_body_open();

	// Custom codes on start body
	echo str_replace( '&', '&amp;', do_shortcode( Codevz_Theme::option( 'body_codes' ) ) );
 
	// Header settings
	$cpt = Codevz_Theme::get_post_type();
	$option_cpt = ( $cpt === 'post' || $cpt === 'page' || empty( $cpt ) ) ? '' : '_' . $cpt;
	$fixed_side = Codevz_Theme::option( 'fixed_side' ) ? ' is_fixed_side' : '';
	$cover = Codevz_Theme::option( 'page_cover' . $option_cpt );
	$option_cpt = ( ! $cover || $cover === '1' ) ? '' :  $option_cpt;
	$layout = Codevz_Theme::option( 'boxed', '' );

	// Reload cover
	$cover = Codevz_Theme::option( 'page_cover' . $option_cpt );
	$cover_rev = Codevz_Theme::option( 'page_cover_rev' . $option_cpt );
	$cover_image = Codevz_Theme::option( 'page_cover_image' . $option_cpt );
	$cover_custom = Codevz_Theme::option( 'page_cover_custom' . $option_cpt );
	$cover_custom_page =  Codevz_Theme::option( 'page_cover_page' . $option_cpt );
	$cover_than_header = Codevz_Theme::option( 'cover_than_header' . $option_cpt );
	$cover_parallax = Codevz_Theme::option( 'title_parallax' . $option_cpt );
	$title = Codevz_Theme::option( 'page_title' . $option_cpt );
	$page_title_center = Codevz_Theme::option( 'page_title_center' . $option_cpt, Codevz_Theme::option( 'page_title_center' ) ) ? ' page_title_center' : '';

	if ( $title === '2' || $title === '6' || $title === '9' ) {
		$page_title_center = '';
	}
	
	$is_404 = is_404();
	$header = $footer = 1;
	$show_br_after = 0;

	$is_home = is_home();

	// Single page settings
	if ( is_singular() || ( $is_404 ) || $is_home ) {

		$_id = get_the_id();

		if ( $is_404 ) {
			$_404 = get_page_by_title( '404' );
			if ( ! empty( $_404->ID ) ) {
				$_id = $_404->ID;
			} else {
				$_404 = get_page_by_path( 'page-404' );
				if ( ! empty( $_404->ID ) ) {
					$_id = $_404->ID;
				}
			}
		}

		$meta = Codevz_Theme::meta( $is_home ? get_option( 'page_for_posts' ) : $_id );

		if ( isset( $meta['cover_than_header'] ) ) {
			if ( $meta['page_cover'] === 'none' ) {
				$cover = 'none';
			} else if ( $meta['page_cover'] !== '1' ) {
				$cover = $meta['page_cover'];
				$cover_rev = $meta['page_cover_rev'];
				$cover_image = isset( $meta['page_cover_image'] ) ? $meta['page_cover_image'] : $cover_image;
				$cover_custom = $meta['page_cover_custom'];
				$cover_custom_page =  $meta['page_cover_page'];
				$show_br_after =  isset( $meta['page_show_br'] ) ? $meta['page_show_br'] : '';
			}
			
			// Others
			$header = !$meta['hide_header'];
			$footer = !$meta['hide_footer'];
		}
		if ( ! empty( $meta['cover_than_header'] ) ) {
			$cover_than_header = ( $meta['cover_than_header'] === 'd' ) ? $cover_than_header : $meta['cover_than_header'];
		}
	}

	// Preloader
	if ( Codevz_Theme::option( 'pageloader' ) && ! isset( $_GET[ 'action' ] ) ) {
		$preloader_type = Codevz_Theme::option( 'preloader_type' );
		if ( $preloader_type === 'custom' && Codevz_Theme::option( 'pageloader_custom' ) ) {
			$preloader_content = '<div>' . Codevz_Theme::option( 'pageloader_custom' ) . '</div>';
		} else if ( $preloader_type === 'percentage' ) {
			$preloader_content = '<div class="pageloader_percentage">0%</div>';
		} else {
			$preloader_content = '<img src="' . esc_attr( Codevz_Theme::option( 'pageloader_img', 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzgiIGhlaWdodD0iMzgiIHZpZXdCb3g9IjAgMCAzOCAzOCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBzdHJva2U9IiNhN2E3YTciPg0KICAgIDxnIGZpbGw9Im5vbmUiIGZpbGwtcnVsZT0iZXZlbm9kZCI+DQogICAgICAgIDxnIHRyYW5zZm9ybT0idHJhbnNsYXRlKDEgMSkiIHN0cm9rZS13aWR0aD0iMiI+DQogICAgICAgICAgICA8Y2lyY2xlIHN0cm9rZS1vcGFjaXR5PSIuMyIgY3g9IjE4IiBjeT0iMTgiIHI9IjE4Ii8+DQogICAgICAgICAgICA8cGF0aCBkPSJNMzYgMThjMC05Ljk0LTguMDYtMTgtMTgtMTgiPg0KICAgICAgICAgICAgICAgIDxhbmltYXRlVHJhbnNmb3JtDQogICAgICAgICAgICAgICAgICAgIGF0dHJpYnV0ZU5hbWU9InRyYW5zZm9ybSINCiAgICAgICAgICAgICAgICAgICAgdHlwZT0icm90YXRlIg0KICAgICAgICAgICAgICAgICAgICBmcm9tPSIwIDE4IDE4Ig0KICAgICAgICAgICAgICAgICAgICB0bz0iMzYwIDE4IDE4Ig0KICAgICAgICAgICAgICAgICAgICBkdXI9IjFzIg0KICAgICAgICAgICAgICAgICAgICByZXBlYXRDb3VudD0iaW5kZWZpbml0ZSIvPg0KICAgICAgICAgICAgPC9wYXRoPg0KICAgICAgICA8L2c+DQogICAgPC9nPg0KPC9zdmc+' ) ) . '" alt="loading" width="150" height="150" />';
		}
		echo '<div class="pageloader ' . esc_attr( Codevz_Theme::option( 'loading_out_fx' ) ) . '">' . $preloader_content . '</div>';
	}

	// Hidden top bar
	$hidden_top_bar = Codevz_Theme::option( 'hidden_top_bar' );
	if ( $hidden_top_bar && $hidden_top_bar !== 'none' ) {
		wp_enqueue_script( 'xtra-extra-panel' );
		echo '<div class="hidden_top_bar"><div class="row clr">' . Codevz_Theme::get_page_as_element( esc_html( $hidden_top_bar ) ) . '</div><i class="' . esc_attr( Codevz_Theme::option( 'hidden_top_bar_icon', 'fa fa-angle-down' ) ) . '"></i></div>';
	}

	// Check fixed side visibility
	if ( $fixed_side && ! is_user_logged_in() ) {

		$elements = (array) Codevz_Theme::option( 'fixed_side_1_top' );
		$elements = wp_parse_args( $elements, (array) Codevz_Theme::option( 'fixed_side_1_middle' ) );
		$elements = wp_parse_args( $elements, (array) Codevz_Theme::option( 'fixed_side_1_bottom' ) );

		foreach ( $elements as $element ) {
			if ( ! empty( $element['elm_visibility'] ) ) {
				$fixed_side = false;
			}
		}

	}

	// Start page
	echo '<div id="layout" class="clr layout_' . esc_attr( $layout . ( $fixed_side ? ' is_fixed_side' : '' ) ) . '">';

	// Fixed Side
	$il_width = '';
	if ( $fixed_side && $header ) {
		$fixed_side = Codevz_Theme::option( 'fixed_side' );
		echo '<aside class="fixed_side fixed_side_' . esc_attr( $fixed_side ) . '">';
		Codevz_Theme::row([
			'id'		=> 'fixed_side_',
			'nums'		=> [ '1' ],
			'row'		=> 0,
			'left'		=> '_top',
			'right'		=> '_middle',
			'center'	=> '_bottom'
		]);
		echo '</aside>';
		$il_width = Codevz_Theme::get_string_between( Codevz_Theme::option( '_css_fixed_side_style' ), 'width:', ';' );
		$il_width = $il_width ? ' style="width: calc(100% - ' . $il_width . ')"' : '';
	}

	// Inner layout
	echo '<div class="inner_layout' . ( $header ? '' : ' cz-no-header' ) . ( $cover_than_header ? ' ' . $cover_than_header : '' ) . '"' . $il_width . '><div class="cz_overlay" aria-hidden="true"></div>';

	// Cover & Title
	$cover_type = $cover;
	if ( $cover && $cover !== 'none' ) {
		ob_start();

		echo '<div class="page_cover' . esc_attr( $page_title_center ) . ' xtra-cover-type-' . esc_attr( $cover ) . '">';

		if ( $cover === 'rev' && $cover_rev ) {

			$slider = do_shortcode( '[rev_slider alias="' . esc_attr( $cover_rev ) . '"]' );

			if ( $slider ) {
				echo do_shortcode( $slider );
			} else {
				echo '<div class="xtra-slider-placeholder cz_post_svg" style="background-color:#676767;"><img src="data:image/svg+xml,%3Csvg%20xmlns%3D&#39;http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg&#39;%20width=&#39;1420&#39;%20height=&#39;650&#39;%20viewBox%3D&#39;0%200%201420%20650&#39;%2F%3E" alt="Placeholder" /></div>';
			}

		} else if ( $cover === 'image' && $cover_image ) {
			echo '<div class="page_cover_image">' . wp_kses_post( wp_get_attachment_image( $cover_image, 'full' ) ) . '</div>';
		} else if ( $cover === 'custom' ) {
			echo '<div class="page_cover_custom">' . do_shortcode( esc_html( $cover_custom ) ) . '</div>';
		} else if ( $cover === 'page' ) {
			echo Codevz_Theme::get_page_as_element( esc_html( $cover_custom_page ) );
		}

		if ( $cover === 'title' || $show_br_after ) {
			echo '<div class="page_title" data-title-parallax="' . esc_attr( $cover_parallax ) . '">';

				$title_content = $breadcrumbs_content = '';
				$breadcrumbs_right = ( $title === '6' || $title === '9' );
				if ( $breadcrumbs_right ) {
					echo '<div class="right_br_full_container clr"><div class="row clr">';
				}

				$preview = is_customize_preview();

				if ( $preview && $cover_than_header !== 'header_onthe_cover' ) {

					echo '<i class="xtra-section-focus fas fa-cog" data-section="title_br"></i>';

				}

				if ( $title !== '2' && $title !== '7' && $title !== '8' && $title !== '9' ) {
					ob_start();

					if ( $preview && $cover_than_header === 'header_onthe_cover' ) {

						echo '<i class="xtra-section-focus fas fa-cog" data-section="title_br"></i>';

					}

					Codevz_Theme::page_title( Codevz_Theme::option( 'page_title_tag', 'h1' ) );
					$title_content = ob_get_clean();
				}

				if ( $title !== '2' && $title !== '3' ) {
					ob_start();
					Codevz_Theme::breadcrumbs();
					$breadcrumbs_content = $breadcrumbs_right ? '<div class="righter">' . ob_get_clean() . '</div>' : '<div class="breadcrumbs_container clr"><div class="row clr">' . ob_get_clean() . '</div></div>';
				}

				if ( $title === '5' ) {
					echo wp_kses_post( $breadcrumbs_content . '<div class="row clr">' . $title_content . '</div>' );
				} else {
					if ( $title_content ) {
						echo '<div class="' . esc_attr( $breadcrumbs_right ? 'lefter' : 'row clr' ) . '">' . wp_kses_post( $title_content ) . '</div>';
					}
					echo wp_kses_post( $breadcrumbs_content );
				}

				if ( $breadcrumbs_right ) {
					echo '</div></div>';
				}
				
			echo '</div>';
		}
		echo '</div>'; // page_cover
		$cover = ob_get_clean();
	} else {
		$cover = '<div class="page_cover"></div>';
	}

	if ( $cover_than_header === 'header_after_cover' ) {
		echo do_shortcode( $cover );
	}

	// Sticky header.
	$sticky = Codevz_Theme::option( 'sticky_header' );
	$sticky = $sticky ? ' cz_sticky_h' . $sticky : '';

	// Start Header.
	$custom_template = Codevz_Theme::option( 'header_elementor' );

	if ( $custom_template ) {

		echo '<header class="page_header clr' . $sticky . '">';

		echo '<div class="row clr">' . Codevz_Theme::get_page_as_element( $custom_template ) . '</div>';

		echo '</header>';

	} else if ( $header ) {

		echo '<header class="page_header clr' . $sticky . '">';

		Codevz_Theme::row([
			'id'		=> 'header_',
			'nums'		=> [ '1', '2', '3', '4', '5' ],
			'row'		=> 1,
			'left'		=> '_left',
			'right'		=> '_right',
			'center'	=> '_center'
		]);

		echo '</header>';

	}

	if ( $cover_than_header != 'header_after_cover' ) {

		echo do_shortcode( $cover );

	}
