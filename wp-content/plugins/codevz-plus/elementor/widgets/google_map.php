<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Core\Kits\Documents\Tabs\Global_Colors;

class Xtra_Elementor_Widget_google_map extends Widget_Base {
	
	protected $id = 'cz_google_map';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Google Map', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-google-map';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Google', 'codevz' ),
			esc_html__( 'Map', 'codevz' ),
			esc_html__( 'Location', 'codevz' ),
			esc_html__( 'Address', 'codevz' ),

		];

	}

	public function get_style_depends() {
		return [ $this->id, 'cz_parallax' ];
	}

	public function get_script_depends() {
		return [ $this->id, 'cz_parallax' ];
	}

	public function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'apikey',
			[
				'label' => esc_html__( 'Google API Key', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'description' => esc_html__("Get your API Key from","codevz") . ' <a href="https://goo.gl/wVcKPP" target="_blank">' . esc_html__("Here","codevz") . '</a>',
			]
		);

		$this->add_control(
			'lat',
			[
				'label' 		=> esc_html__( 'Lat', 'codevz' ),
				'type' 			=> Controls_Manager::TEXT,
				'description' 	=> esc_html__("Find your location Lat and Long from","codevz") . ' <a href="http://www.latlong.net" target="_blank">' . esc_html__("Here","codevz") . '</a>',
				'default' 		=> '40.712784',
				'placeholder' 	=> '40.712784',
			]
		);
		
		$this->add_control(
			'long',
			[
				'label' 		=> esc_html__( 'Long', 'codevz' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> '-74.005941',
				'placeholder' 	=> '-74.005941',
			]
		);

		$this->add_responsive_control(
			'sk_map',
			[
				'label' 	=> esc_html__( 'Map size', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'width', 'height', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.gmap' ),
			]
		);

		$this->add_control(
			'zoom',
			[
				'label' => esc_html__( 'Zoom', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 19,
				'step' => 1,
				'default' => 14,
			]
		);

		$this->add_control(
			'offsetx',
			[
				'label' => esc_html__( 'Marker Offset X', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => -400,
				'max' => 400,
				'step' => 10,
				'default' => 0,
			]
		);

		$this->add_control(
			'offsety',
			[
				'label' => esc_html__( 'Marker Offset Y', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'min' => -400,
				'max' => 400,
				'step' => 10,
				'default' => 0,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_settings',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
			]
		);

		$this->add_control(
			'color',
			[
				'label' => esc_html__( 'Color scheme', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'standard',
				'options' => [
					'standard'  => esc_html__( 'Standard', 'codevz' ),
					'light' => esc_html__( 'Light (Silver)', 'codevz' ),
					'dark' => esc_html__( 'Dark', 'codevz' ),
					'retro' => esc_html__( 'Retro', 'codevz' ),
					'custom' => esc_html__( 'Custom Color', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'custom_color',
			[
				'label' => __( 'Custom color', 'codevz' ),
				'type' => Controls_Manager::COLOR,
				'global' => [
					'default' => Global_Colors::COLOR_ACCENT,
				],
				'condition' => [
					'color' => 'custom',
				]
			]
		);

		$this->add_control(
			'grayscale',
			[
				'label' => esc_html__( 'Grayscale Effect?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_marker',
			[
				'label' => esc_html__( 'Marker', 'codevz' ),
			]
		);

		$this->add_control(
			'marker',
			[
				'label' => esc_html__( 'Marker', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'off',
				'options' => [
					'off'  => esc_html__( 'Off', 'codevz' ),
					'default' => esc_html__( 'Default Marker', 'codevz' ),
					'custom' => esc_html__( 'Custom Image', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Marker image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'marker' => 'custom',
				]
			]
		);

		$this->add_control(
			'infowindow',
			[
				'label' => esc_html__( 'Info text', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'marker' => ['custom', 'default'],
				]
			]
		);

		$this->add_control(
			'infowindowdefault',
			[
				'label' => esc_html__( 'Show info by default?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'marker' => ['default','custom'],
				]
			]
		);

		$this->add_control(
			'scrollwheel',
			[
				'label' => esc_html__( 'Enable mouse wheel?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'codevz' ),
				'label_off' => esc_html__( 'No', 'codevz' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'marker' => ['default','custom'],
				]
			]
		);

		$this->add_control(
			'hidecontrols',
			[
				'label' => esc_html__( 'Hide map controls?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'codevz' ),
				'label_off' => esc_html__( 'No', 'codevz' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'marker' => ['default','custom'],
				]
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		$settings['id'] = 'cz' . rand( 1111,9999 );

		$isfront = false;

		// Data
		$str_data_apikey = $str_apikey = '';

		if ( $settings['apikey'] ) {
			$str_apikey = '?key=' . $settings['apikey'] ;
			$str_data_apikey = ' data-api-key="' . $settings['apikey'] . '"' ;
		} else {
			return '<div style="border:1px solid #ccc;border-radius:5px;color:#bb0000;margin:20px 0;padding:30px 0;text-align: center;">
			<h3>Google API Key Error!</h3><h5 style="color:#555">Get your API Key from <a style="color:#bb0000" href="https://goo.gl/wVcKPP">here</a></h5></div>';
		}

		$classes = [];
		$classes[] = 'gmap';
		$classes[] = ( $settings['grayscale'] === 'yes' ) ? 'fx_grayscale fx_remove_grayscale_hover' : '';

		$str = '<div id="'. $settings['id'] .'"'. Codevz_Plus::classes( [], $classes ) . $str_data_apikey .  '> </div>';

		$str .= '<script type="text/javascript">
			function mapfucntion_' . $settings['id'] . '() {

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

					latlng = new google.maps.LatLng(' . $settings['lat'] . ', ' . $settings['long'] . '),

					myOptions = {
						zoom: ' . ( $settings['zoom'] ? $settings['zoom'] : 14 ) . ',
						scrollwheel: ' . ( ( $settings['scrollwheel'] === 'yes' ) ? 'true' : 'false' ) . ',
						disableDefaultUI: ' . ( ( $settings['hidecontrols'] === 'true' ) ? 'true' : 'false' ) . ',
						center: latlng
					},

					' . $settings['id'] . ' = new google.maps.Map(document.getElementById("' . $settings['id'] . '"),
					myOptions),

					setCenterOffset = function() {

						if ( windx.width() < 768 ) {
							' . $settings['id'] . '.setCenterWithOffset( latlng, 0, 0 );
						} else {
							' . $settings['id'] . '.setCenterWithOffset( latlng, ' . $settings['offsetx'] . ', ' . $settings['offsety'] . ' );
						}

					};

				setCenterOffset();

				windx.on( "resize", function() {
					setCenterOffset();
				});
			';

		if ($settings['color']!='standard'){
			switch ($settings['color']) {
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
					if ($settings['custom_color']!=''){
						$str .= 'var styles = [{"featureType": "all",stylers: [{ hue: "'.$settings['custom_color'].'" },{ saturation: -20}]}];';		
					}
					break;
			}
			
			$str .= 'var styledMap = new google.maps.StyledMapType(styles,{name: "Styled Map"});';
			$str .=  $settings['id'] . '.mapTypes.set("map_style", styledMap);  ';
			$str .=  $settings['id'] . '.setMapTypeId("map_style");';
		}


		if ( $settings['marker'] !='off' ) {

			$marker_icon ='';
			if ( $settings['marker']==='custom' && ! empty( $settings[ 'image' ][ 'id' ] ) ) {
				$settings[ 'image_size' ] = 'full';
				$str .= 'var image = "'. Group_Control_Image_Size::get_attachment_image_src( $settings[ 'image' ][ 'id' ], 'image', $settings ) .'";';
				$marker_icon .= 'icon: image,';
			}

			$str .= 'var marker = new google.maps.Marker({map: ' . $settings['id'] . ',';
			$str .= $marker_icon;
			if ($isfront){
			$str .= 'draggable:true,';	
			}
			$str .= 'position: ' . $settings['id'] . '.getCenter()});';

			if($isfront){
				$str .='google.maps.event.addListener(marker, "dragstart", function(marker){
							var elm = jQuery("#'.$settings['id'].'").parent();
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

		if($settings['infowindow'] != '') {
			$thiscontent = htmlspecialchars_decode($settings['infowindow']);
			$str .= '
			var contentString = \'<div class="infowindow" style="white-space:nowrap">' . $thiscontent . '</div>\';
			var infowindow = new google.maps.InfoWindow({
				content: contentString
			});
						
			google.maps.event.addListener(marker, \'click\', function() {
			  infowindow.open(' . $settings['id'] . ',marker);
			});
			';

			if ($settings['infowindowdefault'] == 'yes') {
				$str .= 'infowindow.open(' . $settings['id'] . ',marker);';
			}
		}

		$str .= '}</script>';

		Xtra_Elementor::parallax( $settings );

		echo do_shortcode( $str );
  
		Xtra_Elementor::parallax( $settings, true );


	}

}