<?php

/* Block registration functions ----------------------- */

function lowtide_register_group_block() {
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
    'render_callback' => 'lowtide_group_block_render',
  );

  register_block_type( 'lowtide/basic-group', $register_args );

}

add_action( 'init', 'lowtide_register_group_block' );

/* Render functions ----------------------- */

function lowtide_group_block_render( $attributes, $content ) {
  $markup = '';
  $markup .= '<div class="section">';
    $markup .= '<div class="container">';
      $markup .= $content;
    $markup .= '</div>';
  $markup .= '</div>';
  
  return $markup;
}