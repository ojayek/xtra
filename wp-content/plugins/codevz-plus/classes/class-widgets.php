<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

/**
 * Codevz custom widgets
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_Widget {

	public static function settings( $widget, $data, $fields = null ) {
		$fields = $fields ? $fields : $widget->fields();

		foreach( $fields as $field ) {
			$name = $field[ 'name' ];
			$field[ 'id' ] = $field[ 'name' ] = $widget->get_field_name( $name );

			if ( isset( $field[ 'hover' ] ) ) {
				$field[ 'hover_id' ] = $widget->get_field_name( $name ) . $field[ 'hover' ];
			}

			$default = ( isset( $field[ 'default' ] ) ? $field[ 'default' ] : '' );
			$out = csf_add_field( $field, ( isset( $data[ $name ] ) ? $data[ $name ] : $default ) );

			echo isset( $field[ 'split' ] ) ? '<div class="cz-w2 csf-field clearfix">' . $out . '</div>' : $out;	
		}
	}

	public static function update( $widget, $data ) {
		foreach( $widget->fields() as $field ) {
			$name = $field[ 'name' ];
			$new[ $name ] = isset( $new[ $name ] ) ? $new[ $name ] : '';
		}

		return $new;
	}

	public static function output( $shortcode, $args, $data, $out = 9 ) {

		// Shortcode.
		if ( $shortcode && $data ) {
			$out = '[' . $shortcode . ' ';

			foreach( $data as $key => $value ) {
				if ( $value && $key !== 'title' ) {

					if ( is_array( $value ) && $key === 'items' && $shortcode === 'cz_stylish_list' ) {
						$value = json_decode( json_encode( $value ), true );

						foreach ( $value as $val => $v ) {
							if ( ! empty( $value[ $val ]['link'] ) ) {
								$value[ $val ]['link'] = 'url:' . urlencode( $value[ $val ]['link'] ) . '|||';
							}
						}
					}

					if ( is_array( $value ) && $key === 'social' && $shortcode === 'cz_social_icons' ) {
						$value = json_decode( json_encode( $value ), true );
					}

					$value = is_array( $value ) ? urlencode( json_encode( $value ) ) : $value;

					$out .= $key . '="' . $value . '" ';
				}
			}

			// Shortcode content.
			if ( isset( $data['content'] ) ) {
				$out .= ']' . $data['content'];
			} else {
				$out .= ']';
			}

			// Close shortcode.
			$out .= '[/' . $shortcode . ']';
		}

		// Manually
		if ( $out !== 9 ) {
			// Widget args
			extract( $args );
			ob_start();

			// Before widget
			echo $before_widget;

			// Title
			if ( ! empty( $data['title'] ) ) {
				echo $before_title . apply_filters( 'widget_title', $data['title'] ) . $after_title;
			}

			// Widget content
			echo do_shortcode( $out );

			// After widget
			echo $after_widget;
			echo apply_filters( 'widget_text', ob_get_clean() );
		}
	}
}

/**
 *
 * Add new options for widgets
 *
 */
function codevz_all_widgets_add_options( $widget, $return, $data ) {

	// Widget search: button StyleKit
	if ( $widget->id_base === 'search' ) {

		Codevz_Widget::settings( $widget, $data, [
			[
				'name'  	=> 'czsk_button',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Button', 'codevz' ),
				'button' 	=> esc_html__( 'Button', 'codevz' ),
				'settings' 	=> [ 'color', 'background', 'padding', 'margin', 'border' ]
			],
			[
				'name'  	=> 'czsk_button_hover',
				'type'  	=> 'cz_sk_hidden'
			],
		]);

	}

	// General StyleKit for all widgets.
	Codevz_Widget::settings( $widget, $data, [
		[
			'name'  	=> 'c_on_mobile',
			'type'  	=> 'switcher',
			'default'  	=> '1',
			'title' 	=> esc_html__( 'Center on mobile?', 'codevz' ),
		],
		[
			'name'  	=> 'hide_on_mobile',
			'type'  	=> 'switcher',
			'title' 	=> esc_html__( 'Hide on Mobile?', 'codevz' ),
		],
		[
			'name'  	=> 'czsk',
			'hover'  	=> '_hover',
			'type'  	=> 'cz_sk',
			'title' 	=> esc_html__( 'Container', 'codevz' ),
			'button' 	=> esc_html__( 'Container', 'codevz' ),
			'settings' 	=> [ 'color', 'background', 'padding', 'margin', 'border' ]
		],
		[
			'name'  	=> 'czsk_hover',
			'type'  	=> 'cz_sk_hidden'
		],
		[
			'name'  	=> 'czsk_tablet',
			'type'  	=> 'cz_sk_hidden'
		],
		[
			'name'  	=> 'czsk_mobile',
			'type'  	=> 'cz_sk_hidden'
		],
	]);

}
add_filter( 'in_widget_form', 'codevz_all_widgets_add_options', 10, 3 );

/**
 * Save custom options for widgets
 */
function codevz_widget_update_callback( $current, $new ) {

	if ( ! isset( $new['hide_on_mobile'] ) ) {
		$new['hide_on_mobile'] = '';
	}

	if ( ! isset( $new['c_on_mobile'] ) ) {
		$new['c_on_mobile'] = '';
	}

	foreach( $new as $key => $value ) {
		
		$current[ $key ] = $value;

	}

	return $current;
}
add_filter( 'widget_update_callback', 'codevz_widget_update_callback', 10, 2 );

/**
 * Output of custom options for widget
 */
function codevz_widget_display_callback( $data, $widget_class, $args ) {

	if ( $data == false ) {
		return $data;
	}

	$css = $inline = '';
	if ( ! empty( $widget_class->id ) ) {
		$id = $widget_class->id;

		if ( ! empty( $data['czsk'] ) ) {
			$css .= '#' . $id . '{' . Codevz_Plus::sk_inline_style( $data['czsk'] ) . '}';
		}
		if ( ! empty( $data['czsk_hover'] ) ) {
			$css .= '#' . $id . ':hover{' . Codevz_Plus::sk_inline_style( $data['czsk_hover'] ) . '}';
		}
		if ( ! empty( $data['czsk_tablet'] ) ) {
			$css .= '@media screen and (max-width:768px){#' . $id . '{' . Codevz_Plus::sk_inline_style( $data['czsk_tablet'] ) . '}}';
		}
		if ( ! empty( $data['czsk_mobile'] ) ) {
			$css .= '@media screen and (max-width:480px){#' . $id . '{' . Codevz_Plus::sk_inline_style( $data['czsk_mobile'] ) . '}}';
		}

		if ( ! empty( $data['czsk_button'] ) ) {
			$css .= '#' . $id . ' form button{' . Codevz_Plus::sk_inline_style( $data['czsk_button'] ) . '}';
		}
		if ( ! empty( $data['czsk_button_hover'] ) ) {
			$css .= '#' . $id . ' form button:hover{' . Codevz_Plus::sk_inline_style( $data['czsk_button_hover'] ) . '}';
		}

		// CSS output.
		if ( is_customize_preview() ) {
			$args['after_widget'] = '<style>' . $css . '</style>' . $args['after_widget'];
			$css = '';
		} else {
			$css = $css ? 'data-cz-style="' . $css . '" ' : '';
		}
	}

	// Widget classes.
	$new_class = $css . 'class="';
	$new_class .= empty( $data['hide_on_mobile'] ) ? '' : 'hide_on_mobile ';

	if ( ! isset( $data['c_on_mobile'] ) || ! empty( $data['c_on_mobile'] ) ) {
		$new_class .= 'center_on_mobile ';
	}

	// Output
	$args['before_widget'] = str_replace( 'class="', $new_class, $args['before_widget'] );
	$widget_class->widget( $args, $data );

	return false;
}
add_filter( 'widget_display_callback', 'codevz_widget_display_callback', 10, 3 );

/**
 *
 * Widget: Working hours
 * 
 */
