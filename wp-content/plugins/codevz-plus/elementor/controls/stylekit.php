<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

/**
 * Image select control for elementor.
 *
 * @since v1.0.0
 * @uses `\Elementor\Control_Base_Multiple` class.
 */
class Xtra_Elementor_Control_StyleKit extends \Elementor\Control_Base_Multiple {

	/**
	 * Control type.
	 *
	 * @return String
	 */
	public function get_type() {
		return 'stylekit';
	}

	/**
	 * Custom control scripts.
	 *
	 * @return String
	 */
	public function enqueue() {

		wp_enqueue_script( 'xtra-elementor-stylekit', Codevz_Plus::$url . 'elementor/assets/js/stylekit.js', [], Codevz_Plus::$ver, false );

	}

	/**
	 * Control template
	 *
	 * @return String template HTML content
	 */
	public function content_template() {

	?>

		<div class="elementor-control-field">

			<label class="elementor-control-title">{{{ data.label }}}</label>

			<div class="elementor-control-input-wrapper">

				<#
					var is_active = data.controlValue.length ? 'active_stylekit' : '',
						fields = data.settings.join( ' ' );
				#>

				<input type="hidden" name="sk" data-fields="{{{ fields }}}" />

				<a href="#" class="button cz_sk_btn {{{ is_active }}}" data-name="{{{ data.name }}}" data-sk="<?php echo esc_attr( $this->get_control_uid( 'sk' ) ); ?>"><span class="cz_skico cz"></span>{{{ data.label }}}</a><div class="sk_btn_preview_image"></div>

			</div>

		</div>

		<# if ( data.description ) { #>

			<div class="elementor-control-field-description">{{ data.description }}</div>

		<# } #>

	<?php

	}

}