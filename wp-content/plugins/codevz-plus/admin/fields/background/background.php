<?php if ( ! defined( 'ABSPATH' ) ) { die; } // Cannot access pages directly.
/**
 * EDITED BY CODEVZ
 * Field: Background
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 */
if( ! class_exists( 'CSF_Field_background' ) ) {
  class CSF_Field_background extends CSF_Fields {

    public function __construct( $field, $value = '', $unique = '', $where = '' ) {
      parent::__construct( $field, $value, $unique, $where );
    }

    public function output() {

      echo $this->element_before();

      $value_defaults = array(
        'image'       => '',
        'repeat'      => '',
        'position'    => '',
        'attachment'  => '',
        'size'        => '',
        'color'       => '',
        'color2'      => '',
        'color3'      => '',
        'orientation' => '90deg',
      );

      $this->value  = wp_parse_args( $this->element_value(), $value_defaults );

      if( isset( $this->field['settings'] ) ) { extract( $this->field['settings'] ); }

      $upload_type  = ( isset( $upload_type  ) ) ? $upload_type  : 'image';
      $button_title = ( isset( $button_title ) ) ? $button_title : esc_html__( 'Upload', 'codevz' );
      $frame_title  = ( isset( $frame_title  ) ) ? $frame_title  : esc_html__( 'Upload', 'codevz' );
      $insert_title = ( isset( $insert_title ) ) ? $insert_title : esc_html__( 'Use Image', 'codevz' );
      $wrap_class   = ( isset( $this->field['wrap_class'] ) ) ? $this->field['wrap_class'] : '';

      echo '<div class="clr mb10">';
      echo '<div class="col s5">' . csf_add_field( array(
          'wrap_class'  => $wrap_class,
          'pseudo'      => true,
          'id'          => $this->field['id'].'_color',
          'type'        => 'color_picker',
          'name'        => $this->element_name('[color]'),
          'title'       => esc_html__( 'Color', 'codevz' ),
          'attributes'  => array(
            'data-atts' => 'bgcolor',
          ),
          'default'     => ( isset( $this->field['default']['color'] ) ) ? $this->field['default']['color'] : '',
          'rgba'        => ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
      ), $this->value['color'], '', 'field/background' ) . '</div>';

      echo '<div class="col s7"><div class="csf-field csf-field-upload csf-pseudo-field '.  $wrap_class .'">';
      echo '<div class="csf-title"><h4>' . esc_html__( 'Image', 'codevz' ) . '</h4></div>';
      echo '<div class="csf-fieldset">';
      echo '<div class="csf-table-cell"><input type="text" name="'. $this->element_name( '[image]' ) .'" value="'. $this->value['image'] .'"'. $this->element_class() . $this->element_attributes() .'/></div>';
      echo '<div class="csf-table-cell"><a href="#" class="button csf-button" data-frame-title="'. $frame_title .'" data-upload-type="'. $upload_type .'" data-insert-title="'. $insert_title .'">'. $button_title .'</a></div>';
      echo '</div></div></div>';

      echo '</div>';

      echo '<div class="clr cz_bg_advanced" style="display:none">';

      echo '<div class="clr cz_hr"></div>';

      // CODEVZ
      echo '<div class="col s5 col_first">' . csf_add_field( array(
          'wrap_class'  => $wrap_class,
          'pseudo'      => true,
          'id'          => $this->field['id'].'_color2',
          'type'        => 'color_picker',
          'name'        => $this->element_name('[color2]'),
          'title'       => esc_html__( 'Color 2', 'codevz' ),
          'attributes'  => array(
            'data-atts' => 'bgcolor',
          ),
          'default'     => ( isset( $this->field['default']['color2'] ) ) ? $this->field['default']['color2'] : '',
          'rgba'        => ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
          'dependency'  => array( 'background_color', '!=', '' ),
      ), $this->value['color2'], '', 'field/background' ) . '</div>';

      echo '<div class="col s5">' . csf_add_field( array(
          'wrap_class'  => $wrap_class,
          'pseudo'      => true,
          'id'          => $this->field['id'].'_color3',
          'type'        => 'color_picker',
          'name'        => $this->element_name('[color3]'),
          'title'       => esc_html__( 'Color 3', 'codevz' ),
          'attributes'  => array(
            'data-atts' => 'bgcolor',
          ),
          'default'     => ( isset( $this->field['default']['color3'] ) ) ? $this->field['default']['color3'] : '',
          'rgba'        => ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
          'dependency'  => array( 'background_color', '!=', '' ),
      ), $this->value['color3'], '', 'field/background' ) . '</div>';

      echo '<div class="col s2" style="width: 16%">' . csf_add_field( array(
          'wrap_class'  => $wrap_class,
          'pseudo'      => true,
          'id'          => $this->field['id'].'_orientation',
          'type'        => 'slider',
          'name'        => $this->element_name('[orientation]'),
          'title'       => '',
          'attributes'  => array(
            'placeholder' => esc_html__( 'Orientation', 'codevz' ),
          ),
          'options'     => array( 'unit' => 'deg', 'step' => 1, 'min' => 0, 'max' => 360 ),
          'default'     => '90deg',
          'rgba'        => ( isset( $this->field['rgba'] ) && $this->field['rgba'] === false ) ? false : '',
          'dependency'  => array( 'background_color', '!=', '' ),
      ), $this->value['orientation'], '', 'field/background' ) . '</div>';

      echo csf_add_field( array(
          'type'        => 'content',
          'content'     => '<div class="clr cz_hr"></div>',
          'dependency'  => array( 'background', '!=', '' ),
      ), $this->value['repeat'], '', 'field/background' );

      // background attributes
      echo '<fieldset>';
      echo csf_add_field( array(
          'wrap_class'  => $wrap_class,
          'pseudo'      => true,
          'type'        => 'select',
          'name'        => $this->element_name( '[layer]' ),
          'options'     => array(
            ''  => esc_html__( 'Color on image', 'codevz' ),
            '1' => esc_html__( 'Image on color', 'codevz' ),
          ),
          'attributes'  => array(
            'data-atts' => 'repeat',
          ),
          'dependency' => array( 'background', '!=', '' ),
      ), $this->value['repeat'], '', 'field/background' );
      echo csf_add_field( array(
          'wrap_class'  => $wrap_class,
          'pseudo'      => true,
          'type'        => 'select',
          'name'        => $this->element_name( '[repeat]' ),
          'options'     => array(
            ''          => 'repeat',
            'repeat-x'  => 'repeat-x',
            'repeat-y'  => 'repeat-y',
            'no-repeat' => 'no-repeat',
            'inherit'   => 'inherit',
          ),
          'attributes'  => array(
            'data-atts' => 'repeat',
          ),
          'dependency' => array( 'background', '!=', '' ),
      ), $this->value['repeat'], '', 'field/background' );

      echo csf_add_field( array(
          'wrap_class'      => $wrap_class,
          'pseudo'          => true,
          'type'            => 'select',
          'name'            => $this->element_name( '[position]' ),
          'options'         => array(
            ''              => 'left top',
            'left center'   => 'left center',
            'left bottom'   => 'left bottom',
            'right top'     => 'right top',
            'right center'  => 'right center',
            'right bottom'  => 'right bottom',
            'center top'    => 'center top',
            'center center' => 'center center',
            'center bottom' => 'center bottom'
          ),
          'attributes'      => array(
            'data-atts'     => 'position',
          ),
          'dependency'  => array( 'background', '!=', '' ),
      ), $this->value['position'], '', 'field/background' );

      echo csf_add_field( array(
          'wrap_class'  => $wrap_class,
          'pseudo'      => true,
          'type'        => 'select',
          'name'        => $this->element_name( '[attachment]' ),
          'options'     => array(
            ''          => 'scroll',
            'fixed'     => 'fixed',
          ),
          'attributes'  => array(
            'data-atts' => 'attachment',
          ),
          'dependency'  => array( 'background', '!=', '' ),
      ), $this->value['attachment'], '', 'field/background' );

      echo csf_add_field( array(
          'wrap_class'  => $wrap_class,
          'pseudo'      => true,
          'type'        => 'select',
          'name'        => $this->element_name( '[size]' ),
          'options'     => array(
            ''          => 'size',
            'cover'     => 'cover',
            'contain'   => 'contain',
            'inherit'   => 'inherit',
            'initial'   => 'initial',
          ),
          'attributes'  => array(
            'data-atts' => 'size',
          ),
          'dependency'  => array( 'background', '!=', '' ),
      ), $this->value['size'], '', 'field/background' );

      echo '</fieldset></div>';

      echo '<a class="button cz_advance_bg" href="#">' . esc_html__( 'Background Settings', 'codevz' ) . '<i class="fas fa-angle-down"></i></a>';


      echo $this->element_after();

    }
  }
}
