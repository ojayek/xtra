<?php if ( ! defined( 'ABSPATH' ) ) {exit;} // Exit if accessed directly.

/**
 * Image select control for elementor.
 *
 * @since v1.0.0
 * @uses `\Elementor\Base_Data_Control` class.
 */
class Xtra_Elementor_Control_Image_Select extends \Elementor\Base_Data_Control {

	/**
	 * Control type.
	 *
	 * @return String
	 */
	public function get_type() {
		return 'image_select';
	}

	/**
	 * Custom control scripts.
	 *
	 * @return String
	 */
	public function enqueue() {

		echo '<style>
.elementor-control-image-selector-wrapper input[type="radio"] {
	display: none;
}
.elementor-label-block .elementor-control-image-selector-wrapper{
	width: 100%;
	margin-top: 10px;
}
.elementor-control-image-selector-wrapper .elementor-image-selector-label {
	display: inline-block;
	border: 3px solid transparent;
	cursor: pointer;
	overflow: hidden;
	width: calc(25% - 3px)
}
.elementor-control-image-selector-wrapper input:checked+.elementor-image-selector-label {
	border: 3px solid #a4afb7;
}
</style>';

	}

	/**
	 * Control template
	 *
	 * @return String template HTML content
	 */
	public function content_template() {

		$control_uid = $this->get_control_uid('{{ value }}');
		?>
		<div class="elementor-control-field">
			<label class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-image-selector-wrapper">
				<# _.each( data.options, function( options, value ) { #>
				<input id="<?php echo $control_uid; ?>" type="radio" name="elementor-image-selector-{{ data.name }}-{{ data._cid }}" value="{{ value }}" data-setting="{{ data.name }}">
				<label class="elementor-image-selector-label tooltip-target" for="<?php echo $control_uid; ?>" data-tooltip="{{ options.title }}" title="{{ options.title }}">
					<img src="{{ options.url }}" alt="{{ options.title }}">
					<span class="elementor-screen-only">{{{ options.title }}}</span>
				</label>
				<# } ); #>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php

	}

}