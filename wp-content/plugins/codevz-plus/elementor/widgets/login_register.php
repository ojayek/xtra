<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Xtra_Elementor_Widget_login_register extends Widget_Base {

	protected $id = 'cz_login_register';

	public function get_name() {
		return $this->id;
	}

	public function get_title() {
		return esc_html__( 'Login,Register', 'codevz' );
	}
	
	public function get_icon() {
		return 'xtra-login-register';
	}

	public function get_categories() {
		return [ 'xtra' ];
	}

	public function get_style_depends() {
		return [ $this->id ];
	}

	public function get_script_depends() {
		return [ $this->id ];
	}

	public function register_controls() {

			$this->start_controls_section(
				'section_login_register',
				[
					'label' => esc_html__( 'Settings', 'codevz' ),
					'tab' => Controls_Manager::TAB_CONTENT,
				]
			);

			$this->add_control(
				'login',
				[
					'label' => esc_html__( 'Login form?', 'codevz' ),
					'type' => Controls_Manager::SWITCHER,
				]
			);

			$this->add_control(
				'register',
				[
					'label' => esc_html__( 'Registration form?', 'codevz' ),
					'type' => Controls_Manager::SWITCHER,
				]
			);

			$this->add_control(
				'pass_r',
				[
					'label' => esc_html__( 'Pass Recovery form?', 'codevz' ),
					'type' => Controls_Manager::SWITCHER,
				]
			);

			$this->add_control(
				'show',
				[
					'label' => esc_html__( 'Show form for admin?', 'codevz' ),
					'type' => Controls_Manager::SWITCHER,
				]
			);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_url',
			[
				'label' => esc_html__( 'Redirect URL', 'codevz' )
			]
		);

		
		$this->add_control(
			'redirect',
			[
				'label' => esc_html__( 'Redirect URL', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_gdpr',
			[
				'label' => esc_html__( 'GDPR', 'codevz' )
			]
		);

		$this->add_control(
			'gdpr',
			[
				'label' => esc_html__( 'GDPR Confirmation', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);

		$this->add_control(
			'gdpr_error',
			[
				'label' => esc_html__( 'GDPR Error', 'codevz' ),
				'type' => Controls_Manager::TEXT
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_translation',
			[
				'label' => esc_html__( 'Translation', 'codevz' )
			]
		);

		$this->add_control(
			'username',
			[
				'label' => esc_html__( 'Username', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Username', 'codevz' ),
				'placeholder' => esc_html__( 'Username', 'codevz' ),
			]
		);

		$this->add_control(
			'password',
			[
				'label' => esc_html__( 'Password', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Password', 'codevz' ),
				'placeholder' => esc_html__( 'Password', 'codevz' ),
			]
		);

		$this->add_control(
			'email',
			[
				'label' => esc_html__( 'Your email', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Your email', 'codevz' ),
				'placeholder' => esc_html__( 'Your email', 'codevz' ),
			]
		);

		$this->add_control(
			'e_or_p',
			[
				'label' => esc_html__( 'Email', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Email', 'codevz' ),
				'placeholder' => esc_html__( 'Email', 'codevz' ),
			]
		);

		$this->add_control(
			'login_btn',
			[
				'label' => esc_html__( 'Login button', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Login now', 'codevz' ),
				'placeholder' => esc_html__( 'Login now', 'codevz' ),
			]
		);

		$this->add_control(
			'register_btn',
			[
				'label' => esc_html__( 'Register button', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Signup', 'codevz' ),
				'placeholder' => esc_html__( 'Signup', 'codevz' ),
			]
		);

		$this->add_control(
			'pass_r_btn',
			[
				'label' => esc_html__( 'Recovery button', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Send my password', 'codevz' ),
				'placeholder' => esc_html__( 'Send my password', 'codevz' ),
			]
		);

		$this->add_control(
			'login_t',
			[
				'label' => esc_html__( 'Custom login link', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Already registered? Sign In', 'codevz' ),
				'placeholder' => esc_html__( 'Already registered? Sign In', 'codevz' ),
			]
		);

		$this->add_control(
			'f_pass_t',
			[
				'label' => esc_html__( 'Forgot password link', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Forgot your password? Get help', 'codevz' ),
				'placeholder' => esc_html__( 'Forgot your password? Get help', 'codevz' ),
			]
		);

		$this->add_control(
			'register_t',
			[
				'label' => esc_html__( 'Regisration link', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Not registered? Create an account',
				'placeholder' => 'Not registered? Create an account',
			]
		);

		$this->add_control(
			'logout',
			[
				'label' => esc_html__( 'Logout', 'codevz' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Logout',
				'placeholder' => 'Logout',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_styling',
			[
				'label' => esc_html__( 'Styling', 'codevz' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'sk_con',
			[
				'label' 	=> esc_html__( 'Container', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_lrpr' ),
			]
		);

		$this->add_control(
			'sk_inputs',
			[
				'label' => esc_html__( 'Inputs', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'text-align', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_lrpr input:not([type="submit"])' ),
			]
		);

		$this->add_control(
			'sk_buttons',
			[
				'label' => esc_html__( 'Buttons', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size', 'background', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_lrpr input[type="submit"]' ),
			]
		);

		$this->add_control(
			'sk_btn_active',
			[
				'label' => esc_html__( 'Buttons loader', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'border-right-color' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_lrpr input.cz_loader' ),
			]
		);

		$this->add_control(
			'sk_links',
			[
				'label' => esc_html__( 'Links', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'font-size' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_lrpr a' ),
			]
		);

		$this->add_control(
			'sk_msg',
			[
				'label' => esc_html__( 'Messages', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'text-align', 'font-size', 'background', 'padding', 'border' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_lrpr .cz_msg' ),
			]
		);

		$this->add_control(
			'sk_content',
			[
				'label' => esc_html__( 'Title', 'codevz' ),
				'type' 		=> 'stylekit',
				'settings' 	=> [ 'color', 'text-align', 'font-family', 'font-size' ],
				'selectors' => Xtra_Elementor::sk_selectors( '.cz_lrpr .cz_lrpr_title' ),
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_title',
			[
				'label' => esc_html__( 'Title', 'codevz' ),
			]
		);

		$this->add_control(
			'content_l',
			[
				'label' => esc_html__( 'Title above login form', 'plugin-domain' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 10,
			]
		);

		$this->add_control(
			'content_r',
			[
				'label' => esc_html__( 'Title above register form', 'plugin-domain' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 10,
			]
		);

		$this->add_control(
			'content_pr',
			[
				'label' => esc_html__( 'Title above password recovery form', 'plugin-domain' ),
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 10,
			]
		);
		$this->end_controls_section();

		// Parallax settings.
		Xtra_Elementor::parallax_settings( $this );

	}

	public function render() {

		$settings = $this->get_settings_for_display();

		// Classes
		$classes = array();
		$classes[] = 'cz_lrpr';
		$classes[] = $settings['login'] ? ' cz_vl' : ( $settings['register'] ? ' cz_vr' : ' cz_vpr' );

		// Out
		$out = '<div data-redirect="' . $settings['redirect'] . '"' . Codevz_Plus::classes( [], $classes ) . '>';

		if ( is_user_logged_in() && ! $settings['show'] ) {

			global $current_user;

			if ( function_exists( 'wp_get_current_user' ) ) {
				wp_get_current_user();
			}

			$out .= isset( $current_user->user_email ) ? get_avatar( $current_user->user_email, 80 ) . '<a href="' . wp_logout_url( home_url() ) . '">' . $settings['logout'] . '</a>' : '';

		} else {

			// Var's
			$action 	= '<input name="action" type="hidden" value="cz_ajax_lrpr" />';
			$user 		= '<input name="username" type="text" placeholder="' . $settings['username'] . '" />';
			$email 		= '<input name="email" type="email" placeholder="' . $settings['email'] . '" />';
			$pass 		= '<input name="password" type="password" placeholder="' . $settings['password'] . '" />';
			$pass_r 	= '<input name="pass_r" type="text" placeholder="' . $settings['e_or_p'] . '" />';
			$msg 		= '<div class="cz_msg"></div>';
			$login_t 	= ( $settings['login'] && $settings['login_t'] ) ? '<a href="#cz_l">' . $settings['login_t'] . '</a>' : '';
			$register_t = ( $settings['register'] && $settings['register_t'] ) ? '<div class="clr"></div><a href="#cz_r">' . $settings['register_t'] . '</a>' : '';
			$f_pass_t 	= ( $settings['pass_r'] && $settings['f_pass_t'] ) ? '<a href="#cz_pr">' . $settings['f_pass_t'] . '</a>' : '';
			$gdpr 		= $settings['gdpr'] ? '<label class="cz_gdpr"><input name="gdpr_error" type="hidden" value="' . $settings['gdpr_error'] . '" /><input type="checkbox" name="gdpr"> ' . $settings['gdpr'] . '</label>' : '';

			if ( $settings['login'] ) {
				$cl = $settings['content_l'] ? '<div class="cz_lrpr_title mb30">' . do_shortcode( $settings['content_l'] ) . '</div>' : '';
				$out .= '<form id="cz_l">' . $cl . $action . $user . $pass . self::security( 'login' ) . $gdpr . '<input type="submit" value="' . $settings['login_btn'] . '">' . $msg . $f_pass_t . $register_t . '</form>';
			}

			if ( $settings['register'] ) {
				$cr = $settings['content_r'] ? '<div class="cz_lrpr_title mb30">' . do_shortcode( $settings['content_r'] ) . '</div>' : '';
				$out .= '<form id="cz_r">' . $cr . $action . $user . $email . $pass . self::security( 'register' ) . $gdpr . '<input type="submit" value="' . $settings['register_btn'] . '">' . $msg . $login_t . '</form>';
			}

			if ( $settings['pass_r'] ) {
				$cpr = $settings['content_pr'] ? '<div class="cz_lrpr_title mb30">' . do_shortcode( $settings['content_pr'] ) . '</div>' : '';
				$out .= '<form id="cz_pr">' . $cpr . $action . $pass_r . self::security( 'password' ) . $gdpr . '<input type="submit" value="' . $settings['pass_r_btn'] . '">' . $msg . $login_t . '</form>';
			}

			$out .= do_action( 'wordpress_social_login' );
		}

		$out .= '</div>';

		echo $out;
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