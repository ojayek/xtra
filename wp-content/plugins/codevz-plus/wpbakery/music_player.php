<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Music Player
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_music_player {

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
			'base'			=> $this->name,
			'name'			=> esc_html__( 'Music Player', 'codevz' ),
			'description'	=> esc_html__( 'Single and playlist music', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' => 'param_group',
					'heading' => esc_html__( 'Add track(s)', 'codevz' ),
					'param_name' => 'tracks',
					'params' => array(
						array(
							"type"        	=> "textfield",
							"heading"     	=> esc_html__("Title", 'codevz'),
							'edit_field_class' => 'vc_col-xs-6',
							"param_name"  	=> "title",
							"admin_label"	=> true
						),
						array(
							"type"        	=> "textfield",
							"heading"     	=> esc_html__("Badge", 'codevz'),
							'edit_field_class' => 'vc_col-xs-6',
							"param_name"  	=> "badge"
						),
						array(
							"type"        	=> "cz_upload",
							"heading"     	=> esc_html__("MP3", 'codevz'),
							"param_name"  	=> "mp3",
							'upload_type' 	=> 'audio/mpeg'
						),
						array(
							"type"        	=> "cz_icon",
							"heading"     	=> esc_html__("Icon", 'codevz'),
							'edit_field_class' => 'vc_col-xs-4',
							"param_name"  	=> "icon",
						),
						array(
							"type"        	=> "textfield",
							"heading"     	=> esc_html__("Icon Title", 'codevz'),
							'edit_field_class' => 'vc_col-xs-4',
							"param_name"  	=> "icon_title"
						),
						array(
							"type"        	=> "textfield",
							"heading"     	=> esc_html__("Icon Link", 'codevz'),
							'edit_field_class' => 'vc_col-xs-4',
							"param_name"  	=> "icon_link"
						),
					),
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' )
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Player width", 'codevz'),
					"param_name"  	=> "css_width",
					'options' 		=> array( 'unit' => '%', 'step' => 1, 'min' => 20, 'max' => 100 ),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'			=> 'only_play',
						'value_not_equal_to'=> array( '1', 'true' )
					)
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Playlist height", 'codevz'),
					"param_name"  	=> "css_height",
					'options' 		=> array( 'unit' => 'px', 'step' => 10, 'min' => 100, 'max' => 600 ),
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'			=> 'only_play',
						'value_not_equal_to'=> array( '1', 'true' )
					)
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_con',
					"heading"     	=> esc_html__( "Container styling", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_con_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_con_mobile' ),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_player',
					"heading"     	=> esc_html__( "Player styling", 'codevz'),
					'button' 		=> esc_html__( "Player", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_player_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_player_mobile' ),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Settings', 'codevz' )
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Only play button ?", 'codevz'),
					"param_name"  	=> "only_play",
					'edit_field_class' => 'vc_col-xs-99',
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Playlist open?", 'codevz'),
					"param_name"  	=> "playlist_open",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'			=> 'only_play',
						'value_not_equal_to'=> array( '1', 'true' )
					)
				),
				array(
					'type'			=> 'checkbox',
					'heading'		=> esc_html__('Fixed bottom?', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name'	=> 'fixed',
					'dependency'	=> array(
						'element'			=> 'only_play',
						'value_not_equal_to'=> array( '1', 'true' )
					)
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Dark text?", 'codevz'),
					"param_name"  	=> "dark_text",
					'edit_field_class' => 'vc_col-xs-99',
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Flat?", 'codevz'),
					"param_name"  	=> "flat",
					'edit_field_class' => 'vc_col-xs-99',
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Auto play?", 'codevz'),
					"param_name"  	=> "autoplay",
					'edit_field_class' => 'vc_col-xs-99',
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Center mode?", 'codevz'),
					"param_name"  	=> "center",
					'edit_field_class' => 'vc_col-xs-99',
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
					'content' 		=> esc_html__( 'Parallax', 'codevz' ),
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type"        	=> "dropdown",
					"heading"     	=> esc_html__( "Parallax", 'codevz' ),
					"param_name"  	=> "parallax_h",
					'edit_field_class' => 'vc_col-xs-99',
					'value'		=> array(
						esc_html__( 'Select', 'codevz' )					=> '',
						
						esc_html__( 'Vertical', 'codevz' )					=> 'v',
						esc_html__( 'Vertical + Mouse parallax', 'codevz' )		=> 'vmouse',
						esc_html__( 'Horizontal', 'codevz' )				=> 'true',
						esc_html__( 'Horizontal + Mouse parallax', 'codevz' )	=> 'truemouse',
						esc_html__( 'Mouse parallax', 'codevz' )				=> 'mouse',
					),
					"group"  		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__( "Parallax speed", 'codevz' ),
					"description"   => esc_html__( "Parallax is according to page scrolling", 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "parallax",
					"value"  		=> "0",
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => -50, 'max' => 50 ),
					'dependency'	=> array(
						'element'		=> 'parallax_h',
						'value'			=> array( 'v', 'vmouse', 'true', 'truemouse' )
					),
					"group"  		=> esc_html__( 'Advanced', 'codevz' )
				),
				array(
					'type' 			=> 'checkbox',
					'heading' 		=> esc_html__( 'Stop when done', 'codevz' ),
					'param_name' 	=> 'parallax_stop',
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'parallax_h',
						'value'			=> array( 'v', 'vmouse', 'true', 'truemouse' )
					),
					'group' 		=> esc_html__( 'Advanced', 'codevz' )
				), 
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Mouse speed", 'codevz'),
					"description"   => esc_html__( "Mouse parallax is according to mouse move", 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "mparallax",
					"value"  		=> "0",
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => -30, 'max' => 30 ),
					'dependency'	=> array(
						'element'		=> 'parallax_h',
						'value'			=> array( 'vmouse', 'truemouse', 'mouse' )
					),
					"group"  		=> esc_html__( 'Advanced', 'codevz' )
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

		// Assets
		wp_enqueue_script( 'codevz-soundmanager' );
		wp_enqueue_script( 'codevz-bar-ui' );
		wp_enqueue_style( 'codevz-bar-ui' );

		// Styles
		if ( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {
			$css_id = '#' . $atts['id'];
			$custom = Codevz_Plus::contains( $atts['sk_player'], 'radius' ) ? 'overflow:hidden;' : '';

			$css_array = array(
				'sk_con' 	=> $css_id,
				'sk_brfx' 	=> $css_id . ':before',
				'sk_player' => array( $css_id . ' .bd.sm2-main-controls, ' . $css_id . ' .bd.sm2-playlist-drawer', $custom )
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			$css .= $atts['css_width'] ? $css_id . '{width:' . $atts['css_width'] . '}' : '';
			$css .= $atts['anim_delay'] ? $css_id . '{animation-delay:' . $atts['anim_delay'] . '}' : '';
			$css .= $atts['css_height'] ? $css_id . ' .sm2-playlist-bd{height:' . $atts['css_height'] . '}' : '';
		}

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz-music-player sm2-bar-ui';
		$classes[] .= $atts['playlist_open'] ? 'playlist-open' : '';
		$classes[] .= $atts['dark_text'] ? 'dark-text' : '';
		$classes[] .= $atts['flat'] ? 'flat' : '';
		$classes[] .= $atts['fixed'] ? 'fixed full-width' : '';
		$classes[] .= $atts['autoplay'] ? 'cz_autoplay' : '';
		$classes[] .= $atts['only_play'] ? 'cz_only_play' : '';
		$classes[] .= $atts['center'] ? 'cz_center_tm' : '';

		// Tracks
		$tracks_out = '';
		$tracks = json_decode( urldecode( $atts[ 'tracks' ] ), true );
		foreach ( $tracks as $track ) {
			$mp3 = isset( $track['mp3'] ) ? $track['mp3'] : '';
			$title = isset( $track['title'] ) ? $track['title'] : '';
			$badge = isset( $track['badge'] ) ? $track['badge'] : '';
			$icon = isset( $track['icon'] ) ? $track['icon'] : '';
			$icon_title = isset( $track['icon_title'] ) ? $track['icon_title'] : '';
			$icon_link = isset( $track['icon_link'] ) ? $track['icon_link'] : '';
			$tracks_out .= '<li><div class="sm2-row"><div class="sm2-col sm2-wide"><a href="' . $mp3 . '" class="exclude button-exclude inline-exclude">' . $title . ' <span class="label">' . $badge . '</span></a></div>';
			$dl = '';
			if ( Codevz_Plus::contains( $icon_link, array( '.mp', '.avi', '.ogg', '.wav', '.wave', '.zip', '.rar' ) ) ) {
				$dl = ' download="download"';
			}
			$tracks_out .= $icon ? '<div class="sm2-col"><a href="' . $icon_link . '" target="_blank" title="' . $icon_title . '" class="sm2-icon sm2-exclude"' . $dl . '><i class="' . $icon . '"></i></a></div>' : '';
			$tracks_out .= '</div></li>';
		}

		// HTML of next prev & menu playlist
		$next_prev = ( count( $tracks ) !== 1 ) ? '<div class="sm2-inline-element sm2-button-element"><div class="sm2-button-bd"><a href="#prev" title="Previous" class="sm2-inline-button previous">&lt; previous</a></div></div><div class="sm2-inline-element sm2-button-element"><div class="sm2-button-bd"><a href="#next" title="Next" class="sm2-inline-button next">&gt; next</a></div></div><div class="sm2-inline-element sm2-button-element sm2-menu"><div class="sm2-button-bd"><a href="#menu" class="sm2-inline-button menu">menu</a></div></div>' : '';

		// Output
		$out = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '><div class="bd sm2-main-controls"><div class="sm2-inline-texture"></div><div class="sm2-inline-gradient"></div><div class="sm2-inline-element sm2-button-element"><div class="sm2-button-bd"><a href="#play" class="sm2-inline-button play-pause">Play / pause</a></div></div><div class="sm2-inline-element sm2-inline-status"><div class="sm2-playlist"><div class="sm2-playlist-target"><noscript><p>JavaScript is required.</p></noscript></div></div><div class="sm2-progress"><div class="sm2-row"><div class="sm2-inline-time">0:00</div><div class="sm2-progress-bd"><div class="sm2-progress-track"><div class="sm2-progress-bar"></div><div class="sm2-progress-ball"><div class="icon-overlay"></div></div></div></div><div class="sm2-inline-duration">0:00</div></div></div></div><div class="sm2-inline-element sm2-button-element sm2-volume"><div class="sm2-button-bd"><span class="sm2-inline-button sm2-volume-control volume-shade"></span><a href="#volume" class="sm2-inline-button sm2-volume-control">volume</a></div></div>' . $next_prev . '</div><div class="bd sm2-playlist-drawer sm2-element"><div class="sm2-inline-texture"><div class="sm2-box-shadow"></div></div><div class="sm2-playlist-wrapper"><ul class="sm2-playlist-bd">' . $tracks_out . '</ul></div></div></div>';

		// Fix fixed player
		$out .= "<script>

			! function( $ ) {

				if ( $( '.sm2-bar-ui.fixed' ).length ) {
					setTimeout(function() {
						$( document.body ).css( 'marginBottom', $( '.sm2-bar-ui.fixed' ).height() );
					}, 2000 );
				}

			}( jQuery );

		</script>";

		return Codevz_Plus::_out( $atts, $out, false, $this->name );
	}

}