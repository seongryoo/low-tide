<?php 

/* Block registration ---------------------------------------- */

function lowtide_register_two_col_main_block() {
  if ( ! function_exists( 'register_block_type' ) ) {
    return;
  }
  
  $register_args = array(
    'attributes' => array(
      'content' => array(
        'type' => 'string',
      ),
      'className' => array(
        'type' => 'string',
      ),
    ),
    'render_callback' => 'lowtide_two_col_main_render',
  );
  
  register_block_type( 'lowtide/two-col-main', $register_args );
}

add_action( 'init', 'lowtide_register_two_col_main_block' );

/* Render functions ---------------------------------------*/

function lowtide_two_col_main_render( $attributes, $content ) {
  $markup = '';
  $markup .= '<main id="content" class="col-md-8">';
    $markup .= $content;
  $markup .= '</main>';
  
  return $markup;
}