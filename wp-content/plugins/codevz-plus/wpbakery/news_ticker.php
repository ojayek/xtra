<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * News Ticker
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_news_ticker {

	public function __construct( $name ) {
		$this->name = $name;
	}

	/**
	 * Shortcode settings
	 */
	public function in( $wpb = false ) {
		add_shortcode( $this->name, [ $this, 'out' ] );

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			//'deprecated' 	=> '4.6',
			'base'			=> $this->name,
			'name'			=> esc_html__( 'News Ticker', 'codevz' ),
			'description'	=> esc_html__( 'News ticker slider', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__('Type', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'type',
					'value'		=> array(
						esc_html__( 'Slide', 'codevz') 		=> 'slider',
						esc_html__( 'Fade', 'codevz') 		=> 'fade',
						esc_html__( 'Vertical', 'codevz') 	=> 'vertical',
					)
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__( "Badge", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "badge_title",
					"value"  		=> esc_html__( "TRENDING", 'codevz'),
					'admin_label' 	=> true
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Auto play seconds", 'codevz'),
					"value"  		=> '4',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 10 ),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "speed"
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_con',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_con_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_con_tablet' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_badge',
					"heading"     	=> esc_html__( "Badge", 'codevz'),
					'button' 		=> esc_html__( "Badge", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'position', 'top', 'right', 'bottom', 'left', 'color', 'font-family', 'font-size', 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_badge_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_links',
					"heading"     	=> esc_html__( "Links", 'codevz'),
					'button' 		=> esc_html__( "Links", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_links_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_meta',
					"heading"     	=> esc_html__( "Meta", 'codevz'),
					'button' 		=> esc_html__( "Meta", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_meta_mobile' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_arrows',
					'hover_id'	 	=> 'sk_arrows_hover',
					"heading"     	=> esc_html__( "Arrows", 'codevz'),
					'button' 		=> esc_html__( "Arrows", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_arrows_mobile' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_arrows_hover' ),

				// WP_Query
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__('Post type(s)', 'codevz'),
					'settings' 		=> array(
						'multiple'		=> true,
						'save_always'	=> true,
						'sortable' 		=> true,
						'groups' 		=> true,
						'unique_values' => true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'post_type',
					'std'			=> 'post',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> esc_html__("Orderby", "codevz"),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"	=> "orderby",
					"value"			=> array(
						__("Date", "codevz")	=> 'date',
						__("ID", "codevz")		=> 'ID',
						__("Random", "codevz") => 'rand',
						__("Author", "codevz") => 'author',
						__("Title", "codevz")	=> 'title',
						__("Name", "codevz")	=> 'name',
						__("Type", "codevz")	=> 'type',
						__("Modified", "codevz") => 'modified',
						__("Parent ID", "codevz") => 'parent',
						__("Comment Count", "codevz") => 'comment_count',
					),
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> esc_html__("Order", "codevz"),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"	=> "order",
					"value"			=> array(
						__("Descending", "codevz") => 'DESC',
						__("Ascending", "codevz") => 'ASC',
					),
					'group' 		=> esc_html__( 'Query', 'codevz' )
				), 
				array(
					"type"			=> "dropdown",
					"heading"		=> esc_html__("Category Taxonomy", "codevz"),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"	=> "cat_tax",
					"value"			=> get_taxonomies(),
					"std"			=> 'category',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__('Category(s)', 'codevz'),
					'settings' 		=> array(
						'multiple'		=> true,
						'save_always'	=> true,
						'sortable' 		=> true,
						'groups' 		=> true,
						'unique_values' => true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'cat',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__('Exclude Category(s)', 'codevz'),
					'settings' 		=> array(
						'multiple'		=> true,
						'save_always'	=> true,
						'sortable' 		=> true,
						'groups' 		=> true,
						'unique_values' => true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'cat_exclude',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					"type"			=> "dropdown",
					"heading"		=> esc_html__("Tags Taxonomy", "codevz"),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"	=> "tag_tax",
					"value"			=> get_taxonomies(),
					"std"			=> 'post_tag',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__('Tag', 'codevz'),
					'settings' 		=> array(
						'multiple'		=> false,
						'save_always'	=> true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'tag_id',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__('Exclude Tag', 'codevz'),
					'settings' 		=> array(
						'multiple'		=> false,
						'save_always'	=> true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'tag_exclude',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__( 'Filter by posts', 'codevz' ),
					'settings' 		=> array(
						'multiple'		=> true,
						'save_always'	=> true,
						'sortable' 		=> true,
						'groups' 		=> true,
						'unique_values' => true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'post__in',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'autocomplete',
					'heading' 		=> esc_html__( 'Filter by authors', 'codevz' ),
					'settings' 		=> array(
						'multiple'		=> true,
						'save_always'	=> true,
						'sortable' 		=> true,
						'groups' 		=> true,
						'unique_values' => true,
					),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'author__in',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),
				array(
					'type' 			=> 'textfield',
					'heading' 		=> esc_html__('Search keyword', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 's',
					'group' 		=> esc_html__( 'Query', 'codevz' )
				),

				// Advanced
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Hide on Desktop?', 'codevz' ),
					'param_name' 	=> 'hide_on_d',
					'edit_field_class' => 'vc_col-xs-4',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				), 
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Hide on Tablet?', 'codevz' ),
					'param_name' 	=> 'hide_on_t',
					'edit_field_class' => 'vc_col-xs-4',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				), 
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Hide on Mobile?', 'codevz' ),
					'param_name' 	=> 'hide_on_m',
					'edit_field_class' => 'vc_col-xs-4',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Animation & Class', 'codevz' ),
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				Codevz_Plus::wpb_animation_tab( false ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_brfx',
					"heading"     	=> esc_html__( "Block Reveal", 'codevz'),
					'button' 		=> esc_html__( "Block Reveal", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99 hidden',
					'group' 	=> esc_html__( 'Advanced', 'codevz' ),
					'settings' 		=> array( 'background' )
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Animation Delay", 'codevz'),
					"description" 	=> 'e.g. 500ms',
					"param_name"  	=> "anim_delay",
					'options' 		=> array( 'unit' => 'ms', 'step' => 100, 'min' => 0, 'max' => 5000 ),
					'edit_field_class' => 'vc_col-xs-6',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__( "Extra Class", 'codevz' ),
					"param_name"  	=> "class",
					'edit_field_class' => 'vc_col-xs-6',
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
			)

		);

		return $wpb ? vc_map( $settings ) : $settings;
	}

	/**
	 *
	 * Shortcode output
	 * 
	 * @return string
	 * 
	 */
	public function out( $atts, $content = '' ) {
		$atts = Codevz_Plus::shortcode_atts( $this, $atts );

		// ID
		if ( ! $atts['id'] ) {
			$atts['id'] = Codevz_Plus::uniqid();
			$public = 1;
		}

		// Styles
		if ( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {
			$css_id = '#' . $atts['id'];

			$css_array = array(
				'sk_brfx' 			=> $css_id . ':before',
				'sk_con' 			=> $css_id . ' .cz_ticker',
				'sk_badge' 			=> $css_id . ' .cz_ticker_badge',
				'sk_links' 			=> $css_id . ' a',
				'sk_meta' 			=> $css_id . ' small',
				'sk_arrows' 		=> $css_id . ' button',
				'sk_arrows_hover' 	=> $css_id . ' button:hover',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			$css .= $atts['anim_delay'] ? $css_id . '{animation-delay:' . $atts['anim_delay'] . '}' : '';

		} else {
			Codevz_Plus::load_font( $atts['sk_badge'] );
			Codevz_Plus::load_font( $atts['sk_links'] );
			Codevz_Plus::load_font( $atts['sk_meta'] );
		}

		// Slick Slider
		$speed = (int) $atts['speed'];
		$slick = array(
			'slidesToShow'		=> 1, 
			'slidesToScroll'	=> 1, 
			'fade'				=> false, 
			'vertical'			=> false, 
			'infinite'			=> true, 
			'speed'				=> 1000, 
			'autoplay'			=> true, 
			'autoplaySpeed'		=> $speed . '000', 
			'dots'				=> false,
		);

		if ( $atts['type'] === 'slider' ) {
			$slick = ' data-slick=\'' . json_encode(array_merge( $slick, array() )) . '\'';
		} else if ( $atts['type'] === 'vertical' ) {
			$slick = ' data-slick=\'' . json_encode(array_merge( $slick, array( 'verticalSwiping' => true, 'vertical' => true ) )) . '\'';
		} else {
			$slick = ' data-slick=\'' . json_encode(array_merge( $slick, array( 'fade' => true ) )) . '\'';
		}

		// Classes
		$classes = array();
		$classes[] = 'cz_ticker arrows_tr arrows_inner';
		
		// Out
		$out = '<div id="' . $atts['id'] . '" class="' . $atts['id'] . ' relative clr"' . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>';
		$out .= $atts['badge_title'] ? '<div class="cz_ticker_badge">' . $atts['badge_title'] . '</div>' : '';
		$out .= '<div' . Codevz_Plus::classes( $atts, $classes ) . $slick . ' data-slick-prev="fa fa-angle-left" data-slick-next="fa fa-angle-right">';

		// Categories
		if ( $atts['cat'] && ! empty( $atts['cat_tax'] ) ) {
			$tax_query[] = array(
				'taxonomy'  => $atts['cat_tax'],
				'field'     => 'term_id',
				'terms'     => explode( ',', str_replace( ', ', ',', $atts['cat'] ) )
			);
		}

		// Exclude Categories
		if ( $atts['cat_exclude'] && ! empty( $atts['cat_tax'] ) ) {
			$tax_query[] = array(
				'taxonomy'  => $atts['cat_tax'],
				'field'     => 'term_id',
				'terms'     => explode( ',', str_replace( ', ', ',', $atts['cat_exclude'] ) ),
				'operator' 	=> 'NOT IN',
			);
		}

		// Tags
		if ( $atts['tag_id'] && ! empty( $atts['tag_tax'] ) ) {
			$tax_query[] = array(
				'taxonomy'  => $atts['tag_tax'],
				'field'     => 'term_id',
				'terms'     => explode( ',', str_replace( ', ', ',', $atts['tag_id'] ) )
			);
		}

		// Exclude Tags
		if ( $atts['tag_exclude'] && ! empty( $atts['tag_tax'] ) ) {
			$tax_query[] = array(
				'taxonomy'  => $atts['tag_tax'],
				'field'     => 'term_id',
				'terms'     => explode( ',', str_replace( ', ', ',', $atts['tag_exclude'] ) ),
				'operator' 	=> 'NOT IN',
			);
		}

		// Post types.
		$atts['post_type'] = $atts['post_type'] ? explode( ',', str_replace( ', ', ',', $atts['post_type'] ) ) : 'post';

		$q = new WP_Query( array_filter( $atts ) );

		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				$out .= '<div class="cz_news_ticker_post"><a href="' . get_the_permalink() . '">' . get_the_title() . '</a> <small>' . get_the_date() . '</small></div>';
			}
		}

		wp_reset_postdata();
		$out .= '</div></div>';

		return Codevz_Plus::_out( $atts, $out, 'slick( true )', $this->name, 'cz_carousel' );
	}

}