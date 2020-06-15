<?php

/* Block registration --------------------------------------------- */

function lowtide_register_link_block() {
  if ( ! function_exists( 'register_block_type' ) ) {
    return;
  }
  
  $register_args = array(
    'attributes' => array(
      'url' => array(
        'type' => 'string',
      ),
      'displayText' => array(
        'type' => 'string',
      ),      
      'aria' => array(
        'type' => 'string',
      ),
      'className' => array(
        'type' => 'string',
      ),
    
    ),
    'render_callback' => 'lowtide_link_render',  
  );
  
  register_block_type( 'lowtide/link', $register_args );
  
}

add_action( 'init', 'lowtide_register_link_block' );

/* Render ------------------------------------------------ */

function lowtide_link_render( $attributes ) {
  $text = $attributes[ 'displayText' ];
  $aria = $attributes[ 'aria' ];
  $url  = $attributes[ 'url' ];

  $ariaOptional = '';
  
  if ( $aria != '' ) {
    $ariaOptional = 'aria-label="' . $aria . '"';
  }
  
  $markup = '';
  $markup .= '<li>';
    $markup .= '<a href="' . esc_url( $url ) . '" ' . $ariaOptional . '>' . $text . '</a>';
  $markup .= '</li>';
  
  
  return $markup;
  
}