class Codevz_Widget_Working_Hours extends WP_Widget {

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Working Hours', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'codevz-widget-working-hours' 
			]
		);
	}

	// Output
	public function widget( $args, $data ) {
		Codevz_Widget::output( 'cz_working_hours', $args, $data );
	}
	
	// Update
	public function update( $data, $new ) {
		Codevz_Widget::update( $this, $new );
	}

	// Settings
	public function form( $data ) {
		Codevz_Widget::settings( $this, $data );
	}

	// Fields
	public function fields() {
		return [
			[
				'name'		=> 'title',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Title', 'codevz' ),
				'default' 	=> esc_html__( 'Working Hours', 'codevz' )
			],
			[
				'name'		=> 'content',
				'type'		=> 'textarea',
				'title'		=> esc_html__( 'Description', 'codevz' )
			],
			[
				'name'            => 'items',
				'type'            => 'group',
				'title' 		  => '',
				'button_title'    => esc_html__( 'Add item', 'codevz' ),
				'fields'          => [
					[
						'id' 			=> 'left_text',
						'type' 			=> 'text',
						'title' 		=> esc_html__('Left', 'codevz'),
						'default' 		=> 'Monday',
					],
					[
						'id' 			=> 'right_text',
						'type' 			=> 'text',
						'title' 		=> esc_html__('Right', 'codevz'),
						'default' 		=> '9:00 to 16:30',
					],
					[
						'id' 			=> 'sub',
						'type' 			=> 'text',
						'title' 		=> esc_html__('Subtitle', 'codevz')
					],
					[
						'id' 			=> 'badge',
						'type' 			=> 'text',
						'title' 		=> esc_html__('Badge', 'codevz')
					],
					[
						'id' 			=> 'icon',
						'type' 			=> 'icon',
						'title' 		=> esc_html__('Icon', 'codevz')
					],
				],
			],
			[
				'name' 		=> 'between_texts',
				'type' 		=> 'switcher',
				'title' 	=> esc_html__( 'Line Between', 'codevz' )
			],
			[
				'name'  	=> 'sk_con',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Container', 'codevz' ),
				'button' 	=> esc_html__( 'Container', 'codevz' ),
				'settings' 	=> [ 'background', 'border', 'padding' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_con_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_con_mobile' ],
			[
				'name'  	=> 'sk_line',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Line', 'codevz' ),
				'button' 	=> esc_html__( 'Line', 'codevz' ),
				'settings' 	=> [ 'margin', 'border', 'border-color' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_line_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_line_mobile' ],
			[
				'name'  	=> 'sk_left',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Left', 'codevz' ),
				'button' 	=> esc_html__( 'Left', 'codevz' ),
				'settings' 	=> [ 'color', 'font-family', 'font-size', 'background', 'padding' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_left_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_left_mobile' ],
			[
				'name'  	=> 'sk_right',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Right', 'codevz' ),
				'button' 	=> esc_html__( 'Right', 'codevz' ),
				'settings' 	=> [ 'color', 'font-family', 'font-size', 'background', 'padding' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_right_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_right_mobile' ],
			[
				'name'  	=> 'sk_badge',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Badge', 'codevz' ),
				'button' 	=> esc_html__( 'Badge', 'codevz' ),
				'settings' 	=> [ 'color', 'font-size', 'background' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_badge_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_badge_mobile' ],
			[
				'name'  	=> 'sk_sub',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Subtitle', 'codevz' ),
				'button' 	=> esc_html__( 'Subtitle', 'codevz' ),
				'settings' 	=> [ 'color', 'font-size', 'background' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_sub_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_sub_mobile' ],
			[
				'name'  	=> 'sk_icon',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Icon', 'codevz' ),
				'button' 	=> esc_html__( 'Icon', 'codevz' ),
				'settings' 	=> [ 'color', 'font-size', 'background' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icon_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icon_mobile' ],
		];
	}
}

/**
 *
 * Widget: Stylish List
 * 
 */
class Codevz_Widget_Stylish_List extends WP_Widget {

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Stylish List', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'codevz-widget-stylish-list' 
			]
		);
	}

	// Output
	public function widget( $args, $data ) {
		Codevz_Widget::output( 'cz_stylish_list', $args, $data );
	}
	
	// Update
	public function update( $data, $new ) {
		Codevz_Widget::update( $this, $new );
	}

	// Settings
	public function form( $data ) {
		Codevz_Widget::settings( $this, $data );
	}

	// Widget fields
	public function fields() {
		return [
			[
				'name'		=> 'title',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Title', 'codevz' ),
				'default' 	=> esc_html__( 'Stylish List', 'codevz' )
			],
			[
				'name'		=> 'content',
				'type'		=> 'textarea',
				'title'		=> esc_html__( 'Description', 'codevz' )
			],
			[
				'name'            => 'items',
				'type'            => 'group',
				'title' 		  => '',
				'button_title'    => esc_html__( 'Items', 'codevz' ),
				'fields'          => [
					[
						'id'          => 'title',
						'type'        => 'text',
						'title'       => esc_html__('Title', 'codevz')
					],
					[
						'id'          => 'subtitle',
						'type'        => 'text',
						'title'       => esc_html__('Subtitle', 'codevz')
					],
					[
						'id'          => 'icon',
						'type'        => 'icon',
						'title'       => esc_html__('Icon', 'codevz')
					],
					[
						'id'          => 'link',
						'type'        => 'text',
						'title'       => esc_html__('Link', 'codevz')
					],
				],
			],
			[
				'name'        => 'default_icon',
				'type'        => 'icon',
				'title'       => esc_html__('Default Icon', 'codevz')
			],
			[
				'name'  	=> 'sk_overall',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Container', 'codevz' ),
				'button' 	=> esc_html__( 'Container', 'codevz' ),
				'settings' 	=> [ 'background', 'padding', 'margin', 'border', 'box-shadow' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_overall_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_overall_mobile' ],
			[
				'name'  	=> 'sk_lists',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Title', 'codevz' ),
				'button' 	=> esc_html__( 'Title', 'codevz' ),
				'settings' 	=> [ 'color', 'font-size', 'margin' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_lists_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_lists_mobile' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_lists_hover' ],
			[
				'name'  	=> 'sk_subtitle',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Subtitle', 'codevz' ),
				'button' 	=> esc_html__( 'Subtitle', 'codevz' ),
				'settings' 	=> [ 'color', 'font-size', 'margin' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_subtitle_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_subtitle_mobile' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_subtitle_hover' ],
			[
				'name'  	=> 'sk_icons',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Icons', 'codevz' ),
				'button' 	=> esc_html__( 'Icons', 'codevz' ),
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icons_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icons_mobile' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icons_hover' ],
			[
				'name'  	=> 'icon_hover_fx',
				'type'  	=> 'select',
				'title' 	=> esc_html__( 'Icons Hover', 'codevz' ),
				'options' 	=> [
					'cz_sl_icon_hover_none' 		=> esc_html__( 'None', 'codevz' ),
					'cz_sl_icon_hover_zoom_in' 		=> esc_html__( 'Zoom in', 'codevz' ),
					'cz_sl_icon_hover_zoom_out' 	=> esc_html__( 'Zoom out', 'codevz' ),
					'cz_sl_icon_hover_blur' 		=> esc_html__( 'Blur', 'codevz' ),
					'cz_sl_icon_hover_flash' 		=> esc_html__( 'Flash', 'codevz' ),
					'cz_sl_icon_hover_absorber' 	=> esc_html__( 'Absorber', 'codevz' ),
					'cz_sl_icon_hover_wobble' 		=> esc_html__( 'Wobble', 'codevz' ),
					'cz_sl_icon_hover_zoom_in_fade' => esc_html__( 'Zoom in fade', 'codevz' ),
					'cz_sl_icon_hover_zoom_out_fade' => esc_html__( 'Zoom out fade', 'codevz' ),
					'cz_sl_icon_hover_push_in' 		=> esc_html__( 'Push in', 'codevz' ),
				]
			],
		];
	}
}

/**
 * Widget: Social Icons
 */
class Codevz_Widget_Social_Icons extends WP_Widget {

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Social Icons', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'codevz-widget-social-icons' 
			]
		);
	}

	// Output
	public function widget( $args, $data ) {

		// Fix icons hover.
		if ( isset( $data['sk_icons_hover'] ) ) {
			$data['sk_hover'] = Codevz_Plus::sk_inline_style( $data['sk_icons_hover'] );
		}

		Codevz_Widget::output( 'cz_social_icons', $args, $data );
	}
	
	// Update
	public function update( $data, $new ) {
		Codevz_Widget::update( $this, $new );
	}

	// Settings
	public function form( $data ) {
		Codevz_Widget::settings( $this, $data );
	}

	// Widget fields
	public function fields() {
		return [
			[
				'name'		=> 'title',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Title', 'codevz' ),
				'default' 	=> esc_html__( 'Social icons', 'codevz' )
			],
			[
				'name'            => 'social',
				'type'            => 'group',
				'title' 		  => '',
				'button_title'    => esc_html__( 'Icons', 'codevz' ),
				'fields'          => [
					[
						'id'          => 'title',
						'type'        => 'text',
						'title'       => esc_html__('Title', 'codevz')
					],
					[
						'id'          => 'icon',
						'type'        => 'icon',
						'title'       => esc_html__('Icon', 'codevz')
					],
					[
						'id'          => 'link',
						'type'        => 'text',
						'title'       => esc_html__('Link', 'codevz')
					],
				]
			],
			[
				'name'        => 'position',
				'type'        => 'select',
				'title'       => esc_html__('Position', 'codevz'),
				'options'	  => [
					'tal' 		=> esc_html__('Left', 'codevz'),
					'tac' 		=> esc_html__('Center', 'codevz'),
					'tar' 		=> esc_html__('Right', 'codevz'),
				]
			],
			[
				'name'        => 'tooltip',
				'type'        => 'select',
				'title'       => esc_html__('Tooltip', 'codevz'),
				'help' 		  => esc_html__( 'StyleKit located in Theme Options > General > Colors & Styles', 'codevz' ),
				'options'	  => [
					'' 							 	=> esc_html__('~ Default ~', 'codevz'),
					'cz_tooltip cz_tooltip_up' 	 	=> esc_html__('Up', 'codevz'),
					'cz_tooltip cz_tooltip_down' 	=> esc_html__('Down', 'codevz'),
					'cz_tooltip cz_tooltip_left' 	=> esc_html__('Left', 'codevz'),
					'cz_tooltip cz_tooltip_right' 	=> esc_html__('Right', 'codevz'),
				]
			],
			[
				'name'        => 'fx',
				'type'        => 'select',
				'title'       => esc_html__( 'Icons Hover', 'codevz' ),
				'options'	  => [
					'' 					=> esc_html__('~ Default ~', 'codevz'),
					'cz_social_fx_0' 	 => esc_html__('ZoomIn', 'codevz'),
					'cz_social_fx_1' 	 => esc_html__('ZoomOut', 'codevz'),
					'cz_social_fx_2' 	 => esc_html__('Bottom to Top', 'codevz'),
					'cz_social_fx_3' 	 => esc_html__('Top to Bottom', 'codevz'),
					'cz_social_fx_4' 	 => esc_html__('Left to Right', 'codevz'),
					'cz_social_fx_5' 	 => esc_html__('Right to Left', 'codevz'),
					'cz_social_fx_6' 	 => esc_html__('Rotate', 'codevz'),
					'cz_social_fx_7' 	 => esc_html__('Infinite Shake', 'codevz'),
					'cz_social_fx_8' 	 => esc_html__('Infinite Wink', 'codevz'),
					'cz_social_fx_9' 	 => esc_html__('Quick Bob', 'codevz'),
					'cz_social_fx_10' 	 => esc_html__('Flip Horizontal', 'codevz'),
					'cz_social_fx_11' 	 => esc_html__('Flip Vertical', 'codevz'),
				]
			],
			[
				'name'        => 'inline_title',
				'type'        => 'switcher',
				'title'       => esc_html__('Inline Title', 'codevz')
			],
			[
				'name'        => 'color_mode',
				'type'        => 'select',
				'title'       => esc_html__( 'Color Mode', 'codevz' ),
				'options'	  => [
					'' 							=> esc_html__('~ Default ~', 'codevz'),
					'cz_social_colored' 		=> esc_html__( 'Brand Colors', 'codevz' ),
					'cz_social_colored_hover' 	=> esc_html__( 'Brand Colors on Hover', 'codevz' ),
					'cz_social_colored_bg' 		=> esc_html__( 'Brand Background', 'codevz' ),
					'cz_social_colored_bg_hover' => esc_html__( 'Brand Background on Hover', 'codevz' ),
				]
			],
			[
				'name'  	=> 'sk_con',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Container', 'codevz' ),
				'button' 	=> esc_html__( 'Container', 'codevz' ),
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_con_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_con_mobile' ],
			[
				'name'  	=> 'sk_icons',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Icons', 'codevz' ),
				'button' 	=> esc_html__( 'Icons', 'codevz' ),
				'settings' 	=> [ 'width', 'color', 'font-size', 'background', 'border' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icons_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icons_mobile' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icons_hover' ],
			[
				'name'  	=> 'sk_inner_icon',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Inner Icons', 'codevz' ),
				'button' 	=> esc_html__( 'Inner Icons', 'codevz' ),
				'settings' 	=> [ 'width', 'height', 'color', 'line-height', 'font-size', 'background', 'padding', 'border' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_inner_icon_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_inner_icon_mobile' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_inner_icon_hover' ],
			[
				'name'  	=> 'sk_title',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Inline Title', 'codevz' ),
				'button' 	=> esc_html__( 'Inline Title', 'codevz' ),
				'settings' 	=> [ 'color', 'font-family', 'font-size' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_title_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_title_mobile' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_title_hover' ],
		];
	}
}


/**
 * Widget: Newsletter
 */
class Xtra_Widget_Newsletter extends WP_Widget {

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Newsletter', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'xtra-widget-newsletter' 
			]
		);
	}

	// Output
	public function widget( $args, $data ) {
		Codevz_Widget::output( 'cz_subscribe', $args, $data );
	}
	
	// Update
	public function update( $data, $new ) {
		Codevz_Widget::update( $this, $new );
	}

	// Settings
	public function form( $data ) {
		Codevz_Widget::settings( $this, $data );
	}

	// Widget fields
	public function fields() {
		return [
			[
				'name'		=> 'title',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Title', 'codevz' ),
				'default' 	=> esc_html__( 'Newsletter', 'codevz' )
			],
			[
				'name'		=> 'content',
				'type'		=> 'textarea',
				'title'		=> esc_html__( 'Description', 'codevz' )
			],
			[
				'name'        => 'style',
				'type'        => 'select',
				'title'       => esc_html__( 'Style', 'codevz' ),
				'options'	  => [
					'' 						=> esc_html__( 'Square', 'codevz' ),
					'cz_subscribe_round' 	=> esc_html__( 'Up', 'codevz' ),
					'cz_subscribe_round_2' 	=> esc_html__( 'Round 2', 'codevz' ),
					'cz_subscribe_relative' => esc_html__( 'Square, Button next line', 'codevz' ),
					'cz_subscribe_relative cz_subscribe_round' 	=> esc_html__( 'Round, Button next line', 'codevz' ),
				],
				'default' 	=> 'cz_subscribe_relative'
			],
			[
				'name'        => 'position',
				'type'        => 'select',
				'title'       => esc_html__( 'Position', 'codevz' ),
				'options'	  => [
					'' 			=> esc_html__( '~ Default ~', 'codevz' ),
					'center' 	=> esc_html__( 'Center', 'codevz' ),
					'right' 	=> esc_html__( 'Right', 'codevz' ),
				]
			],
			[
				'name'        => 'btn_position',
				'type'        => 'select',
				'title'       => esc_html__( 'Button Position', 'codevz' ),
				'options'	  => [
					'' 							=> esc_html__( '~ Default ~', 'codevz' ),
					'cz_subscribe_btn_center' 	=> esc_html__( 'Center', 'codevz' ),
					'cz_subscribe_btn_right' 	=> esc_html__( 'Right', 'codevz' ),
				]
			],
			[
				'name' 		=> 'action',
				'type' 		=> 'text',
				'title' 	=> esc_html__( 'Action URL', 'codevz' ),
				'help' 		=> esc_html__( 'Mailchimp action or Google feedburner url', 'codevz' ),
			],
			[
				'name' 		=> 'placeholder',
				'type' 		=> 'text',
				'title' 	=> esc_html__( 'Placeholder', 'codevz' ),
				'default' 	=> esc_html__( 'Enter your email ...', 'codevz' ),
			],
			[
				'name'        => 'input_attr',
				'type'        => 'select',
				'title'       => esc_html__( 'Type', 'codevz' ),
				'options'	  => [
					'email'		=> 'email',
					'text'		=> 'text',
					'number'	=> 'number',
					'search'	=> 'search',
					'tel'		=> 'tel',
					'time'		=> 'time',
					'date'		=> 'date',
					'url'		=> 'url',
					'password'	=> 'password',
				]
			],
			[
				'name' 		=> 'name_attr',
				'type' 		=> 'text',
				'title' 	=> esc_html__( 'Name Attribute', 'codevz' ),
				'default' 	=> 'MERGE0',
				'help' 		=> esc_html__( 'This is useful for mailchip, You can get your form input name from mailchip', 'codevz')
			],
			[
				'name' 		=> 'btn_title',
				'type' 		=> 'text',
				'title' 	=> esc_html__( 'Button Title', 'codevz' ),
				'default' 	=> esc_html__( 'Join Now', 'codevz' ),
			],
			[
				'name'        => 'icon',
				'type'        => 'icon',
				'title'       => esc_html__( 'Button Icon', 'codevz' )
			],
			[
				'name'  	=> 'sk_overall',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Container', 'codevz' ),
				'button' 	=> esc_html__( 'Container', 'codevz' ),
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_overall_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_overall_mobile' ],
			[
				'name'  	=> 'sk_input',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Input', 'codevz' ),
				'button' 	=> esc_html__( 'Input', 'codevz' ),
				'settings' 	=> [ 'text-align', 'color', 'font-size', 'background', 'border' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_input_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_input_mobile' ],
			[
				'name'  	=> 'sk_button',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Button', 'codevz' ),
				'button' 	=> esc_html__( 'Button', 'codevz' ),
				'settings' 	=> [ 'color', 'font-size', 'background', 'border', 'box-shadow' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_button_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_button_mobile' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_button_hover' ],
			[
				'name'  	=> 'sk_icon',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Button Icon', 'codevz' ),
				'button' 	=> esc_html__( 'Button Icon', 'codevz' ),
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icon_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icon_mobile' ],
		];
	}
}

/**
 *
 * Facebook widget
 *
 */
class CodevzFacebook extends WP_Widget {

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Facebook', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'codevz-widget-facebook' 
			]
		);
	}

	// Output
	public function widget( $args, $data ) {
		ob_start(); ?>
			<div id="fb-root"></div><div class="fb-page" data-href="<?php echo isset( $data['url'] ) ? esc_url( $data['url'] ) : ''; ?>" data-small-header="<?php echo isset( $data['head'] ) ? $data['head'] : ''; ?>" data-adapt-container-width="true" data-hide-cover="<?php echo isset( $data['cover'] ) ? $data['cover'] : ''; ?>" data-hide-cta="false" data-show-facepile="<?php echo isset( $data['faces'] ) ? $data['faces'] : ''; ?>" data-show-posts="<?php echo isset( $data['posts'] ) ? $data['posts'] : ''; ?>">
			</div><script>(function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(d.getElementById(id))return;js=d.createElement(s);js.id=id;js.src="//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=376512092550885";fjs.parentNode.insertBefore(js,fjs)}(document,'script','facebook-jssdk'));</script>
		<?php 

		echo empty( $data['url'] ) ? esc_html__( 'Please insert correct facebook url page.', 'codevz' ) : '';
		Codevz_Widget::output( 0, $args, $data, ob_get_clean() );
	}
	
	// Update
	public function update( $data, $new ) {
		Codevz_Widget::update( $this, $new );
	}

	// Settings
	public function form( $data ) {
		Codevz_Widget::settings( $this, $data );
	}

	public function fields() {
		return [
			[
				'name'		=> 'title',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Title', 'codevz' ),
				'default' 	=> esc_html__( 'Like us on Facebook', 'codevz' )
			],
			[
				'name'		=> 'url',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Facebook Page URL', 'codevz' ),
				'default' 	=> 'https://facebook.com/xtratheme'
			],
			[
				'name'		=> 'head',
				'type'		=> 'switcher',
				'title'		=> esc_html__( 'Show Header?', 'codevz' )
			],
			[
				'name'		=> 'posts',
				'type'		=> 'switcher',
				'title'		=> esc_html__( 'Show Posts?', 'codevz' ),
				'default'	=> true
			],
			[
				'name'		=> 'faces',
				'type'		=> 'switcher',
				'title'		=> esc_html__( 'Show Faces?', 'codevz' ),
				'default'	=> true
			],
			[
				'name'		=> 'cover',
				'type'		=> 'switcher',
				'title'		=> esc_html__( 'Hide Cover?', 'codevz' )
			],
		];
	}
}


/**
 *
 * Custom nav menu
 *
 */
class CodevzCustomMenuList extends WP_Widget {

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Custom Nav Menu', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'codevz-widget-custom-nav-menu' 
			]
		);
	}
	
	// Output
	public function widget( $args, $data ) {
		ob_start();

		$css = '';
		if ( ! empty( $args['widget_id'] ) ) {
			$id = $args['widget_id'];

			// Container
			if ( ! empty( $data['sk_container'] ) ) {
				$css .= '#' . $id . ' ul{' . Codevz_Plus::sk_inline_style( $data['sk_container'] ) . '}';
			}
			if ( ! empty( $data['sk_container_tablet'] ) ) {
				$css .= '@media screen and (max-width:768px){#' . $id . ' ul{' . Codevz_Plus::sk_inline_style( $data['sk_container_tablet'] ) . '}}';
			}
			if ( ! empty( $data['sk_container_mobile'] ) ) {
				$css .= '@media screen and (max-width:480px){#' . $id . ' ul{' . Codevz_Plus::sk_inline_style( $data['sk_container_mobile'] ) . '}}';
			}

			// Container
			if ( ! empty( $data['sk_menus'] ) ) {
				$css .= '#' . $id . ' a{' . Codevz_Plus::sk_inline_style( $data['sk_menus'] ) . '}';
			}
			if ( ! empty( $data['sk_menus_hover'] ) ) {
				$css .= '#' . $id . ' a:hover, #' . $id . ' .current_menu a{' . Codevz_Plus::sk_inline_style( $data['sk_menus_hover'] ) . '}';
			}
			if ( ! empty( $data['sk_menus_tablet'] ) ) {
				$css .= '@media screen and (max-width:768px){#' . $id . ' a{' . Codevz_Plus::sk_inline_style( $data['sk_menus_tablet'] ) . '}}';
			}
			if ( ! empty( $data['sk_menus_mobile'] ) ) {
				$css .= '@media screen and (max-width:480px){#' . $id . ' a{' . Codevz_Plus::sk_inline_style( $data['sk_menus_mobile'] ) . '}}';
			}
		}

		$style = empty( $data['style'] ) ? '' : 'codevz-widget-custom-menu-horizontal';

		echo '<div class="' . ( empty( $data['disable_default_styles'] ) ? 'codevz-widget-custom-menu' : '' ) . ' ' . $style . '"' . Codevz_Plus::data_stlye( $css ) . '>';
		
		$menus = get_nav_menu_locations();
		$menu = get_term( $menus[ $data['menu'] ], 'nav_menu' );
		wp_nav_menu( array( 'menu' => ( isset( $menu->slug ) ? $menu->slug : $data['menu'] ) ) );
		
		echo '</div>';

		Codevz_Widget::output( 0, $args, $data, ob_get_clean() );
	}
	
	// Update
	public function update( $data, $new ) {
		Codevz_Widget::update( $this, $new );
	}

	// Settings
	public function form( $data ) {
		Codevz_Widget::settings( $this, $data );
	}

	// Fields
	public function fields() {
		return [
			[
				'name'		=> 'title',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Title', 'codevz' ),
				'default' 	=> esc_html__( 'Custom Menu', 'codevz' )
			],
			[
				'name'		=> 'menu',
				'type'		=> 'select',
				'title'		=> esc_html__( 'Menu', 'codevz' ),
				'options' 	=> get_registered_nav_menus(),
				'default' 	=> 'primary'
			],
			[
				'name'		=> 'disable_default_styles',
				'type'		=> 'switcher',
				'title'		=> esc_html__( 'Disable Default Styles?', 'codevz' ),
			],
			[
				'name'        => 'style',
				'type'        => 'select',
				'title'       => esc_html__( 'Style', 'codevz' ),
				'options'	  => [
					'' 			=> esc_html__('Vertical', 'codevz'),
					'1' 		=> esc_html__('Horizontal', 'codevz')
				]
			],
			[
				'name'  	=> 'sk_container',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Container', 'codevz' ),
				'button' 	=> esc_html__( 'Container', 'codevz' ),
				'settings' 	=> [ 'background', 'padding', 'margin', 'border', 'box-shadow', 'display' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_container_mobile' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_container_tablet' ],
			[
				'name'  	=> 'sk_menus',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Links', 'codevz' ),
				'button' 	=> esc_html__( 'Links', 'codevz' ),
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin', 'border', 'box-shadow' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_menus_mobile' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_menus_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_menus_hover' ],
		];
	}
}


/**
 *
 * Custom menu list widget [Group, New]
 * 
 */
class Codevz_Widget_Custom_Menu_List extends WP_Widget {

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Custom menu list', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'codevz-widget-custom-menu-2' 
			]
		);
	}

	// Output
	public function widget( $args, $data ) {
		ob_start();

		$id = Codevz_Plus::uniqid();

		$col = empty( $data['columns'] ) ? '' : $data['columns'];
		$target = empty( $data['target_blank'] ) ? '' : ' target="_blank"';
		$icon_hover = empty( $data['sk_icons_hover'] ) ? '' : '.codevz-widget-custom-menu-2 .' . $id . ' a:hover i{' . Codevz_Plus::sk_inline_style( $data['sk_icons_hover'] ) . '}';
		$icon_css = empty( $data['sk_icons'] ) ? '' : '.codevz-widget-custom-menu-2 .' . $id . ' i{' . Codevz_Plus::sk_inline_style( $data['sk_icons'] ) . '}' . $icon_hover;
		$default_icon = empty( $data['default_icon'] ) ? '' : '<i class="' . $data['default_icon'] . ' mr8"></i>';

		echo '<div class="' . $id . ' clr"' . Codevz_Plus::data_stlye( $icon_css ) . '>';
		
		$items = isset( $data['items'] ) ? $data['items'] : [];
		$items = json_decode( json_encode( $items ), true );

		$i = 1;

		echo '<div class="clr">';
		foreach( $items as $item ) {
			$title = empty( $item['title'] ) ? '' : $item['title'];
			$icon = empty( $item['icon'] ) ? $default_icon : '<i class="' . $item['icon'] . ' mr8"></i>';
			$link = empty( $item['link'] ) ? '' : $item['link'];

			echo '<div class="' . $col . '"><a href="' . $link . '"' . $target . '>' . $icon . $title . '</a></div>';
			if ( ( $col === 'col s6' && $i % 2 === 0 ) || ( $col === 'col s4' && $i % 3 === 0 ) ) {
				echo '</div><div class="clr">';
			}

			$i++;
		}
		echo '</div>';

		echo '</div>';

		Codevz_Widget::output( 0, $args, $data, ob_get_clean() );
	}
	
	// Update
	public function update( $data, $new ) {
		Codevz_Widget::update( $this, $new );
	}

	// Settings
	public function form( $data ) {
		Codevz_Widget::settings( $this, $data );
	}

	// Widget fields
	public function fields() {
		return [
			[
				'name'		=> 'title',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Title', 'codevz' ),
				'default' 	=> esc_html__( 'Custom Menu List', 'codevz' )
			],
			[
				'name'            => 'items',
				'type'            => 'group',
				'title' 		  => '',
				'button_title'    => esc_html__( 'Items', 'codevz' ),
				'fields'          => [
					[
						'id'          => 'title',
						'type'        => 'text',
						'title'       => esc_html__( 'Title', 'codevz' ),
						'default' 	  => esc_html__( 'Menu Item', 'codevz' )
					],
					[
						'id'          => 'icon',
						'type'        => 'icon',
						'title'       => esc_html__('Icon', 'codevz')
					],
					[
						'id'          => 'link',
						'type'        => 'text',
						'title'       => esc_html__('Link', 'codevz')
					],
				],
			],
			[
				'name'        => 'default_icon',
				'type'        => 'icon',
				'title'       => esc_html__('Default Icon', 'codevz')
			],
			[
				'name'  	=> 'sk_icons',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Icons', 'codevz' ),
				'button' 	=> esc_html__( 'Icons', 'codevz' ),
				'settings' 	=> [ 'color', 'font-size', 'background', 'padding', 'margin' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icons_hover' ],
			[
				'name'        => 'columns',
				'type'        => 'select',
				'title'       => esc_html__( 'Layout', 'codevz' ),
				'options'	  => [
					'' 				=> '1 ' . esc_html__('Column', 'codevz'),
					'col s6' 		=> '2 ' . esc_html__('Columns', 'codevz'),
					'col s4' 		=> '3 ' . esc_html__('Columns', 'codevz')
				]
			],
			[
				'name'		=> 'target_blank',
				'type'		=> 'switcher',
				'title'		=> esc_html__( 'New Tab?', 'codevz' )
			],
		];
	}
}


/**
 *
 * Custom menu list widget [Deprecated]
 * 
 */
class CodevzCustomMenuList2 extends WP_Widget {

	private static $count = 18;

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Custom Menu [Deprecated]', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'codevz-widget-custom-menu-2-old' 
			]
		);
	}
	
	// Output
	public function widget( $args, $data ) {
		ob_start();

		$col = empty( $data['two_col'] ) ? '' : 'col s6';
		$target = empty( $data['target_blank'] ) ? '' : ' target="_blank"';
		$icon = empty( $data['menus_icon'] ) ? '' : '<i class="' . $data['menus_icon'] . ' mr8"></i>';

		echo '<div class="clr">';
		for( $i = 1; $i < self::$count; $i++ ) {
			if ( ! empty( $data[ 'title_' . $i ] ) && ! empty( $data[ 'link_' . $i ] ) ) {
				echo '<div class="' . $col . '"><a href="' . $data[ 'link_' . $i ] . '"' . $target . '>' . $icon . $data[ 'title_' . $i ] . '</a></div>';
				if ( $col && $i % 2 === 0 ) {
					echo '</div><div class="clr">';
				}
			}
		}
		echo '</div>';

		Codevz_Widget::output( 0, $args, $data, ob_get_clean() );
	}
	
	// Update
	public function update( $data, $new ) {
		Codevz_Widget::update( $this, $new );
	}

	// Settings
	public function form( $data ) {
		Codevz_Widget::settings( $this, $data );
	}

	// Fields
	public function fields() {
		$fields = [
			[
				'name'		=> 'title',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Title', 'codevz' ),
				'default' 	=> esc_html__( 'Custom Menu', 'codevz' )
			],
			[
				'name'		=> 'two_col',
				'type'		=> 'switcher',
				'title'		=> esc_html__( 'Two Columns?', 'codevz' )
			],
			[
				'name'		=> 'target_blank',
				'type'		=> 'switcher',
				'title'		=> esc_html__( 'New Tab?', 'codevz' )
			],
			[
				'name'		=> 'menus_icon',
				'type'		=> 'icon',
				'title'		=> esc_html__( 'Icon', 'codevz' )
			],
		];

		for( $i = 1; $i < self::$count; $i++ ) {
			$fields[] = [
				'name'		=> 'title_' . $i,
				'type'		=> 'text',
				'title'		=> esc_html__( 'Title', 'codevz' ) . ' ' . $i
			];
			$fields[] = [
				'name'		=> 'link_' . $i,
				'type'		=> 'text',
				'title'		=> esc_html__( 'Link', 'codevz' ) . ' ' . $i
			];
		}

		return $fields;
	}
}

/**
 *
 * Widget: Unboxed content
 * 
 */
class Codevz_Widget_Unboxed extends WP_Widget {

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Unboxed', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'codevz-widget-unboxed' 
			]
		);
	}
	
	// Output
	public function widget( $args, $data ) {

		echo '<div class="codevz-widget-unboxed mb30">' . do_shortcode( $data['content'] ) . '</div>';

	}
	
	// Update
	public function update( $data, $new ) {
		Codevz_Widget::update( $this, $new );
	}

	// Settings
	public function form( $data ) {
		Codevz_Widget::settings( $this, $data );
	}

	// Fields
	public function fields() {
		$fields = [
			[
				'name'		=> 'content',
				'type'		=> 'textarea',
				'title'		=> esc_html__( 'Content', 'codevz' )
			],
		];

		return $fields;
	}

}

/**
 *
 * Widget: Instagram
 * 
 */
class Codevz_Widget_Gallery extends WP_Widget {

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Gallery', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'codevz-widget-gallery' 
			]
		);
	}

	// Output.
	public function widget( $args, $data ) {

		// Default.
		if ( empty( $data['type'] ) ) {
			$data['type'] = 'instagram';
		}
		if ( empty( $data['gap'] ) ) {
			$data['gap'] = '10px';
		}
		$data['arrows_position'] = 'arrows_bc';

		// Fix group.
		if ( isset( $data['gallery2'] ) && is_array( $data['gallery2'] ) ) {
			$data['gallery2'] = urlencode( json_encode( json_decode( json_encode( $data['gallery2'] ), true ) ) );
		}

		Codevz_Widget::output( 'cz_gallery', $args, $data );
	}
	
	// Update
	public function update( $data, $new ) {
		Codevz_Widget::update( $this, $new );
	}

	// Settings
	public function form( $data ) {
		Codevz_Widget::settings( $this, $data );
	}

	// Widget fields
	public function fields() {
		return [
			[
				'name'		=> 'title',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Title', 'codevz' ),
				'default' 	=> esc_html__( 'Gallery', 'codevz' )
			],
			[
				'name'        => 'type',
				'type'        => 'select',
				'title'       => esc_html__( 'Type', 'codevz' ),
				'options'	  => [
					'gallery' 		=> esc_html__('Photo Gallery', 'codevz'),
					'gallery2' 		=> esc_html__('Linkable Gallery', 'codevz'),
					'instagram' 	=> esc_html__('Instagram', 'codevz') . ' ' . esc_html__('[Deprecated]', 'codevz'),
				],
				'default' 	  => 'gallery',
				'attributes' 	=> [ 'data-depend-id' => 'type' ]
			],
			[
				'name'            => 'gallery2',
				'type'            => 'group',
				'title' 		  => '',
				'button_title'    => esc_html__( 'Images', 'codevz' ),
				'fields'          => [
					[
						'id'          => 'title',
						'type'        => 'text',
						'title'       => esc_html__( 'Title', 'codevz' )
					],
					[
						'id'          => 'image',
						'type'        => 'image',
						'title'       => esc_html__( 'Image', 'codevz' )
					],
					[
						'id'          => 'link',
						'type'        => 'text',
						'title'       => esc_html__( 'Link', 'codevz' )
					],
				],
				'dependency' => [ 'type', '==', 'gallery2' ]
			],
			[
				'name'		=> 'images',
				'type'		=> 'gallery',
				'title'		=> esc_html__( 'Images', 'codevz' ),
				'dependency' => [ 'type', '==', 'gallery' ]
			],
			[
				'name'		=> 'insta_username',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Username or Hashtag', 'codevz' ),
				"help"   	=> esc_html__( "For hashtag # is required before word", 'codevz'),
				'dependency' => [ 'type', '==', 'instagram' ]
			],
			[
				'name'		=> 'insta_count',
				'type'		=> 'slider',
				'options' 	=> array( 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 12 ),
				'title'		=> esc_html__( 'Count', 'codevz' ),
				'dependency' => [ 'type', '==', 'instagram' ]
			],
			[
				'name'        => 'insta_update',
				'type'        => 'select',
				'title'       => esc_html__( 'Update cache', 'codevz' ),
				'options'	  => [
					'12' 		=> '12 ' . esc_html__('Hours', 'codevz'),
					'24' 		=> '24 ' . esc_html__('Hours', 'codevz'),
					'36' 		=> '36 ' . esc_html__('Hours', 'codevz'),
					'48' 		=> '48 ' . esc_html__('Hours', 'codevz'),
					'72' 		=> '72 ' . esc_html__('Hours', 'codevz'),
					'96' 		=> '96 ' . esc_html__('Hours', 'codevz'),
					'120' 		=> '120 ' . esc_html__('Hours', 'codevz'),
					'18000' 	=> esc_html__( 'Store data once', 'codevz' )
				],
				'default' 	  => '72',
				'dependency' => [ 'type', '==', 'instagram' ]
			],
			[
				'name'        => 'insta_size',
				'type'        => 'select',
				'title'       => esc_html__( 'Size', 'codevz' ),
				'options'	  => [
					'thumbnail' 	=> esc_html__('Thumbnail', 'codevz'),
					'large' 		=> esc_html__('Medium', 'codevz'),
					'original' 		=> esc_html__('Large', 'codevz')
				],
				'default' 	  => '72',
				'dependency' => [ 'type', '==', 'instagram' ]
			],
			[
				'name'        => 'layout',
				'type'        => 'image_select',
				'title'       => esc_html__( 'Layout', 'codevz' ),
				'options'	  => [
					'cz_grid_c2' 			=> Codevz_Plus::$url . 'assets/img/gallery_4.png',
					'cz_grid_c3' 			=> Codevz_Plus::$url . 'assets/img/gallery_5.png',
					'cz_grid_c1 cz_grid_l1' => Codevz_Plus::$url . 'assets/img/gallery_2.png',
					'cz_metro_5 cz_grid_c3' => Codevz_Plus::$url . 'assets/img/gallery_24.png',
					'cz_metro_6 cz_grid_c3' => Codevz_Plus::$url . 'assets/img/gallery_25.png',
					'cz_grid_carousel' 		=> Codevz_Plus::$url . 'assets/img/gallery_30.png',
				],
				'default' 	  => 'cz_grid_c3',
				'attributes' 	=> [ 'data-depend-id' => 'layout' ]
			],
			[
				'name'		=> 'slidestoshow',
				'type'		=> 'slider',
				'options' 	=> [ 'unit' => '', 'step' => 1, 'min' => 1, 'max' => 10 ],
				'title'		=> esc_html__( 'Slides to show', 'codevz' ),
				'default'   => '1',
				'dependency' => [ 'layout', '==', 'cz_grid_carousel' ]
			],
			[
				'name'		=> 'gap',
				'type'		=> 'slider',
				'title'		=> esc_html__( 'Images Gap', 'codevz' )
			],
			[
				'name'		=> 'icon',
				'type'		=> 'icon',
				'title'		=> esc_html__( 'Icon', 'codevz' ),
				'default'	=> 'fa czico-125-add-song'
			],
			[
				'name'  	=> 'sk_overlay',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Overlay', 'codevz' ),
				'button' 	=> esc_html__( 'Overlay', 'codevz' ),
				'settings' 	=> [ 'background' ]
			],
			[
				'name'  	=> 'sk_icon',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Icon', 'codevz' ),
				'button' 	=> esc_html__( 'Icon', 'codevz' ),
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ]
			],
		];
	}
}


/**
 * Widget: Logo, Text, Social
 */
class Codevz_Widget_About extends WP_Widget {

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'About', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'codevz-widget-about' 
			]
		);
	}
	
	// Output
	public function widget( $args, $data ) {
		ob_start();
		echo '<div class="' . ( isset( $data['position'] ) ? $data['position'] : '' ) . '">';

		// Logo
		if ( ! empty( $data['logo'] ) ) {

			$sizes = Codevz_Plus::getimagesize( $data['logo'] );

			if ( $sizes ) {

				list( $lw, $lh ) = $sizes;

				if ( ! empty( $data['logo_size'] ) && ! Codevz_Plus::contains( $data['logo'], '.svg' ) ) {

					$nw = preg_replace( '/[^0-9]/', '', $data['logo_size'] );
					$lp = preg_replace( '/[0-9]/', '', $data['logo_size'] );

					$lh = (integer) round( ( $lh * $nw ) / $lw, 0 );
					$lw = (integer) $nw;

				}

			} else {

				$lw = empty( $data[ 'logo_size' ] ) ? 'auto' : (integer) $data[ 'logo_size' ];
				$lh = 'auto';

			}

			if ( empty( $lp ) ) {
				$lp = 'px';
			}

			$data['sk_logo'] .= $lw ? 'width:' . esc_attr( $lw . $lp ) . ';' : '';
			$logo_css = $data['sk_logo'] ? ' style="' . Codevz_Plus::sk_inline_style( $data['sk_logo'] ) . '"' : '';
			echo '<img class="mb30" src="' . $data['logo'] . '" width="' . esc_attr( $lw ) . '" height="' . esc_attr( $lh ) . '" alt="Logo"' . $logo_css . ' />';
		}

		// Content
		if ( ! empty( $data['content'] ) ) {
			$content_css = $data['sk_content'] ? ' style="' . Codevz_Plus::sk_inline_style( $data['sk_content'] ) . '"' : '';
			echo '<div class="codevz-widget-about-content mb30"' . $content_css . '>';
			echo do_shortcode( $data['content'] );
			echo '</div>';
		}

		// Button
		if ( ! empty( $data['button'] ) ) {
			$link = isset( $data['button_link'] ) ? $data['button_link'] : '';
			echo do_shortcode( '[cz_button class="mb30" link="url:' . urlencode( $link ) . '|||" title="' . $data['button'] . '" sk_button="' . $data['sk_button'] . '" sk_hover="' . $data['sk_button_hover'] . '"]' );
		}

		// Social icons
		if ( ! empty( $data['social'] ) ) {
			$out = '[cz_social_icons ';

			if ( isset( $data['social'] ) && is_array( $data['social'] ) ) {
				$data['social'] = json_decode( json_encode( $data['social'] ), true );
				$out .= 'social="' . urlencode( json_encode( $data['social'] ) ) . '" ';
			}

			if ( isset( $data['fx'] ) ) {
				$out .= 'fx="' . $data['fx'] . '" ';
			}

			if ( isset( $data['color_mode'] ) ) {
				$out .= 'color_mode="' . $data['color_mode'] . '" ';
			}

			if ( isset( $data['sk_icons'] ) ) {
				$out .= 'sk_icons="' . Codevz_Plus::sk_inline_style( $data['sk_icons'] ) . '" ';
			}

			if ( isset( $data['sk_icons_tablet'] ) ) {
				$out .= 'sk_icons_tablet="' . Codevz_Plus::sk_inline_style( $data['sk_icons_tablet'] ) . '" ';
			}

			if ( isset( $data['sk_icons_mobile'] ) ) {
				$out .= 'sk_icons_mobile="' . Codevz_Plus::sk_inline_style( $data['sk_icons_mobile'] ) . '" ';
			}

			if ( isset( $data['sk_icons_hover'] ) ) {
				$out .= 'sk_hover="' . Codevz_Plus::sk_inline_style( $data['sk_icons_hover'] ) . '" ';
			}

			if ( isset( $data['sk_inner_icon'] ) ) {
				$out .= 'sk_inner_icon="' . Codevz_Plus::sk_inline_style( $data['sk_inner_icon'] ) . '" ';
			}

			if ( isset( $data['sk_inner_icon_hover'] ) ) {
				$out .= 'sk_inner_icon_hover="' . Codevz_Plus::sk_inline_style( $data['sk_inner_icon_hover'] ) . '" ';
			}

			if ( isset( $data['inline_title'] ) ) {
				$out .= 'inline_title="1" ';
			}

			if ( isset( $data['sk_title'] ) ) {
				$out .= 'sk_title="' . $data['sk_title'] . '" ';
			}

			if ( isset( $data['sk_title_mobile'] ) ) {
				$out .= 'sk_title_mobile="' . $data['sk_title_mobile'] . '" ';
			}

			if ( isset( $data['sk_title_tablet'] ) ) {
				$out .= 'sk_title_tablet="' . $data['sk_title_tablet'] . '" ';
			}

			if ( isset( $data['sk_title_hover'] ) ) {
				$out .= 'sk_title_hover="' . $data['sk_title_hover'] . '" ';
			}

			if ( isset( $data['tooltip'] ) ) {
				$out .= 'tooltip="' . $data['tooltip'] . '" ';
			}

			$out .= '[/cz_social_icons]';
			echo $out;
		}

		echo '</div>';
		Codevz_Widget::output( 0, $args, $data, ob_get_clean() );
	}
	
	// Update
	public function update( $data, $new ) {
		Codevz_Widget::update( $this, $new );
	}

	// Settings
	public function form( $data ) {
		Codevz_Widget::settings( $this, $data );
	}

	// Fields
	public function fields() {
		$fields = [
			[
				'name'		=> 'title',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Title', 'codevz' ),
				'default' 	=> esc_html__( 'About Us', 'codevz' )
			],
			[
				'name'        => 'position',
				'type'        => 'select',
				'title'       => esc_html__('Position', 'codevz'),
				'options'	  => [
					'tal' 		=> esc_html__('Left', 'codevz'),
					'tac' 		=> esc_html__('Center', 'codevz'),
					'tar' 		=> esc_html__('Right', 'codevz'),
				]
			],
			[
				'name'		=> 'logo',
				'type'		=> 'upload',
				'title'		=> esc_html__( 'Logo', 'codevz' ),
				'preview'	=> 1
			],
			[
				'name'		=> 'logo_size',
				'type'		=> 'slider',
				'options' 	=> array( 'unit' => 'px', 'step' => 1, 'min' => 50, 'max' => 400 ),
				'title'		=> esc_html__( 'Logo Size', 'codevz' )
			],
			[
				'name'		=> 'content',
				'type'		=> 'textarea',
				'title'		=> esc_html__( 'Content', 'codevz' )
			],
			[
				'name'		=> 'button',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Button', 'codevz' )
			],
			[
				'name'		=> 'button_link',
				'type'		=> 'text',
				'title'		=> esc_html__( 'Link', 'codevz' )
			],
			[
				'name'            => 'social',
				'type'            => 'group',
				'title' 		  => '',
				'button_title'    => esc_html__( 'Icons', 'codevz' ),
				'fields'          => [
					[
						'id'          => 'title',
						'type'        => 'text',
						'title'       => esc_html__('Title', 'codevz')
					],
					[
						'id'          => 'icon',
						'type'        => 'icon',
						'title'       => esc_html__('Icon', 'codevz')
					],
					[
						'id'          => 'link',
						'type'        => 'text',
						'title'       => esc_html__('Link', 'codevz')
					],
				]
			],
			[
				'name'        => 'fx',
				'type'        => 'select',
				'title'       => esc_html__('Icons Hover', 'codevz'),
				'options'	  => [
					'' 					=> esc_html__( '~ Default ~', 'codevz' ),
					'cz_social_fx_0' 	 => esc_html__('ZoomIn', 'codevz'),
					'cz_social_fx_1' 	 => esc_html__('ZoomOut', 'codevz'),
					'cz_social_fx_2' 	 => esc_html__('Bottom to Top', 'codevz'),
					'cz_social_fx_3' 	 => esc_html__('Top to Bottom', 'codevz'),
					'cz_social_fx_4' 	 => esc_html__('Left to Right', 'codevz'),
					'cz_social_fx_5' 	 => esc_html__('Right to Left', 'codevz'),
					'cz_social_fx_6' 	 => esc_html__('Rotate', 'codevz'),
					'cz_social_fx_7' 	 => esc_html__('Infinite Shake', 'codevz'),
					'cz_social_fx_8' 	 => esc_html__('Infinite Wink', 'codevz'),
					'cz_social_fx_9' 	 => esc_html__('Quick Bob', 'codevz'),
					'cz_social_fx_10' 	 => esc_html__('Flip Horizontal', 'codevz'),
					'cz_social_fx_11' 	 => esc_html__('Flip Vertical', 'codevz'),
				]
			],
			[
				'name'        => 'color_mode',
				'type'        => 'select',
				'title'       => esc_html__('Color Mode', 'codevz'),
				'options'	  => [
					'' 							=> esc_html__( '~ Default ~', 'codevz'),
					'cz_social_colored' 		=> esc_html__( 'Brand Colors', 'codevz' ),
					'cz_social_colored_hover' 	=> esc_html__( 'Brand Colors on Hover', 'codevz' ),
					'cz_social_colored_bg' 		=> esc_html__( 'Brand Background', 'codevz' ),
					'cz_social_colored_bg_hover' => esc_html__( 'Brand Background on Hover', 'codevz' ),
				]
			],
			[
				'name'        => 'tooltip',
				'type'        => 'select',
				'title'       => esc_html__('Tooltip', 'codevz'),
				'help' 		  => esc_html__( 'StyleKit located in Theme Options > General > Colors & Styles', 'codevz' ),
				'options'	  => [
					'' 								=> esc_html__( '~ Default ~', 'codevz' ),
					'cz_tooltip cz_tooltip_up' 		=> esc_html__('Up', 'codevz'),
					'cz_tooltip cz_tooltip_down' 	=> esc_html__('Down', 'codevz'),
					'cz_tooltip cz_tooltip_left' 	=> esc_html__('Left', 'codevz'),
					'cz_tooltip cz_tooltip_right' 	=> esc_html__('Right', 'codevz'),
				]
			],
			[
				'name'        => 'inline_title',
				'type'        => 'switcher',
				'title'       => esc_html__('Inline Title', 'codevz'),
			],
			[
				'name'  	=> 'sk_logo',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Logo', 'codevz' ),
				'button' 	=> esc_html__( 'Logo', 'codevz' ),
				'settings' 	=> [ 'width', 'background', 'padding', 'margin', 'border' ]
			],
			[
				'name'  	=> 'sk_content',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Content', 'codevz' ),
				'button' 	=> esc_html__( 'Content', 'codevz' ),
				'settings' 	=> [ 'width', 'background', 'padding', 'margin', 'border' ]
			],
			[
				'name'  	=> 'sk_button',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Button', 'codevz' ),
				'button' 	=> esc_html__( 'Button', 'codevz' ),
				'settings' 	=> [ 'width', 'background', 'padding', 'margin', 'border' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_button_hover' ],
			[
				'name'  	=> 'sk_icons',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Icons', 'codevz' ),
				'button' 	=> esc_html__( 'Icons', 'codevz' ),
				'settings' 	=> [ 'width', 'color', 'font-size', 'background', 'border' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icons_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icons_mobile' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_icons_hover' ],
			[
				'name'  	=> 'sk_inner_icon',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Inner Icons', 'codevz' ),
				'button' 	=> esc_html__( 'Inner Icons', 'codevz' ),
				'settings' 	=> [ 'width', 'color', 'font-size', 'background', 'border' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_inner_icon_hover' ],
			[
				'name'  	=> 'sk_title',
				'hover'  	=> '_hover',
				'type'  	=> 'cz_sk',
				'title' 	=> esc_html__( 'Social Title', 'codevz' ),
				'button' 	=> esc_html__( 'Social Title', 'codevz' ),
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ]
			],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_title_tablet' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_title_mobile' ],
			[ 'type' => 'cz_sk_hidden', 'name' => 'sk_title_hover' ],
		];

		return $fields;
	}
}


/**
 *
 * Widget: Flickr
 * 
 */
class CodevzFlickr extends WP_Widget {

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Flickr', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'cz_flickr' 
			]
		);
	}
	
	public function form($data) {
		$defaults = array(
			'title' => 'Photostream',
			'id' => '7388060@N08',
			'type' => 'user',
			'number' => '9',
			'shorting' => 'latest',
		);
		$data = wp_parse_args( (array) $data, $defaults );
		
		$title_field = array(
			'id'    => $this->get_field_name('title'),
			'name'  => $this->get_field_name('title'),
			'type'  => 'text',
			'title' => esc_html__('Title', 'codevz')
		);
		echo csf_add_field( $title_field, esc_attr( $data['title'] ) );

		$id_field = array(
			'id'    => $this->get_field_name('id'),
			'name'  => $this->get_field_name('id'),
			'type'  => 'text',
			'title' => esc_html__('Flikr ID ( idgettr.com )', 'codevz')
		);
		echo csf_add_field( $id_field, esc_attr( $data['id'] ) );

		$number_field = array(
			'id'    => $this->get_field_name('number'),
			'name'  => $this->get_field_name('number'),
			'type'  => 'text',
			'title' => esc_html__('Count', 'codevz')
		);
		echo csf_add_field( $number_field, esc_attr( $data['number'] ) );

		$type_field = array(
			'id'    => $this->get_field_name('type'),
			'name'  => $this->get_field_name('type'),
			'type'  => 'select',
			'options' => array(
				'user' => esc_html__('User', 'codevz'),
				'group' => esc_html__('Group', 'codevz')
			),
			'title' => esc_html__('Type', 'codevz')
		);
		echo csf_add_field( $type_field, esc_attr( $data['type'] ) );

		$shorting_field = array(
			'id'    => $this->get_field_name('shorting'),
			'name'  => $this->get_field_name('shorting'),
			'type'  => 'select',
			'options' => array(
				'latest' => esc_html__('Latest Photos', 'codevz'),
				'random' => esc_html__('Random', 'codevz')
			),
			'title' => esc_html__('Sorting', 'codevz')
		);
		echo csf_add_field( $shorting_field, esc_attr( $data['shorting'] ) );
	}

	public function update($new_instance, $old_instance) {
		$data = $old_instance;
		$data['title'] = strip_tags( $new_instance['title'] );
		$data['number'] = strip_tags( $new_instance['number'] );
		$data['id'] = strip_tags( $new_instance['id'] );
		$data['type'] = strip_tags( $new_instance['type'] );
		$data['shorting'] = strip_tags( $new_instance['shorting'] );

		return $data;
	}

	public function widget( $args, $data ) {
		extract( $args );
		ob_start();
		$title = apply_filters( 'widget_title', $data['title'] );
		$number = esc_attr( $data['number'] );
		$shorting = esc_attr( $data['shorting'] );
		$type = esc_attr( $data['type'] );
		$id = esc_attr( $data['id'] );
		echo $before_widget;
		echo $title ? $before_title . esc_attr( $title ) . $after_title : '';

		if ( $id ) : ?>
			<div class="flickr-widget clr">
				<sc<?php echo 'r'; ?>ipt type="text/javascript" src="<?php echo 'https://flickr.com/badge_code_v2.gne?count=' . $number . '&amp;display=' . $shorting . '&amp;&amp;layout=x&amp;source=' . $type . '&amp;' . $type . '=' . $id . '&amp;size=s'; ?>"></sc<?php echo 'r'; ?>ipt> 
			</div>
		<?php endif;

		echo $after_widget;

		$out = ob_get_clean();
		echo apply_filters( 'widget_text', $out );
	}
 
}


