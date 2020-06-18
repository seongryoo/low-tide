<?php

/* Creating block category for GCP  ----------------------- */

function lowtide_register_quote_block() {
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
    'render_callback' => 'lowtide_quote_block_render',
  );

  register_block_type( 'lowtide/quote-block', $register_args );

}

add_action( 'init', 'lowtide_register_quote_block' );


function lowtide_quote_block_render( $attributes ) {
  $text = $attributes[ 'content' ];
  
  $markup = '';
  $markup .= '<blockquote class="card bio-quote">';
    $markup .= '<div class="card-body">';
      $markup .= '<p class="quote-text">';
        $markup .= $text;
      $markup .= '</p>';
    $markup .= '</div>';
  $markup .= '</blockquote>';
  
  return $markup;
}
