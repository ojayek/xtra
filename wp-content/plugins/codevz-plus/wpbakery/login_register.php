<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.

/**
 * Ajax Login and Register
 * 
 * @author Codevz
 * @link http://codevz.com/
 */

class Codevz_WPBakery_login_register {

	public function __construct( $name ) {
		$this->name = $name;
		add_action( 'wp_ajax_cz_ajax_lrpr', array( $this, 'ajax' ) );
		add_action( 'wp_ajax_nopriv_cz_ajax_lrpr', array( $this, 'ajax' ) );
	}

	/**
	 * Shortcode settings
	 */
	public function in( $wpb = false ) {
		add_shortcode( $this->name, [ $this, 'out' ] );

		$settings = array(
			'category'		=> Codevz_Plus::$title,
			'base'			=> $this->name,
			'name'			=> esc_html__( 'Login, Register', 'codevz' ),
			'description'	=> esc_html__( 'Ajax tabbed login forms', 'codevz' ),
			'icon'			=> 'czi',
			'params'		=> array(
				array(
					'type' 			=> 'cz_sc_id',
					'param_name' 	=> 'id',
					'save_always' 	=> true
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Login form?", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "login"
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Registration form?", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "register"
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Pass Recovery form?", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "pass_r"
				),
				array(
					"type"        	=> "checkbox",
					"heading"     	=> esc_html__("Show form for admin?", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "show"
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Redirect URL', 'codevz' )
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Redirect URL", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					"param_name"  	=> "redirect"
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'GDPR', 'codevz' )
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("GDPR Confirmation", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'description' 	=> esc_html__("By accepting you will allow us to store your data.", 'codevz'),
					"param_name"  	=> "gdpr"
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("GDPR Error", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__("Check GDPR agreement then submit form.", 'codevz'),
					"param_name"  	=> "gdpr_error"
				),
				array(
					'type' 			=> 'cz_title',
					'param_name' 	=> 'cz_title',
					'class' 		=> '',
					'content' 		=> esc_html__( 'Translation', 'codevz' )
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Username", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__("Username", 'codevz'),
					"param_name"  	=> "username"
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Password", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__("Password", 'codevz'),
					"param_name"  	=> "password"
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Your email", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__("Your Email", 'codevz'),
					"param_name"  	=> "email"
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Email", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__("Email", 'codevz'),
					"param_name"  	=> "e_or_p"
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Login button", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__("Login Now", 'codevz'),
					"param_name"  	=> "login_btn"
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Register button", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__("Signup Now", 'codevz'),
					"param_name"  	=> "register_btn"
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Password recovery button", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__("Send my Password", 'codevz'),
					"param_name"  	=> "pass_r_btn"
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Custom login link", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__("Already registered? Sign In", 'codevz'),
					"param_name"  	=> "login_t"
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Forgot password link", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__("Forgot your password? Get help", 'codevz'),
					"param_name"  	=> "f_pass_t"
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Regisration link", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__("Not registered? Create an account", 'codevz'),
					"param_name"  	=> "register_t"
				),
				array(
					"type"        	=> "textfield",
					"heading"     	=> esc_html__("Logout", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'value' 		=> esc_html__("Logout", 'codevz'),
					"param_name"  	=> "logout"
				),

				// Styling
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_con',
					"heading"     	=> esc_html__( "Container", 'codevz'),
					'button' 		=> esc_html__( "Container", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'background', 'padding', 'border' ),
					'group' 		=> esc_html__( 'Styling', 'codevz' ),

				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_inputs',
					"heading"     	=> esc_html__( "Inputs", 'codevz'),
					'button' 		=> esc_html__( "Inputs", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'text-align', 'font-size', 'background', 'border' ),
					'group' 		=> esc_html__( 'Styling', 'codevz' ),

				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_buttons',
					'hover_id' 		=> 'sk_buttons_hover',
					"heading"     	=> esc_html__( "Buttons", 'codevz'),
					'button' 		=> esc_html__( "Buttons", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size', 'background', 'border' ),
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_buttons_hover' ),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_btn_active',
					"heading"     	=> esc_html__( "Buttons loader", 'codevz'),
					'button' 		=> esc_html__( "Buttons loader", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'border-right-color' ),
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
				),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_links',
					"heading"     	=> esc_html__( "Links", 'codevz'),
					'button' 		=> esc_html__( "Links", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'font-size' ),
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
				),

				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_msg',
					"heading"     	=> esc_html__( "Messages", 'codevz'),
					'button' 		=> esc_html__( "Messages", 'codevz'),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'text-align', 'font-size', 'background', 'padding', 'border' ),
					'group' 		=> esc_html__( 'Styling', 'codevz' ),
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_msg_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_msg_mobile' ),

				array(
					"type"        	=> "textarea",
					"heading"     	=> esc_html__("Title above login form", 'codevz'),
					"param_name"  	=> "content_l",
					'edit_field_class' => 'vc_col-xs-99',
					"group"  		=> esc_html__( 'Title', 'codevz' )
				),
				array(
					"type"        	=> "textarea",
					"heading"     	=> esc_html__("Title above register form", 'codevz'),
					"param_name"  	=> "content_r",
					'edit_field_class' => 'vc_col-xs-99',
					"group"  		=> esc_html__( 'Title', 'codevz' )
				),
				array(
					"type"        	=> "textarea",
					"heading"     	=> esc_html__("Title above password recovery form", 'codevz'),
					"param_name"  	=> "content_pr",
					'edit_field_class' => 'vc_col-xs-99',
					"group"  		=> esc_html__( 'Title', 'codevz' )
				),
				array(
					'type' 			=> 'cz_sk',
					'param_name' 	=> 'sk_content',
					"heading"     	=> esc_html__( "Title styling", 'codevz'),
					'button' 		=> esc_html__( "Title", 'codevz'),
					'group' 		=> esc_html__( 'Title', 'codevz' ),
					'edit_field_class' => 'vc_col-xs-99',
					'settings' 		=> array( 'color', 'text-align', 'font-family', 'font-size' ),
					"group"  		=> esc_html__( 'Title', 'codevz' )
				),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_tablet' ),
				array( 'type' => 'cz_hidden','param_name' => 'sk_content_mobile' ),

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

		// Styles
		if ( isset( $public ) || Codevz_Plus::$vc_editable || Codevz_Plus::$is_admin ) {
			$css_id = '#' . $atts['id'];
			
			$css_array = array(
				'sk_con' 				=> $css_id,
				'sk_brfx' 				=> $css_id . ':before',
				'sk_inputs' 			=> $css_id . ' input:not([type="submit"])',
				'sk_buttons' 			=> $css_id . ' input[type="submit"]',
				'sk_buttons_hover' 		=> $css_id . ' input[type="submit"]:hover',
				'sk_btn_active' 		=> $css_id . ' input.cz_loader',
				'sk_links' 				=> $css_id . ' a',
				'sk_msg' 				=> $css_id . ' .cz_msg',
				'sk_content' 			=> $css_id . ' .cz_lrpr_title',
			);

			$css = Codevz_Plus::sk_style( $atts, $css_array );
			$css_t = Codevz_Plus::sk_style( $atts, $css_array, '_tablet' );
			$css_m = Codevz_Plus::sk_style( $atts, $css_array, '_mobile' );

			$css .= $atts['anim_delay'] ? $css_id . '{animation-delay:' . $atts['anim_delay'] . '}' : '';

		} else {
			Codevz_Plus::load_font( $atts['sk_inputs'] );
			Codevz_Plus::load_font( $atts['sk_buttons'] );
			Codevz_Plus::load_font( $atts['sk_links'] );
			Codevz_Plus::load_font( $atts['sk_content'] );
			Codevz_Plus::load_font( $atts['sk_msg'] );
		}

		// Classes
		$classes = array();
		$classes[] = $atts['id'];
		$classes[] = 'cz_lrpr';
		$classes[] = $atts['login'] ? ' cz_vl' : ( $atts['register'] ? ' cz_vr' : ' cz_vpr' );

		// Out
		$out = '<div id="' . $atts['id'] . '" data-redirect="' . $atts['redirect'] . '"' . Codevz_Plus::classes( $atts, $classes ) . Codevz_Plus::data_stlye( $css, $css_t, $css_m ) . '>';

		if ( is_user_logged_in() && ! $atts['show'] ) {
			global $current_user;
			if ( function_exists( 'wp_get_current_user' ) ) {
				wp_get_current_user();
			}
			$out .= isset( $current_user->user_email ) ? get_avatar( $current_user->user_email, 80 ) . '<a href="' . wp_logout_url( home_url() ) . '">' . $atts['logout'] . '</a>' : '';
		} else {

			// Var's
			$action 	= '<input name="action" type="hidden" value="cz_ajax_lrpr" />';
			$user 		= '<input name="username" type="text" placeholder="' . $atts['username'] . '" />';
			$email 		= '<input name="email" type="email" placeholder="' . $atts['email'] . '" />';
			$pass 		= '<input name="password" type="password" placeholder="' . $atts['password'] . '" />';
			$pass_r 	= '<input name="pass_r" type="text" placeholder="' . $atts['e_or_p'] . '" />';
			$msg 		= '<div class="cz_msg"></div>';
			$login_t 	= ( $atts['login'] && $atts['login_t'] ) ? '<a href="#cz_l">' . $atts['login_t'] . '</a>' : '';
			$register_t = ( $atts['register'] && $atts['register_t'] ) ? '<div class="clr"></div><a href="#cz_r">' . $atts['register_t'] . '</a>' : '';
			$f_pass_t 	= ( $atts['pass_r'] && $atts['f_pass_t'] ) ? '<a href="#cz_pr">' . $atts['f_pass_t'] . '</a>' : '';
			$gdpr 		= $atts['gdpr'] ? '<label class="cz_gdpr"><input name="gdpr_error" type="hidden" value="' . $atts['gdpr_error'] . '" /><input type="checkbox" name="gdpr"> ' . $atts['gdpr'] . '</label>' : '';

			if ( $atts['login'] ) {
				$cl = $atts['content_l'] ? '<div class="cz_lrpr_title mb30">' . do_shortcode( $atts['content_l'] ) . '</div>' : '';
				$out .= '<form id="cz_l">' . $cl . $action . $user . $pass . self::security( 'login' ) . $gdpr . '<input type="submit" value="' . $atts['login_btn'] . '">' . $msg . $f_pass_t . $register_t . '</form>';
			}

			if ( $atts['register'] ) {
				$cr = $atts['content_r'] ? '<div class="cz_lrpr_title mb30">' . do_shortcode( $atts['content_r'] ) . '</div>' : '';
				$out .= '<form id="cz_r">' . $cr . $action . $user . $email . $pass . self::security( 'register' ) . $gdpr . '<input type="submit" value="' . $atts['register_btn'] . '">' . $msg . $login_t . '</form>';
			}

			if ( $atts['pass_r'] ) {
				$cpr = $atts['content_pr'] ? '<div class="cz_lrpr_title mb30">' . do_shortcode( $atts['content_pr'] ) . '</div>' : '';
				$out .= '<form id="cz_pr">' . $cpr . $action . $pass_r . self::security( 'password' ) . $gdpr . '<input type="submit" value="' . $atts['pass_r_btn'] . '">' . $msg . $login_t . '</form>';
			}

			$out .= do_action( 'wordpress_social_login' );
		}

		$out .= '</div>';

		return Codevz_Plus::_out( $atts, $out, 'login', $this->name );
	}

	/**
	 *
	 * Generate security input
	 * 
	 * @return string
	 * 
	 */
	public static function security( $i ) {
		$num_a = rand( 1, 10 );
		$num_b = rand( 1, 10 );
		return '<input name="security_' . $i . '" type="text" placeholder="' . $num_a . ' + ' . $num_b . ' ?" /><input name="security_' . $i . '_a" type="hidden" value="' . md5( $num_a + $num_b ) . '" />';
	}

	/**
	 *
	 * Ajax process for Login - Register - Password recovery
	 * 
	 * @return string
	 * 
	 */
	public function ajax() {

		// GDPR
		if ( isset( $_POST['gdpr_error'] ) && empty( $_POST['gdpr'] ) ) {
			wp_die( $_POST['gdpr_error'] );
		}

		// Prepare
		$username = isset( $_POST['username'] ) ? $_POST['username'] : 0;
		$password = isset( $_POST['password'] ) ? $_POST['password'] : 0;
		$email = isset( $_POST['email'] ) ? $_POST['email'] : 0;
		$pass_r = isset( $_POST['pass_r'] ) ? $_POST['pass_r'] : 0;

		$security_error 		= esc_html__( 'Invalid security answer, Please try again', 'codevz' );
		$cant_find_user 		= esc_html__( "Can't find user with this information", 'codevz' );
		$email_sent 			= esc_html__( 'Email sent, Please check your email', 'codevz' );
		$server_cant_send 		= esc_html__( 'Server unable to send email', 'codevz' );
		$registration_complete 	= esc_html__( 'Registration was completed, You can log in now', 'codevz' );
		$please_try_again 		= esc_html__( 'Please try again ...', 'codevz' );
		$up_is_wrong 			= esc_html__( 'Username or password is wrong', 'codevz' );
		$wrong_email 			= esc_html__( 'Wrong email, Please try again !', 'codevz' );
		$cant_be_same 			= esc_html__( 'Username and password can not be same', 'codevz' );
		$atleast_eight 			= esc_html__( 'Password should be atleast 8 charachters', 'codevz' );

		// Password recovery
		if ( $pass_r ) {

			// Security
			$security = isset( $_POST['security_password'] ) ? md5( $_POST['security_password'] ) : 1;
			$security_a = isset( $_POST['security_password_a'] ) ? $_POST['security_password_a'] : 1;
			if ( $security !== $security_a ) {
				die( $security_error );
			}

			/* Check email */
			if ( is_email( $pass_r ) && email_exists( $pass_r ) ) {
				$get_by = 'email';
			//} else if ( validate_username( $pass_r ) && username_exists( $pass_r ) ) {
				//$get_by = 'login';
			} else {
				wp_die( $cant_find_user );
			}

			/* New pass */
			$pass = wp_generate_password();

			/* Get user data */
			$user = get_user_by( $get_by, $pass_r );
			/* Update user */
			$update_user = wp_update_user( array( 'ID' => $user->ID, 'user_pass' => $pass ) );
				
			/* if update user return true, so send email containing the new password */
			if( $update_user ) {
				$from = 'do-not-reply@' . preg_replace( '/^www\./', '', $_SERVER['SERVER_NAME'] ); 
				$to = $user->user_email;
				$subject = 'New Password - ' . get_bloginfo( 'name' );
				$sender = 'From: '.get_bloginfo('name').' <'.$from.'>' . "\r\n";

				$message = 'Your new password for "' . $pass_r . '" is: <strong>' . $pass . '</strong><br /><br /><a href="' . get_home_url() . '/' . '">' . get_home_url() . '</a>';

				$headers[] = 'MIME-Version: 1.0' . "\r\n";
				$headers[] = 'Content-type: text/html; charset=UTF-8' . "\r\n";
				$headers[] = "X-Mailer: PHP \r\n";
				$headers[] = $sender;
					
				$mail = wp_mail( $to, $subject, $message, $headers );
				if ( $mail ) {
					wp_die( $email_sent );
				} else {
					wp_die( $server_cant_send );
				}
			} else {
				wp_die( $please_try_again );
			}

		// Registration
		} else if ( $email ) {

			// Security
			$security = isset( $_POST['security_register'] ) ? md5( $_POST['security_register'] ) : 1;
			$security_a = isset( $_POST['security_register_a'] ) ? $_POST['security_register_a'] : 1;
			
			if ( $security !== $security_a ) {
				wp_die( $security_error );
			}

			if ( $username === $password ) {
				wp_die( $cant_be_same );
			} else if ( strlen( $password ) < 8 ) {
				wp_die( $atleast_eight );
			}

			/* Prepare */
			$info = array();
			$info['user_nicename'] = $info['nickname'] = $info['display_name'] = $info['first_name'] = $info['user_login'] = $username = sanitize_user( $username );
			$info['user_pass'] = $password;
			$info['user_email'] = sanitize_email( $email );

			/* Check email */
			if ( ! is_email( $info['user_email'] ) ) {
				wp_die( $wrong_email );
			}
			
			/* Register */
		    $user = wp_insert_user( $info );

			/* Check and Send email */
		 	if ( is_wp_error( $user ) ){	
				$error = $user->get_error_codes();

				if ( in_array( 'empty_user_login', $error ) ) {
					die( $user->get_error_message( 'empty_user_login' ) );
				} else if ( in_array( 'existing_user_login', $error ) ) {
					die( $user->get_error_message( 'existing_user_login' ) );
				} else if ( in_array( 'existing_user_email', $error ) ) {
					die( $user->get_error_message( 'existing_user_email' ) );
				}
		    } else {
				$from = 'do-not-reply@'.preg_replace( '/^www\./', '', $_SERVER['SERVER_NAME'] ); 
				$subject = get_bloginfo( 'name' ) . ' - Registration successful';
				$sender = 'From: '.get_bloginfo('name').' <'.$from.'>' . "\r\n";
				
				$message = '<h4>Thank you for resigtration.</h4><br /><ul>
					<li>Username: ' . $username . '</li>
					<li>Password: ' . $password . '</li>
					<li><a href="' . get_home_url() . '">' . get_home_url() . '</a></li>
				</ul>';

				$headers[] = 'MIME-Version: 1.0' . "\r\n";
				$headers[] = 'Content-type: text/html; charset=UTF-8' . "\r\n";
				$headers[] = "X-Mailer: PHP \r\n";
				$headers[] = $sender;
					
				$mail = wp_mail( $info['user_email'], $subject, $message, $headers );

				$user = wp_signon( array(
					'user_login' 	=> $username,
					'user_password'	=> $password,
					'remember'		=> true
				), false );

				wp_die( is_wp_error( $user ) ? $registration_complete : '' );
		    }

		// Login
		} else {

			// Security
			$security = isset( $_POST['security_login'] ) ? md5( $_POST['security_login'] ) : 1;
			$security_a = isset( $_POST['security_login_a'] ) ? $_POST['security_login_a'] : 1;
			
			if ( $security !== $security_a ) {
				wp_die( $security_error );
			}

			$user = wp_signon( array(
				'user_login' 	=> $username,
				'user_password'	=> $password,
				'remember'		=> true
			), false );

			wp_die( is_wp_error( $user ) ? $up_is_wrong : '' );
		}
	}

}