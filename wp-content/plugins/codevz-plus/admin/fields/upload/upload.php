<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 *
 * Field: Upload
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if ( ! class_exists( 'CSF_Field_upload' ) ) {
  class CSF_Field_upload extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '' ) {
      parent::__construct( $field, $value, $unique, $where );
    }

    public function output() {

      echo $this->element_before();

      $value = $this->element_value();

      $upload_type  = ( ! empty( $this->field['settings']['upload_type']  ) ) ? $this->field['settings']['upload_type']  : '';
      $button_title = ( ! empty( $this->field['settings']['button_title'] ) ) ? $this->field['settings']['button_title'] : esc_html__( 'Upload', 'codevz' );
      $frame_title  = ( ! empty( $this->field['settings']['frame_title']  ) ) ? $this->field['settings']['frame_title']  : esc_html__( 'Upload', 'codevz' );
      $insert_title = ( ! empty( $this->field['settings']['insert_title'] ) ) ? $this->field['settings']['insert_title'] : esc_html__( 'Use Image', 'codevz' );

      if( ! empty( $this->field['preview'] ) ) {

        // CODEVZ
        $image  = ( empty( $value ) ) ? '' : $value;
        $hidden = ( empty( $value ) ) ? ' hidden' : '';

        echo '<div class="csf-image-preview'. $hidden .'">';
        echo '<div class="csf-image-inner"><i class="fa fa-times csf-image-remove"></i><img src="'. $image .'" alt="preview" /></div>';
        echo '</div>';

      }

      echo '<div class="csf-table">';
      echo '<div class="csf-table-cell"><input type="text" name="'. $this->element_name() .'" value="'. $value .'"'. $this->element_class() . $this->element_attributes() .'/></div>';
      echo '<div class="csf-table-cell"><a href="#" class="button csf-button" data-frame-title="'. $frame_title .'" data-upload-type="'. $upload_type .'" data-insert-title="'. $insert_title .'">'. $button_title .'</a></div>';
      echo '</div>';

      echo $this->element_after();

    }
  }
}
