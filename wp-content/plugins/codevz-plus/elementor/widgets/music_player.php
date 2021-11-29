<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Repeater;
use Elementor\Widget_Base;
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_music_player extends Widget_Base {

	protected $id = 'cz_music_player';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Music Player', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-music-player';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_keywords() {

		return [

			esc_html__( 'XTRA', 'codevz' ),
			esc_html__( 'Music', 'codevz' ),
			esc_html__( 'Play', 'codevz' ),
			esc_html__( 'Player', 'codevz' ),
			esc_html__( 'Audio', 'codevz' ),
			esc_html__( 'Song', 'codevz' ),
		];

	}
	
	public function get_style_depends() {
		return [ $this->id,  'codevz-bar-ui' ,'cz_parallax' ];
	}
	
	public function get_script_depends() {
		return [ $this->id, 'codevz-soundmanager', 'codevz-bar-ui' ,'cz_parallax' ];
	}

	public function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Settings', 'codevz' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'title', [
				'label' => esc_html__( 'Title', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$repeater->add_control(
			'badge', [
				'label' => esc_html__( 'Badge', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$repeater->add_control(
			'mp3',
			[
				'label' 	=> esc_html__('MP3','codevz'),
				'type' 		=> Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter your MP3 URL', 'codevz' )
			]
		);

		$repeater->add_control(
			'icon_title', [
				'label' => esc_html__( 'Icon Title', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$repeater->add_control (
			'icon',
			[
				'label' => __( 'Icon', 'codevz' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$repeater->add_control(
			'icon_link',
			[
				'label' => esc_html__( 'Icon Link', 'codevz' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'https://xtratheme.com',
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);

		$this->add_control(
			'tracks',
			[
				'label' => esc_html__( 'Add track(s)', 'codevz' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'title' => 'Track 1',
						'badge' => '',
						'mp3' => '',
						'icon' => '',
						'icon_title' => '',
						'link' => ''
					],
				],
			]
		);

		$this->add_responsive_control(
			'width',
			[
				'label' 	=> esc_html__( 'Player Width', 'codevz' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ '%', 'px' ],
				'range' => [
					'%' => [
						'min' => 0,
						'max' => 120,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => '%'
				],
				'selectors' => [
					'{{WRAPPER}} .cz-music-player' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'height',
			[
				'label' 	=> esc_html__( 'Playlist Height', 'codevz' ),
				'type' 		=> Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' 	=> [
					'px' 		=> [
						'min' 		=> 10,
						'max' 		=> 1200,
						'step' 		=> 1,
					],
				],
				'default' => [
					'unit' => 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .cz-music-player' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'only_play',
			[
				'label' => esc_html__( 'Only play button?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'playlist_open',
			[
				'label' => esc_html__( 'Playlist open?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'fixed',
			[
				'label' => esc_html__( 'Fixed bottom?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'dark_text',
			[
				'label' => esc_html__( 'Dark text?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'flat',
			[
				'label' => esc_html__( 'Flat?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Auto play?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->add_control(
			'center',
			[
				'label' => esc_html__( 'Center mode?', 'codevz' ),
				'type' => Controls_Manager::SWITCHER
			]
		);

		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

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
				'label' 	=> esc_html__( 'Container Styling', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz-music-player' ),
			]
		);

		$this->add_responsive_control(
			'sk_player',
			[
				'label' 	=> esc_html__( 'Player Styling', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz-music-player .bd.sm2-main-controls, .cz-music-player .bd.sm2-playlist-drawer' ),
			]
		);

		$this->end_controls_section();

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Classes
		$classes = array();
		$classes[] = 'cz-music-player sm2-bar-ui';
		$classes[] .= $settings['playlist_open'] ? 'playlist-open' : '';
		$classes[] .= $settings['dark_text'] ? 'dark-text' : '';
		$classes[] .= $settings['flat'] ? 'flat' : '';
		$classes[] .= $settings['fixed'] ? 'fixed full-width' : '';
		$classes[] .= $settings['autoplay'] ? 'cz_autoplay' : '';
		$classes[] .= $settings['only_play'] ? 'cz_only_play' : '';
		$classes[] .= $settings['center'] ? 'cz_center_tm' : '';

		// Tracks
		$tracks_out = '';
		$tracks = (array) $settings['tracks'];
		foreach ( $tracks as $track ) {
			$mp3 = isset( $track['mp3'] ) ? $track['mp3'] : '';
			$title = isset( $track['title'] ) ? $track['title'] : '';
			$badge = isset( $track['badge'] ) ? $track['badge'] : '';
			$icon = isset( $track['icon'] ) ? $track['icon'] : '';
			$icon_title = isset( $track['icon_title'] ) ? $track['icon_title'] : '';
			$icon_link = isset( $track['icon_link']['url'] ) ? $track['icon_link']['url'] : '';
			$tracks_out .= '<li><div class="sm2-row"><div class="sm2-col sm2-wide"><a href="' . $mp3 . '" class="exclude button-exclude inline-exclude">' . $title . ' <span class="label">' . $badge . '</span></a></div>';
			$dl = '';
			if ( Codevz_Plus::contains( $icon_link, array( '.mp', '.avi', '.ogg', '.wav', '.wave', '.zip', '.rar' ) ) ) {
				$dl = ' download="download"';
			}
			ob_start();
			Icons_Manager::render_icon( $track['icon'] );
			$icon = ob_get_clean();
			$tracks_out .= '<div class="sm2-col"><a href="' . $icon_link . '" target="_blank" title="' . $icon_title . '" class="sm2-icon sm2-exclude"' . $dl . '>' . $icon . '</a></div>';
			$tracks_out .= '</div></li>';
		}

		// HTML of next prev & menu playlist
		$next_prev = ( count( $tracks ) !== 1 ) ? '<div class="sm2-inline-element sm2-button-element"><div class="sm2-button-bd"><a href="#prev" title="Previous" class="sm2-inline-button previous">&lt; previous</a></div></div><div class="sm2-inline-element sm2-button-element"><div class="sm2-button-bd"><a href="#next" title="Next" class="sm2-inline-button next">&gt; next</a></div></div><div class="sm2-inline-element sm2-button-element sm2-menu"><div class="sm2-button-bd"><a href="#menu" class="sm2-inline-button menu">menu</a></div></div>' : '';

		// Output
		echo '<div' . Codevz_Plus::classes( [], $classes ) . '><div class="bd sm2-main-controls"><div class="sm2-inline-texture"></div><div class="sm2-inline-gradient"></div><div class="sm2-inline-element sm2-button-element"><div class="sm2-button-bd"><a href="#play" class="sm2-inline-button play-pause">Play / pause</a></div></div><div class="sm2-inline-element sm2-inline-status"><div class="sm2-playlist"><div class="sm2-playlist-target"><noscript><p>JavaScript is required.</p></noscript></div></div><div class="sm2-progress"><div class="sm2-row"><div class="sm2-inline-time">0:00</div><div class="sm2-progress-bd"><div class="sm2-progress-track"><div class="sm2-progress-bar"></div><div class="sm2-progress-ball"><div class="icon-overlay"></div></div></div></div><div class="sm2-inline-duration">0:00</div></div></div></div><div class="sm2-inline-element sm2-button-element sm2-volume"><div class="sm2-button-bd"><span class="sm2-inline-button sm2-volume-control volume-shade"></span><a href="#volume" class="sm2-inline-button sm2-volume-control">volume</a></div></div>' . wp_kses_post( $next_prev ) . '</div><div class="bd sm2-playlist-drawer sm2-element"><div class="sm2-inline-texture"><div class="sm2-box-shadow"></div></div><div class="sm2-playlist-wrapper"><ul class="sm2-playlist-bd">' . wp_kses_post( $tracks_out ) . '</ul></div></div></div>';

		// Fix fixed player
		echo "<script>

			! function( $ ) {

				if ( $( '.sm2-bar-ui.fixed' ).length ) {
					setTimeout(function() {
						$( document.body ).css( 'marginBottom', $( '.sm2-bar-ui.fixed' ).height() );
					}, 2000 );
				}

			}( jQuery );

		</script>";
	}

}