<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_hotspot extends Widget_Base {

	protected $id = 'cz_hotspot';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Hotspot', 'codevz' );
	}

	public function get_icon() {
		return 'xtra-hotspot';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Hotspot', 'codevz' ),
			esc_html__( 'Tooltip', 'codevz' ),

		];

	}

	public function get_style_depends() {
		return [ $this->id, 'cz_parallax' ];
	}

	public function get_script_depends() {
		return [ $this->id, 'cz_parallax', 'cz_free_position_element', 'codevz-tooltip' ];
	}

	public function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_responsive_control(
			'css_top',
			[
				'label' => esc_html__( 'Top offset', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -20,
						'max' => 120,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_hotspot' => 'top: {{SIZE}}%;',
				],
			]
		);

		$this->add_responsive_control(
			'css_left',
			[
				'label' => esc_html__( 'Left offset', 'codevz' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => -20,
						'max' => 120,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_hotspot' => 'left: {{SIZE}}%;',
				],
			]
		);

		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Content Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  		=> esc_html__( 'Content', 'codevz' ),
					'template' 	=> esc_html__( 'Saved template', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'xtra_elementor_template',
			[
				'label' 	=> esc_html__( 'Select template', 'codevz' ),
				'type' 		=> Controls_Manager::SELECT,
				'options' 	=> Xtra_Elementor::get_templates(),
				'condition' => [
					'type!' => '',
				]
			]
		);

		$this->add_control(
			'content',
			[
				'label' => esc_html__('Content','codevz'),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Hello World ...',
				'placeholder' => 'Hello World ...',
				'condition' => [
					'type' => '',
				]
			]
		);

		$this->add_control(
			'model',
			[
				'label' => esc_html__( 'Choose', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'  => esc_html__( '~ Default ~', 'codevz' ),
					'cora' => esc_html__( 'Animated 1', 'codevz' ),
					'smaug' => esc_html__( 'Animated 2', 'codevz' ),
					'uldor' => esc_html__( 'Animated 3', 'codevz' ),
					'dori' => esc_html__( 'Animated 4', 'codevz' ),
					'gram' => esc_html__( 'Animated 5', 'codevz' ),
					'indis' => esc_html__( 'Animated 6', 'codevz' ),
					'narvi' => esc_html__( 'Animated 7', 'codevz' ),
					'amras' => esc_html__( 'Animated 8', 'codevz' ),
					'hador' => esc_html__( 'Animated 9', 'codevz' ),
					'malva' => esc_html__( 'Animated 10', 'codevz' ),
					'sadoc' => esc_html__( 'Animated 11', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'content_svg_background',
			[
				'label' => esc_html__( 'Background', 'codevz' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'model' => [
						'cora',
						'smaug',
						'uldor',
						'dori',
						'gram',
						'indis',
						'narvi',
						'amras',
						'hador',
						'malva',
						'sadoc',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_hotspot' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'position',
			[
				'label' => esc_html__( 'Position', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'cz_hotspot_top',
				'options' => [
					'cz_hotspot_top'  => esc_html__( 'Top Center', 'codevz' ),
					'cz_hotspot_top cz_hotspot_tr' => esc_html__( 'Top Right', 'codevz' ),
					'cz_hotspot_top cz_hotspot_tl' => esc_html__( 'Top Left', 'codevz' ),
					'cz_hotspot_bottom' => esc_html__( 'Bottom Center', 'codevz' ),
					'cz_hotspot_bottom cz_hotspot_br' => esc_html__( 'Bottom Right', 'codevz' ),
					'cz_hotspot_bottom cz_hotspot_bl' => esc_html__( 'Bottom Left', 'codevz' ),
					'cz_hotspot_right' => esc_html__( 'Right Center', 'codevz' ),
					'cz_hotspot_left' => esc_html__( 'Left Center', 'codevz' ),
				],
				'condition' => [
					'model' => 'default',
				]
			]
		);

		
		$this->add_control(
			'always_open',
			[
				'label' => esc_html__( 'Always open?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER,
				'condition' => [
					'model' => 'default',
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' )
			]
		);

		$this->add_control(
			'icon_type',
			[
				'label' => esc_html__( 'Icon Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'icon',
				'options' => [
					'icon'  => esc_html__( 'Icon', 'codevz' ),
					'number' => esc_html__( 'Number', 'codevz' ),
					'image' => esc_html__( 'Image', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
				'condition' => [
					'icon_type' => 'icon'
				]
			]
		);

		$this->add_control(
			'number',
			[
				'label' => esc_html__( 'Number', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'condition' => [
					'icon_type' => 'number'
				],
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA,
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image',
				'default' => 'large',
				'separator' => 'none',
				'condition' => [
					'icon_type' => 'image',
				],
			]
		);

		$this->add_control(
			'icon_transform',
			[
				'label' => esc_html__( 'Icon style', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'scale(1, 1)',
				'options' => [
					'scale(1, 1)'  => esc_html__( 'Standard', 'codevz' ),
					'scale(1.2, 1.2)' => esc_html__( 'Medium', 'codevz' ),
					'scale(1.4, 1.4)' => esc_html__( 'Large', 'codevz' ),
					'scale(1.8, 1.8)' => esc_html__( 'XLarge', 'codevz' ),

					'scale(1, 1);border-radius: 0' => esc_html__( 'Standard Square', 'codevz' ),
					'scale(1.2, 1.2);border-radius: 0' => esc_html__( 'Medium Square', 'codevz' ),
					'scale(1.4, 1.4);border-radius: 0' => esc_html__( 'Large Square', 'codevz' ),
					'scale(1.8, 1.8);border-radius: 0' => esc_html__( 'XLarge Squar', 'codevz' ),

					'scale(1, 1) rotate(45deg);border-radius: 0' => esc_html__( 'Standard Rotated', 'codevz' ),
					'scale(1.2, 1.2) rotate(45deg);border-radius: 0' => esc_html__( 'Medium Rotated', 'codevz' ),
					'scale(1.4, 1.4) rotate(45deg);border-radius: 0' => esc_html__( 'Large Rotated', 'codevz' ),
					'scale(1.8, 1.8) rotate(45deg);border-radius: 0' => esc_html__( 'XLarge Rotated', 'codevz' ),

					'scale(1, 1) rotate(45deg);border-radius: 8px' => esc_html__( 'Standard Rotated Radius', 'codevz' ),
					'scale(1.2, 1.2) rotate(45deg);border-radius: 8px' => esc_html__( 'Medium Rotated Radius', 'codevz' ),
					'scale(1.4, 1.4) rotate(45deg);border-radius: 8px' => esc_html__( 'Large Rotated Radius', 'codevz' ),
					'scale(1.8, 1.8) rotate(45deg);border-radius: 8px' => esc_html__( 'XLarge Rotated Radius', 'codevz' ),
				],
				'selectors' => [
					'{{WRAPPER}} .cz_hotspot_circle' => 'transform: {{VALUE}};'
				],
			]
		);

		$this->add_control(
			'icon_effect',
			[
				'label' => esc_html__( 'Icon effect', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					''  => esc_html__( '~ Default ~', 'codevz' ),
					'cz_hotspot_pulse' => esc_html__( 'Pulse', 'codevz' ),
					'cz_hotspot_waves' => esc_html__( 'Waves', 'codevz' ),
					'cz_hotspot_ripple' => esc_html__( 'Ripple', 'codevz' ),
					'cz_hotspot_bob' => esc_html__( 'Bob', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'icon_animation-duration',
			[
				'label' => esc_html__( 'Effect duration', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => '1s',
				'options' => [
					'0.4s'  => '0.4s',
					'0.6s' 	=> '0.6s',
					'0.8s' 	=> '0.8s',
					'1s' 	=> '1s',
					'1.2s' 	=> '1.2s',
					'1.4s' 	=> '1.4s',
					'1.6s' 	=> '1.6s',
					'1.8s' 	=> '1.8s',
					'2s' 	=> '2s',
				],
				'selectors' => [
					'{{WRAPPER}} .cz_hotspot_circle' => 'animation-duration: {{VALUE}};'
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_hotspot',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_icon',
			[
				'label' 	=> esc_html__( 'Icon', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_hotspot .cz_hotspot_circle' ),
			]
		);

		$this->add_responsive_control(
			'sk_content',
			[
				'label' 	=> esc_html__( 'Contnet', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'display', 'width', 'color', 'text-align', 'font-family', 'font-size', 'font-weight', 'line-height', 'letter-spacing', 'background', 'padding', 'margin', 'border', 'box-shadow', 'text-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_hotspot .cz_wpe_content' ),
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Icon
		$icon = '';
		if ( $settings['icon_type'] === 'number' ) {

			$icon = '<i class="cz_hotspot_number">' . $settings['number'] . '</i>';

		} else if ( $settings['icon_type'] === 'image' ) {

			$icon = Group_Control_Image_Size::get_attachment_image_html( $settings );

		} else if ( $settings['icon'] ) {

			ob_start();
			Icons_Manager::render_icon( $settings['icon'] );
			$icon = ob_get_clean();

		}

		$rotate_icon = '';
		if ( Codevz_Plus::contains( $settings[ 'icon_transform' ], '45' ) ) {
			$rotate_icon = ' xtra-hotspot-rotate-icon';
		}

		$classes = array();;
		$classes[] = 'cz_hotspot';
		$classes[] = $settings['always_open'] ? 'cz_hotspot_always_open' : '';

		if ( $settings[ 'type' ] === 'template' ) {
			$content = Codevz_Plus::get_page_as_element( $settings[ 'xtra_elementor_template' ] );
		} else {
			$content = do_shortcode( $settings[ 'content' ] );
		}

		if ( empty( $settings['model'] ) || $settings['model'] === 'default' ) {
			?><div<?php echo Codevz_Plus::classes( [], $classes ); ?>><div class="<?php echo $settings['icon_effect']; ?>"><div class="cz_hotspot_circle<?php echo $rotate_icon; ?>"><?php echo $icon; ?></div></div><div class="cz_hotspot_content cz_wpe_content <?php echo $settings['position']; ?>"><?php echo $content; ?></div></div><?php
		}else {

			$name = $settings['model'];
			$svg_fill = $settings['content_svg_background'] ? ' style="fill: ' . $settings['content_svg_background'] . '"' : '';
			$classes[] = 'tooltip tooltip--' . $name;
			?>
			<div data-type="<?php echo $name; ?>"<?php echo Codevz_Plus::classes( [], $classes ); ?>>
				<div class="tooltip__trigger" role="tooltip" aria-describedby="info-<?php echo $name; ?>">	<span class="tooltip__trigger-text"><div class="<?php echo $settings['icon_effect']; ?>"<?php echo $circle_parent; ?>><div class="cz_hotspot_circle"><?php echo $icon; ?></div></div></span>
				</div>
				<div class="tooltip__base">
					<svg class="tooltip__shape" width="100%" height="100%" viewBox="0 0 400 300" <?php echo $svg_fill; ?>><?php echo self::path( $settings['model'] ); ?></svg>
					<div class="tooltip__content cz_wpe_content id="info-<?php echo $name; ?>"><?php echo $content; ?></div>
				</div>
			</div>
			<?php
		}
	}

	public static function path( $name = '' ) {
		if ( $name === 'cora' ) {
			return '<path d="M 199,21.9 C 152,22.2 109,35.7 78.8,57.4 48,79.1 29,109 29,142 29,172 45.9,201 73.6,222 101,243 140,258 183,260 189,270 200,282 200,282 200,282 211,270 217,260 261,258 299,243 327,222 354,201 371,172 371,142 371,109 352,78.7 321,57 290,35.3 247,21.9 199,21.9 Z"/>';
		} else if ( $name === 'smaug' ) {
			return '<path d="M 314,100 C 313,100 312,100 311,100 L 89.5,100 C 55.9,100 29.1,121 29.1,150 29.1,178 53.1,201 89.5,201 L 184,201 200,223 217,201 311,201 C 344,201 371,178 371,150 371,122 346,99 314,100 Z"/>';
		} else if ( $name === 'uldor' ) {
			return '<path d="M 79.5,66 C 79.5,66 128,106 202,105 276,104 321,66 321,66 321,66 287,84 288,155 289,226 318,232 318,232 318,232 258,198 200,198 142,198 80.5,230 80.5,230 80.5,230 112,222 111,152 110,82 79.5,66 79.5,66 Z"/>';
		} else if ( $name === 'dori' ) {
			return '<path d="M 22,108 22,236 C 22,236 64,216 103,212 142,208 184,212 184,212 L 200,229 216,212 C 216,212 258,207 297,212 336,217 378,236 378,236 L 378,108 C 378,108 318,83.7 200,83.7 82,83.7 22,108 22,108 Z"/>';
		} else if ( $name === 'gram' ) {
			return '<path d="M 92.4,79 C 136,79 154,115 200,116 246,117 263,80.4 308,79 353,77.6 381,111 381,150 381,189 346,220 308,221 270,222 236,188 200,188 164,188 130,222 92.4,221 54.4,220 19,189 19,150 19,111 48.6,79 92.4,79 Z"/>';
		} else if ( $name === 'indis' ) {
			return '<path d="M 44.5,24 C 138,4.47 246,-6.47 356,24 367,26.9 376,32.9 376,44 L 376,256 C 376,267 367,279 356,276 231,240 168,241 44.5,276 33.8,279 24.5,267 24.5,256 L 24.5,44 C 24.5,32.9 33.6,26.3 44.5,24 Z"/>';
		} else if ( $name === 'narvi' ) {
			return '<path class="path-narvi" d="M 307,150 199,212 92.5,274 92.7,150 92.5,26.2 200,88.4 Z"/>';
		} else if ( $name === 'amras' ) {
			return '<path class="path-amras-2" d="M 293,106 A 90.1,90.1 0 0 1 203,197 90.1,90.1 0 0 1 112,106 90.1,90.1 0 0 1 203,16.2 90.1,90.1 0 0 1 293,106 Z"/>
					<path class="path-amras-3" d="M 324,66.2 A 46.9,46.9 0 0 1 277,113 46.9,46.9 0 0 1 230,66.2 46.9,46.9 0 0 1 277,19.3 46.9,46.9 0 0 1 324,66.2 Z"/>
					<path class="path-amras-1" d="M 180,111 A 67.2,67.2 0 0 1 112,178 67.2,67.2 0 0 1 45.9,111 67.2,67.2 0 0 1 112,43.5 67.2,67.2 0 0 1 180,111 Z"/>
					<path class="path-amras-4" d="M 371,98.6 A 52.7,52.7 0 0 1 318,152 52.7,52.7 0 0 1 266,98.6 52.7,52.7 0 0 1 318,45.9 52.7,52.7 0 0 1 371,98.6 Z"/>
					<path class="path-amras-9" d="M 375,167 A 66.8,55.1 0 0 1 308,222 66.8,55.1 0 0 1 241,167 66.8,55.1 0 0 1 308,112 66.8,55.1 0 0 1 375,167 Z"/>
					<path class="path-amras-5" d="M 187,199 A 52,52 0 0 1 136,251 52,52 0 0 1 84.1,199 52,52 0 0 1 136,147 52,52 0 0 1 187,199 Z"/>
					<path class="path-amras-6" d="M 287,217 A 66.8,66.8 0 0 1 221,284 66.8,66.8 0 0 1 154,217 66.8,66.8 0 0 1 221,150 66.8,66.8 0 0 1 287,217 Z"/>
					<path class="path-amras-7" d="M 132,168 A 53.9,53.9 0 0 1 78.7,222 53.9,53.9 0 0 1 24.8,168 53.9,53.9 0 0 1 78.7,114 53.9,53.9 0 0 1 132,168 Z"/>
					<path class="path-amras-8" d="M 343,211 A 48.7,48.7 0 0 1 295,260 48.7,48.7 0 0 1 246,211 48.7,48.7 0 0 1 295,163 48.7,48.7 0 0 1 343,211 Z"/>';
		} else if ( $name === 'hador' ) {
			return '<path class="path-hador-1" d="M 154,283 A 6.12,6.12 0 0 1 149,290 6.12,6.12 0 0 1 142,286 6.12,6.12 0 0 1 146,278 6.12,6.12 0 0 1 154,283 Z"/>
					<path class="path-hador-2" d="M 167,265 A 7.83,7.83 0 0 1 162,276 7.83,7.83 0 0 1 152,270 7.83,7.83 0 0 1 157,261 7.83,7.83 0 0 1 167,265 Z"/>
					<path class="path-hador-3" d="M 183,244 A 11.9,11.9 0 0 1 174,258 11.9,11.9 0 0 1 160,250 11.9,11.9 0 0 1 168,235 11.9,11.9 0 0 1 183,244 Z"/>
					<path class="path-hador-4" d="M 327,120 A 127,111 0 0 1 200,231 127,111 0 0 1 72.9,120 127,111 0 0 1 200,9.44 127,111 0 0 1 327,120 Z"/>';
		} else if ( $name === 'malva' ) {
			return '<path d="M 94.9,90.2 101,30.7 163,72.3 229,17.7 263,68.2 319,55.9 315,102 375,144 316,175 340,228 265,220 251,263 180,233 143,282 98.9,218 57.5,236 82,189 25,170 82.8,141 48.7,93.7 Z"/>';
		} else if ( $name === 'sadoc' ) {
			return '<path d="M 32.1,42.7 54.5,257 185,257 193,269 200,282 207,269 214,257 342,257 368,23.9 Z"/>';
		}
	}
}