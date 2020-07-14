<?php

/* Registration  ----------------------- */

function lowtide_register_back_link_block() {
  if ( ! function_exists( 'register_block_type' ) ) {
    return;
  }
  
  $register_args = array(
    'attributes' => array(
      'linkUrl' => array(
        'type' => 'string',
      ),
      'linkText' => array(
        'type' => 'string',
      ),
      'linkAria' => array(
        'type' => 'string',
      ),
      'className' => array(
        'type' => 'string',
      ),
    ),
    'render_callback' => 'lowtide_back_link_block_render',
  );

  register_block_type( 'lowtide/back-link-block', $register_args );

}

add_action( 'init', 'lowtide_register_back_link_block' );


/* Rendering -------------------------------------*/

function lowtide_back_link_block_render( $attributes ) {
  $linkText = $attributes[ 'linkText' ];
  $linkUrl  = $attributes[ 'linkUrl' ];
  $linkAria = $attributes[ 'linkAria' ];
  $linkLabelOptional = '';
  
  if ( $linkAria != '' ) {
    $linkLabelOptional = 'aria-label="' . $linkAria . '"';
  }
  
  $markup = '';
  $markup .= '<nav aria-label="Breadcrumbs" class="breadcrumbs">';
    $markup .= '<p>';
      $markup .= '<a href="' . $linkUrl . '" ' . $linkLabelOptional . ' class="back-link">';
        $markup .= '<span aria-hidden="true">❮ </span>';
        $markup .= $linkText;
      $markup .= '</a>';
    $markup .= '</p>';
  $markup .= '</nav>';
  
  return $markup;
}