/**
 * Soundcloud
 */
if ( !class_exists( 'Codevz_Widget_Soundcloud' ) ) {

	class Codevz_Widget_Soundcloud extends WP_Widget {

		public function __construct() {
			parent::__construct( 
				false, 
				'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Soundcloud', 'codevz' ), 
				[ 
					'customize_selective_refresh' => true, 
					'classname' => 'cz_soundcloud' 
				]
			);
		}

		public function widget( $args, $data ) {
			extract( $args );

			ob_start();
			$title = apply_filters('widget_title', $data['title'] );
			$url = esc_url( $data['url'] );
			$play = 'false';
			if ( ! empty( $data['autoplay'] ) ) $play = 'true';

			echo $before_widget;
			if($title) {
				echo $before_title.$title.$after_title;
			} else {
				?> <div class="widget clr"> <?php  
			}
			?><<?php echo 'iframe'; ?> width="100%" height="166" scrolling="no" frameborder="no" src="//w.soundcloud.com/player/?url=<?php echo esc_url( $url ); ?>&amp;auto_play=<?php echo $play; ?>&amp;show_artwork=true"></<?php echo 'iframe'; ?>><?php
			echo $after_widget;

			$out = ob_get_clean();
			echo apply_filters( 'widget_text', $out );
		}
		public function update( $new_instance, $old_instance ) {
			$data = $old_instance;
			$data['title'] 		= esc_html( $new_instance['title'] );
			$data['url'] 		= esc_url( $new_instance['url'] );
			$data['autoplay'] 	= esc_html( $new_instance['autoplay'] );
			
			return $data;
		}
		public function form( $data ) {

			$defaults = array( 
				'title' 	=> 'SoundCloud', 
				'url' 		=> 'https://soundcloud.com/almerchoy/pitbull-bon-bon', 
				'autoplay' 	=> ''  
			);
			$data = wp_parse_args( (array) $data, $defaults ); ?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__('Title :', 'codevz'); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $data['title'] ); ?>" class="widefat" type="text" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>"><?php echo esc_html__('URL :', 'codevz'); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'url' ) ); ?>" value="<?php echo esc_url( $data['url'] ); ?>" type="text" class="widefat" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id( 'autoplay' ) ); ?>"><?php echo esc_html__('Autoplay :', 'codevz'); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id( 'autoplay' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'autoplay' ) ); ?>" value="true" <?php if( $data['autoplay'] ) echo 'checked="checked"'; ?> type="checkbox" />
			</p>
		<?php
		}

	}

}

