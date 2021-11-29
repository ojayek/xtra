<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Separator
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_separator {

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
			'deprecated' 	=> '4.6',
			'name'			=> esc_html__( 'Separator', 'codevz' ) . ' ' . esc_html__( '[Deprecated]', 'codevz' ),
			'description'	=> esc_html__( 'Row separator space', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__('Style', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'style',
					'value'		=> array(
						'Style 1 (Triangle Down)' 	=> 'cz_sep_1',
						'Style 2 (Triangle Up)' 	=> 'cz_sep_2',
						'Style 3 (Slanted Right)' 	=> 'cz_sep_3',
						'Style 4 (Slanted Left)' 	=> 'cz_sep_4',
						'Style 5 (Halfcircle Top)' 	=> 'cz_sep_5',
						'Style 6 (Halfcircle Bottom)' 	=> 'cz_sep_6',
						'Style 7 (Big Triangle Down)' 	=> 'cz_sep_7',
						'Style 8 (Big Triangle Up)' 	=> 'cz_sep_8',
						'Style 9 (Big Curve Up)' 	=> 'cz_sep_9',
						'Style 10 (Big Curve Down)' 	=> 'cz_sep_10',
						'Style 11 (Roundedsplit Down)' 	=> 'cz_sep_11',
						'Style 12 (Roundedsplit Up)' 	=> 'cz_sep_12',
						'Style 13 (ZigZag Up)' 	=> 'cz_sep_13',
						'Style 14 (ZigZag Down)' 	=> 'cz_sep_14',
						'Style 15 (Roundedges Top)' 	=> 'cz_sep_15',
						'Style 16 (Roundedges Top)' 	=> 'cz_sep_16',
						'Style 17 (Spikey Top)' 	=> 'cz_sep_17',
						'Style 18 (Spikey Down)' 	=> 'cz_sep_18',
						'Style 19 (Saw left)' 	=> 'cz_sep_19',
						'Style 20 (Saw Right)' 	=> 'cz_sep_20',
						'Style 21 (Alternating Squares)' 	=> 'cz_sep_21',
						'Style 22 (Castle)' 	=> 'cz_sep_22',
						'Style 23 (Clouds Up)' 	=> 'cz_sep_23',
						'Style 24 (Clouds Down)' 	=> 'cz_sep_24',
						'Style 25' 	=> 'cz_sep_25',
						'Style 26' 	=> 'cz_sep_26',
						'Style 27' 	=> 'cz_sep_27',
						'Style 28' 	=> 'cz_sep_28',
						'Style 29' 	=> 'cz_sep_29',
						'Style 30' 	=> 'cz_sep_30',
						'Style 31' 	=> 'cz_sep_31',
						'Style 32' 	=> 'cz_sep_32',
						'Style 33' 	=> 'cz_sep_33',
						'Style 34' 	=> 'cz_sep_34',
						'Style 35' 	=> 'cz_sep_35',
						'Style 36' 	=> 'cz_sep_36',
						'Style 37' 	=> 'cz_sep_37',
						'Style 38' 	=> 'cz_sep_38',
						'Style 39' 	=> 'cz_sep_39',
						'Style 40' 	=> 'cz_sep_40',
						'Style 41' 	=> 'cz_sep_41',
						'Style 42' 	=> 'cz_sep_42',
						'Style 43' 	=> 'cz_sep_43',
						'Style 44' 	=> 'cz_sep_44',
						'Style 45' 	=> 'cz_sep_45',
						'Style 46' 	=> 'cz_sep_46',
						'Style 47' 	=> 'cz_sep_47',
						'Style 48' 	=> 'cz_sep_48',
						'Style 49' 	=> 'cz_sep_49',
						'Style 50' 	=> 'cz_sep_50',
					),
					'std'	=> 'cz_sep_25'
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__('Rotate', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'rotate',
					'value'		=> array(
						'Select' 			=> '',
						'Rotate Full' 		=> 'cz_sep_rotate',
						'Rotate Horizontal' => 'cz_sep_rotatey',
						'Rotate Vertical' 	=> 'cz_sep_rotatex'
					)
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Height", 'codevz'),
					"param_name"  	=> "sep_height",
					"description"   => "e.g. 100px",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'style',
						'value'			=> array( 'cz_sep_7','cz_sep_8','cz_sep_9','cz_sep_10','cz_sep_13','cz_sep_14' ,'cz_sep_19' ,'cz_sep_20','cz_sep_21','cz_sep_22','cz_sep_23','cz_sep_24','cz_sep_32','cz_sep_33','cz_sep_34','cz_sep_35', 'cz_sep_40', 'cz_sep_41' )
					)
				),
				array(
					"type"        	=> "cz_slider",
					"heading"     	=> esc_html__("Width", 'codevz'),
					"param_name"  	=> "sep_width",
					"description"   => "e.g. 100px",
					'edit_field_class' => 'vc_col-xs-99',
					'dependency'	=> array(
						'element'		=> 'style',
						'value'			=> array('cz_sep_21','cz_sep_22')
					)
				),
				array(
					"type"        	=> "colorpicker",
					"heading"     	=> esc_html__("Color", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "top_color",
					"value"  		=> "#a7a7a7",
				),
				array(
					"type"        	=> "colorpicker",
					"heading"     	=> esc_html__("Color 2", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "bottom_color",
					'dependency'	=> array(
						'element'		=> 'style',
						'value'			=> array( 'cz_sep_1','cz_sep_2','cz_sep_3','cz_sep_4','cz_sep_5','cz_sep_6','cz_sep_7','cz_sep_8','cz_sep_9','cz_sep_10','cz_sep_15','cz_sep_16','cz_sep_17','cz_sep_18','cz_sep_19','cz_sep_20','cz_sep_21','cz_sep_22','cz_sep_38','cz_sep_40')
					)
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Advanced Position', 'codevz' ),
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__('Layer Position', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'css_position',
					'value'		=> array(
						__( 'Select', 'codevz' ) => '',
						'relative' => 'relative',
						'absolute' => 'absolute;width:100%',
						'fixed' => 'fixed',
						'static' => 'static',
						'sticky' => 'sticky',
						'inherit' => 'inherit',
						'initial' => 'initial',
						'unset' => 'unset'

					),
				),
				array(
					'type' 			=> 'dropdown',
					'heading' 		=> esc_html__('Layer Priority', 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' 	=> 'css_z-index',
					'value'		=> array(
						'-2' => '-2',
						'-1' => '-1',
						'0' => '0',
						'1'	=> '1',
						'2' => '2',
						'3'	=> '3',
						'4' => '4',
						'5' => '5',
						'6' => '6',
						'7' => '7',
						'8' => '8',
						'9' => '9',
						'10' => '10',
						'99' => '99',
						'999' => '999',
					),
					'std'			=> '0',
				),
				array(
					'type' => 'cz_slider',
					'heading' => esc_html__( 'Top offset', 'codevz' ),
					'description' => 'e.g. 20px or 20%',
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' => 'css_top',
				),
				array(
					'type' => 'cz_slider',
					'heading' => esc_html__( 'Bottom offset', 'codevz' ),
					'description' => 'e.g. 20px or 20%',
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' => 'css_bottom',
				),
				array(
					'type' => 'cz_slider',
					'heading' => esc_html__( 'Left offset', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'param_name' => 'css_left',
				),

				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'More', 'codevz' ),
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Extra class", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "class"
				),
			)

		);

		return $wpb ? vc_map( $settings ) : $settings;
	}

	public function out( $atts, $content = '' ) {
		$atts = Codevz_Plus::shortcode_atts( $this, $atts );

		// ID
		if ( ! $atts['id'] ) {
			$atts['id'] = Codevz_Plus::uniqid();
			$public = 1;
		}
		$css_id = '#' . $atts['id'];

		$style = $atts['style'];
		$top_color = $atts['top_color'] ? $atts['top_color'] : '';
		$bottom_color = $atts['bottom_color'] ? ' style="background: ' . $atts['bottom_color'] . '"' : '';
		$svg_attrs = 'preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"';

		$out = '<div class="cz_sep ' . $style . '_inner ' . $atts['rotate'] . '">';

		if ( $style==='cz_sep_25' ) {
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 1920 213"><path fill="' . $top_color . '" d="M0,193S392.385-118.6,838,49c462.98,171.608,1083,143,1082,144v19H0Z" /></svg>';
		} else if ( $style==='cz_sep_26' ) {
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 1920 180"><g style="isolation: isolate"><g><path fill="' . $top_color . '" style="fill-rule: evenodd" d="M0,0V27S513.4-16.386,995,129c519.509,155.418,926-101,927-102V0Z"/></g></g></svg>';
		} else if ( $style==='cz_sep_27' ) {
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 1920 146"><path fill="' . $top_color . '" d="M1182,48C633.293-88.421,0,115,0,116v30H1920V116S1612.972,156.488,1183,48Z" /></svg>';
		} else if ( $style==='cz_sep_28' ) {
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 1920 150"><path fill="' . $top_color . '" d="M0,0V52s198.63,76.671,575,94C1156.292,175.308,1921,35,1921,36V0Z" /></svg>';
		} else if ( $style==='cz_sep_29' ) {
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 1920 176"><path fill="' . $top_color . '" d="M1249,2C708.25-18.224,0,147,0,146v30H1920V146S1663.461,17.267,1247,2Z" /></svg>';
		} else if ( $style==='cz_sep_30' ) {
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 1920 109"><path fill="' . $top_color . '" d="M0,0V48S265.751,172.965,856,65c512.379-93.672,1066-18,1066-17V0Z" /></svg>';
		} else if ( $style==='cz_sep_31' ) {
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 100 18"><path fill="' . $top_color . '" opacity="0.5" d="M0 30 V15 Q30 3 60 15 V30z"></path><path fill="' . $top_color . '" d="M0 30 V12 Q30 17 55 12 T100 11 V30z"></path></svg>';
		} else if ( $style==='cz_sep_32' ) {
			$height = $atts['sep_height'] ? $atts['sep_height'] : '150px';
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 200 100" style="height:' . $height . ';width:100%"><circle fill="' . $top_color . '" cx="0" cy="100" r="100" /><circle fill="' . $top_color . '" cx="200" cy="100" r="100" /></svg>';
		} else if ( $style==='cz_sep_33' ) {
			$height = $atts['sep_height'] ? $atts['sep_height'] : '180px';
			$out .= '<svg ' . $svg_attrs . ' style="height:' . $height . ';width:100%" viewBox="0 0 100 100"><polygon fill="' . $top_color . '" points="0,0 30,100 65,21 90,100 100,75 100,100 0,100"/><polygon fill="' . $top_color . '" points="0,0 15,100 33,21 45,100 50,75 55,100 72,20 85,100 95,50 100,80 100,100 0,100" /></svg>';
		} else if ( $style==='cz_sep_34' ) {
			$height = $atts['sep_height'] ? $atts['sep_height'] : '200px';
			$out .= '<svg ' . $svg_attrs . ' style="height:' . $height . ';width:100%" viewBox="0 0 100 100"><polygon fill="' . $top_color . '" points="0,100 100,0 100,100"/></svg>';
		} else if ( $style==='cz_sep_35' ) {
			$height = $atts['sep_height'] ? $atts['sep_height'] : '200px';
			$out .= '<svg ' . $svg_attrs . ' style="height:' . $height . ';width:100%" viewBox="0 0 1280 140"><g fill="' . $top_color . '"><path d="M0 47.44L170 0l626.48 94.89L1110 87.11l170-39.67V140H0V47.44z" fill-opacity=".5"></path><path d="M0 90.72l140-28.28 315.52 24.14L796.48 65.8 1140 104.89l140-14.17V140H0V90.72z"></path></g></svg>';
		} else if ( $style==='cz_sep_36' ) {
			$out .= '<svg ' . $svg_attrs . ' style="height:220px;width:100%">
      <defs>
        <linearGradient id="' . $atts['id'] . '_x" x1="50%" x2="50%" y1="-10.959%" y2="100%">
          <stop stop-color="' . $top_color . '" stop-opacity=".10" offset="0%"></stop>
          <stop stop-color="' . $top_color . '" stop-opacity=".80" offset="100%"></stop>
        </linearGradient>
        <linearGradient id="' . $atts['id'] . '_y" x1="50%" x2="50%" y1="-10.959%" y2="100%">
          <stop stop-color="' . $top_color . '" stop-opacity=".10" offset="0%"></stop>
          <stop stop-color="' . $top_color . '" stop-opacity=".50" offset="100%"></stop>
        </linearGradient>
        <linearGradient id="' . $atts['id'] . '_z" x1="50%" x2="50%" y1="-10.959%" y2="100%">
          <stop stop-color="' . $top_color . '" offset="0%"></stop>
          <stop stop-color="' . $top_color . '" offset="65%"></stop>
        </linearGradient>
      </defs>
      <path transform="translate(700, 0)" fill="url(#' . $atts['id'] . '_x)" fill-rule="evenodd" d="M.005 121C311 121 409.898-.25 811 0c400 0 500 121 789 121v77H0s.005-48 .005-77z"></path>
      <path transform="translate(-900, 0)" fill="url(#'  .$atts['id'] . '_x)" fill-rule="evenodd" d="M.005 121C311 121 409.898-.25 811 0c400 0 500 121 789 121v77H0s.005-48 .005-77z"></path>
      <path transform="translate(0, 0)" fill="url(#' . $atts['id'] . '_y)" fill-rule="evenodd" d="M.005 121C311 121 409.898-.25 811 0c400 0 500 121 789 121v77H0s.005-48 .005-77z"></path>
      <path transform="translate(1600, 0)" fill="url(#' . $atts['id'] . '_y)" fill-rule="evenodd" d="M.005 121C311 121 409.898-.25 811 0c400 0 500 121 789 121v77H0s.005-48 .005-77z"></path>
      <path transform="translate(1000, 75)" fill="url(#' . $atts['id'] . '_z)" fill-rule="evenodd" d="M.005 121C311 121 409.898-.25 811 0c400 0 500 121 789 121v77H0s.005-48 .005-77z"></path>
      <path transform="translate(-600, 75)" fill="url(#' . $atts['id'] . '_z)" fill-rule="evenodd" d="M.005 121C311 121 409.898-.25 811 0c400 0 500 121 789 121v77H0s.005-48 .005-77z"></path>
	</svg>';
		} else if ( $style==='cz_sep_37' ) {
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 1600 130">
<path style="opacity: .2;fill: ' . $top_color . ';" d="M-40,71.627C20.307,71.627,20.058,32,80,32s60.003,40,120,40s59.948-40,120-40s60.313,40,120,40s60.258-40,120-40s60.202,40,120,40s60.147-40,120-40s60.513,40,120,40s60.036-40,120-40c59.964,0,60.402,40,120,40s59.925-40,120-40s60.291,40,120,40s60.235-40,120-40s60.18,40,120,40s59.82,0,59.82,0l0.18,26H-60V72L-40,71.627z"></path>
<path style="opacity: .6;fill: ' . $top_color . ';" d="M-40,83.627C20.307,83.627,20.058,44,80,44s60.003,40,120,40s59.948-40,120-40s60.313,40,120,40s60.258-40,120-40s60.202,40,120,40s60.147-40,120-40s60.513,40,120,40s60.036-40,120-40c59.964,0,60.402,40,120,40s59.925-40,120-40s60.291,40,120,40s60.235-40,120-40s60.18,40,120,40s59.82,0,59.82,0l0.18,14H-60V84L-40,83.627z"></path>
<path style="fill: ' . $top_color . ';" d="M-40,95.627C20.307,95.627,20.058,56,80,56s60.003,40,120,40s59.948-40,120-40s60.313,40,120,40s60.258-40,120-40s60.202,40,120,40s60.147-40,120-40s60.513,40,120,40s60.036-40,120-40c59.964,0,60.402,40,120,40s59.925-40,120-40s60.291,40,120,40s60.235-40,120-40s60.18,40,120,40s59.82,0,59.82,0l0.18,138H-60V96L-40,95.627z"></path></svg>';
		} else if ( $style==='cz_sep_38' ) {
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 1600 200">
<path style="opacity: .2;fill: ' . $top_color . ';" d="M1615.6,58.4c-8.6,20.7-19.2,31.4-26.8,36.8c-4.4-7.6-8.7-18.4-10.9-33.4
c-9.2,22.6-20.9,33.4-28.6,38.3c-2.4-7.7-4.4-16.4-6-26.2c-10.4,25.9-22.8,42.1-33.4,52.2c-2.8-6.5-5.2-14.5-6.8-24.4
c-5.2,13.2-11.2,22.4-16.8,28.8c-3.9-5.1-8.1-12.1-11.9-21.5c6.8,0.7,13.6,1.4,20.4,2.1c0,0-20-9.9-26.6-49.3
c-4.6,12-10,20.7-15.2,27c-8.1-12.1-16.5-30.2-21.1-56.8c-15.1,39.7-35.4,57-47.2,64.2c-3.8-7.1-7.4-16.7-9.7-29.2
c-8.5,22.9-19.8,34-27.3,39.2c-8.5-11.9-17.5-30.1-22.7-57.5c-8.3,22.9-18.3,38.4-27.6,48.9c-5.4-7.4-11.2-18.9-14.5-36.1
c-7.8,22-18.2,33.2-25.7,38.8c-2.7-4.1-5.5-9-8-14.9c6.8,0.5,13.6,1,20.4,1.4c0,0-20.4-9.2-28.3-48.4
c-8.5,24.4-20.4,35.7-27.8,40.6c-9-11.7-18.8-30-24.7-58c-17,50.2-43.2,65.9-51.6,69.7c-7.3-11.5-14.5-27.5-19.4-49.5
c-9.3,28.5-21.7,46-32.1,56.5c-4.5-7.1-9-17-12.1-30.5c-5.7,18-13.4,29-19.9,35.6c-8.8-11.5-18.2-29.1-24.3-55.3
c-5.6,17.9-12.4,31.5-19.2,41.8c-6.8-5.6-17.6-18-23.7-43.2c-4.8,15.8-11.2,26.3-17.1,33.2c-9.3-5.7-32.3-24.1-44.6-72.7
c-8.8,29.7-21.1,47.7-31.5,58.5C889,58.6,885,49.8,882,38.2c-11,38.5-32,46-32,46c6.8,0.1,13.6,0.1,20.4,0.2
c-8.5,15.8-17.6,23.9-23.2,27.7c-7.1-7.5-15-18-22.5-32.6c10.8,0,21.7,0.1,32.5,0.2c0,0-33.2-12.5-49.9-74
c-7.2,26.2-17.4,43.4-26.7,54.6c-4.1-6.6-8.2-15.2-11.3-26.6c-2.2,8.3-4.9,15.2-7.8,20.9C752,43.5,741.6,26.3,734.1,0
c-11.6,44.1-31.9,63.1-42.4,70.4c-6.3-6.8-13.5-17.9-18.7-35.6c-6,23.7-16,36-23,41.9c-7.9-10.8-16-25.9-22.3-46.9
c-5.8,23.6-14.1,40.1-22.2,51.6c-3.5-5.9-6.9-13.4-9.7-22.6c-8.3,34.8-25.7,45.2-29.2,47c-0.2,0-0.4,0-0.6,0
c-6.8-10.2-13.5-23.7-19.1-41.3c-3.4,14.8-7.9,26.9-12.7,36.6c-7.3-5.2-18.6-16.8-26.4-41C505,72.7,501,82,496.8,89
c-7.9-10.6-16-25.2-22.6-45.3c-4.7,21.5-11.7,37.2-18.8,48.6c-6.9-6.4-15.3-17.5-21.6-36.4c-5.7,26.8-17.1,39.4-23.7,44.7
c-10.5-10.4-22.8-27.6-32.4-55.4c-3.9,19.2-9.7,33.9-15.8,45c-6.6-6.5-14.4-17.2-20.5-34.5c-2.2,11.3-5.5,20-9,26.8
c-11.9-8.4-29.9-26.2-43.1-63.1c-12.1,62.6-44.2,77.5-44.3,77.6c10.8-0.9,21.7-1.8,32.5-2.7c-9,21.2-19.4,34.5-27.5,42.5
c-5.8-6.5-12.1-16.2-17.3-30.4c-0.8,4.3-1.7,8.3-2.8,12c-7.2-4-20.7-14.7-30.6-41.1c-6.9,39.4-27,49-27,49
c6.8-0.7,13.5-1.3,20.3-1.9c-0.9,2.1-1.8,4.1-2.7,6c-11.9,1.1-23.8,2.3-35.7,3.5c-7-3.7-21.1-14.1-31.6-41
c-4.5,27.3-15.4,40.4-21.7,45.9c-5.9-6.4-12.3-15.8-17.7-29.7c-1.5,9.8-3.9,17.7-6.6,24.2C66.7,123,55.2,107.3,45.5,83.1
c-2.2,14.5-5.6,26.5-9.5,36.4c-7.5-5.7-17.5-16.3-25.7-36.3C9.3,90.4,7.8,96.6,6,101.9c-10.5-10-22.5-25.7-32.8-50.4
c-0.4,2.5-0.7,4.9-1.2,7.2c7.3,57.9,10.2,77.5,17.5,135.4l1619.5-1.6c6.2-49.3,7.4-58.7,13.6-108
C1619.7,77.7,1617.1,69.1,1615.6,58.4z"></path>
<path style="opacity: .5;fill: ' . $top_color . ';" d="M85.8,155c5.1-0.6,10.1-1.1,15.2-1.7c0,0-13.6-3.6-27.3-24c5.9-0.7,11.7-1.3,17.6-1.9
c0,0-18.8-4.7-31.6-37c-5.3,34.4-22.6,43.1-22.6,43.1c5.9-0.7,11.7-1.4,17.6-2C46,154.4,33.5,161,33.5,161
c5.1-0.6,10.1-1.2,15.2-1.8c-11.7,23-25.4,30-25.5,30l94.1-10.6C117.3,178.6,102.4,174.9,85.8,155z"></path>
<path style="opacity: .5;fill: ' . $top_color . ';" d="M367.5,131.9c4.7-0.3,9.5-0.6,14.2-0.9c0,0-12.6-3.9-24.5-23.6c5.5-0.4,11-0.7,16.5-1.1
c0,0-17.3-5.1-28-35.9c-6.3,32-22.8,39.4-22.8,39.4c5.5-0.4,11-0.8,16.5-1.2c-9.1,21.1-21,26.7-21,26.7c4.7-0.4,9.5-0.7,14.2-1
c-11.9,21.1-25,27.1-25,27.1l88.3-6.1C396,155.3,382.2,151.1,367.5,131.9z"></path>
<path style="opacity: .5;fill: ' . $top_color . ';" d="M714.6,108.9c6.5-0.1,13-0.2,19.5-0.2c0,0-16.8-6.3-31.7-33.9c7.5-0.1,15.1-0.2,22.6-0.3
c0,0-23.3-8.2-35.7-50.9c-11,43.1-33.9,52.1-33.9,52.1c7.5-0.2,15.1-0.3,22.6-0.5c-13.9,28.1-30.5,34.9-30.5,34.9
c6.5-0.2,13-0.3,19.5-0.4c-17.7,27.9-35.9,35.1-35.9,35.1l120.6-2.1C751.7,142.8,733.3,136.1,714.6,108.9z"></path>
<path style="opacity: .5;fill: ' . $top_color . ';" d="M997.7,115.2c5.7,0.2,11.5,0.3,17.2,0.5c0,0-14.6-6.2-26.7-31.1c6.7,0.2,13.3,0.4,20,0.6
c0,0-20.2-8.2-29.5-46.3c-11.3,37.6-31.9,44.7-31.9,44.7c6.7,0.1,13.3,0.3,20,0.5c-13.4,24.3-28.3,29.6-28.3,29.7
c5.7,0.1,11.5,0.2,17.2,0.4c-16.7,23.9-33,29.6-33,29.6l106.5,2.8C1029.2,146.5,1013.1,139.9,997.7,115.2z"></path>
<path style="opacity: .5;fill: ' . $top_color . ';" d="M1224.6,124.7c5.9,0.4,11.9,0.8,17.8,1.2c0,0-15-6.9-26.6-33.2c6.9,0.4,13.8,0.9,20.7,1.3
c0,0-20.7-9.2-29-49.1c-13.1,38.6-34.7,45.2-34.7,45.2c6.9,0.4,13.8,0.8,20.7,1.2c-14.7,24.7-30.4,29.7-30.4,29.7
c5.9,0.3,11.9,0.7,17.8,1c-18.1,24.2-35.3,29.5-35.3,29.5l110.4,6.7C1256.1,158.3,1239.7,150.9,1224.6,124.7z"></path>
<path style="opacity: .5;fill: ' . $top_color . ';" d="M1357.2,144.3c4.1,0.3,8.2,0.7,12.3,1.1c0,0-10.2-5-17.9-23.3c4.8,0.4,9.5,0.8,14.3,1.2
c0,0-14.1-6.6-19.2-34.3c-9.6,26.5-24.6,30.7-24.6,30.7c4.8,0.4,9.5,0.8,14.3,1.1c-10.5,16.8-21.4,20.1-21.4,20.1
c4.1,0.3,8.2,0.6,12.3,1c-12.9,16.4-24.9,19.8-24.9,19.8l76.2,6.3C1378.5,168,1367.2,162.7,1357.2,144.3z"></path>
<path style="opacity: .5;fill: ' . $top_color . ';" d="M1593.7,159.5c5.8,0.7,11.6,1.4,17.4,2.1c0,0-14.3-7.6-24.2-34c6.8,0.8,13.5,1.6,20.3,2.5
c0,0-19.8-10.2-25.7-49.7c-15,37.1-36.6,42.4-36.6,42.4c6.8,0.8,13.5,1.5,20.3,2.3c-15.8,23.4-31.5,27.5-31.5,27.5
c5.8,0.7,11.6,1.3,17.4,2c-19.1,22.7-36.3,27-36.3,27l104,12.2c1.3,0.2,2.7,0.3,4,0.5C1622.7,194.2,1607,186,1593.7,159.5z"></path>
<path style="fill: ' . $atts['bottom_color'] . ';" d="M1475.4,177c0,0-16.4-8.1-30.8-35.3c6,0.6,12.1,1.2,18.1,1.8
c0,0-15-7.6-25.8-34.8c7,0.7,14,1.4,21,2.1c0,0-20.7-10.1-27.7-51c-14.7,38.8-36.9,44.7-37,44.7c7,0.6,14,1.3,21,2
c-15.9,24.6-32,29.2-32,29.2c6,0.5,12.1,1.1,18.1,1.7c-19.3,24-37,28.8-37,28.8c-10.7-0.9-21.3-1.9-32-2.7c0,0-18.1-8.4-34.4-37.7
c6.6,0.5,13.2,1,19.7,1.6c0,0-16.5-7.9-29-37.2c7.6,0.6,15.3,1.2,22.9,1.8c0,0-22.8-10.5-31.5-54.8c-15,42.6-39.1,49.6-39.1,49.7
c7.7,0.5,15.3,1.1,23,1.6c-16.7,27.2-34.1,32.6-34.1,32.6c6.6,0.4,13.2,0.9,19.7,1.3c-20.4,26.6-39.5,32.3-39.5,32.3
c-10.7-0.7-21.4-1.3-32.1-2c0,0-12.9-5.6-24.8-26c4.6,0.3,9.3,0.5,13.9,0.8c0,0-11.7-5.3-20.9-25.8c5.4,0.3,10.8,0.6,16.1,0.9
c0,0-16.2-7-22.9-38.1c-9.9,30.3-26.7,35.6-26.7,35.6c5.4,0.3,10.8,0.5,16.1,0.8c-11.3,19.4-23.5,23.4-23.5,23.4
c4.6,0.2,9.3,0.4,13.9,0.7c-14,19-27.4,23.3-27.4,23.3c-53.5-2.4-107-4.2-160.5-5.3c0,0-21.3-8.4-42.1-40.7
c7.6,0.1,15.1,0.2,22.7,0.4c0,0-19.4-7.9-35.9-40.5c8.8,0.1,17.6,0.2,26.4,0.4c0,0-26.9-10.4-40.1-60.5
c-14.1,49.9-41.2,59.7-41.2,59.7c8.8,0,17.6,0.1,26.4,0.1c-17.1,32.3-36.6,39.8-36.7,39.8c7.6,0,15.1,0,22.7,0.1
c-21.4,32-42.8,39.9-42.9,39.9c-46.2,0.1-92.3,0.6-138.5,1.7c0,0-20.7-7.1-41.9-37.2c7.2-0.2,14.5-0.4,21.7-0.6
c0,0-18.9-6.7-36-37.3c8.4-0.3,16.8-0.5,25.3-0.7c0,0-26.1-8.8-40.8-56.2c-11.5,48.3-36.9,58.8-36.9,58.8c8.4-0.3,16.8-0.7,25.3-1
c-15,31.6-33.4,39.6-33.4,39.6c7.2-0.3,14.5-0.6,21.7-0.9c-19.2,31.4-39.3,39.9-39.4,39.9c-12.7,0.6-25.4,1.2-38.1,1.8
c0,0-17.2-5.4-35.2-29.6c5.9-0.3,11.9-0.6,17.8-1c0,0-15.6-5.1-30.3-29.8c6.9-0.4,13.8-0.8,20.7-1.1c0,0-21.6-6.7-34.6-45.3
c-8.4,39.9-29,49-29,49c6.9-0.4,13.8-0.9,20.7-1.3c-11.7,26.3-26.6,33.2-26.6,33.2c5.9-0.4,11.9-0.8,17.8-1.1
c-15.1,26.2-31.5,33.5-31.5,33.5c-40.1,2.7-80.1,5.8-120.2,9.2c0,0-22.1-6.1-46.2-36c7.5-0.7,15.1-1.4,22.6-2.1
c0,0-20.2-5.8-40-36.5c8.8-0.8,17.6-1.6,26.3-2.4c0,0-27.9-7.4-46.3-55.9C156.2,83.3,130.3,96,130.3,96c8.8-0.9,17.6-1.8,26.3-2.7
c-13.6,34-32.3,43.5-32.3,43.5c7.5-0.8,15.1-1.6,22.6-2.3c-17.9,34.1-38.4,44.2-38.4,44.3c-38,4.1-75.9,7.6-113.8,12.3
c0.4,3.3,0.8,7.6,1.3,10.9c0,13.3,1608,13.3,1608,0c0.4-3.3,0.8-7.6,1.3-10.9C1562,185.6,1518.8,181.6,1475.4,177z"></path></svg>';
		} else if ( $style==='cz_sep_39' ) {
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 1600 200">
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="8" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="8" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="8" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="8" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="96" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="96" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="96" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="96" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="184" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="184" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="184" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="184" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="272" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="272" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="272" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="272" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="360" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="360" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="360" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="360" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="448" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="448" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="448" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="448" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="536" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="536" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="536" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="536" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="624" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="624" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="624" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="624" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="712" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="712" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="712" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="712" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="800" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="800" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="800" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="800" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="888" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="888" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="888" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="888" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="976" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="976" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="976" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="976" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1064" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1064" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1064" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1064" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1152" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1152" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1152" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1152" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1240" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1240" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1240" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1240" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1328" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1328" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1328" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1328" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1416" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1416" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1416" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1416" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1504" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1504" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1504" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1504" cy="16" r="2"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1592" cy="112" r="26"></circle> 
<circle style="opacity: .8;fill: ' . $top_color . ';" cx="1592" cy="64" r="14"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1592" cy="34" r="8"></circle> 
<circle style="opacity: .6;fill: ' . $top_color . ';" cx="1592" cy="16" r="2"></circle> 
<path style="fill: ' . $top_color . ';" d="M1638,192c0-25.405-20.595-46-46-46c-20.729,0-38.25,13.713-44,32.561 c-5.75-18.848-23.271-32.561-44-32.561s-38.25,13.713-44,32.561c-5.75-18.848-23.271-32.561-44-32.561s-38.25,13.713-44,32.561 c-5.75-18.848-23.271-32.561-44-32.561s-38.25,13.713-44,32.561c-5.75-18.848-23.271-32.561-44-32.561s-38.25,13.713-44,32.561 c-5.75-18.848-23.271-32.561-44-32.561s-38.25,13.713-44,32.561c-5.75-18.848-23.271-32.561-44-32.561s-38.25,13.713-44,32.561 C1014.25,159.713,996.729,146,976,146s-38.25,13.713-44,32.561C926.25,159.713,908.729,146,888,146s-38.25,13.713-44,32.561 C838.25,159.713,820.729,146,800,146s-38.25,13.713-44,32.561C750.25,159.713,732.729,146,712,146s-38.25,13.713-44,32.561 C662.25,159.713,644.729,146,624,146s-38.25,13.713-44,32.561C574.25,159.713,556.729,146,536,146s-38.25,13.713-44,32.561 C486.25,159.713,468.729,146,448,146s-38.25,13.713-44,32.561C398.25,159.713,380.729,146,360,146s-38.25,13.713-44,32.561 C310.25,159.713,292.729,146,272,146s-38.25,13.713-44,32.561C222.25,159.713,204.729,146,184,146s-38.25,13.713-44,32.561 C134.25,159.713,116.729,146,96,146s-38.25,13.713-44,32.561C46.25,159.713,28.729,146,8,146c-25.405,0-46,20.595-46,46 c0,24.056,18.469,43.787,42,45.816V238h1596v-0.708C1621.589,233.504,1638,214.675,1638,192z"></path></svg>';
		} else if ( $style==='cz_sep_40' ) {
			$height = $atts['sep_height'] ? $atts['sep_height'] : '300px';
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 100 100" fill="' . $top_color . '" style="width:100%;height:' . $height . ';"><path d="M 104 100 V 0 Q 85 100, 45 104" stroke="' . $atts['bottom_color'] . '" stroke-width="7" fill="transparent"></path><path d="M 100 100 V 15 Q 80 100, 45 100 Z"></path></svg>';
		} else if ( $style==='cz_sep_41' ) {
			$height = $atts['sep_height'] ? $atts['sep_height'] : '50px';
			$out .= '<svg ' . $svg_attrs . ' style="width:100%;height:' . $height . '" role="presentation">
	<defs>
		<pattern id="' . $atts['id'] . '_pattern-stripe" width="24" height="4" patternUnits="userSpaceOnUse" patternTransform="rotate(-45)">
			<rect width="10" height="5" transform="translate(0,0)" fill="white"></rect>
		</pattern>
		<mask id="' . $atts['id'] . '_mask-stripe">
			<rect x="0" y="0" width="100%" height="100%" fill="url(#' . $atts['id'] . '_pattern-stripe)"></rect>
		</mask>
	</defs>
	<rect class="hbar" x="0" y="0" width="100%" height="100%" style="-webkit-mask: url(#' . $atts['id'] . '_mask-stripe);mask: url(#' . $atts['id'] . '_mask-stripe);fill: ' . $top_color . ';"></rect>
</svg>';
		} else if ( $style==='cz_sep_42' ) {
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 1600 100" style="fill:' . $top_color . '">
<path class="gambit_sep_decor1" style="opacity: .4;" d="M1040,56c0.5,0,1,0,1.6,0c-16.6-8.9-36.4-15.7-66.4-15.7c-56,0-76.8,23.7-106.9,41C881.1,89.3,895.6,96,920,96
C979.5,96,980,56,1040,56z"></path>
<path class="gambit_sep_decor1" style="opacity: .4;" d="M1699.8,96l0,10H1946l-0.3-6.9c0,0,0,0-88,0s-88.6-58.8-176.5-58.8c-51.4,0-73,20.1-99.6,36.8
c14.5,9.6,29.6,18.9,58.4,18.9C1699.8,96,1699.8,96,1699.8,96z"></path>
<path class="gambit_sep_decor1" style="opacity: .4;" d="M1400,96c19.5,0,32.7-4.3,43.7-10c-35.2-17.3-54.1-45.7-115.5-45.7c-32.3,0-52.8,7.9-70.2,17.8
c6.4-1.3,13.6-2.1,22-2.1C1340.1,56,1340.3,96,1400,96z"></path>
<path class="gambit_sep_decor1" style="opacity: .4;" d="M320,56c6.6,0,12.4,0.5,17.7,1.3c-17-9.6-37.3-17-68.5-17c-60.4,0-79.5,27.8-114,45.2
c11.2,6,24.6,10.5,44.8,10.5C260,96,259.9,56,320,56z"></path>
<path class="gambit_sep_decor1" style="opacity: .4;" d="M680,96c23.7,0,38.1-6.3,50.5-13.9C699.6,64.8,679,40.3,622.2,40.3c-30,0-49.8,6.8-66.3,15.8
c1.3,0,2.7-0.1,4.1-0.1C619.7,56,620.2,96,680,96z"></path>
<path class="gambit_sep_decor1" style="opacity: .4;" d="M-40,95.6c28.3,0,43.3-8.7,57.4-18C-9.6,60.8-31,40.2-83.2,40.2c-14.3,0-26.3,1.6-36.8,4.2V106h60V96L-40,95.6
z"></path>
<path class="gambit_sep_decor2" style="opacity: .7;" d="M504,73.4c-2.6-0.8-5.7-1.4-9.6-1.4c-19.4,0-19.6,13-39,13c-19.4,0-19.5-13-39-13c-14,0-18,6.7-26.3,10.4
C402.4,89.9,416.7,96,440,96C472.5,96,487.5,84.2,504,73.4z"></path>
<path class="gambit_sep_decor2" style="opacity: .7;" d="M1205.4,85c-0.2,0-0.4,0-0.6,0c-19.5,0-19.5-13-39-13s-19.4,12.9-39,12.9c0,0-5.9,0-12.3,0.1
c11.4,6.3,24.9,11,45.5,11C1180.6,96,1194.1,91.2,1205.4,85z"></path>
<path class="gambit_sep_decor2" style="opacity: .7;" d="M1447.4,83.9c-2.4,0.7-5.2,1.1-8.6,1.1c-19.3,0-19.6-13-39-13s-19.6,13-39,13c-3,0-5.5-0.3-7.7-0.8
c11.6,6.6,25.4,11.8,46.9,11.8C1421.8,96,1435.7,90.7,1447.4,83.9z"></path>
<path class="gambit_sep_decor2" style="opacity: .7;" d="M985.8,72c-17.6,0.8-18.3,13-37,13c-19.4,0-19.5-13-39-13c-18.2,0-19.6,11.4-35.5,12.8
c11.4,6.3,25,11.2,45.7,11.2C953.7,96,968.5,83.2,985.8,72z"></path>
<path class="gambit_sep_decor2" style="opacity: .7;" d="M743.8,73.5c-10.3,3.4-13.6,11.5-29,11.5c-19.4,0-19.5-13-39-13s-19.5,13-39,13c-0.9,0-1.7,0-2.5-0.1
c11.4,6.3,25,11.1,45.7,11.1C712.4,96,727.3,84.2,743.8,73.5z"></path>
<path class="gambit_sep_decor2" style="opacity: .7;" d="M265.5,72.3c-1.5-0.2-3.2-0.3-5.1-0.3c-19.4,0-19.6,13-39,13c-19.4,0-19.6-13-39-13
c-15.9,0-18.9,8.7-30.1,11.9C164.1,90.6,178,96,200,96C233.7,96,248.4,83.4,265.5,72.3z"></path>
<path class="gambit_sep_decor2" style="opacity: .7;" d="M1692.3,96V85c0,0,0,0-19.5,0s-19.6-13-39-13s-19.6,13-39,13c-0.1,0-0.2,0-0.4,0c11.4,6.2,24.9,11,45.6,11
C1669.9,96,1684.8,96,1692.3,96z"></path>
<path style="opacity: .7;" d="M25.5,72C6,72,6.1,84.9-13.5,84.9L-20,85v8.9C0.7,90.1,12.6,80.6,25.9,72C25.8,72,25.7,72,25.5,72z"></path>
<path style="stroke:' . $top_color . '" d="M-40,95.6C20.3,95.6,20.1,56,80,56s60,40,120,40s59.9-40,120-40s60.3,40,120,40s60.3-40,120-40
s60.2,40,120,40s60.1-40,120-40s60.5,40,120,40s60-40,120-40s60.4,40,120,40s59.9-40,120-40s60.3,40,120,40s60.2-40,120-40
s60.2,40,120,40s59.8,0,59.8,0l0.2,143H-60V96L-40,95.6z"></path>
</svg>';
		} else if ( $style==='cz_sep_43' ) {
			$out .= '<svg ' . $svg_attrs . ' viewBox="0 0 1600 190"><path fill="' . $top_color . '" d="M1600,1V190H0V139H610c9.113,0,44.472,2.092,48,0,5.64-3.522,9.4-9.4,14-14l33-33L798,0Z"/></svg>';
		} else if ( $style==='cz_sep_44' ) {
			$out .= '<svg ' . $svg_attrs . ' fill="' . $top_color . '" viewBox="0 0 1600 100"><path d="M728,18v3c9.867,0.242,20.534.766,28,0,2.3-.237,8.558,2.892,12,1,0,0,2.76-3.554,3-4h-3c0.828-3.245-.49-1.528,2-3,1.073,0.752.946,0.6,3,1v4h1v1c9.7,0.493,23.078,1.539,30,0l11,1c1.827-.913,1.178-2.883,4-4,4.024,3.117,15.568,4.1,23,4V20c-1.624-1.35-1.121-.64-2-3h1V16h1c0.739,3.955.094,3.212,4,4,0.087-.1,1.313-2.886,2-2v1c3.4,1.866,5.6,5.48,9,7,9.366,4.193,19.371-1.294,26-3,0.66-.17,2.97,1.715,5,2V21c2.338,0.279,9.635-.411,14,1-1.133,1.546-1.085.843-2,3h2c3.568,2.192,9.316.406,15,0V24a6.107,6.107,0,0,1-2-2h7c5.26,4.154,13.806.469,21,0,0.387-2.53-.581-1.578,3-2,3.307,1.819,7.655-1.454,13,0,14.406,3.918,32.795-2.457,49,0,5.32,0.807,15.79,2.563,24,2,3.13-.214,6.75-1.777,11-1,3.27,0.6,11.19,2.185,16,1,2.78-.684,5.38-3.133,7-4,2.1-1.123,4,.991,5,1,0,0,4.82-.916,5-1V16l14,1c1.12-.343.58-1.789,3-2,1.72-.15,10.17,2.029,13,0v2c1.29,0.548,11.74-2.385,13-2,0.33,1,.67,2,1,3,2.45,1.275,9.71-2.853,14-2,0.61,0.122,6.36.35,8,1,0.67,1,1.33,2,2,3,2.9,0.862,2.94-3.552,7-2,0.59,0.224,5.46,2.757,7,3,4.32,0.684,14.32-4.136,18-5,0.89-1.1,1,1,1,1,3.03,2.83,4.95-.019,8,1,0.33,1,.67,2,1,3,2.42,1.165,8.08-3.73,12-4,1.87,5.56,4,8.189,12,8a41.751,41.751,0,0,0,6,2h3l2-2h28V23h-13c-1.02-.8-0.27-0.355-2-1-0.33-.667-0.67-1.333-1-2h3c0.12,0.088,1.98,1.994,2,2,2.31,0.691,2.59-2.589,4-3,4.85-1.41,12.01,2.473,17,1,0.83,3.245-.49,1.528,2,3,6.92-6.858,14.45,4.146,19,6,3.47,1.411,15.52,1.106,18,1,0.52-.022,9.1,1.344,12,0,1.9-.881,3.7-3.883,8-3,2.81,0.577,5.31,3.224,7,4,5.81,2.667,9.31-1.921,15,0,1.11,0.375,1.83,2.8,3,3,1.39,0.235-.4-0.811,1-1,3.54-.477,15.77,3.761,18,0,0.98,1.06.9,4.2,2,5l3-1c7.98,3.264,28.79,10.7,38,9V44c-0.88-1.295-.89-2.467-1-5,11.5,0.411,36.76,5.877,45,1,2.84-.967.88-1.745,7-2,6.84,4.413,37.88,3.4,50,3,1.18,2.651-.9,1.794,2,3,6.7,5.129,35.86,4.051,46,6v1h-7c-1.02-.8-0.27-0.355-2-1v2c22.29,2.147,39.82,2.646,55,9v3H0V39a114.663,114.663,0,0,0,30-4l9,1a115.56,115.56,0,0,0,18-3l13,1c16.78-3.016,30.253-2.1,44-5-0.01,3.166-1.423,1.869,2,3,4.515-3.9,30.062-3.889,42-4,0.906-2.424,1.069-1.59,2-4h-2c-3.226-1.945-5.256.553-8,2-0.075-3.042-.756-2.744,0-4,1.073-.752.946-0.6,3-1,5.794,3.215,9.6-2.669,13,3-3.764.142-3.515-.471-5,2h1c0.74,0.872,1.26,1.128,2,2,4.157-1.125,13.193-2.37,18-1,1.116,0.318.925,2.9,4,2V27l6,1c2.667-.5,6.414-3.493,8-4l5,1c11.156-1.029,16.226.229,26,4,5.647,2.178,11.92-2.14,15-2,15.182,0.692,34.428.456,44,3,0.846-.309,3.153-2.757,4-3,2.412-.691,10.51,1.98,14,1l1-2h12c1.321-.739,1.626-3.157,6-4,3.431-.661,5.537,1.456,8,2,4.261,0.941,20.7-1.093,25-2,4.7-.992,8.07,1.1,10,2,2.293,1.07,18.147-3.892,19-5,1.624,1.856,8.764,5.882,14,5,1.926-.324,7.2-3.147,10-2,1.761,0.722,3.8,4.064,8,3,2.2-.558,5.218-3.315,8-4l12,1c19.763-3.527,40.9,2.694,60-2l17,1c2.857-.646,7.1-2.164,10-3,1.887-.543,7.284,2.832,8,3,0.885,0.207,9.949-2.985,10-3l16,1c0.838-.3,1.569-2.744,2-3,4.606-2.733,18.975-1.941,25-2v4c10.193,0.971,22.188,3.447,28-1,0.682-.771,1.311-2.232,2-3,3.223,2.392,9.383,3.661,11,3,3.864-.7,5.542-2.939,15-3v3c6.4-.114,15.856-1.357,21,0v1h-1a11.576,11.576,0,0,1-3,2v1h9c2.975,2.111,5.677.79,7-2,0.707-1.545.129-.135,0-3h3c7.306,4.479,20.485-6.062,27-2,5.111,1.785,5.238,4.7,9,6,1.69,1.142,4.9,1.066,8,1C725.117,17.259,723.794,18.558,728,18Zm265-5h2v1C993.246,13.369,994.029,13.807,993,13ZM766,14h2v1C766.246,14.369,767.029,14.807,766,14Zm19,0c2.532,0.074,3.716.12,5,1C787.468,14.926,786.284,14.88,785,14ZM205,16c5.976-.2,14.308-0.518,18,2C216.307,18.2,204.088,18.434,205,16Zm194,0a12.667,12.667,0,0,1,4,1c-1.021.8-.274,0.355-2,1C399.125,20.118,399.13,16.823,399,16Zm98,0c0.752,1.073.6,0.946,1,3h-1V16Zm302,0h7c0.961,1.766,1.257,1.68,2,4h-6c-2.345-1.476-9.227-.515-10-1h-1V18C793.689,17.779,797.248,17.269,799,16Zm47,0h2v1C846.246,16.369,847.029,16.807,846,16ZM335,17l2,2C334.318,19.9,333.661,18.386,335,17Zm11,0h2v1C346.246,17.369,347.029,17.807,346,17Zm374,0c2.076,0.372,1.924.242,3,1C720.924,17.628,721.076,17.758,720,17Zm8,0c3.28-.117,7.184-0.236,9,1C733.72,18.117,729.816,18.236,728,17Zm475,0c4.17-.072,6.83.228,9,2h-2c-1.18.8-1.68,0.773-4,1C1205.01,18.64,1204.07,18.194,1203,17Zm16,0c2.08,0.372,1.92.242,3,1h-2C1219.36,17.719,1219.54,18.308,1219,17ZM809,18c2.076,0.372,1.924.242,3,1C809.924,18.628,810.076,18.758,809,18Zm413,0h2v1C1222.25,18.369,1223.03,18.807,1222,18Zm5,0a10.962,10.962,0,0,1,5,2C1228.24,19.858,1228.48,20.472,1227,18ZM256,20h2v2h-1Zm610,0c6.226,0.1,7.088.179,13,0v2h-2c-0.919.325-9.181,0.472-12,1ZM303,21c6.289-.219,11.551.337,15,3C311.428,24.3,305.8,24.514,303,21Zm553,0h2v1C856.246,21.369,857.029,21.807,856,21ZM279,22h2v1C279.246,22.369,280.029,22.807,279,22Zm6,0h2v1C285.246,22.369,286.029,22.807,285,22ZM1371,32l4,1v1h-1C1371.63,36.718,1371.06,32.962,1371,32Zm-4,1c0.8,1.021.35,0.274,1,2h-1V33Zm29,0,10,1v1h-2c-2.71,1.741-6.21,1-10,1V34C1395.75,33.369,1394.97,33.807,1396,33Zm12,0c0.35,0.3,2,.523,1,2C1407.89,34.512,1405.89,33.224,1408,33Zm-9,6h2v1C1399.25,39.369,1400.03,39.807,1399,39Z"/><rect x="-68" y="61" width="1731" height="187"/></svg>';
		} else if ( $style==='cz_sep_45' ) {
			$out .= '<svg ' . $svg_attrs . ' fill="' . $top_color . '" viewBox="0 0 1600 280">
  <rect x="-11" y="214" width="1614" height="104"/>
  <circle cx="31" cy="145" r="17"/>
  <rect x="15" y="179" width="30" height="55" rx="15" ry="15"/>
  <rect x="77" y="111" width="29" height="90" rx="14.5" ry="14.5"/>
  <rect x="136" y="141" width="32" height="88" rx="15" ry="15"/>
  <rect x="199" y="179" width="31" height="31" rx="15.5" ry="15.5"/>
  <rect x="259" y="72" width="32" height="129" rx="16" ry="16"/>
  <rect x="322" y="114" width="29" height="45" rx="14.5" ry="14.5"/>
  <rect x="321" y="174" width="31" height="65" rx="15.5" ry="15.5"/>
  <rect x="382" y="32" width="31" height="175" rx="15.5" ry="15.5"/>
  <rect x="445" y="98" width="28" height="43" rx="14" ry="14"/>
  <rect x="445" y="154" width="29" height="82" rx="14.5" ry="14.5"/>
  <rect x="504" y="114" width="31" height="42" rx="15.5" ry="15.5"/>
  <rect x="506" y="172" width="29" height="60" rx="14.5" ry="14.5"/>
  <rect x="567" y="136" width="30" height="100" rx="15" ry="15"/>
  <rect x="628" y="60" width="28" height="116" rx="14" ry="14"/>
  <rect x="629" y="192" width="28" height="40" rx="14" ry="14"/>
  <rect x="689" y="121" width="29" height="87" rx="14.5" ry="14.5"/>
  <rect x="750" y="155" width="30" height="78" rx="15" ry="15"/>
  <rect x="810" y="57" width="31" height="166" rx="15.5" ry="15.5"/>
  <rect x="873" y="167" width="30" height="67" rx="15" ry="15"/>
  <rect x="933" y="133" width="30" height="74" rx="15" ry="15"/>
  <rect x="932" y="84" width="33" height="33" rx="16.5" ry="16.5"/>
  <rect x="996" y="142" width="27" height="40" rx="13.5" ry="13.5"/>
  <rect x="995" y="192" width="29" height="58" rx="14.5" ry="14.5"/>
  <rect x="1055" y="126" width="32" height="106" rx="16" ry="16"/>
  <rect x="1118" y="86" width="28" height="81" rx="14" ry="14"/>
  <rect x="1119" y="183" width="27" height="52" rx="13.5" ry="13.5"/>
  <rect x="1178" y="142" width="31" height="65" rx="15.5" ry="15.5"/>
  <rect x="1240" y="63" width="29" height="97" rx="14.5" ry="14.5"/>
  <rect x="1240" y="181" width="30" height="53" rx="15" ry="15"/>
  <rect x="1301" y="171" width="30" height="37" rx="15" ry="15"/>
  <rect x="1362" y="187" width="30" height="44" rx="15" ry="15"/>
  <rect x="1423" y="128" width="30" height="70" rx="15" ry="15"/>
  <rect x="1484" y="32" width="31" height="108" rx="15.5" ry="15.5"/>
  <rect x="1484" y="159" width="32" height="74" rx="16" ry="16"/>
  <rect x="1546" y="156" width="30" height="37" rx="15" ry="15"/>
  <rect x="1551" y="204" width="23" height="39" rx="11.5" ry="11.5"/>
</svg>';
		} else if ( $style==='cz_sep_46' ) {
			$out .= '<svg ' . $svg_attrs . ' fill="' . $top_color . '" viewBox="0 0 1600 160"><path d="M385,0h2V1C384.99,0.426,386.135,1.12,385,0Zm94,2h3V3h-3V2ZM417,5l3,1c-0.844,1.135.127,0.145-1,1-1.139,1.139,0,.4-2,1V5Zm5,1c1.719,1.127,1.355.633,2,3h-1V8C421.861,6.861,422.6,8,422,6Zm24,2c1.719,1.127,1.355.633,2,3h-1V10C445.861,8.861,446.6,10,446,8Zm336,2v2l-4,1V11Zm489,8h-2c-0.33-2.333-.67-4.667-1-7h1v1h1C1270.33,14,1270.67,16,1271,18Zm-265,3h2v1C1005.99,21.426,1007.13,22.12,1006,21ZM476,23c9.871-.079,21.718-0.525,30,1v1H474V24C476.01,23.426,474.865,24.12,476,23Zm-85,1c10.6-.093,34.129-1.907,42,1v1H416V25c-2.1-1.147-4.1,1.2-5,1-6.458-1.463-12.363-.168-20,0V24Zm47,0h4v1h-4V24Zm94,0c4.623-.12,15.364-1.386,18,1l-19,1Zm54,0v2h-4V25h2C585.139,23.861,584,24.6,586,24ZM366,25c3.579-.058,6.3.228,8,2-4.354.528-6.895,1.424-11,0h-4V26h7V25Zm245,0h3v1h-3V25ZM313,26c4.543-.106,18.815-0.968,21,1C328.526,27.427,312.618,32.4,313,26Zm401,0h3v1h-3V26Zm194,0v2h-3V27h1C907.139,25.861,906,26.6,908,26Zm12,0h3v1h-1c-1.139,1.139,0,.4-2,1V26Zm15,0h4v1h-4V26Zm14,0h4v1h-3v1C949.1,29.87,949,26,949,26Zm16,0h7v1h-7V26Zm14,0h3v1h-1c-0.583.494-1.147,1.733-3,1Zm15,2h-3V27h2V26C993.95,24.157,994,28,994,28ZM731,27h3v1h-1c-0.583.494-1.147,1.733-3,1Zm120,0h24c3.182,0,7.312-.5,9,1l-53,2c-4.61,0-17.662,1.038-20-1h6V28h23C843.525,28,849.077,28.711,851,27Zm46,0h3v1h-3V27ZM741,28h4v1h-4V28Zm13,0h3v1h-3V28Zm309,0h6v1h-6V28ZM773,29h6v1h-6V29Zm18,0h3v1h-3V29Zm9,2h-2V30h1V29C800.139,30.139,799.4,29,800,31Zm4-2h3v1h-3V29Zm326,0h2v1C1129.99,29.426,1131.13,30.12,1130,29Zm114,0,3,1v1h-3V29Zm-40,1c0.67,0.333,1.33.667,2,1v1h-4V31C1204.01,30.426,1202.87,31.12,1204,30Zm48,0c4.45-.12,14.48-1.282,17,1C1263.48,31.18,1254.74,33.889,1252,30Zm23,1h4v1h-4V31ZM134,40c2.217-7.891,25.9-8,37-8,4.034,0,11.732-1.029,14,1a52.323,52.323,0,0,0-14,2h-8v1h-8v1h-8C143.34,38.074,138,39.216,134,40Zm1174-8v2c-2.55.55-6.74,2.076-9,0h2V33Zm-41,1h3v1h-3V33Zm45,1h4v1h-3v1C1312.1,37.87,1312,34,1312,34Zm89,2h-2c0.84-1.135-.13-0.145,1-1V34C1401.14,35.139,1400.4,34,1401,36Zm51-2h2c-0.84,1.135.13,0.145-1,1-1.77,1.984-1.95.845-4,2v1h-2c-0.33.667-.67,1.333-1,2h-2v1h-2l-3,4-7,1v1c-3.13,1.606-5.32,3.174-9,4V50h1c1.65-2.394.75-.627,3-2V47h2c0.33-.667.67-1.333,1-2l4-1V43h2V42h2V41h2V40h2V39h2V38C1446.94,36.424,1449.96,36.375,1452,34ZM429,35h2v1C428.99,35.426,430.135,36.12,429,35Zm490,0,3,1v3h-2Q919.5,37,919,35Zm417,0h2v1h-2V35Zm135,1h4v1h-4V36ZM359,39h-3V38h2V37C358.95,35.157,359,39,359,39Zm1108,0h3v1h-2v1C1467.05,42.843,1467,39,1467,39ZM241,40h6v1h-6V40Zm370,0h3v1h-3V40ZM212,41v2h-3V42h1C211.139,40.861,210,41.6,212,41Zm276,3c31.18,2.785,67.116,1,100,1h41c9.481,2.655,24.722.995,36,1h10c10.326,2.892,26.839,1,39,1h13c8.913,2.5,23.315,1,34,1h68V47h6l64-2c8.2,0,22.138-1.282,27,0h13v1h9v1c4.185,1.176,10.245-1.392,13,1a34.228,34.228,0,0,0-8,1v3l3-1V50h2V49c2.1-.735,13.044,2.3,15,3v1l24-1V51h7V50l13,1v1h2v1h6c-0.4-4.264-2.07-4.521-3-8a20.39,20.39,0,0,1,9,5c0.33,0.667.67,1.333,1,2l6,1v1l67,1v1h-2c-2.77,2.387-8.81-.186-13,1-8.05,2.279-21.21,1-31,1h-10c-7.74,2.2-18.94-.324-27-1l-80-1H915l-9-1v1l-41,1c-6.547,0-13.91-1.13-15,2h3v1H843V59h-1v1H827c-8.913-2.5-23.315-1-34-1H675V58H605c-10.946-3.06-30.933-1-44-1-31.163,0-64.455.281-94,1,0.113,3.16,1.955,1.874-1,3V59l-5-1V57H449v1H418v1H395c-4.861,1.378-13.523-.553-19,1-4.848,1.375-13.549-.561-19,1v1h-7v1h-2v1c-1.695.393-1.75-.946-2-1H322v1H306v1H293v1H278c-12.151,3.456-29,1.569-41,5h-8v1c-2.311.706-3.975,0.23-6,1v1h20c3.634,3.221,22.909.032,28,0l50-2V71h9V70h7V69h4V68c3.355-1.421,3.752-.487,7,0-1.139,1.139,0,.4-2,1-3.041,2.9-6.425.5-9,2v1c9.79,0.022,22.944,1.255,31-1h27V70h14l34-1V68l38,1c5.526-1.547,14.856-1,22-1,12.462,0,29.385-1.963,40,1l91-1V66c-7.44,0-17.19.623-23-1l-40,1-1-3c2.652-1,6.944-1.041,11-1,1.292-1.136,18.731-1.925,22-1,6.081,1.72,16.273,1,24,1l33,1h20v1h24v1h16v1h12v1h14v1h14c7.48,2.1,36.51,2.082,44,0l15,1V68h52l34-1c3.245,0.541,11.3,2.419,16,1V67h4l1-2c3.309-1.089,3.165,1.659,4,2l11,1,31,1V68h16v2c13.3,2.411,29.13.053,43,0-0.33.667-.67,1.333-1,2h6c1.34,1.2,8.5,1,14,1h35l100,2v1h12l20,1,39,1c4.25,0,12.56,1.114,15-1-3.01-.3-2.72-0.135-4-2h9c2.2,1.99,8.17.668,12,1,1.87,0.162,2,1,2,1,7.13,1.961,15.75-.975,21-2-0.93,3.45-1.3,1.867-3,4h38c2.4,2.108,8.06.343,12,1,5.79,0.966,14.66,1,22,1,28.95,0,61.44,3.469,85-4h5c7.31-2.269,16.74-3.865,24-6h7V70h2v1h2v1c-8.12.016-12.61,3.52-19,5v2c15.3,0.24,29.51-.427,41-4l9-1V73h2V72h3V71l14,1V71l29-5c-0.56,2.448-1.04,2.21-2,4h1c1.69,1.416,5.23.169,9,0,1.09,2.078,1.61,1.771,2,5-2.5,2.247-.96,3.836-5,5-2.24,1.976-5.85.869-9,2-2.79,1-4.92,3.207-8,4-0.33,1.333-.67,2.667-1,4h14c0.33,0.667.67,1.333,1,2l14-1v4h-1v1h-4v1h5v98H0V97H20V96H19V93H18c-1.522,1.4-7.932,1.985-11,2V93l23-3V89l7-1V87h3V86l6-1V84l8-1,8-4V78H57c-2.959,2.543-8.615.7-13,2v1H39v1H33v1H27v1H23v1H17c-4.856,1.508-8.055,2.892-15,3V87H3c1.32-2.006,3.228-2.489,6-3a34.62,34.62,0,0,0,1-7l56-9c5.07-1.266,10.259,3.929,17,2,7.582-2.17,17.54-2.843,25-5h9V64h8V63h11V62h14V61l18-1h10V59h13V58l31-1V56l18-1h10V54h14V53h16V52h16V51h15V50c6.057-1.706,15.555.533,21-1h39V48h33V47h25V46c6.042-1.7,16.1.661,22-1h32V44h2V43l3-2v3Zm504-3v6c-3.041-.724-4.668-2.646-6-5h2V41h4Zm466,0h3v1h-1c-2.56,2.556-12.28,4.034-18,4V45h1c1.96-2.094,7.39-2.692,11-3h4V41ZM0,71V65H1c3.106,3.5,5.452,1.1,9,0h6V64l16-2a99.163,99.163,0,0,1,26-4V56l19-4,23-2V49h6V48h6V47h6V46h4V45h3V44l23,1h5V44h21c3.384,0,8.154-.576,10,1l-25,1v1H147v1H136v1H124v1h-9v1L77,54v1H73v1H71v1H68v1H63c-6.128,1.927-14.8,3.127-21,5L26,65v1l-9,1v1H11C7.758,69.016,4.165,70.544,0,71ZM1575,49v2a39,39,0,0,0-12,2h-9v1h-9v1h-8v1h-10v1h-8v1h-9v1h-10v1h-6v1h-7v1l-12,3v1h14c3.36-3.038,6.8.665,10,0V65h3v1h-1c-1.54,1.732-11.62,5.216-16,4-13.71-3.811-34.21-1.863-51-2v1h1c3.64,4.073,13.57,5.582,19,8v1h-5V77l-7-1V75h-3V74h-2V73a8.334,8.334,0,0,0-4-2V69c-2.19-.355-6.21-1.485-8-2h-13V66c-6.07-1.72-15.54.571-21-1h-8V64h2c2.44-2.111,17.15-1,22-1l52,2V64h3a9.409,9.409,0,0,1,2-2V61h-9c-4.11-3.538-23.93.474-31-1-3.24-.676-13.51-1-21-1h-11V58h-14v1h-4v1l-6-1V58l-4,1V58h-4V57h-1c-0.33.667-.67,1.333-1,2h-3v2c2.61-.039,19.01,1.1,20,2h-7v1h-37c-6.55,0-15.04.391-20-1h-31V62h-33V61h-37V60h-15l-19-2c-0.33-.667-0.67-1.333-1-2,2.65-1,6.94-1.041,11-1v1c2.04,1.08,9.26-2.123,13-2,4.34,0.143,9.48,1.984,13,3h10v1h35c9.14,2.547,23.13.51,34,1,13.01,0.587,32.01,2.326,45,0V57c-5.86,2.3-10.23.1-15-1-0.33-1-.67-2-1-3l10,1v2l12-2v1h4v1h7V55h14v1c2.35,0.62,2.62-.858,4-1,0.33,0.667.67,1.333,1,2l5-1V55l16-1v1h7v1h18c4.97,1.379,13.44,1,20,1h19V56h10l20-1V54h12V53h12s0.08-.834,2-1h11V51h12V50h16C1557.82,48.363,1567.55,49.031,1575,49Zm-92,1h5v1h-5V50Zm13,0h3v1h-2c-0.49.427-1.25,1.853-3,1V51C1496.01,50.426,1494.87,51.12,1496,50Zm-207,2h3v1h-3V52Zm-145,5c0.83-3.2,1.18-2.9,5-3v1h-1v1A10.6,10.6,0,0,1,1144,57ZM875,61h2v2h-2V61Zm6,4,1-3s-2.171-.207-1-1h13v1c3.942,2.175,13.5-2.044,17,1C902.722,63.678,889.082,63.506,881,65ZM358,67c-0.88,3.4-1.861,3.065-6,3V68Zm7,2h-2c0.844-1.135-.127-0.145,1-1V67C365.139,68.139,364.4,67,365,69Zm691-2c6.39-.1,15.25-0.777,20,1v1C1070.18,69.166,1058.48,70.524,1056,67Zm26,1h6v1h-6V68Zm15,2h-2c0.84-1.135-.13-0.145,1-1V68C1097.14,69.139,1096.4,68,1097,70Zm189,3V71c5.3-1.007,6.9-1.855,12-1-0.84,1.135.13,0.145-1,1C1295.25,72.643,1289.61,73.03,1286,73Z"/></svg>';

		} else if ( $style==='cz_sep_47' ) {
			$out .= '<svg ' . $svg_attrs . ' fill="' . $top_color . '" viewBox="0 0 1600 160"><path d="M736,18h3v1h-3V18Zm14,0h2v1C749.99,18.426,751.135,19.12,750,18Zm33,0,4,1v1C783.991,19.7,784.277,19.865,783,18Zm8,1h3v1h-3V19Zm11,0,4,1v1C802.991,20.7,803.277,20.865,802,19Zm9,1h6v1h-6V20Zm16,0h3v1h-3V20Zm8,0a9.746,9.746,0,0,1,4,1v1C835.991,21.7,836.277,21.865,835,20Zm-40,3H777V22h1c2.325-2.219,8.846-.015,13,0a20,20,0,0,0,4-1v2Zm55-2h4v1h-4V21Zm491,0h2v1C1340.99,21.426,1342.13,22.12,1341,21Zm7,0h4v1h-4V21Zm13,0c3.8-.08,7.58.081,10,1v1h-3V22c-1.9-1.037-4.16.684-5,1,0.33,1,.67,2,1,3h-1V25C1361.64,23.835,1361.47,23.176,1361,21Zm32,2v2h4v2h-5V26h-2V25l-4,1v1l-8-1V23h-3V22h-1V21h11v1c3.27,1.8,8.5-1.181,11,1h-3ZM831,23v2l-24-1-1-2h5C813.153,23.948,826.559,23.09,831,23Zm51-1h3v1h-3V22Zm447,0h2c-0.84,1.135.13,0.145-1,1-0.61.608-10.3,2.075-12,1V23C1321.43,23.117,1327.18,23.669,1329,22Zm8,4h-2c0.33-1,.67-2,1-3h-1c-1.47-1.21,3.62-.985,4-1v1C1337.28,24.127,1337.64,23.633,1337,26Zm6-4h3v1h-3V22Zm56,0,9,1v1c1.85,1.346,1.94,1.565,5,2V23h7c0.33,0.667.67,1.333,1,2,1.25,0.53,4.07.339,4-1h1v2h5V24h4c1.24,3.326-.83,2.959,5,3V26c0.95-1.843,1,2,1,2-6.84.132-11.6,1.171-18,0V26l-3,1v1c-2.56.869-4.69-.81-6-1h-5v1l-10-1V22Zm134,1c-0.33,1.666-.67,3.334-1,5-1.59.078-1.68-.544-2,1-1.22-.614-1.4-1.834-2-3a9.492,9.492,0,0,0-2,2c-1.93,1-5.96.827-8,0V27h-2V26l-17,1V25h-6c-1.63.494-4.6,3.383-8,2-0.33-.667-0.67-1.333-1-2-0.67.333-1.33,0.667-2,1V25h-1V24c6.78-2.508,19.42-1.052,28-1,1.43-1.313,7.98-1.7,11-1v1c1.67,0.279,1.75-.945,2-1C1525.98,21.128,1530.27,22.456,1533,23Zm67,5-10,2c-0.5-.232-0.3-1.77-2-2-0.46.217-.36,1.7-2,2-0.33-.667-0.67-1.333-1-2h-2V27l-3-1v2h-2v1h-9v1h-5v1h-1V30h-2V29h-5V28c-1.14-1.139-.4,0-1-2,1.14-1.139.4,0,1-2l-3,1v3c-2.21-.106-3.35-1.02-4-1v1h-6v1c-3,.811-4.88-0.621-7-1V27a3.991,3.991,0,0,0,2-2c-2.39-.607-1.86-0.317-3-2,2.32-.875,4.41-1.017,8-1v1c2.18,1.228,5.1-1.133,6-1v1h32v1h13a2.49,2.49,0,0,0,3,1V24l3-1v5ZM854,25h-2V24h1V23C854.139,24.139,853.4,23,854,25Zm45-2v2c-1.135-.844-0.145.127-1-1-1.135-.844-0.145.127-1-1h2Zm561,0,3,1a9.655,9.655,0,0,0,1,4h-4V26h-1C1459.33,25,1459.67,24,1460,23Zm6,2V23h1v1h1C1466.86,25.139,1468,24.4,1466,25Zm7-2h7v1h-7V23ZM840,24h3v1h-2v1C840.05,27.843,840,24,840,24Zm19,0h4v1h-4V24Zm17,0,7,1v1c3.3,1.054,7.626.1,10,1v1h-8V27c-4.273-2.34-10.819,2.621-15-2h6V24Zm424,0a9.773,9.773,0,0,1,4,1v1h-2C1301.23,24.984,1300.77,25.016,1300,24Zm10,4-6,1V27c5.43-.235,3.23-0.775,6-3v4Zm31-4v2h-3V25h1C1340.14,23.861,1339,24.6,1341,24Zm14,0h2v1h-2V24Zm84,0h5v1h-5V24Zm31,0h2v1C1469.99,24.426,1471.13,25.12,1470,24ZM922,25h2v1C921.99,25.426,923.135,26.12,922,25Zm374,2v2l-8-1v1h-4V28c2.01-.574.87,0.12,2-1-2.01-.574-0.87.12-2-1l9-1c0.33,0.667.67,1.333,1,2h2Zm2-2h2c-0.33,1-.67,2-1,3h-1V25Zm19,0v2h-3V26h1C1316.14,24.861,1315,25.6,1317,25Zm14,0h2v1C1330.99,25.426,1332.13,26.12,1331,25Zm122,0v2a9.773,9.773,0,0,0-4,1c-0.33-1-.67-2-1-3h5Zm-203,2v2h4v1a40.117,40.117,0,0,0-9,1V26h1v1h4Zm8-1,10,1V26h4v1h1V26h8c-0.33,1.333-.67,2.667-1,4h-7v1l-12-1V28l-3,1V26Zm207,0h2v1C1464.99,26.426,1466.13,27.12,1465,26Zm6,0h3v1h-3V26Zm8,0h3v1h-3V26ZM898,27h8v1h-8V27Zm324,2c-2.39-.607-1.86-0.317-3-2h14v1l4-1v1h1c-0.57,2.01.12,0.865-1,2-3.15,3.7-10.91,3.018-16,2V31C1222.14,29.861,1221.4,31,1222,29Zm221-2h2v1h-2V27ZM926,28l3,1v1h-5V29C926.01,28.426,924.865,29.12,926,28Zm289,2V28h1v1h1C1215.86,30.139,1217,29.4,1215,30ZM647,29v2l-5-1V29h5Zm1,0h4v1h-4V29Zm539,0h4v1h-3v1C1187.1,32.87,1187,29,1187,29ZM893,37c-0.64,2.743-1.9,3.69-3,6-1.139-1.139-.4,0-1-2h-4v1c1.139,1.139.4,0,1,2-1.774-.27-2-1-2-1l-3,1c-1.293-.574.352-0.586-1-1l1-3h-2V39l-3,1a12.709,12.709,0,0,1,1,5l-6-1V43a3.983,3.983,0,0,0,2-2c-2.316-.875-4.407-1.017-8-1V37H852c-0.978.387,0.079,2.319-3,2V38H831V37h-4V36h-1v1l-3,1V36l-11,1v1a9.584,9.584,0,0,1-4,2V38l-5-1V35l-5,1v1h-1V36h-9v1l-6-1h-1v1l-14-1-27,1-1-2c-2.794-1.139-7.282.392-9,1v1c-1.685.376-1.761-.949-2-1H703l-1,2a1.509,1.509,0,0,1-2-1h-1v1a5.025,5.025,0,0,1-5-2h2c3.4-3.64,1.826.109,6-1V34h2V31l35,1h14l1-2c1.679-.481,1.789.957,2,1h7v1h27v1h16V32h11v1h2v1l11-1v1h28C867.9,36.8,880.8,34.79,893,37Zm46-7a18.235,18.235,0,0,1,7,1v1h-7V30Zm14,0c1.139,1.139.4,0,1,2h-1V30Zm6,1c1.139,1.139.4,0,1,2h-1V31Zm7,0,13,1v1H966V31Zm184,0h3v1h-3V31Zm19,0h2c0.33,1,.67,2,1,3h-5c1.14-1.139,0-.4,2-1V31Zm12,2h-4V32h3V31C1180.9,29.13,1181,33,1181,33Zm18-2h2v1C1198.99,31.426,1200.13,32.12,1199,31ZM699,32h2v1C698.99,32.426,700.135,33.12,699,32Zm285,0h11v2H984V32Zm123,0h4v1h-4V32Zm10,0h4v1h-4V32Zm-116,1h2v1C1000.99,33.426,1002.13,34.12,1001,33Zm8,0h29c4.8,0,10.58-.279,13,2-10,.163-23.76,1.343-32-1C1015.48,33,1011.24,35,1009,33Zm142,0h2v1C1150.99,33.426,1152.13,34.12,1151,33Zm221,2V33h5c0.33,0.667.67,1.333,1,2,1,0.314,5.04-1.751,8-1v1l11-1v1h5v1l5,1V36c1.69,0.3,4.2,3.711,8,2,0.67-1,1.33-2,2-3h2v1h10v1c4.12,1.21,13.82-.184,17-1V35c3.12-.559,3.84,2.593,8,2,1.88-.268,5.53-2.562,8-2v1l13,1V36h2V35h13v1l3-1v1l11-2v1h25V34c0.67,0.333,1.33.667,2,1V34l3-1c2.94,0.358,5.07,4.3,10,3V35c0.67,0.333,1.33.667,2,1,0.77-.284.39-2.165,2-2v1c5.11,0.8,22.47-.623,23-1,1.33,0.667,2.67,1.333,4,2,0.33-.667.67-1.333,1-2,2.03,0.133,1.49,1.916,2,2h1V35h7v1c2.36,0.591,2.68-1.132,4-1a1.7,1.7,0,0,0,2,1V35h3V34h1v1l4,1V195H0V79c6.848-1.12,15.864-2.209,22-4H35l2-3h6c2.017-.607,6.667-1.555,9-2,1.382-2.612,1.769-1.693,2-6-4.758-.29-7.394-3.6-13-2v1H36v1l-9,1v1H13V65L0,66V63H1V62H7c2.889,2.568,6.459-.833,11,0v1l4-1V61l20-2V55h1V54a104.323,104.323,0,0,0,19-4l29-1V48h4V47c4.749-1.451,14.266.222,19-1l10,1c2.3-.72,5.116-4.217,9-3v1h2v1l15,1V46h4V45h1v1h6v1h11v1h2v1c2.323,0.751,2.667-1.029,4-1v1l33,1,10-1v1l7,1v1c1.713,0.364,1.716-.934,2-1h16a160.339,160.339,0,0,1,31,3v2l-18,1V56H237V55h-4V54h-2V53l-12,1-27-1v1h-1c-2.663.316-3-1-3-1H176V52H163v1c-1.716.3-1.689-.924-2-1h-5V51h-1v1h-4v1c-1.653.388-7.865-1.692-9-2-3.752-1.018-6.059,1.384-8,2h-7v1h1c0.861,0.959,8.291,4.353,10,4V57l6-1,1,2h7l1-2,7-1v1h2l1,2h2l1,2c4.676,1.9,7.427-3.786,13-2v1l4,1,1-2,15-1v1h3v1h6V58h21v1h3v1l11-1v1h2v1c2.957,0.528,2.133-1.663,3-2h37c-0.871-3.428-2.068-2.575-3-6l3-1V51h6c1.72,2.135,2.033.566,3,4h1v1h-1v1h-3v1h2c2.105,3.1,9.88,2.155,15,2V59h7V58c2.289-.7,4-0.256,6-1V56h-6c-2.305,2.2-7.426.547-10,0V55c1.719-1.127,1.355-.633,2-3h5V51a29.368,29.368,0,0,1-7-1V48h24v1h1v3c4.169,0.715,4.189.248,9,0V49c6.6-.024,14.973-0.153,20,1l7-1v1c3.465,1.091,3.047.308,6,0v2h9v1c2.32,1.889.2,1.834,4,3,0.945-1.8,1.385-1.575,2-4h12v1h4v1l7-1c3.044,0.006,3.407.739,5,1h24v1h18v1l17,1c-0.113,3.16-1.955,1.874,1,3v2c2.492-.776,7.071-0.06,12,0V57l6-1c2.166,0.564,4.282,2.383,7,3v2h-2v2c2.393,0.607,1.858.318,3,2a9.074,9.074,0,0,0,2-2c3.976-1.776,10.133,1.546,13-1l-5-1V60l-3,1V60h1V59h2V58h4v1h15l41,1,1,2h2v1h2v1h8l1,2h2c1.429-2.193,3.607-2.685,5-5l-3-1V59h-3V58h-4V57c-2.436-2.09-1.651-3.367-6-4-1.219,1.083-15.009,1.883-18,1V53h-5V52h-5V51h-2V50c-1.709-.408-1.732.939-2,1h-9v1h-5V51l-10,1V47a40.875,40.875,0,0,1,14,2l24-1v1l28,1v1l5-1,16,1,8-1v1l11,1,1-3c5.527,2.051,15.778,1.051,23,1V48c-3.268.114-8.3,0.569-10-1l14-1c0.936-3.586,2.61-3.156,7-3v2c2.158,0.587,2.387.246,1,2,3.167,1.53,2.932-1.447,4,3,6.249,0.018,14.335.329,19-1,2.3-.654,6.95,1.009,7,1V49h2V48l9,1c2.127-.087,2-1,2-1h15c0.723-2.762.279-2.237,3-3v2h1V46c0.86-1.889,1,2,1,2l8-1v1h11V47l19-1v1c1.4,0.207-.374-0.666,1-1l10,1a2.669,2.669,0,0,1,3-1v1h14c4.476-.387,13.12-2.555,19-1v1l7-1v1l10-1,1,2,5-1h26c1.522,0.429,8.867,2.524,11,2V48h11c6.73,1.879,15.315.609,22,0V46c-3.313-1.4-5.531-2.573-6-7l4-1V37l8,3,8-2v1l3,1v1l5-1V39h7c1.077,4.084,3.138,3.216,8,3V40h4l-1,2c0.784,1.295,5.643,1.111,8,1,1.539,1.357,9.831,1.9,13,1,11.236-3.179,27.347.067,39,0v2l-5,1v2c4.23,0.142,8,.068,10-2-1.72-1.127-1.36-.633-2-3,2.65-1,6.94-1.041,11-1V42h1v1h14l5-1c0.33,0.667.67,1.333,1,2l8,1V44h5V43c2.69-.582,5.09,1.657,7,2,8.68,1.553,19.72-1.026,27,1l29-1V43a143.639,143.639,0,0,1,31,3c-0.33-.667-0.67-1.333-1-2h1c1.13-1.233-.03-0.415,2-1v3h5c-0.33-1-.67-2-1-3,2.18-.422,2.85-0.664,4-2h2c0.33,0.667.67,1.333,1,2l5-1v1h-1v1h-3v1h6a7.534,7.534,0,0,1,2-3c2.43,3.487,6.07,1.787,11,3v1l3-1V44h2V43l7,1V43h3V42c4.4-1.323,6.3,1.638,10,2V42h5V40c7.95,1.362,17.2.693,25,0h8V39l3-1v2l13,1V40l3-1V38c1.96-.461,1.31.561,2,1,1.14,1.139.4,0,1,2a60.045,60.045,0,0,0,8-2V38c3.98-1,3,2.921,7,2V39l5-1c0.33,0.667.67,1.333,1,2h1V39h5V38h2V37l10,1V37l4-1V35c1.16-.231,6.7,3.251,11,2V36h4V35h1v1h10c3.05-.868,11.38-3.107,16-2v1c0.67-.333,1.33-0.667,2-1v1h4c1.53-.417,6.04-3.184,9-2,0.33,0.667.67,1.333,1,2h8Zm-318-1h3v1h-3V34Zm10,0h3v1h-3V34Zm12,0v2a7.5,7.5,0,0,1-3-2h3Zm2,0,3,1v1c-2.12-.092-3.17.494-4-1h1V34Zm21,1v2h-10c-0.33-.667-0.67-1.333-1-2h1V34c4.48,1.57,4.62-.784,8,0v1h2Zm5-1c0.67,0.333,1.33.667,2,1v1h-4V35C1104.01,34.426,1102.87,35.12,1104,34Zm15,0h4v1h-1v1h-4C1118.33,35.333,1118.67,34.667,1119,34Zm14,0h3v1h-3V34Zm25,0h2v1C1157.99,34.426,1159.13,35.12,1158,34Zm-75,1h2v1C1082.99,35.426,1084.13,36.12,1083,35ZM242,37h2v1C241.99,37.426,243.135,38.12,242,37Zm86,1h4v1h-4V38Zm9,0h4v1h-4V38Zm25,0h2v2h-2V38Zm320,0h4v1h-4V38Zm48,0v4c-2.465,1.093-4,2.339-7,3V44h1c1.256-1.909,1.014-1.659,4-2l-1-3h1C729.139,37.861,728,38.6,730,38Zm19,0v2h-3V39h1C748.139,37.861,747,38.6,749,38ZM368,39h3v2h-3V39Zm6,0h2v1C373.99,39.426,375.135,40.12,374,39Zm282,0h3v1h-3V39Zm15,0h3v1h-3V39Zm28,0h2v2h-2V39Zm63,0h2v1C761.99,39.426,763.135,40.12,762,39Zm28,0h3v2h-3V39Zm148,0h2v1C937.99,39.426,939.135,40.12,938,39ZM387,40h2v1C386.99,40.426,388.135,41.12,387,40Zm6,0h3v1h-3V40Zm253,0v2c-1.135-.844-0.145.127-1-1-1.135-.844-0.145.127-1-1h2ZM411,41l4,1v1h-4V41Zm55,1,1,3,23,1v1h13V46l5,1v2l-15-1v2h-4V49l-22,1c-0.575-2.987-1.879-4.587-3-7-4.22.116-5.233,0.577-7-2h2v1h7Zm704,1V41l3,1v1h-3Zm3-2h4v1h-4V41ZM423,42l4,1v1h-4V42Zm53,0a11.885,11.885,0,0,1,5,1v1h-5V42Zm158,0c3.1-.11,7.411-0.47,9,1l-10,1Zm40,0h2v1C673.99,42.426,675.135,43.12,674,42Zm21,0h2c-0.28.552-1,3.3-2,3C693.563,42.916,694.819,44.06,695,42Zm8,0h2v1h-2V42ZM493,43v2h-3V44c-1.135-.844-0.145.127-1-1h4Zm9,0,5,1v1h-5V43Zm165,0h4v2h-4V43ZM440,47c0.826-3.2,1.179-2.9,5-3v1h8c0.29-.068.3-1.309,2-1v1h6v1h-2C456.24,48.5,444.8,47.1,440,47Zm246-3h4v1h-4V44Zm17,0h3v1h-3V44Zm24,0h3v1h-3V44ZM543,45h2v1C542.99,45.426,544.135,46.12,543,45Zm20,2h-2V46h1V45C563.139,46.139,562.4,45,563,47Zm148-2h2v1h-1v1C710.861,45.861,711.6,47,711,45Zm184,0h5v1l-6,1ZM684,46h3v1h-3V46ZM286,47h4v1h-1v1h-4Zm225,0h5v1h-2v1C511.607,48.393,512.142,48.683,511,47Zm75,0h4v1h-4V47Zm81,0h3v1h-3V47Zm8,0h3v1h-3V47ZM278,48h3v1h-3V48Zm0,3h2v1h-1v1C277.861,51.861,278.6,53,278,51Zm274,3h2v1h-1v1C551.861,54.861,552.6,56,552,54ZM298,55h2v1C297.99,55.426,299.135,56.12,298,55Z"/></svg>';

		} else if ( $style==='cz_sep_48' ) {
			$out .= '<svg ' . $svg_attrs . ' fill="' . $top_color . '" viewBox="0 0 1600 160"><path d="M333,20l9-1v1c-1.139,1.139-.4,0-1,2l-2-1v1l-5,1V22C332.861,20.861,333.6,22,333,20Zm-68,0h2v1C264.99,20.426,266.135,21.12,265,20Zm8,0,6,1v1c-3.1,1.643-1.8,1.9-7,2Q272.5,22,273,20Zm-10,1h2v1C262.99,21.426,264.135,22.12,263,21Zm43,3v2l6,1v1h-1v1h-8c-1.139-1.139,0-.4-2-1,1.01-3.129.557-1.518,0-5h-8V22h1V21h3c2.05,1.885,9.224,1.1,13,1v1h-1v1h-3Zm-46-2h3v1h-2c-0.73.474-.011,1.392-2,1Zm118,0v2c-3.138.048-8.5-.565-10-2h10Zm28,0v2c-5.937.177-15.034,1.018-18-2h18Zm35,3h8q-0.5,2.5-1,5h2v3l15,1,1,2h18v1c2.719,0.547,4.4-1.148,6-2V34l6,1q-0.5,3-1,6h13v1h2v1h5l1-2,7,2c0.671,1.893,1.289,6.118,2,8,1.889,0.831,5,2.4,7,3h20v1l7,1V55l4-1v1h5V54h1v1h8V54c-1.975-1.129-2.338-1.417-3-4l-4-1v1l-9,1V50h-3V49h-1v1H545V49l-12-1V46h-3V45c1.719-1.127,1.355-.633,2-3h3v3c2.333,0.4,5.561,1.943,9,1V45h8v1h4v1h1V46h8c2.487-4.155,6.738-4.358,8-10l-5-1-1-2h2c3.694,3.591,4.09-.421,9,1v1h3v1l4-1c-0.083,2.971.011,4.984-1,7l-2,1v1h1c3.922,4.2,11.712-.247,18,2v1h2v1h3v1c5.15,1.671,8.465-2.556,10,1h-5c-0.88,3.4-1.861,3.065-6,3V51a12.7,12.7,0,0,0-5-1v3a29.368,29.368,0,0,0-7,1l1,3,14,1a25.313,25.313,0,0,0,5-1v1h3v1c1.94,0.4,3.789-1.883,7-1v1l13,1V58c3.577,0.063,5.714-.128,8-1V56l-5-1V54c-4.24-1.526-7.016,1.066-10-2,1.139-1.139.4,0,1-2h-8V49l15,1V49h13V48l6-1v2l4-1,1,3h5v1h-3v4c2.533-.505,5.063-2.78,7-3,1.6-.182,1.247,1.736,2,2,2.8,0.981,5.051-.647,7-1l1-3c4.465,0.373,2.915.628,7,0-0.36,2.022-1.432,2.944,0,4h-4v1c1.135,0.844.145-.127,1,1a10.6,10.6,0,0,0,4,1v2l8-1,1,2h2v1h8V59l8-1v1h2l1,2h9v1c4.4,1.239,12.371.419,15,0V61a3.983,3.983,0,0,1-2-2h6c1.392,1.14,5.988.69,9,0V58c3.224-.468,3.834,3.133,8,2V59l6-1v1h4v1h2v1h16v1h10v1c3.08,0.817,5.362-1.476,7-1l1,2h6V62c5.272-.273,15.725-2.758,22-1v1h5l12-1,1,2a10.88,10.88,0,0,0,7,3c1.139-1.139,0-.4,2-1V64h-3V62l15-1v1h10c3.061,0.9,8.609,3.347,14,2V63a2.657,2.657,0,0,1,3,1c4.524,1.1,8.372-.942,11-2l1-2c-1.135-.844-0.145.127-1-1l-8-1q1-3.5,2-7l10-1v1l9,1v1l32,1v2l16,1v1h-1c-0.233,1.59,1.616,1.242,2,2,1.813,3.58-1.626,3.413,4,4l3-5,6,1,1-2h6V57l9,1a100.135,100.135,0,0,1,25-3v2a105.972,105.972,0,0,0,17,1v2a11.352,11.352,0,0,0,2-2l17,1V58h3V57c3.14-.787,3.9,1.769,6,2V58l26,1v1c4.82,1.292,5.48-1.914,10-2h5v1h22v1h-1c-3.2,2.784-11.41-.178-16,0h-3v1h-14l-7-1c-0.33.667-.67,1.333-1,2l-6,1V62c1.14-1.139.4,0,1-2a40.117,40.117,0,0,0-9,1c-0.33,2-.67,4-1,6l3,1c3.13,2.854,25.01,2.763,32,2,2.93-.32,4.02-1.757,6-3V66l23,2c5.34,0.173,8.26-1.859,12-3h7V64h4v1c4.09,1.066,9.19-.477,12-1,1.43-3.826.63-3.158,8-3v1h5c0.33,0.667.67,1.333,1,2l6-1c0.67-1,1.33-2,2-3l4,1V60h4V58c-4.22.139-11.01-.111-13-2h2c3.9-3.759,16.04,1.162,19,2l8-1c0.33,1,.67,2,1,3h-3v2c2.66,1.412,4.22,2.03,9,2v1c1.61,0.894,7-1,7-1,0.33,0.667.67,1.333,1,2,1.47,0.767,6.4,2.038,9,1,0.33-.667.67-1.333,1-2h1c2.08-.276,2,1,2,1l10-1c0.33,0.667.67,1.333,1,2h21v1l12,1V68l12-1c2.02,0.176,2,1,2,1h5v1l9-1v1l4,1v1c2.87,0.571,2.36-1.808,3-2a1.549,1.549,0,0,1,2,1l5-1v2l4-1c0.33-.667.67-1.333,1-2l22-1V66h-3V64l5,1v2a12.66,12.66,0,0,0,5,1V67h3V65l6,3v1c2.76,0.74,2.28-1.723,3-2h12V66h5V65l13,1v1a2.583,2.583,0,0,0,3-1h16V65l5-1v2l37-2v1l3,1h11a2.614,2.614,0,0,1,3-1v1h4v1h7v1h-8v1h-1c-0.99,0-3.79-2.2-6-1-0.33.667-.67,1.333-1,2h-1c-0.23,1.4.37-.268,1,1,3.2,2.78,10.43-.685,15,1v1h2v1l3,1V73l31,1v1h8V74c3.92-1.1,12.43,1.283,15,2l9-1v1h7v1l5-1c0.33,0.667.67,1.333,1,2h15v7l-20-1V83h-11l-14-1V81h-13V80h-6V79h-15v1l-9-1c-0.33,1-.67,2-1,3l4,1v1h12v1h10v1c2.18,0.356,3.93-2.224,7-1,0.33,0.667.67,1.333,1,2l28-1c0.67,1,1.33,2,2,3l11-1c0.68,2.927,1.11,3.275,4,4,0.88-3.4,1.86-3.065,6-3v1h4V195H0V56c6.264-.162,9.232-2.562,14-4l19-1V50c2.893-.836,8.879-0.905,10,0-3.537.286-4.884,1.349-8,2v2l-6,1v1c-4.671,1.436-7.876-1.19-11,2l16-1v2l-4,1v3H27l1,2c-0.36.566-14.554,1.781-16,2,0.844,1.135-.127.145,1,1,2.786,3.1,6.729,1.543,12,2,1.848,0.16,2,1,2,1l15-1,45-1,1-2,8,1V67h5v1c3.858,1.033,8.376-.514,11-1V62a13.307,13.307,0,0,0,4-3h8v2l20,1V61c2.509-.907,4.5-0.283,6-2l1-3,10-1c6.236-2.012,12.925-4.594,20-6V47l28-5h14V41h17V40h11l20-1V38l11,1V38h3V37l6-2V34h11V33h3V32c2.225-.659,17.162,2.252,19,3v1h2v1c1.682,0.383,1.767-.951,2-1h15c1.382-2.612,1.769-1.693,2-6-0.223-.105-3.626-1.439-3-2h1c4.8-4.753,15.039.813,20,2v4h10c0.249,0.054.311,1.362,2,1V34h2V33l15,1v1h3v1c3.564,1.13,8.383.022,11,1v1h-5c-2.7-2.515-10.481-.273-14,0l1,3,5-1,1,2,11,2V43l6-1q-0.5-2.5-1-5c1.135-.844.145,0.127,1-1h13V35c3.394-2.495,1.052-3.507,7-4,1.653,1.6,5.613,1.99,9,2l4-5h-1c-1.139-1.139,0-.4-2-1V25ZM289,27h2v1C288.99,27.426,290.135,28.12,289,27Zm177,0h3v1h-3V27Zm26,1h4l1,2h1v1h-1v1h-5c-1.139-1.139,0-.4-2-1l-1-2h3V28Zm28,2v2c-3.619.431-12.181,1.593-15-1Zm4,1h3v1h-1c-1.139,1.139,0,.4-2,1V31Zm23,1,10,1c-1.107,4.237-3.875,4.177-5,9-1.882-.275-1.963-0.988-2-1h-5V40c3.686-.326,4.21-0.818,5-4C548.025,34.871,547.662,34.583,547,32Zm57,7h-3l-2-3h4v1C604.139,38.139,603.4,37,604,39Zm20-3h2v1C623.99,36.426,625.135,37.12,624,36Zm18,5,9,2q-0.5-2.5-1-5a15.68,15.68,0,0,1,6-1v1l5,1v1h-1c-4.27,6.684-12.423,3.763-20,5V44C641.719,42.873,641.355,43.368,642,41Zm126-3h2v1C767.99,38.426,769.135,39.12,768,38Zm44,0h6v1h-2v1h-5ZM120,39h2v1C119.99,39.426,121.135,40.12,120,39Zm566,0h4v1h-4V39Zm25,0h4v1h-4V39Zm23,0v2c-2.393-.607-1.858-0.318-3-2h3Zm198,2c4.652,0.037,5.545,1.217,9,2q0.5,2.5,1,5l-6,1v1l-4-1V41ZM610,42h2v1C609.99,42.426,611.135,43.12,610,42Zm129,0q0.5,2,1,4H729V44C734.707,44.063,735.29,42.956,739,42Zm118,4V42h1v1h9v1c2.31,0.7,3.994.252,6,1v1h-3C868.839,45.041,861.093,45.882,857,46ZM719,43h2v3h-1V45C718.861,43.861,719.6,45,719,43Zm85,0a14.823,14.823,0,0,1,6,1c-1.139,1.139,0,.4-2,1v1h-4V43Zm-30,1h1v1h10v1c-2.312.3-9.676,1.363-12,0Zm241,0h4v1h-4V44ZM900,45c2.694-.049,15.922,1.015,17,2l-10,1s0.094-1.211-2-1v1l-6-1Zm55,0h2v1h-1v1C954.861,45.861,955.6,47,955,45Zm54,0v2h-3V46h1C1008.14,44.861,1007,45.6,1009,45Zm53,3c-5.74.111-7.33,0.818-12,0V46a76.022,76.022,0,0,1,13-1v1C1061.86,47.139,1062.6,46,1062,48Zm8-3h3v1h-3V45Zm77,1c-1.08,4.573-4.35,4.249-10,4V49h1C1139.77,45.877,1141.94,45.851,1147,46ZM58,47h3v1H58V47Zm1114,0,8,1v1c-2.52,1.339-3.5,1.99-8,2V47Zm-168,1,3,1v1h3v1h-1v1h-6V51h-4V50h2V49h3V48ZM768,49c4.557,0.462,5.621.8,10,0-1.434,6.056-4.632,2.552-10,2V49Zm29,0,10,1v1h1l-1,2h9V52a3.983,3.983,0,0,1-2-2h2v1h8l1,2,5,1,1,2h4v1l-20,1V57c1.139-1.139.4,0,1-2h-9v1h-2v1l-11-1-1-3h2v1c0.6,0.021,1.85-.888,4-1V51h-2V49Zm54,3V50c0.865-.1,6.292-1.071,8,0v1Zm351,0c1.12-4.218,3.99-3.24,9-3v1c3.04,1.679,7.69-1.018,10,1ZM666,50h5v1h-5V50Zm36,0,5,1v1h-3C703.229,50.984,702.771,51.016,702,50Zm47,0h9c0.254,0.056.331,1.27,2,1V50h3v1h-2c-0.8,3.506-1.772,3.9-6,4v1h-5V55l-11,1-1-3c5.062,0.282,6.348.843,11,0V50Zm36,0h3v1h-3V50Zm80,0a9.746,9.746,0,0,1,4,1v1C865.991,51.7,866.277,51.865,865,50ZM700,51v2c-1.135-.844-0.145.127-1-1-1.135-.844-0.145.127-1-1h2Zm13,0c3.553-.072,6.785.157,9,1v1h-1v1h-3v1h-2v1h-7v1h-1V56h-4V54l9-1V51Zm12,0h2v1C724.99,51.426,726.135,52.12,725,51Zm295,0h2v1C1019.99,51.426,1021.13,52.12,1020,51Zm4,0h3v1h-3V51Zm7,0h2v1C1030.99,51.426,1032.13,52.12,1031,51Zm10,0h3v1h-3V51Zm8,2h-5V52h4s0.03-2.031,1-1v2Zm6-2h2c-0.84,1.135.13,0.145-1,1v1C1054.86,51.861,1055.6,53,1055,51ZM690,52v2c-1.135-.844-0.145.127-1-1-1.135-.844-0.145.127-1-1h2Zm367,0h3v1h-3V52Zm9,0h2v1C1065.99,52.426,1067.13,53.12,1066,52Zm4,0h2v1C1069.99,52.426,1071.13,53.12,1070,52Zm10,0h2v1C1079.99,52.426,1081.13,53.12,1080,52Zm48,0,6,1v1h-8V53C1128.01,52.426,1126.87,53.12,1128,52Zm-17,1h6v1h-6V53ZM852,55v2c-2.755.053-14.878-.973-16-2C842.784,54.955,847.071,54.143,852,55Zm308-1,3,1v1C1160.61,55.393,1161.14,55.683,1160,54Zm7,0h3v1h-3V54Zm125,0v2l-11,1c-0.33-.667-0.67-1.333-1-2h2C1283.7,53.417,1288.73,53.9,1292,54ZM875,55l9,1V55h4v3l-6-1-1,2-8,1V57h2V55ZM40,56h4v1H43v1H39Zm7,0h5v1H47V56Zm1137,0h2v1C1183.99,56.426,1185.13,57.12,1184,56ZM897,57h2v1C896.99,57.426,898.135,58.12,897,57Zm85,0h2v1C981.99,57.426,983.135,58.12,982,57Zm377,1c3.58-.063,5.71.128,8,1-0.77,1.016-1.23.984-2,2-2.22-.292-1.58-0.841-2-1h-5C1358.33,59.333,1358.67,58.667,1359,58Zm-101,1,4,1v1h-4V59ZM47,65c4.294-.088,9.149-0.079,12,1v1H49V66H44V65H42V64c-3.917-1.242-5.477,1.339-8,2V64c2.871-.672,2.524-1.8,3-2h9c2.374-.716,4.4-1.8,8-2v1H53v1H48v1C46.861,64.139,47.6,63,47,65Zm1099-5h5v1h-5V60Zm129,1h2v1C1274.99,61.426,1276.13,62.12,1275,61Zm7,0h3v1h-3V61Zm12,0h3v1c4.24,1.214,9.8-.206,13,1v1h-8V63h-8V61ZM74,66V63c5.361-1.138,14.557-1.544,18,2L82,66v1Zm24-4v2H92V63h2V62h4Zm1426,5v2c-1.8-.253-2-1-2-1h-6c-0.33-.667-0.67-1.333-1-2h2v1h7Z"/></svg>';

		} else if ( $style==='cz_sep_49' ) {
			$out .= '<svg ' . $svg_attrs . ' fill="' . $top_color . '" viewBox="0 0 1920 280"><path opacity="0.1" d="M-54,130.744s109,77.629,301,83.138S571,79.659,571,79.659,758.5-81.607,979,60.628s409,104.172,478,67.111,227.22-120.572,400-20.033c120.5,70.116,181,155.257,181,155.257l-2092,2V130.744Z"/><path d="M-90,167.806s109,77.628,301,83.137S535,116.721,535,116.721,695.5-70.589,943,97.689c217,147.54,409,104.173,478,67.111s227-120.2,400-20.032S1982.5,285,1982.5,285L-90,284V167.806Z"/></svg>';

		} else if ( $style==='cz_sep_50' ) {
			$out .= '<svg ' . $svg_attrs . ' fill="' . $top_color . '" viewBox="0 0 1920 120"><path class="cls-1" d="M1920,120H0V44.429C33.7,60.964,110.574,81.343,248.1,33c200.658-70.537,275,10,378.62,32s121.557,32,266.03-34,220.2,30,272.005,51,109.11,32.5,228.17-37c121.75-71.064,142.98,7.518,246.1,13,114.67,6.1,124.41-19.985,184.08-38.527,30.03-9.333,65.62-17.406,96.9-19.464V120Z"/></svg>';

		} else if ($style==='cz_sep_7' || $style==='cz_sep_8') {
			$top_color = $top_color ? ' style="fill: ' . $top_color . ';stroke:' . $top_color .';stroke-width:2"' : '';
			$sep_height = $atts['sep_height'] ?  $atts['sep_height'] : '100';
			$out .= '<svg ' . $svg_attrs . ' version="1.1" '. $bottom_color .' width="100%" height="'.$sep_height.'" viewBox="0 0 100 105" preserveAspectRatio="none" ><path d="M0 0 L50 100 L100 0 Z" '.$top_color.'/></svg>';
		} else if ($style==='cz_sep_9' || $style==='cz_sep_10') {
			$top_color = $top_color ? ' style="fill: ' . $top_color . ';stroke:' . $top_color .';"' : '';
			$sep_height = $atts['sep_height'] ? $atts['sep_height'] : '100';
			$out .= '<svg ' . $svg_attrs . ' version="1.1" '. $bottom_color .' width="100%" height="'.$sep_height.'" viewBox="0 0 100 100" preserveAspectRatio="none" ><path d="M0 100 C30 0 70 0 100 100 Z" '.$top_color.'/></svg>';

		} else if ($style==='cz_sep_23' || $style==='cz_sep_24') {
			$top_color = $top_color ? ' style="fill: ' . $top_color . ';stroke:' . $top_color .';"' : '';
			$sep_height = $atts['sep_height'] ? $atts['sep_height'] : '100';
			$out .= '<svg ' . $svg_attrs . ' version="1.1" '. $bottom_color .' width="100%" height="'.$sep_height.'" viewBox="0 0 100 100" preserveAspectRatio="none" ><path d="M-5 100 Q 0 20 5 100 Z M0 100 Q 5 0 10 100 M5 100 Q 10 30 15 100 M10 100 Q 15 10 20 100 M15 100 Q 20 30 25 100 M20 100 Q 25 -10 30 100 M25 100 Q 30 10 35 100 M30 100 Q 35 30 40 100 M35 100 Q 40 10 45 100 M40 100 Q 45 50 50 100 M45 100 Q 50 20 55 100 M50 100 Q 55 40 60 100 M55 100 Q 60 60 65 100 M60 100 Q 65 50 70 100 M65 100 Q 70 20 75 100 M70 100 Q 75 45 80 100 M75 100 Q 80 30 85 100 M80 100 Q 85 20 90 100 M85 100 Q 90 50 95 100 M90 100 Q 95 25 100 100 M95 100 Q 100 15 105 100 Z" '.$top_color.'/></svg>';

		} else {

			$after_color ='';
			if ($style==='cz_sep_11' || $style==='cz_sep_12' || $style==='cz_sep_15' || $style==='cz_sep_16') {
				$after_color ='data-after-color=":after{background:'.$top_color.';}"';
			}

			if ($style==='cz_sep_21' || $style==='cz_sep_22') {
				$bottom_color = $atts['bottom_color'] ? $atts['bottom_color'] : '#FFFFFF';
				$top_color = $atts['top_color'] ? $atts['top_color'] : '#CCCCCC';
				$sep_height = $atts['sep_height'] ? $atts['sep_height'] : '130px';
				$sep_width = $atts['sep_width'] ? $atts['sep_width'] : '50px';
				$after_color ='data-after-color=":before{background-image: linear-gradient(to right, '.$top_color.' 50%, '.$bottom_color.' 50%);background-size: '.$sep_width.' 100%;height: '.$sep_height.';}" style="height:'.$sep_height.'"';

				if ($style==='cz_sep_22'){$after_color =str_replace('to right', '40deg', $after_color);}
			}

			if ($style==='cz_sep_13' || $style==='cz_sep_14') {
				$sep_height = $atts['sep_height'] ? $atts['sep_height'] : '50px';
				$sep_inline_style = self::separator_style( $atts, array(
					'extra'		=> 'background-image: linear-gradient(315deg, '.$top_color.' 24%, transparent 24%), linear-gradient(45deg, '.$top_color.' 24%, transparent 24%);background-size: '.$sep_height.' 100%;height: '.$sep_height.';' ));
				$style .= ' cz_separator2';
				$out .= '<div' . $sep_inline_style .'></div>';

			} else {
				if ($style==='cz_sep_17' || $style==='cz_sep_18') {
					$after_color ='data-after-color=":after{background:'.$top_color.';}'.$css_id.':before{box-shadow: -50px 50px 0 '.$top_color.', 50px -50px 0 '.$top_color.';}"';
				}
				if ($style==='cz_sep_19' || $style==='cz_sep_20'){
					$bottom_color = $atts['bottom_color'] ? $atts['bottom_color'] : '#CCCCCC';
					$top_color = $atts['top_color'] ? $atts['top_color'] : '#FFFFFF';
					$sep_height = $atts['sep_height'] ? $atts['sep_height'] : '50px';
					$after_color ='data-after-color=":after{background-size: '.$sep_height.' 100%;height: '.$sep_height.';}'.$css_id.':before{background-image: linear-gradient(15deg, '.$bottom_color.' 50%, '.$top_color.' 50%); background-size: '.$sep_height.' 100%;height: '.$sep_height.';top:0;}" style="height:'.$sep_height.'"';
					if ($style==='cz_sep_20'){$after_color =str_replace('15deg', '165deg', $after_color);}
				}
				
				// Parent ID
				$style .= ' cz_separator';
				$out .= '<div'. $bottom_color . ' data-before-color=":before{background:'.$top_color.';}" '.$after_color.'></div>';
			}
		}

		// Inner
		$out .= '</div>';

		// Parent ID
		if ($style==='cz_sep_25' || $style==='cz_sep_26' || $style==='cz_sep_27' || $style==='cz_sep_28' || $style==='cz_sep_29' || $style==='cz_sep_30'){
			$out = '<div id="'.$atts['id'].'" class="cz_separator3 '. $style . '" ' . self::separator_style( $atts ) .'>'.$out.'</div>';
		} else {
			$out = '<div id="' . $atts['id'] . '" class="'. $style . ' '. $atts['class'] . '" ' . self::separator_style( $atts ) .'>' . $out . '</div>';
		}

		// Output
		return Codevz_Plus::_out( $atts, $out, 'separator', $this->name );
	}

	/**
	 *
	 * TEMP: Generate style in tag mode or inline
	 * 
	 */
	public static function separator_style( $a = array(), $s = array() ) {
		if ( empty( $a ) ) {
			return;
		}

		// Prepare
		$a = array_filter( (array) $a );
		$s = wp_parse_args( $s, array(
			'prefix' 	=> 'css_',
			'important' => ';',
			'before' 	=> ' style="',
			'after' 	=> '"',
			'extra' 	=> ''
		));

		$prefix = $s['prefix'];
		$out = array();

		// Start split styles with their values
		foreach ( $a as $key => $val ) {
			if ( ! empty( $val ) && strpos( $key, $prefix ) === 0 ) {

				// Define key
				$key = str_replace( $prefix, '', $key );

				// Continue to next, if its VC CSS box value
				if ( Codevz_Plus::contains( $val, 'vc_custom' ) ) {
					continue;
				}

				// Out
				$out[] = $key . ': ' . $val . $s['important'];
			}
		}

		// Output plus extra styles
		$out = empty( $out ) ? '' : implode( '', $out );
		$out .= str_replace( ' !important', '', $s['extra'] );

		return $out ? $s['before'] . $out . $s['after'] : '';
	}

}