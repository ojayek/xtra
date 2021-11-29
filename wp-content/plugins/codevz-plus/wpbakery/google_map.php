<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Video Popup and inline
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_google_map {

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
			'name'			=> esc_html__( 'Google Map', 'codevz' ),
			'description'	=> esc_html__( 'Customisable google map', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					"type" => "textfield",
					"heading" => esc_html__("Google API Key","codevz"),
					"param_name" => "apikey",
					'edit_field_class' => 'vc_col-xs-99',
					"description" => esc_html__("Get your API Key from","codevz") . ' <a href="https://goo.gl/wVcKPP" target="_blank">' . esc_html__("Here","codevz") . '</a>',
				),
				array(
					"type" => "textfield",
					"heading" => esc_html__("Lat","codevz"),
					"param_name" => "lat",
					"value" => "40.712784",
					'edit_field_class' => 'vc_col-xs-99',
					"description" => esc_html__("Find your location Lat and Long from","codevz") . ' <a href="http://www.latlong.net" target="_blank">' . esc_html__("Here","codevz") . '</a>',
				),
				array(
					"type" => "textfield",
					"heading" => esc_html__("Long","codevz"),
					"param_name" => "long",
					"value" => "-74.005941",
					'edit_field_class' => 'vc_col-xs-99',
				),
				array(
					"type" => "cz_slider",
					"heading" => esc_html__("Zoom","codevz"),
					"param_name" => "zoom",
					"value"		=> "14",
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 1, 'min' => 0, 'max' => 19 ),
				),
				array(
					"type" => "cz_slider",
					"heading" => esc_html__("Marker Offset X","codevz"),
					"param_name" => "offsetx",
					"value"		=> "0",
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 10, 'min' => -400, 'max' => 400 ),
				),
				array(
					"type" => "cz_slider",
					"heading" => esc_html__("Marker Offset Y","codevz"),
					"param_name" => "offsety",
					"value"		=> "0",
					'edit_field_class' => 'vc_col-xs-99',
					'options' 		=> array( 'unit' => '', 'step' => 10, 'min' => -400, 'max' => 400 ),
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_map',
					"heading"     	=> esc_html__( "Map size", 'codevz'),
					'button' 		=> esc_html__( "Map container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'width', 'height', 'border' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_map_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_map_mobile' ),
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Color scheme","codevz"),
					"param_name" => "color",
					'edit_field_class' => 'vc_col-xs-99',
					"value" => array(
						esc_html__('Standard',"codevz") 			=> 'standard',
						esc_html__('Light (Silver)',"codevz") 		=> 'light',
						esc_html__('Dark',"codevz") 				=> 'dark',
						esc_html__('Retro',"codevz") 				=> 'retro',
						esc_html__('Custom Color',"codevz") 		=> 'custom',
					)
				 ),
				 array(
					"type" => "colorpicker",
					"heading" => esc_html__("Custom color","codevz"),
					"param_name" => "custom_color",
					'edit_field_class' => 'vc_col-xs-99',
					"value" => '',
					'dependency'	=> array(
						'element'		=> 'color',
						'value'			=> array( 'custom')
					)
				 ),
				 array(
					"type" => "checkbox",
					"heading" => esc_html__("Grayscale Effect?","codevz"),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name" => "grayscale",
					"value" => array( esc_html__( "Yes, please", "codevz" ) => 'yes' )
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Marker', 'codevz' ),
				),
				array(
					"type" => "dropdown",
					"heading" => esc_html__("Marker","codevz"),
					"param_name" => "marker",
					'edit_field_class' => 'vc_col-xs-99',
					"value" => array(
						'Off' => 'off',
						'Default Marker' => 'default',
						'Custom Image' => 'custom',
					)
				),
				array(
					"type" => "attach_image",
					"heading" => esc_html__("Marker image","codevz"),
					"param_name" => "markerimage",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'marker',
						'value'			=> array( 'custom')
					)
				),
				array(
					"type" => "textfield",
					"heading" => esc_html__("Info text","codevz"),
					"param_name" => "infowindow",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'marker',
						'value'			=> array( 'default','custom')
					)
				),
				array(
					"type" => "checkbox",
					"heading" => esc_html__("Show info by default?","codevz"),
					"param_name" => "infowindowdefault",
					'edit_field_class' => 'vc_col-xs-99',
					"value" => array( esc_html__( "Yes, please", "codevz" ) => 'yes' ),
					'dependency'	=> array(
						'element'		=> 'marker',
						'value'			=> array( 'default','custom')
					)
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Advanced Settings', 'codevz' ),
				),
				array(
					"type" => "checkbox",
					"heading" => esc_html__("Enable mouse wheel?","codevz"),
					"param_name" => "scrollwheel",
					'edit_field_class' => 'vc_col-xs-99',
					"value" => array( esc_html__( "Yes, please", "codevz" ) => 'yes' )
				),
				array(
					"type" => "checkbox",
					"heading" => esc_html__("Hide map controls?","codevz"),
					"param_name" => "hidecontrols",
					'edit_field_class' => 'vc_col-xs-99',
					"value" => array( esc_html__( "Yes, please", "codevz" ) => 'yes' )
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
			$atts['id'] = Codevz_Plus::uniqid( 'cz_map_' );
			$public = 1;
		}

		$isfront = Codevz_Plus::$vc_editable ? true : false;

		// Data
		$str_data_apikey = $str_apikey = '';
		if( $atts['apikey'] ) {
			$str_apikey = '?key=' . $atts['apikey'] ;
			$str_data_apikey = ' data-api-key="' . $atts['apikey'] . '"' ;
		} else {
			return '<div style="border:1px solid #ccc;border-radius:5px;color:#bb0000;margin:20px 0;padding:30px 0;text-align: center;">
			<h3>Google API Key Error!</h3><h5 style="color:#555">Get your API Key from <a style="color:#bb0000" href="https://goo.gl/wVcKPP">here</a></h5></div>';
		}

		// Styles
		if ( isset( $public ) || $isfront || Codevz_Plus::$is_admin ) {
			$css_id = '#' . $atts['id'];
			$custom = $atts['anim_delay'] ? 'animation-delay:' . $atts['anim_delay'] . ';' : '';

			$css_array = array(
				'sk_map' 	=> array( $css_id, $custom ),
				'sk_brfx' 	=> $css_id . ':before',
			);

			$css 	= Codevz_Plus::sk_style( $atts, $css_array );
			$css_t 	= Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m 	= Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );
		}

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'gmap';
		$classes[] = ( $atts['grayscale'] === 'yes' ) ? 'fx_grayscale fx_remove_grayscale_hover' : '';

		$str = '<div id="' . $atts['id'] . '"' . Codevz_Plus::classes( $atts, $classes ) . $str_data_apikey . '> </div><div '. Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '></div>';

		$str .= '<script type="text/javascript">
			function mapfucntion_' . $atts['id'] . '() {

				google.maps.Map.prototype.setCenterWithOffset= function(latlng, offsetX, offsetY) {
				    var map = this;
				    var ov = new google.maps.OverlayView();
				    ov.onAdd = function() {
				        var proj = this.getProjection();
				        var aPoint = proj.fromLatLngToContainerPixel(latlng);
				        aPoint.x = aPoint.x+offsetX;
				        aPoint.y = aPoint.y+offsetY;
				        map.setCenter(proj.fromContainerPixelToLatLng(aPoint));
				    }; 
				    ov.draw = function() {}; 
				    ov.setMap(this); 
				};

				var windx = jQuery(window), 

					latlng = new google.maps.LatLng(' . $atts['lat'] . ', ' . $atts['long'] . '),

					myOptions = {
						zoom: ' . ( $atts['zoom'] ? $atts['zoom'] : 14 ) . ',
						scrollwheel: ' . ( ( $atts['scrollwheel'] === 'yes' ) ? 'true' : 'false' ) . ',
						disableDefaultUI: ' . ( ( $atts['hidecontrols'] === 'true' ) ? 'true' : 'false' ) . ',
						center: latlng
					},

					' . $atts['id'] . ' = new google.maps.Map(document.getElementById("' . $atts['id'] . '"),
					myOptions),

					setCenterOffset = function() {

						if ( windx.width() < 768 ) {
							' . $atts['id'] . '.setCenterWithOffset( latlng, 0, 0 );
						} else {
							' . $atts['id'] . '.setCenterWithOffset( latlng, ' . $atts['offsetx'] . ', ' . $atts['offsety'] . ' );
						}

					};

				setCenterOffset();

				windx.on( "resize", function() {
					setCenterOffset();
				});
			';
		
		if ($atts['color']!='standard'){
			switch ($atts['color']) {
				case 'light':
					$str .= 'var styles = [{"elementType": "geometry","stylers": [{"color": "#f5f5f5"}]},{"elementType": "labels.icon","stylers": [{"visibility": "off"}]},{"elementType": "labels.text.fill","stylers": [{"color": "#616161"}]},{"elementType": "labels.text.stroke","stylers": [{"color": "#f5f5f5"}]},{"featureType": "administrative.land_parcel","elementType": "labels","stylers": [{"visibility": "off"}]},{"featureType": "administrative.land_parcel","elementType": "labels.text.fill","stylers": [{"color": "#bdbdbd"}]},{"featureType": "poi","elementType": "geometry","stylers": [{"color": "#eeeeee"}]},{"featureType": "poi","elementType": "labels.text","stylers": [{"visibility": "off"}]},{"featureType": "poi","elementType": "labels.text.fill","stylers": [{"color": "#757575"}]},{"featureType": "poi.park","elementType": "geometry","stylers": [{"color": "#e5e5e5"}]},{"featureType": "poi.park","elementType": "labels.text.fill","stylers": [{"color": "#9e9e9e"}]},{"featureType": "road","elementType": "geometry","stylers": [{"color": "#ffffff"}]},{"featureType": "road.arterial","elementType": "labels.text.fill","stylers": [{"color": "#757575"}]},{"featureType": "road.highway","elementType": "geometry","stylers": [{"color": "#dadada"}]},{"featureType": "road.highway","elementType": "labels.text.fill","stylers": [{"color": "#616161"}]},{"featureType": "road.local","elementType": "labels","stylers": [{"visibility": "off"}]},{"featureType": "road.local","elementType": "labels.text.fill","stylers": [{"color": "#9e9e9e"}]},{"featureType": "transit.line","elementType": "geometry","stylers": [{"color": "#e5e5e5"}]},{"featureType": "transit.station","elementType": "geometry","stylers": [{"color": "#eeeeee"}]},{"featureType": "water","elementType": "geometry","stylers": [{"color": "#c9c9c9"}]},{"featureType": "water","elementType": "labels.text.fill","stylers": [{"color": "#9e9e9e"}]}];';
					break;
				case 'dark':
					$str .= 'var styles =[{"elementType": "geometry","stylers": [{"color": "#212121"}]},{"elementType": "labels.icon","stylers": [{"visibility": "off"}]},{"elementType": "labels.text.fill","stylers": [{"color": "#757575"}]},{"elementType": "labels.text.stroke","stylers": [{"color": "#212121"}]},{"featureType": "administrative","elementType": "geometry","stylers": [{"color": "#757575"}]},{"featureType": "administrative.country","elementType": "labels.text.fill","stylers": [{"color": "#9e9e9e"}]},{"featureType": "administrative.land_parcel","stylers": [{"visibility": "off"}]},{"featureType": "administrative.land_parcel","elementType": "labels","stylers": [{"visibility": "off"}]},{"featureType": "administrative.locality","elementType": "labels.text.fill","stylers": [{"color": "#bdbdbd"}]},{"featureType": "poi","elementType": "labels.text","stylers": [{"visibility": "off"}]},{"featureType": "poi","elementType": "labels.text.fill","stylers": [{"color": "#757575"}]},{"featureType": "poi.park","elementType": "geometry","stylers": [{"color": "#181818"}]},{"featureType": "poi.park","elementType": "labels.text.fill","stylers": [{"color": "#616161"}]},{"featureType": "poi.park","elementType": "labels.text.stroke","stylers": [{"color": "#1b1b1b"}]},{"featureType": "road","elementType": "geometry.fill","stylers": [{"color": "#2c2c2c"}]},{"featureType": "road","elementType": "labels.text.fill","stylers": [{"color": "#8a8a8a"}]},{"featureType": "road.arterial","elementType": "geometry","stylers": [{"color": "#373737"}]},{"featureType": "road.highway","elementType": "geometry","stylers": [{"color": "#3c3c3c"}]},{"featureType": "road.highway.controlled_access","elementType": "geometry","stylers": [{"color": "#4e4e4e"}]},{"featureType": "road.local","elementType": "labels","stylers": [{"visibility": "off"}]},{"featureType": "road.local","elementType": "labels.text.fill","stylers": [{"color": "#616161"}]},{"featureType": "transit","elementType": "labels.text.fill","stylers": [{"color": "#757575"}]},{"featureType": "water","elementType": "geometry","stylers": [{"color": "#000000"}]},{"featureType": "water","elementType": "labels.text.fill","stylers": [{"color": "#3d3d3d"}]}];';
					break;
				case 'retro':
					$str .= 'var styles =[{"elementType": "geometry","stylers": [{"color": "#ebe3cd"}]},{"elementType": "labels.text.fill","stylers": [{"color": "#523735"}]},{"elementType": "labels.text.stroke","stylers": [{"color": "#f5f1e6"}]},{"featureType": "administrative","elementType": "geometry.stroke","stylers": [{"color": "#c9b2a6"}]},{"featureType": "administrative.land_parcel","elementType": "geometry.stroke","stylers": [{"color": "#dcd2be"}]},{"featureType": "administrative.land_parcel","elementType": "labels","stylers": [{"visibility": "off"}]},{"featureType": "administrative.land_parcel","elementType": "labels.text.fill","stylers": [{"color": "#ae9e90"}]},{"featureType": "landscape.natural","elementType": "geometry","stylers": [{"color": "#dfd2ae"}]},{"featureType": "poi","elementType": "geometry","stylers": [{"color": "#dfd2ae"}]},{"featureType": "poi","elementType": "labels.text","stylers": [{"visibility": "off"}]},{"featureType": "poi","elementType": "labels.text.fill","stylers": [{"color": "#93817c"}]},{"featureType": "poi.park","elementType": "geometry.fill","stylers": [{"color": "#a5b076"}]},{"featureType": "poi.park","elementType": "labels.text.fill","stylers": [{"color": "#447530"}]},{"featureType": "road","elementType": "geometry","stylers": [{"color": "#f5f1e6"}]},{"featureType": "road.arterial","elementType": "geometry","stylers": [{"color": "#fdfcf8"}]},{"featureType": "road.highway","elementType": "geometry","stylers": [{"color": "#f8c967"}]},{"featureType": "road.highway","elementType": "geometry.stroke","stylers": [{"color": "#e9bc62"}]},{"featureType": "road.highway.controlled_access","elementType": "geometry","stylers": [{"color": "#e98d58"}]},{"featureType": "road.highway.controlled_access","elementType": "geometry.stroke","stylers": [{"color": "#db8555"}]},{"featureType": "road.local","elementType": "labels","stylers": [{"visibility": "off"}]},{"featureType": "road.local","elementType": "labels.text.fill","stylers": [{"color": "#806b63"}]},{"featureType": "transit.line","elementType": "geometry","stylers": [{"color": "#dfd2ae"}]},{"featureType": "transit.line","elementType": "labels.text.fill","stylers": [{"color": "#8f7d77"}]},{"featureType": "transit.line","elementType": "labels.text.stroke","stylers": [{"color": "#ebe3cd"}]},{"featureType": "transit.station","elementType": "geometry","stylers": [{"color": "#dfd2ae"}]},{"featureType": "water","elementType": "geometry.fill","stylers": [{"color": "#b9d3c2"}]},{"featureType": "water","elementType": "labels.text.fill","stylers": [{"color": "#92998d"}]}];';
					break;
				case 'custom':
					if ($atts['custom_color']!=''){
						$str .= 'var styles = [{"featureType": "all",stylers: [{ hue: "'.$atts['custom_color'].'" },{ saturation: -20}]}];';		
					}
					break;
			}
			
			$str .= 'var styledMap = new google.maps.StyledMapType(styles,{name: "Styled Map"});';
			$str .=  $atts['id'] . '.mapTypes.set("map_style", styledMap);  ';
			$str .=  $atts['id'] . '.setMapTypeId("map_style");';
		}


		if ($atts['marker'] !='off'){
			
			$marker_icon ='';
			if($atts['marker']==='custom'){
				$img = Codevz_Plus::get_image( $atts['markerimage'], 0, 1 );
				$str .= 'var image = "'. $img .'";';
				$marker_icon .= 'icon: image,';
			}

			$str .= 'var marker = new google.maps.Marker({map: ' . $atts['id'] . ',';
			$str .= $marker_icon;
			if ($isfront){
			$str .= 'draggable:true,';	
			}
			$str .= 'position: ' . $atts['id'] . '.getCenter()});';

			if($isfront){
				$str .='google.maps.event.addListener(marker, "dragstart", function(marker){
					        var elm = jQuery("#'.$atts['id'].'").parent();
							jQuery("> .vc_controls .vc-c-icon-mode_edit", elm ).trigger("click");
					     });';

				$str .='google.maps.event.addListener(marker, "dragend", function(marker){
					        var latLng = marker.latLng; 
					        currentLatitude = latLng.lat();
					        currentLongitude = latLng.lng();
							jQuery( ".lat", parent.document.body ).val( currentLatitude);
							jQuery( ".long", parent.document.body ).val(currentLongitude);

					     });';
			}

		}

		if($atts['infowindow'] != '') {
			$thiscontent = htmlspecialchars_decode($atts['infowindow']);
			$str .= '
			var contentString = \'<div class="infowindow" style="white-space:nowrap">' . $thiscontent . '</div>\';
			var infowindow = new google.maps.InfoWindow({
				content: contentString
			});
						
			google.maps.event.addListener(marker, \'click\', function() {
			  infowindow.open(' . $atts['id'] . ',marker);
			});
			';

			if ($atts['infowindowdefault'] == 'yes')
			{
				$str .= 'infowindow.open(' . $atts['id'] . ',marker);';
			}
		}

		$str .= '}</script>';

		// Output
		$out = $str;
		$out .= $isfront ? '<script type="text/javascript">if ( typeof google != "undefined"){mapfucntion_' . $atts['id'] . '();}</script>' : '';

		return Codevz_Plus::_out( $atts, $out, 'google_map', $this->name );
	}
}