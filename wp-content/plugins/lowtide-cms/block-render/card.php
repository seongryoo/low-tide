<?php

/* Block registration functions ----------------------- */

function lowtide_register_card_block() {
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
    'render_callback' => 'lowtide_card_block_render',
  );

  register_block_type( 'lowtide/card', $register_args );

}

add_action( 'init', 'lowtide_register_card_block' );

/* Render functions ----------------------- */

function lowtide_card_block_render( $attributes, $content ) {
  $markup = '';
  $markup .= '<div class="card">';
    $markup .= '<div class="card-body">';
      $markup .= $content;
    $markup .= '</div>';
  $markup .= '</div>';
  
  return $markup;
}
