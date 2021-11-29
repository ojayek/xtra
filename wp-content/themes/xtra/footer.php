<?php 

	// Footer
	if ( is_404() ) {

		$_404 = get_page_by_title( '404' );
		if ( ! empty( $_404->ID ) ) {
			$footer = $_404;
		} else {
			$_404 = get_page_by_path( 'page-404' );
			if ( ! empty( $_404->ID ) ) {
				$footer = $_404;
			}
		}

		$footer = isset( $footer->ID ) ? !Codevz_Theme::meta( $footer->ID, 'hide_footer' ) : 1;

	} else if ( is_single() || is_page() ) {
		$footer = !Codevz_Theme::meta( false, 'hide_footer' );
	} else {
		$footer = 1;
	}

	// Footer content
	$custom_template = Codevz_Theme::option( 'footer_elementor' );

	if ( $custom_template ) {

		echo '<footer class="page_footer' . esc_attr( Codevz_Theme::option( 'fixed_footer' ) ? ' cz_fixed_footer' : '' ) . '">';

		echo '<div class="row clr">' . Codevz_Theme::get_page_as_element( $custom_template ) . '</div>';

		echo '</footer>';

	} else if ( $footer ) {

		echo '<footer class="page_footer' . esc_attr( Codevz_Theme::option( 'fixed_footer' ) ? ' cz_fixed_footer' : '' ) . '">';

		// Focus to section.
		if ( Codevz_Theme::$preview ) {
			echo '<i class="xtra-section-focus fas fa-cog" data-section="footer_widgets"></i>';
		}

		// Row before footer
		Codevz_Theme::row([
			'id'		=> 'footer_',
			'nums'		=> [ '1' ],
			'row'		=> 1,
			'left'		=> '_left',
			'right'		=> '_right',
			'center'	=> '_center'
		]);

		// Footer widgets
		$footer_layout = Codevz_Theme::option( 'footer_layout' );
		if ( $footer_layout ) {
			$layout = explode( ',', $footer_layout );
			$count = count( $layout );
			$is_widget = 0;

			foreach ( $layout as $num => $col ) {
				$num++;
				if ( is_active_sidebar( 'footer-' . $num ) ) {
					$is_widget = 1;
				}
			}

			foreach ( $layout as $num => $col ) {

				$num++;

				if ( ! $is_widget ) {
					break;
				}

				if ( $num === 1 ) {
					echo '<div class="cz_middle_footer"><div class="row clr">';
				}

				if ( is_active_sidebar( 'footer-' . $num ) ) {
					echo '<div class="col ' . esc_attr( $col ) . ' sidebar_footer-' . esc_attr( $num ) . ' clr">';
					dynamic_sidebar( 'footer-' . $num );  
					echo '</div>';
				} else {
					echo '<div class="col ' . esc_attr( $col ) . ' sidebar_footer-' . esc_attr( $num ) . ' clr">&nbsp;</div>';
				}

				if ( $num === $count ) {
					echo '</div></div>';
				}

			}

		}

		// Row after footer
		Codevz_Theme::row([
			'id'		=> 'footer_',
			'nums'		=> [ '2' ],
			'row'		=> 1,
			'left'		=> '_left',
			'right'		=> '_right',
			'center'	=> '_center'
		]);

		echo '</footer>';
	}

	echo '</div></div>'; // layout
?>

		<?php wp_footer(); ?>

	</body>
	
</html>