<?php

/* Block registration -------------------------------- */

function lowtide_register_contained_width_block() {
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
    'render_callback' => 'lowtide_width_container_render',
  );

  register_block_type( 'lowtide/width-container', $register_args );
}

add_action( 'init', 'lowtide_register_contained_width_block' );


/* Render ----------------------------------------------------- */

function lowtide_width_container_render( $attributes, $content ) {
  $markup = '';
  $markup .= '<div class="row justify-content-md-center">';
    $markup .= '<div class="col-md-9">';
      $markup .= $content;
    $markup .= '</div>';
  $markup .= '</div>';
  
  return $markup;
}