/**
 * 
 * Subscribe
 * 
 */
if ( !class_exists( 'CodevzSubscribe' ) ) {

	class CodevzSubscribe extends WP_Widget {

		public function __construct() {
			parent::__construct( 
				false, 
				'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Feedburner', 'codevz' ), 
				[ 
					'customize_selective_refresh' => true, 
					'classname' => 'cz_subscribe' 
				]
			);
		}

		public function form($data) {	
			$data = wp_parse_args( (array) $data, array('title' => 'Subscribe to RSS Feeds', 'subscribe_text' => 'Get all latest content delivered to your email a few times a month.', 'feedid' => '', 'placeholder' => 'Your Email', 'icon' => 'fa fa-check') );
			
			$title_value = esc_attr( $data['title'] );
			$title_field = array(
				'id'    => $this->get_field_name('title'),
				'name'  => $this->get_field_name('title'),
				'type'  => 'text',
				'title' => esc_html__('Title', 'codevz')
			);
			echo csf_add_field( $title_field, $title_value );

			$subscribe_text_value = esc_attr( $data['subscribe_text'] );
			$subscribe_text_field = array(
				'id'    => $this->get_field_name('subscribe_text'),
				'name'  => $this->get_field_name('subscribe_text'),
				'type'  => 'textarea',
				'title' => esc_html__('Description', 'codevz')
			);
			echo csf_add_field( $subscribe_text_field, $subscribe_text_value );

			$icon_value = esc_attr( $data['icon'] );
			$icon_field = array(
				'id'    => $this->get_field_name('icon'),
				'name'  => $this->get_field_name('icon'),
				'type'  => 'icon',
				'title'	=> esc_html__('Icon', 'codevz'),
			);
			echo csf_add_field( $icon_field, $icon_value );

			$placeholder_value = esc_attr( $data['placeholder'] );
			$placeholder_field = array(
				'id'    => $this->get_field_name('placeholder'),
				'name'  => $this->get_field_name('placeholder'),
				'type'  => 'text',
				'title' => esc_html__('Placeholder', 'codevz')
			);
			echo csf_add_field( $placeholder_field, $placeholder_value );

			$feedid_value = esc_attr( $data['feedid'] );
			$feedid_field = array(
				'id'    => $this->get_field_name('feedid'),
				'name'  => $this->get_field_name('feedid'),
				'type'  => 'text',
				'title' => esc_html__('Feedburner ID or Name', 'codevz')
			);
			echo csf_add_field( $feedid_field, $feedid_value );
	    }

		public function update($new_instance, $old_instance) {
			$data=$old_instance;
			$data['title'] = strip_tags($new_instance['title']);
			$data['feedid'] = $new_instance['feedid'];
			$data['icon'] = $new_instance['icon'];
			$data['placeholder'] = $new_instance['placeholder'];
			$data['subscribe_text'] = $new_instance['subscribe_text'];
			
			return $data;
		}

		public function widget($args, $data) {
			extract($args);
			ob_start();
			$title = apply_filters('widget_title', $data['title']);
			if ( empty($title) ) $title = false;
			$feedid = $data['feedid'];	
			$feedbtn = $data['icon'];	
			$placeholder = $data['placeholder'];	
			$subscribe_text = $data['subscribe_text'];	
			echo $before_widget;

			if($title) {
				echo $before_title.$title.$after_title;
			}
		?>
			<p><?php echo $subscribe_text; ?></p>
			<form class="widget_rss_subscription clr" action="https://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open('//feedburner.google.com/fb/a/mailverify?uri=<?php echo $feedid; ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520');return true">
				<input type="text" placeholder="<?php echo esc_attr( $placeholder ) ?>" name="email" required />
				<input type="hidden" value="<?php echo esc_attr( $feedid ); ?>" name="uri"/>
				<input type="hidden" name="loc" value="en_US"/>
				<button type="submit" id="submit" value="Subscribe"><i class="<?php echo esc_attr( $feedbtn ); ?>"></i></button>
			</form>
		<?php
			echo $after_widget;

			$out = ob_get_clean();
			echo apply_filters( 'widget_text', $out );
		}

	}

}

