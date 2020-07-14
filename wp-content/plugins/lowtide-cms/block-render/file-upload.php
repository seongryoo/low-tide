<?php

/* Block registration --------------------------------------------- */

function lowtide_register_file_upload_block() {
  if ( ! function_exists( 'register_block_type' ) ) {
    return;
  }
  
  $register_args = array(
    'attributes' => array(
      'mediaId' => array(
        'type' => 'number',
      ),
      'mediaName' => array(
        'type' => 'string',
      ),
      'mediaUrl' => array(
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
    'render_callback' => 'lowtide_file_upload_render',  
  );
  
  register_block_type( 'lowtide/file-upload', $register_args );
  
}

add_action( 'init', 'lowtide_register_file_upload_block' );

/* Render ------------------------------------------------ */

function lowtide_file_upload_render( $attributes ) {
  $id   = $attributes[ 'mediaId' ];
  $name = $attributes[ 'mediaName' ];
  $text = $attributes[ 'displayText' ];
  $aria = $attributes[ 'aria' ];
  $url  = $attributes[ 'mediaUrl' ];

  $ariaOptional = '';
  
  if ( $aria != '' ) {
    $ariaOptional = 'aria-label="' . $aria . '"';
  }
  
  $markup = '';
  $markup .= '<li class="no-list">';
    $markup .= '<a href="' . esc_url( $url ) . '" ' . $ariaOptional . '>' . $text . '</a>';
  $markup .= '</li>';
  
  
  return $markup;
  
}