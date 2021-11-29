<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_news_ticker extends Widget_Base {

	protected $id = 'cz_news_ticker';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'News Ticker', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-news-ticker';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Ticker', 'codevz' ),
			esc_html__( 'News', 'codevz' ),
			esc_html__( 'Slider', 'codevz' ),
			esc_html__( 'Rotate', 'codevz' ),

		];

	}

	public function get_style_depends() {
		return [ $this->id, 'cz_parallax', 'cz_carousel' ];
	}

	public function get_script_depends() {
		return [ $this->id, 'cz_parallax', 'cz_carousel' ];
	}

	public function register_controls(){

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'type',
			[
				'label' => esc_html__( 'Type', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'slider',
				'options' => [
					'slider' => esc_html__( 'Slide', 'codevz' ),
					'fade' => esc_html__( 'Fade', 'codevz' ),
					'vertical' => esc_html__( 'Vertical', 'codevz' ),
				],
			]
		);

		
		$this->add_control(
			'badge_title', [
				'label' => esc_html__( 'Badge', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'TRENDING' , 'codevz' )
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Auto play seconds', 'codevz' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4
			]
		);

		$this->end_controls_section();

		//WP Query
		$this->start_controls_section(
			'query_section',
			[
				'label' => esc_html__( 'Query', 'codevz' )
			]
		);

		$this->add_control(
			'post_type', [
				'label' => esc_html__( 'Post type(s)', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => esc_html__( 'Orderby', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'date',
				'options' => [
					'date' => esc_html__( 'Date', 'codevz' ),
					'ID' => esc_html__( 'ID', 'codevz' ),
					'rand' => esc_html__( 'Random', 'codevz' ),
					'author' => esc_html__( 'Author', 'codevz' ),
					'title' => esc_html__( 'Title', 'codevz' ),
					'name' => esc_html__( 'Name', 'codevz' ),
					'type' => esc_html__( 'Type', 'codevz' ),
					'modified' => esc_html__( 'Modified', 'codevz' ),
					'parent' => esc_html__( 'Parent ID', 'codevz' ),
					'comment_count' => esc_html__( 'Comment Count', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => esc_html__( 'Order', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'DESC',
				'options' => [
					'DESC' => esc_html__( 'Descending', 'codevz' ),
					'ASC' => esc_html__( 'Ascending', 'codevz' ),
				],
			]
		);

		$this->add_control(
			'cat_tax',
			[
				'label' => esc_html__( 'Category Taxonomy', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => get_taxonomies()
			]
		);

		$this->add_control(
			'cat', 
			[
				'label' => esc_html__( 'Category(s)', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'cat_exclude', 
			[
				'label' => esc_html__( 'Exclude Category(s)', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'tag_tax',
			[
				'label' => esc_html__( 'Tags Taxonomy', 'codevz' ),
				'type' => Controls_Manager::SELECT,
				'options' => get_taxonomies()
			]
		);

		$this->add_control(
			'tag_id', 
			[
				'label' => esc_html__( 'Tag', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'tag_exclude', 
			[
				'label' => esc_html__( 'Exclude Tag', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'post__in', 
			[
				'label' => esc_html__( 'Filter by posts', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'author__in', 
			[
				'label' => esc_html__( 'Filter by authors', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			's', 
			[
				'label' => esc_html__( 'Search keyword', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'codevz' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_con',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_ticker' ),
			]
		);

		$this->add_responsive_control(
			'sk_badge',
			[
				'label' 	=> esc_html__( 'Badge', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'position', 'top', 'right', 'bottom', 'left', 'color', 'font-family', 'font-size', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_ticker_el .cz_ticker_badge' ),
			]
		);

		$this->add_responsive_control(
			'sk_links',
			[
				'label' 	=> esc_html__( 'Links', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_ticker_el a' ),
			]
		);

		$this->add_responsive_control(
			'sk_meta',
			[
				'label' 	=> esc_html__( 'Meta', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_ticker_el small' ),
			]
		);

		$this->add_responsive_control(
			'sk_arrows',
			[
				'label' 	=> esc_html__( 'Arrows', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_ticker_el button' ),
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		$atts = $this->get_settings_for_display();

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
		$out = '<div class="cz_ticker_el relative clr">';
		$out .= $atts['badge_title'] ? '<div class="cz_ticker_badge">' . $atts['badge_title'] . '</div>' : '';
		$out .= '<div' . Codevz_Plus::classes( [], $classes ) . $slick . ' data-slick-prev="fa fa-angle-left" data-slick-next="fa fa-angle-right">';

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

		echo $out;

		// Fix live preivew.
		Xtra_Elementor::render_js( 'slick' );

	}

}