/**
 * 
 * Simple ads
 * 
 */
if ( ! class_exists( 'CodevzSimpleAds' ) ) {

	class CodevzSimpleAds extends WP_Widget {

		public function __construct() {
			parent::__construct( 
				false, 
				'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Simple Ads', 'codevz' ), 
				[ 
					'customize_selective_refresh' => true, 
					'classname' => 'cz_simple_ads' 
				]
			);
		}
		
		public function widget( $args, $data ) {

			extract( $args );
			$title = apply_filters('widget_title', $data['title'] );
			$out = $before_widget."\n";
			$out .= $title ? $before_title.$title.$after_title : '';
			$out .= '<a href="'.esc_url( $data['link'] ).'" target="_blank" title="'.esc_attr( $title ).'"><img src="'.esc_url( $data['img'] ).'" alt="'.esc_attr( $title ).'" width="200" height="200" /></a>';
			$out .= $data['custom'];
			$out .= $after_widget."\n";

			echo apply_filters( 'widget_text', $out );
		}

		public function update($new,$old) {

			$data = $old;
			$data['title'] = esc_html( $new['title'] );
			$data['img'] = esc_url( $new['img'] );
			$data['link'] = esc_url( $new['link'] );
			$data['custom'] = $new['custom'];

			return $data;
		}
		 
		public function form($data) {

			$defaults = array('title' => '','link' => '','img' => '', 'custom' => '');
			$data = wp_parse_args( (array) $data, $defaults );

			echo csf_add_field( array(
				'id'    => $this->get_field_name('title'),
				'name'  => $this->get_field_name('title'),
				'type'  => 'text',
				'title' => esc_html__('Title', 'codevz')
			), esc_attr( $data['title'] ) ); 

			echo csf_add_field( array(
				'id'    => $this->get_field_name('img'),
				'name'  => $this->get_field_name('img'),
				'type'  => 'upload',
				'title' => esc_html__('Image', 'codevz')
			), esc_attr( $data['img'] ) );

			echo csf_add_field( array(
				'id'    => $this->get_field_name('link'),
				'name'  => $this->get_field_name('link'),
				'type'  => 'text',
				'title' => esc_html__('Link', 'codevz')
			), esc_attr( $data['link'] ) );

			echo csf_add_field( array(
				'id'    => $this->get_field_name('custom'),
				'name'  => $this->get_field_name('custom'),
				'type'  => 'textarea',
				'sanitize' => false,
				'title' => esc_html__('Custom Ads', 'codevz')
			), $data['custom'] );

		}

	}

}

