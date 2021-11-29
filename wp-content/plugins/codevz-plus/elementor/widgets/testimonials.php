<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Utils;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;

class Xtra_Elementor_Widget_testimonials extends Widget_Base {

	protected $id = 'cz_testimonials';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Testimonials', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-testimonials';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}
	
	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Testimonials', 'codevz' ),
			esc_html__( 'Comment', 'codevz' ),
			esc_html__( 'Opinion', 'codevz' ),
			esc_html__( 'Client', 'codevz' ),
			esc_html__( 'Customer', 'codevz' ),
			esc_html__( 'Quote', 'codevz' ),
			esc_html__( 'Cite', 'codevz' ),
			esc_html__( 'Blockquote', 'codevz' ),

		];

	}
	public function get_style_depends() {

		$array = [ $this->id, 'cz_parallax' ];

		if ( Codevz_Plus::$is_rtl ) {
			$array[] = $this->id . '_rtl';
		}

		return $array;

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
			'style',
			[
				'label' => esc_html__( 'Template', 'codevz' ),
				'type' => 'image_select',
				'options' => array(
					'1' => [
						'title'=> '1',
						'url'=> Codevz_Plus::$url . 'assets/img/testimonial_1.jpg'
					],
					'2' => [
						'title'=> '2',
						'url'=> Codevz_Plus::$url . 'assets/img/testimonial_2.jpg'
					],
					'3' => [
						'title'=> '3',
						'url'=> Codevz_Plus::$url . 'assets/img/testimonial_3.jpg'
					],
					'4' => [
						'title'=> '4',
						'url'=> Codevz_Plus::$url . 'assets/img/testimonial_4.jpg'
					],
					'5' => [
						'title'=> '5',
						'url'=> Codevz_Plus::$url . 'assets/img/testimonial_5.jpg'
					],
					'6' => [
						'title'=> '6',
						'url'=> Codevz_Plus::$url . 'assets/img/testimonial_6.jpg'
					],
					'7' => [
						'title'=> '7',
						'url'=> Codevz_Plus::$url . 'assets/img/testimonial_7.jpg'
					],
				),
				'default' => '1',
			]
		);

		$this->add_control(
			'content',
			[
				'label' => esc_html__('Content','codevz'),
				'type' => Controls_Manager::WYSIWYG,
				'default' => 'Your company have been great at keeping me in work, they always line something else up.',
			]
		);

		$this->add_control(
			'image',
			[
				'label' => esc_html__( 'Image', 'codevz' ),
				'type' => Controls_Manager::MEDIA
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image',
				'default' => 'large',
				'separator' => 'none',
				'condition' => [
					'xxx' => 'xxx',
				],
			]
		);

		$this->add_control(
			'name',
			[
				'label' => esc_html__('Name','codevz'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'John Carter', 'codevz' ),
				'placeholder' => esc_html__( 'John Carter', 'codevz' )
			]
		);

		$this->add_control(
			'subname',
			[
				'label' => esc_html__('Sub name','codevz'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Businessman', 'codevz' ),
				'placeholder' => esc_html__( 'Businessman', 'codevz' )
			]
		);

		$this->add_control(
			'arrow',
			[
				'label' => esc_html__( 'Arrow', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Select', 'codevz' ),
					'cz_testimonials_bottom_arrow' => esc_html__( 'Bottom left', 'codevz' ),
					'cz_testimonials_top_arrow' => esc_html__( 'Top left', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'text_center',
			[
				'label' => esc_html__( 'Center on mobile?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->end_controls_section();

	   // Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

		$this->start_controls_section(
			'section_style_testimonials',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'arrow_background',
			[
				'label' => __( 'Arrow color', 'codevz' ),
				'type' => Controls_Manager::COLOR,
				'default' => 'rgba(167, 167, 167, .1)',
				'condition' => [
					'arrow' => [ 'cz_testimonials_bottom_arrow', 'cz_testimonials_top_arrow' ],
				],
				'selectors' => [
					'{{WRAPPER}} .cz_testimonials_bottom_arrow .cz_testimonials_content:after' => 'border-top-color: {{VALUE}}',
					'{{WRAPPER}} .cz_testimonials_top_arrow .cz_testimonials_content:after' => 'border-left-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'sk_content',
			[
				'label' 	=> esc_html__( 'Content', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_testimonials .cz_testimonials_content', '.cz_testimonials:hover .cz_testimonials_content' ),
			]
		);

		$this->add_responsive_control(
			'sk_avatar',
			[
				'label' 	=> esc_html__( 'Image', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border', 'box-shadow' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_testimonials .cz_testimonials_avatar', '.cz_testimonials:hover .cz_testimonials_avatar' ),
			]
		);

		$this->add_responsive_control(
			'sk_name',
			[
				'label' 	=> esc_html__( 'Name', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-family', 'font-size', 'background' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_testimonials .cz_testimonials_name', '.cz_testimonials:hover .cz_testimonials_name' ),
			]
		);

		$this->add_responsive_control(
			'sk_subname',
			[
				'label' 	=> esc_html__( 'Sub name', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_testimonials .cz_testimonials_subname', '.cz_testimonials:hover .cz_testimonials_subname' ),
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Avatar & Name
		$inner_meta_after = $inner_meta_before = $outer_meta_after = $outer_meta_before = $name = '';
		$avatar = $settings['image'] ? '<div class="cz_testimonials_avatar">' . Group_Control_Image_Size::get_attachment_image_html( $settings ) . '</div>' : '';

		if ( $settings['name'] ) {
			$subname = $settings['subname'] ? '<div class="cz_testimonials_subname">' . $settings['subname'] . '</div>' : '';
			$name = '<div class="cz_testimonials_name">' . $settings['name'] . '</div>';
			$name = '<div class="cz_testimonials_name_subname">' . $name . $subname . '</div>';
		}

		$content = $settings['content'];

		// Meta position
		$meta = '<div class="cz_testimonials_meta">' . $avatar . $name . '</div>';
		if ( $settings['style'] == '1' ) {
			$outer_meta_after = $meta;
		} else if ( $settings['style'] == '2' ) {
			$outer_meta_before = $meta;
		} else if ( $settings['style'] == '3' || $settings['style'] == '5' ) {
			$inner_meta_after = $meta;
		} else if ( $settings['style'] == '4' || $settings['style'] == '6' ) {
			$inner_meta_before = $meta;
		} else if ( $settings['style'] == '7' ) {
			$inner_meta_before = '<div class="cz_testimonials_meta cz_tes_only_meta mb20">' . $avatar . '</div>';
			$inner_meta_after = '<div class="cz_testimonials_meta cz_tes_only_meta mt20">' . $name . '</div>';
		}

		// Classes
		$classes = array();
		$classes[] = 'cz_testimonials';
		$classes[] = 'cz_testimonials_s' . $settings[ 'style' ];
		$classes[] = $settings[ 'arrow' ];
		$classes[] = $settings[ 'text_center' ] ? 'cz_mobile_text_center' : '';

		Xtra_Elementor::parallax( $settings );

		?>
		<div<?php echo Codevz_Plus::classes( [], $classes ); ?>><?php echo wp_kses_post( $outer_meta_before ); ?>
			<div class="cz_testimonials_content"><?php echo wp_kses_post( $inner_meta_before ); ?>
				<div class="cz_wpe_content"><?php echo wp_kses_post( $content ); ?></div>
				<?php echo wp_kses_post( $inner_meta_after ); ?>
			</div>
			<?php echo wp_kses_post( $outer_meta_after ); ?>
		</div>
		<?php

		Xtra_Elementor::parallax( $settings, true );
	}

	public function content_template() {

		?>
		<#
		if ( settings.image.url ) {
			var avatar = {
				id: settings.image.id,
				url: settings.image.url,
				size: settings.image_size,
				dimension: settings.image_custom_dimension,
				model: view.getEditModel()
			};

			var avatar_url = elementor.imagesManager.getImageUrl( avatar );

			if ( ! avatar_url ) {
				return;
			}
		}

		var inner_meta_after = inner_meta_before = outer_meta_after = outer_meta_before = name = '',
			avatar = avatar_url ? '<div class="cz_testimonials_avatar"><img src="' + avatar_url + '"></div>' : '';

		if ( settings.name ) {
			var subname = settings.subname ? '<div class="cz_testimonials_subname">' + settings.subname + '</div>' : '';
			var name = '<div class="cz_testimonials_name">' + settings.name + '</div>';
			var name = '<div class="cz_testimonials_name_subname">' + name + subname + '</div>';
		}

		var content = settings.content;

		var meta = '<div class="cz_testimonials_meta">' + avatar + name + '</div>';
		if ( settings.style == '1' ) {
			var outer_meta_after = meta;
		} else if ( settings.style == '2' ) {
			var outer_meta_before = meta;
		} else if ( settings.style == '3' || settings.style == '5' ) {
			var inner_meta_after = meta;
		} else if ( settings.style == '4' || settings.style == '6' ) {
			var inner_meta_before = meta;
		} else if ( settings.style == '7' ) {
			var inner_meta_before = '<div class="cz_testimonials_meta cz_tes_only_meta mb20">' + avatar + '</div>';
			var inner_meta_after = '<div class="cz_testimonials_meta cz_tes_only_meta mt20">' + name + '</div>';
		}

		var classes = 'cz_testimonials', 
			classes = settings.style ? classes + ' cz_testimonials_s' + settings.style : classes,
			classes = settings.arrow ? classes + ' ' + settings.arrow : classes,
			classes = settings.text_center ? classes + ' cz_mobile_text_center' : classes,

			parallaxOpen = xtraElementorParallax( settings ),
			parallaxClose = xtraElementorParallax( settings, true );
		#>

		{{{ parallaxOpen }}}
		
		<div class="{{{classes}}}">{{{outer_meta_before}}}
			<div class="cz_testimonials_content">{{{inner_meta_before}}}
				<div class="cz_wpe_content">
					{{{content}}}
				</div>
				{{{inner_meta_after}}}
			</div>
			{{{outer_meta_after}}}
		</div>

		{{{ parallaxClose }}}
		<?php

	}
}