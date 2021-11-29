<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


function xtra_admin_notice_warning() {

	if ( is_plugin_active( 'rtl-xtra/rtl-xtra.php' ) ) {
		?>
			<div class="notice notice-warning">
				<p>لطفا افزونه "افزودنی های فارسی قالب اکسترا" را غیرفعال کنید (سپس پاک کنید) <br/>
از نگارش 4.0.0 به بالاتر دیگر نیازی به این افزونه نمی باشد.
</p>
			</div>
		<?php
	}

	esc_html__( 'Codevz Plus', 'codevz' );
	esc_html__( 'StyleKit, custom post types, options and page builder elements.', 'codevz' );

}
add_action( 'admin_notices', 'xtra_admin_notice_warning' );

function xtra_plugin_load_my_own_textdomain( $mofile, $domain ) {
   
	if ( 'revslider' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
		$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
		$mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/revslider-fa_IR.mo';
	}
	return $mofile;
}
add_filter( 'load_textdomain_mofile', 'xtra_plugin_load_my_own_textdomain', 10, 2 );

function xtra_add_rtl_xtra_front() {
	wp_enqueue_style( 'rtl-xtra-front', plugin_dir_url(__FILE__) . 'css/rtl-xtra-front.css' );
	wp_enqueue_style( 'rtl-js_composer_front', plugin_dir_url(__FILE__) . 'css/xtra_js_composer.min.css', [ 'js_composer_front' ] );
}
add_action( 'wp_enqueue_scripts', 'xtra_add_rtl_xtra_front' );

function xtra_add_rtl_xtra_back() {
	wp_enqueue_style( 'rtl-xtra-back', plugin_dir_url(__FILE__) . 'css/rtl-xtra-back.css' );
}
add_action( 'admin_enqueue_scripts', 'xtra_add_rtl_xtra_back' );

function xtra_vc_backend_editor_enqueue_js_css() {
	wp_enqueue_style( 'rtl-js_composer', plugin_dir_url(__FILE__) . 'css/xtra_js_composer_backend_editor.min.css', [ 'js_composer' ] );
}
add_action( 'vc_backend_editor_enqueue_js_css', 'xtra_vc_backend_editor_enqueue_js_css' );

function xtra_vc_load_iframe_jscss() {
	wp_enqueue_style( 'rtl-js_composer_front', plugin_dir_url(__FILE__) . 'css/xtra_js_composer.min.css', [ 'js_composer_front' ] );
	wp_enqueue_style( 'rtl-vc_inline_css_iframe', plugin_dir_url(__FILE__) . 'css/xtra_js_composer_frontend_editor_iframe.min.css', [ 'vc_inline_css' ] );
}
add_action( 'vc_load_iframe_jscss', 'xtra_vc_load_iframe_jscss' );

function xtra_vc_frontend_editor_enqueue_js_css() {
	wp_enqueue_style( 'rtl-vc_inline_css', plugin_dir_url(__FILE__) . 'css/xtra_js_composer_frontend_editor.min.css', [ 'vc_inline_css' ] );
}
add_action( 'vc_frontend_editor_enqueue_js_css', 'xtra_vc_frontend_editor_enqueue_js_css' );


function xtra_revslider_data_get_font_familys( $fonts ) {

	$persian_fonts = [];

	//Web Safe fonts

	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'iranyekan');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'iranyekan_standard');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'IRANSans');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'IRANSans_standard');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'iransansdn');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'iransansdn_standard');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'Iran Kharazmi');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'Pelak');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'Pelak_standard');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'Javan');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'Azhdar');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'Azhdar_standard');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'mahboubeh_mehravar');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'Shabnam');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'Lalezar');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'broyabold');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'rezvan');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'khodkar');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'DastNevis');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'BTitrBold');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'BYekan');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'BZar');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'BSinaBold');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'BZiba');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'aviny');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'dana');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'Anjoman');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'Anjoman_standard');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'iransharp');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'IRAN');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'Kalameh');
	$persian_fonts[] = array('type' => 'websafe', 'version' => __('Persian Fonts', 'revslider'), 'label' => 'Farhang');
	
	return wp_parse_args( $fonts, $persian_fonts );
}
add_filter( 'revslider_data_get_font_familys', 'xtra_revslider_data_get_font_familys' );

function xtra_csf_fonts_library( $fonts ) {

	$persian_fonts = [];

	//Web Safe Fonts
	$persian_fonts['iranyekan'] = 'iranyekan';
	$persian_fonts['iranyekan_standard'] = 'iranyekan_standard';
	$persian_fonts['IRANSans'] = 'IRANSans';
	$persian_fonts['IRANSans_standard'] = 'IRANSans_standard';
	$persian_fonts['iransansdn'] = 'iransansdn';
	$persian_fonts['iransansdn_standard'] = 'iransansdn_standard';
	$persian_fonts['Iran Kharazmi'] = 'Iran Kharazmi';
	$persian_fonts['Pelak'] = 'Pelak';
	$persian_fonts['Javan'] = 'Javan';
	$persian_fonts['Azhdar'] = 'Azhdar';
	$persian_fonts['Azhdar_standard'] = 'Azhdar_standard';
	$persian_fonts['mahboubeh_mehravar'] = 'mahboubeh_mehravar';
	$persian_fonts['Shabnam'] = 'Shabnam';
	$persian_fonts['Lalezar'] = 'Lalezar';
	$persian_fonts['broyabold'] = 'broyabold';
	$persian_fonts['rezvan'] = 'rezvan';
	$persian_fonts['khodkar'] = 'khodkar';
	$persian_fonts['DastNevis'] = 'DastNevis';
	$persian_fonts['BTitrBold'] = 'BTitrBold';
	$persian_fonts['BYekan'] = 'BYekan';
	$persian_fonts['BZar'] = 'BZar';
	$persian_fonts['BSinaBold'] = 'BSinaBold';
	$persian_fonts['BZiba'] = 'BZiba';
	$persian_fonts['aviny'] = 'aviny';
	$persian_fonts['dana'] = 'dana';
	$persian_fonts['Anjoman'] = 'Anjoman';
	$persian_fonts['Anjoman_standard'] = 'Anjoman_standard';
	$persian_fonts['iransharp'] = 'iransharp';
	$persian_fonts['IRAN'] = 'IRAN';	
	$persian_fonts['Kalameh'] = 'Kalameh';
	$persian_fonts['Farhang'] = 'Farhang';
	

	return wp_parse_args( $fonts, $persian_fonts );
}
add_filter( 'csf/field/fonts/websafe', 'xtra_csf_fonts_library' );