/**
 * 
 * Load page content
 * 
 */
if ( ! class_exists( 'CodevzPageContent' ) ) {

	class CodevzPageContent extends WP_Widget {

		public function __construct() {
			parent::__construct( 
				false, 
				'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Page Content', 'codevz' ), 
				[ 
					'customize_selective_refresh' => true, 
					'classname' => 'cz_page_content_widget' 
				]
			);
		}
		
		public function widget( $args, $data ) {
			extract( $args );
			if ( ! empty( $data['id'] ) ) {
				ob_start();
				echo $before_widget;
				$title = apply_filters('widget_title', $data['title'] );
				echo $title ? $before_title . $title . $after_title : '';
				echo Codevz_Plus::get_page_as_element( $data['id'] );
				echo $after_widget;

				$out = ob_get_clean();
				echo apply_filters( 'widget_text', $out );
			}
		}

		public function update($new,$old) {

			$data = $old;
			$data['title'] 	= esc_html( $new['title'] );
			$data['id'] 	= esc_html( $new['id'] );

			return $data;
		}
		 
		public function form( $data ) {

			$defaults = array( 'title' => '', 'id' => '' );
			$data = wp_parse_args( (array) $data, $defaults );

			echo csf_add_field( array(
				'id'    => $this->get_field_name('title'),
				'name'  => $this->get_field_name('title'),
				'type'  => 'text',
				'title' => esc_html__('Title', 'codevz')
			), esc_attr( $data['title'] ) );

			echo csf_add_field( array(
				'id'            => $this->get_field_name('id'),
				'name'  		=> $this->get_field_name('id'),
				'type'          => 'select',
				'title'         => esc_html__('Page', 'codevz'),
				'options'       => Codevz_Plus::$array_pages,
			), esc_attr( $data['id'] ) );

		}

	}

}

/**
 * 
 * Gallery
 * 
 */
if ( !class_exists( 'CodevzPortfolio' ) ) {

	class CodevzPortfolio extends WP_Widget {

		public function __construct() {
			parent::__construct( 
				false, 
				'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Portfolio', 'codevz' ), 
				[ 
					'customize_selective_refresh' => true, 
					'classname' => 'cz_portfolio_widget' 
				]
			);
		}

		public function widget($args, $data) {
			extract( $args );

			$title = apply_filters( 'widget_title', $data['title'] );

			$out = $before_widget . "\n";
			$out .= $title ? $before_title . $title . $after_title : '';

			ob_start();

			$post_type 		= isset( $data['post_type'] ) ? $data['post_type'] : 'portfolio';
			$gallery_order 	= isset( $data['gallery_order'] ) ? $data['gallery_order'] : 'DESC';

			$popular = new WP_Query( array(
				'post_type'		=> $post_type,
				'order'			=> $gallery_order,
				'showposts'		=> $data['posts_num']
			) );

			$columns = isset( $data['columns'] ) ? 'xtra-portfolio-widget-' . $data['columns'] : '';

		?>

		<div class="cd_gallery_in clr <?php echo $columns; ?>">
			<?php while ( $popular->have_posts() ): $popular->the_post(); ?>
					<?php if ( has_post_thumbnail() ): ?>
						<a class="cdEffect noborder" href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
							<?php

								if ( function_exists( 'wpb_getImageBySize' ) ) {

									echo Codevz_Plus::get_image( get_post_thumbnail_id( get_the_id() ), '150x150' );

								} else {

									the_post_thumbnail( 'thumbnail' );
									
								}

							?>
							<i class="fas fa-link"></i>
						</a>
					<?php endif; ?>
			<?php endwhile; wp_reset_query(); ?>
		</div>

		<?php
			$out .= ob_get_clean();
			$out .= $after_widget."\n";

			echo apply_filters( 'widget_text', $out );
		}
		
		public function update( $new, $old ) {

			$data = $old;
			$data['title'] = esc_html( $new['title'] );
			$data['gallery_order'] = esc_html( $new['gallery_order'] );
			$data['posts_num'] = esc_html( $new['posts_num'] );
			$data['columns'] = esc_html( $new['columns'] );

			return $data;
		}

		public function form($data) {
			$defaults = array(
				'title' 			=> 'Portfolio',
				'post_type' 		=> 'portfolio',
				'gallery_order' 	=> 'DESC',
				'posts_num' 		=> '9',
				'columns' 			=> '3'
			);
			$data = wp_parse_args( (array) $data, $defaults );
			
			$title_value = esc_attr( $data['title'] );
			$title_field = array(
				'id'    => $this->get_field_name('title'),
				'name'  => $this->get_field_name('title'),
				'type'  => 'text',
				'title' => esc_html__('Title', 'codevz')
			);
			echo csf_add_field( $title_field, $title_value );
			
			$title_value = esc_attr( $data['post_type'] );
			$title_field = array(
				'id'    => $this->get_field_name( 'post_type' ),
				'name'  => $this->get_field_name( 'post_type' ),
				'type'  => 'text',
				'title' => esc_html__( 'Post Type', 'codevz' )
			);
			echo csf_add_field( $title_field, $title_value );

			$posts_num_value = esc_attr( $data[ 'posts_num' ] );
			$posts_num_field = array(
				'id'    => $this->get_field_name( 'posts_num' ),
				'name'  => $this->get_field_name( 'posts_num' ),
				'type'  => 'number',
				'title'	=> esc_html__( 'Count', 'codevz' ),
			);
			echo csf_add_field( $posts_num_field, $posts_num_value );

			$posts_num_value = esc_attr( $data[ 'columns' ] );
			$posts_num_field = array(
				'id'    	=> $this->get_field_name( 'columns' ),
				'name'  	=> $this->get_field_name( 'columns' ),
				'type'  	=> 'radio',
				'options' 	=> [
					'2' 		=> '2',
					'3' 		=> '3',
				],
				'title'		=> esc_html__( 'Columns', 'codevz' ),
			);
			echo csf_add_field( $posts_num_field, $posts_num_value );

			$gallery_order_value = esc_attr( $data['gallery_order'] );
			$gallery_order_field = array(
				'id'    => $this->get_field_name('gallery_order'),
				'name'  => $this->get_field_name('gallery_order'),
				'type'  => 'radio',
				'options' => array(
					'DESC' => 'DESC',
					'ASC' => 'ASC'
				),
				'title' => esc_html__('Order', 'codevz')
			);
			echo csf_add_field( $gallery_order_field, $gallery_order_value );
		}
	}

}


/**
 * 
 * Custom taxonomy list widget
 * 
 */
if ( ! function_exists( 'init_lc_taxonomy' ) ) {

class lc_taxonomy extends WP_Widget {

	public function __construct() {
		parent::__construct( 
			false, 
			'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Taxonomy menus', 'codevz' ), 
			[ 
				'customize_selective_refresh' => true, 
				'classname' => 'lc_taxonomy' 
			]
		);
	}

	public function widget( $args, $data ) {
		global $post;
		extract($args);
		ob_start();

		// Widget options
		$title 	 = apply_filters('widget_title', $data['title'] ); // Title		
		$this_taxonomy = $data['taxonomy']; // Taxonomy to show
		$hierarchical = !empty( $data['hierarchical'] ) ? '1' : '0';
		$showcount = !empty( $data['count'] ) ? '1' : '0';
		if( array_key_exists('orderby',$data) ){
			$orderby = $data['orderby'];
		}
		else{
			$orderby = 'count';
		}
		if( array_key_exists('ascdsc',$data) ){
			$ascdsc = $data['ascdsc'];
		}
		else{
			$ascdsc = 'desc';
		}
		if( array_key_exists('exclude',$data) ){
			$exclude = $data['exclude'];
		}
		else {
			$exclude = '';
		}
		if( array_key_exists('childof',$data) ){
			$childof = $data['childof'];
		}
		else {
			$childof = '';
		}
		if( array_key_exists('dropdown',$data) ){
			$dropdown = $data['dropdown'];
		}
		else {
			$dropdown = false;
		}
        // Output
		$tax = $this_taxonomy;
		echo $before_widget;
		echo $before_title . $title . $after_title;
		echo '<div id="lct-widget-'.$tax.'-container" class="list-custom-taxonomy-widget">';
		if($dropdown){
			$taxonomy_object = get_taxonomy( $tax );
			$args = array(
				'show_option_all'    => false,
				'show_option_none'   => '',
				'orderby'            => $orderby,
				'order'              => $ascdsc,
				'show_count'         => $showcount,
				'hide_empty'         => 1,
				'child_of'           => $childof,
				'exclude'            => $exclude,
				'echo'               => 1,
				//'selected'           => 0,
				'hierarchical'       => $hierarchical,
				'name'               => $taxonomy_object->query_var,
				'id'                 => 'lct-widget-'.$tax,
				//'class'              => 'postform',
				'depth'              => 0,
				//'tab_index'          => 0,
				'taxonomy'           => $tax,
				'hide_if_empty'      => true
			);
			echo '<form action="'. esc_url( home_url( '/' ) ). '" method="get">';
			wp_dropdown_categories($args);
			echo '<input type="submit" value="go &raquo;" /></form>';
		}
		else {
			$args = array(
					'show_option_all'    => false,
					'orderby'            => $orderby,
					'order'              => $ascdsc,
					'style'              => 'list',
					'show_count'         => $showcount,
					'hide_empty'         => 1,
					'use_desc_for_title' => 1,
					'child_of'           => $childof,
					//'feed'               => '',
					//'feed_type'          => '',
					//'feed_image'         => '',
					'exclude'            => $exclude,
					//'exclude_tree'       => '',
					//'include'            => '',
					'hierarchical'       => $hierarchical,
					'title_li'           => '',
					'show_option_none'   => 'No Categories',
					'number'             => null,
					'echo'               => 1,
					'depth'              => 0,
					//'current_category'   => 0,
					//'pad_counts'         => 0,
					'taxonomy'           => $tax
				);
			echo '<ul id="lct-widget-'.$tax.'">';
			wp_list_categories($args);
			echo '</ul>';
		}
		echo '</div>';
		echo $after_widget;

		$out = ob_get_clean();
		echo apply_filters( 'widget_text', $out );
	}
	/** Widget control update */
	public function update( $new_instance, $old_instance ) {
		$data    = $old_instance;
		
		$data['title']  = strip_tags( $new_instance['title'] );
		$data['taxonomy'] = strip_tags( $new_instance['taxonomy'] );
		$data['orderby'] = $new_instance['orderby'];
		$data['ascdsc'] = $new_instance['ascdsc'];
		$data['exclude'] = $new_instance['exclude'];
		$data['expandoptions'] = $new_instance['expandoptions'];
		$data['childof'] = $new_instance['childof'];
		$data['hierarchical'] = !empty($new_instance['hierarchical']) ? 1 : 0;
        $data['count'] = !empty($new_instance['count']) ? 1 : 0;
        $data['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;

		return $data;
	}
	
	/* Widget settings */
	public function form( $data ) {
		echo "<sc" . "r" . "ipt>function lctwExpand(t){jQuery('#'+t).val('expand'),jQuery('.lctw-all-options').show(500),jQuery('.lctw-expand-options').hide(500)}function lctwContract(t){jQuery('#'+t).val('contract'),jQuery('.lctw-all-options').hide(500),jQuery('.lctw-expand-options').show(500)}jQuery(function($){var t=jQuery('#" . $this->get_field_id('expandoptions') . "').val();'expand'==t?jQuery('.lctw-expand-options').hide():'contract'==t&&jQuery('.lctw-all-options').hide()});</sc" . "r" . "ipt>";
		if ( $data ) {
			$title  = $data['title'];
			$this_taxonomy = $data['taxonomy'];
			$orderby = $data['orderby'];
			$ascdsc = $data['ascdsc'];
			$exclude = $data['exclude'];
			$expandoptions = $data['expandoptions'];
			$childof = $data['childof'];
			$showcount = isset($data['count']) ? (bool) $data['count'] :false;
			$hierarchical = isset( $data['hierarchical'] ) ? (bool) $data['hierarchical'] : false;
			$dropdown = isset( $data['dropdown'] ) ? (bool) $data['dropdown'] : false;
		} else {
			$title  = '';
			$orderby  = 'count';
			$ascdsc  = 'desc';
			$exclude  = '';
			$expandoptions  = 'contract';
			$childof  = '';
			$this_taxonomy = 'category';//this will display the category taxonomy, which is used for normal, built-in posts
			$hierarchical = true;
			$showcount = true;
			$dropdown = false;
		}

		?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php echo esc_html__( 'Title:', 'codevz' ); ?></label>
				<input id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" class="widefat" />
			</p>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('taxonomy') ); ?>"><?php echo esc_html__( 'Select Taxonomy:', 'codevz' ); ?></label>
				<select name="<?php echo esc_attr( $this->get_field_name('taxonomy') ); ?>" id="<?php echo esc_attr( $this->get_field_id('taxonomy') ); ?>" class="widefat" style="height: auto;" size="4">
			<?php 
			$args=array(
			  'public'   => true,
			  '_builtin' => false //these are manually added to the array later
			); 
			$output = 'names'; // or objects
			$operator = 'and'; // 'and' or 'or'
			$taxonomies=get_taxonomies($args,$output,$operator); 
			$taxonomies[] = 'category';
			$taxonomies[] = 'post_tag';
			$taxonomies[] = 'post_format';
			foreach ($taxonomies as $taxonomy ) { ?>
				<option value="<?php echo esc_attr( $taxonomy ); ?>" <?php if( $taxonomy == $this_taxonomy ) { echo 'selected="selected"'; } ?>><?php echo $taxonomy; ?></option>
			<?php }	?>
			</select>
			</p>
			<h4 class="lctw-expand-options"><a href="javascript:void(0)" onclick="lctwExpand('<?php echo esc_attr( $this->get_field_id('expandoptions') ); ?>')" >More Options...</a></h4>
			<div class="lctw-all-options">
				<h4 class="lctw-contract-options"><a href="javascript:void(0)" onclick="lctwContract('<?php echo esc_attr( $this->get_field_id('expandoptions') ); ?>')" >Hide Extended Options</a></h4>
				<input type="hidden" value="<?php echo esc_attr( $expandoptions ); ?>" id="<?php echo esc_attr( $this->get_field_id('expandoptions') ); ?>" name="<?php echo esc_attr( $this->get_field_name('expandoptions') ); ?>" />
				
				<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('count') ); ?>" name="<?php echo esc_attr( $this->get_field_name('count') ); ?>"<?php checked( $showcount ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id('count') ); ?>"><?php _e( 'Show post counts', 'codevz' ); ?></label><br />
				<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id('hierarchical') ); ?>" name="<?php echo esc_attr( $this->get_field_name('hierarchical') ); ?>"<?php checked( $hierarchical ); ?> />
				<label for="<?php echo esc_attr( $this->get_field_id('hierarchical') ); ?>"><?php _e( 'Show hierarchy', 'codevz' ); ?></label></p>
				
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id('orderby') ); ?>"><?php echo esc_html__( 'Order By:', 'codevz' ); ?></label>
					<select name="<?php echo esc_attr( $this->get_field_name('orderby') ); ?>" id="<?php echo esc_attr( $this->get_field_id('orderby') ); ?>" class="widefat" >
						<option value="ID" <?php if( $orderby == 'ID' ) { echo 'selected="selected"'; } ?>>ID</option>
						<option value="name" <?php if( $orderby == 'name' ) { echo 'selected="selected"'; } ?>>Name</option>
						<option value="slug" <?php if( $orderby == 'slug' ) { echo 'selected="selected"'; } ?>>Slug</option>
						<option value="count" <?php if( $orderby == 'count' ) { echo 'selected="selected"'; } ?>>Count</option>
						<option value="term_group" <?php if( $orderby == 'term_group' ) { echo 'selected="selected"'; } ?>>Term Group</option>
					</select>
				</p>
				<p>
					<label><input type="radio" name="<?php echo esc_attr( $this->get_field_name('ascdsc') ); ?>" value="asc" <?php if( $ascdsc == 'asc' ) { echo 'checked'; } ?>/> Ascending</label><br/>
					<label><input type="radio" name="<?php echo esc_attr( $this->get_field_name('ascdsc') ); ?>" value="desc" <?php if( $ascdsc == 'desc' ) { echo 'checked'; } ?>/> Descending</label>
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id('exclude') ); ?>">Exclude (comma-separated list of ids to exclude)</label><br/>
					<input type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name('exclude') ); ?>" value="<?php echo esc_attr( $exclude ); ?>" />
				</p>
				<p>
					<label for="<?php echo esc_attr( $this->get_field_id('exclude') ); ?>">Only Show Children of (category id)</label><br/>
					<input type="text" class="widefat" name="<?php echo esc_attr( $this->get_field_name('childof') ); ?>" value="<?php echo esc_attr( $childof ); ?>" />
				</p>
			</div>
<?php 
	}

}
}

/**
 * 
 * Posts widget
 * 
 */
if ( ! class_exists( 'CodevzPostsList' ) ) {

	class CodevzPostsList extends WP_Widget {

		public function __construct() {
			parent::__construct( 
				false, 
				'( ' . Codevz_Plus::$title . ' ) ' . esc_html__( 'Posts list', 'codevz' ), 
				[ 
					'customize_selective_refresh' => true, 
					'classname' => 'codevz-widget-posts' 
				]
			);
		}

		public function widget( $args, $data ) {
			extract( $args, EXTR_SKIP );

	        $title = apply_filters( 'widget_title', $data['title'] );
	        $post_amount = $data['show'];
			$post_orderby = $data['orderby'];
			$post_order = $data['order'];
			$post_catin = $data['catin'];
			$post_catout = $data['catout'];
			$pagecount = $data['pagecount'];
			$post_taxis = $data['taxis'];
			$post_taxterm = $data['taxterm'];
			$post_typed = $data['ptipe'];
			$post_metakey = $data['metakey'];
			$post_metavalue = $data['metavalue'];
			$post_comparison = $data['metacompare'];
			$post_widgeid = $data['widgetidentifier'];
			$post_widgeclass = $data['widgetclassifier'];
			$post_readmoretitle = $data['readmoretitle'];
			$post_readmorelink = $data['readmorelink'];
	        //$term = $data['term'];

			if(!$post_typed){$post_typed = 'post';}
			if(!$post_comparison){$post_comparison = '=';}
	        // getting the posts we want
			
			//$cpage = get_query_var('paged')?get_query_var('paged'):0;
			//if(!isset($cpage) || $cpage == "" || $cpage === 0){
				//$cpage = get_query_var('page')?get_query_var('page'):1;
			//}
			
	        $qargs = array(
	          'post_type' 		=> $post_typed,
	          'showposts' 		=> $post_amount,
			  'post_status' 	=> 'publish',
			  'paged' 			=> 1
	        );
			if($post_catin){
				$catin = explode(",", $post_catin);
				$qargs['category__in'] = $catin;
			}
			if($post_catout){
				$catout = explode(",", $post_catout);
				$qargs['category__not_in'] = $catout;
			}
			if($post_taxis && $post_taxterm){
				$taxray = explode(",", $post_taxterm);
				$qargs['tax_query'] = array(
					array(
					'taxonomy'  => $post_taxis,
					'field'     => 'slug',
					'terms'     => $taxray,
					)
				);
			}
			if($post_metakey && $post_metavalue){
				$qargs['meta_query'] = array(
					array(
						'key'     => $post_metakey,
						'value'   => $post_metavalue,
						'compare' => $post_comparison,
					),
				);
			}
			if($post_orderby){
				$qargs['orderby'] = $post_orderby;
			}
			if($post_order){
				$qargs['order'] = $post_order;
			}

			$qargs = apply_filters('wpr_adjust_genposts_query', $qargs, $args, $data);
	        $postsQ = new WP_Query( $qargs ); //get_posts
			
			$maxpages = $postsQ->max_num_pages;
			$totalfound = $postsQ->found_posts;

			$title = apply_filters( 'widget_title', $data['title'] );
			ob_start();
			echo $before_widget . "\n";
			echo $title ? $before_title . $title . $after_title : '';		
				
				$makeid = '';
				$makeclass = '';
				if($post_widgeid){$makeid = 'id="' . $post_widgeid . '"';}
				if($post_widgeclass){$makeclass = 'id="' . $makeclass . '"';}

				$toprint = '';
				$count = 1;			

				if($postsQ->have_posts()){
					while($postsQ->have_posts()){ $postsQ->the_post(); global $post;
						$thisprint = '<div class="item_small">';
						$thm = get_the_post_thumbnail( $post->ID, 'thumbnail' );
						if ( has_post_thumbnail() && $thm ):
							$thisprint .= '<a href="'.get_permalink( $post->ID ).'" title="'.get_the_title( $post->ID ).'">' . $thm . '<i class="fas fa-link"></i></a>';
						endif;
						$thisprint .= '<div class="item-details"><h3><a class="genposts_linktitle" href="'.get_permalink( $post->ID ).'" title="'.get_the_title( $post->ID ).'">'.get_the_title( $post->ID ).'</a></h3>';
						$thisprint .= '<div class="cz_small_post_date"><i class="fa fa-clock-o mr8"></i>' . get_the_date() . '</div>';
						$thisprint .= '</div></div>';
						$toprint .= apply_filters('wpr_genposts_listloop', $thisprint, $postsQ->found_posts, $post, $count, $data);
						$count++;
					}
					wp_reset_postdata();
				}
				$readingon = $openprint = $closeprint = '';
				$extern = '';
				if($post_readmoretitle && $post_readmorelink){
					$readingon = '<div class="tac mtt"><a href="' . $post_readmorelink . '" rel="bookmark" title="' . $post_readmoretitle . '" class="tbutton"><span>' . $post_readmoretitle . '</span></a></div>';
				}
				$closeprint .= apply_filters('wpr_genposts_addtoend', $readingon, $data);
				$finalprint = apply_filters('wpr_genposts_list_print', $openprint . $toprint . $closeprint, $openprint, $toprint, $closeprint, $data, $postsQ);
				echo $finalprint;
				
	        echo $after_widget;	

			$out = ob_get_clean();
			echo apply_filters( 'widget_text', $out );

		}

		public function update( $new_instance, $old_instance ) {
			$data = $old_instance;
			$data['title']	= strip_tags( $new_instance['title'] );
			$data['show']	= strip_tags( $new_instance['show'] );
			$data['orderby']	= strip_tags( $new_instance['orderby'] );
			$data['order']	= strip_tags( $new_instance['order'] );
			$data['catin']	= strip_tags( $new_instance['catin'] );
			$data['catout']	= strip_tags( $new_instance['catout'] );
			$data['pagecount'] = strip_tags( $new_instance['pagecount']);
			$data['taxis'] = strip_tags( $new_instance['taxis']);
			$data['taxterm'] = strip_tags( $new_instance['taxterm']);
			$data['ptipe'] = strip_tags( $new_instance['ptipe']);
			$data['metakey'] = strip_tags( $new_instance['metakey']);
			$data['metavalue'] = strip_tags( $new_instance['metavalue']);
			$data['metacompare'] = strip_tags( $new_instance['metacompare']);
			$data['widgetidentifier'] = strip_tags( $new_instance['widgetidentifier']);
			$data['widgetclassifier'] = strip_tags( $new_instance['widgetclassifier']);
			$data['readmoretitle'] = $new_instance['readmoretitle'];
			$data['readmorelink'] = strip_tags( $new_instance['readmorelink']);
			//$data['term']	= absint( $new_instance['term'] );
			return $data;
		}

		public function form( $data ) {

			$defaults = array(
				'title' => esc_html__( 'General Posts', 'codevz' ),
				'show' => '3',
				'orderby'=> 'date',
				'order'=>'DESC',
				'catin' => '',
				'catout' => '',
				'pagecount' => '3',
				'taxis' => '',
				'taxterm' => '',
				'ptipe' => 'post',
				'metakey'=> '',
				'metavalue' => '',
				'metacompare' => '=',
				'widgetidentifier' => '',
				'widgetclassifier' => '',
				'readmoretitle' => '',
				'readmorelink' => ''
			);

			$data = wp_parse_args( (array) $data, $defaults );
			$title = $data['title'];
			$show  = $data['show'];
			$orderby  = $data['orderby'];
			$order  = $data['order'];
			$post_catin = $data['catin'];
			$post_catout = $data['catout'];
			$pagecount = $data['pagecount'];
			$post_taxis = $data['taxis'];
			$post_taxterm = $data['taxterm'];
			$post_typed = $data['ptipe'];
			$post_metakey = $data['metakey'];
			$post_metavalue = $data['metavalue'];
			$post_comparison = $data['metacompare'];
			$post_widgeid = $data['widgetidentifier'];
			$post_widgeclass = $data['widgetclassifier'];
			$post_readmoretitle = $data['readmoretitle'];
			$post_readmorelink = $data['readmorelink'];
			//$term  = $data['term'];

	        // get the parent term
	        //$season = get_term_by( 'slug', 'seasonal', 'featured' );
			$orbe = array('none', 'ID', 'author', 'title', 'name', 'date', 'modified', 'parent', 'rand', 'comment_count', 'menu_order', 'meta_value', 'meta_value_num');
			$metcompare = array( '=', '!=', '>', '>=', '<', '<=', 'LIKE', 'NOT LIKE', 'IN', 'NOT IN', 'EXISTS', 'NOT EXISTS');
			
			?>

			<p><?php esc_html_e( 'Title', 'codevz' ); ?> <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>" /></p>
			
			<p><?php esc_html_e( 'ID', 'codevz' ); ?> <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'widgetidentifier' ) ); ?>" value="<?php echo esc_attr($post_widgeid); ?>" /></p>
			
			<p><?php esc_html_e( 'Class', 'codevz' ); ?> <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'widgetclassifier' ) ); ?>" value="<?php echo esc_attr($post_widgeclass); ?>" /></p>
			
			<p><?php esc_html_e( 'Post type', 'codevz' ); ?> 	
				<select name="<?php echo esc_attr( $this->get_field_name('ptipe') ); ?>"><?php
			
				$datype = get_post_types(array('public'=>true), 'objects'); 
				foreach($datype as $atipe){
					?>
						<option value="<?php echo esc_attr( $atipe->name ); ?>" <?php if($atipe->name == $post_typed){echo "selected";} ?>><?php echo esc_attr( $atipe->label ); ?></option>
					<?php
				}
				?>
				</select>
			</p>

			<p><?php esc_html_e( 'Number of posts', 'codevz' ); ?> <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'show' ) ); ?>" value="<?php echo esc_attr( $show ); ?>" /></p>
			<p><?php esc_html_e( 'Posts to show at once', 'codevz' ); ?> <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'pagecount' ) ); ?>" value="<?php echo esc_attr( $pagecount ); ?>" /></p>
	        <p><?php esc_html_e( 'Order by', 'codevz' ); ?>

	            <select name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>">
	                <?php
	                foreach( $orbe as $orb ){
	                ?>
	                    <option value="<?php echo esc_attr( $orb ); ?>" <?php selected( $orderby, $orb); ?>><?php echo esc_attr( $orb ); ?></option>
	                <?php } ?>
	            </select>
	        </p>

			<p><?php esc_html_e( 'Order', 'codevz' ); ?>

	            <select name="<?php echo esc_attr( $this->get_field_name( 'order' ) ); ?>">
	                    <option value="ASC" <?php selected( $order, 'ASC'); ?>><?php esc_html_e( 'Ascending', 'codevz' ); ?></option>
						<option value="DESC" <?php selected( $order, 'DESC'); ?>><?php esc_html_e( 'Descending', 'codevz' ); ?></option>
	             </select>
	        </p>
			<p><?php esc_html_e( 'USE ONLY ONE OPTION BELOW', 'codevz' ); ?></p>
			<p><?php esc_html_e( 'Category ID include', 'codevz' ); ?> <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'catin' ) ); ?>" value="<?php echo esc_attr( $post_catin ); ?>" /></p>

			<p><?php esc_html_e( 'Category ID exclude', 'codevz' ); ?> <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'catout' ) ); ?>" value="<?php echo esc_attr( $post_catout ); ?>" /></p>

			<p><?php esc_html_e( 'Taxonomy', 'codevz' ); ?> <select name="<?php echo esc_attr( $this->get_field_name('taxis') ); ?>"><?php
			
				$dataxes = get_object_taxonomies($post_typed, 'objects');
				foreach($dataxes as $atax){
					?>
						<option value="<?php echo esc_attr( $atax->name ); ?>" <?php if($atax->name == $post_taxis){echo "selected";} ?>><?php echo esc_attr( $atax->label ); ?></option>
					<?php
				}
			?>
			</select>
			<br/>
			<?php esc_html_e( 'Enter the term slug', 'codevz' ); ?>
			<input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'taxterm' ) ); ?>" value="<?php echo esc_attr( $post_taxterm ); ?>" />
			</p>
			
			<?php esc_html_e( 'Meta key', 'codevz' ); ?> <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'metakey' ) ); ?>" value="<?php echo esc_attr( $post_metavalue ); ?>" />
			<br/>
			<?php esc_html_e( 'Meta value', 'codevz' ); ?> <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'metavalue' ) ); ?>" value="<?php echo esc_attr( $post_metavalue ); ?>" />
			<br/>
			<?php esc_html_e( 'Meta compare', 'codevz' ); ?>
			<select name="<?php echo esc_attr( $this->get_field_name( 'metacompare' ) ); ?>">
	                <?php
	                foreach( $metcompare as $mc ){
	                ?>
	                    <option value="<?php echo esc_attr( $mc ); ?>" <?php selected( $post_comparison, $mc); ?>><?php echo esc_attr( $mc ); ?></option>
	                <?php } ?>
	            </select>
			</p>
			
			<p><?php esc_html_e( 'Read more', 'codevz' ); ?> <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'readmoretitle' ) ); ?>" value="<?php echo esc_attr($post_readmoretitle); ?>" /></p>

			<p><?php esc_html_e( 'Read more link', 'codevz' ); ?> <input class="widefat" name="<?php echo esc_attr( $this->get_field_name( 'readmorelink' ) ); ?>" value="<?php echo esc_attr($post_readmorelink); ?>" /></p>
			<?php
		}
	}
}

/**
 * 
 * Register custom widgets
 * 
 */
function codevz_register_widgets() {
	register_widget( 'Codevz_Widget_Working_Hours' );
	register_widget( 'Codevz_Widget_Stylish_List' );
	register_widget( 'Codevz_Widget_Social_Icons' );
	register_widget( 'Codevz_Widget_Custom_Menu_List' );
	register_widget( 'Codevz_Widget_Gallery' );
	register_widget( 'Codevz_Widget_About' );

	register_widget( 'CodevzFacebook' );
	register_widget( 'CodevzFlickr' );
	register_widget( 'CodevzCustomMenuList' );
	register_widget( 'CodevzCustomMenuList2' );
	register_widget( 'CodevzPostsList' );
	register_widget( 'CodevzSimpleAds' );
	register_widget( 'CodevzSubscribe' );
	register_widget( 'CodevzPageContent' );
	register_widget( 'CodevzPortfolio' );
	register_widget( 'lc_taxonomy' );

	register_widget( 'Codevz_Widget_Soundcloud' );
	register_widget( 'Codevz_Widget_Unboxed' );
	register_widget( 'Xtra_Widget_Newsletter' );
}
add_action( 'widgets_init', 'codevz_register_widgets' );
