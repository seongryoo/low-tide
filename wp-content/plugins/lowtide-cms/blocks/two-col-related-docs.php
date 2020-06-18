<?php 

/* Block registration ---------------------------------------- */

function lowtide_register_two_col_related_docs_block() {
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
    'render_callback' => 'lowtide_two_col_related_docs_render',
  );
  
  register_block_type( 'lowtide/two-col-related-docs', $register_args );
}

add_action( 'init', 'lowtide_register_two_col_related_docs_block' );

/* Render functions ---------------------------------------*/

function lowtide_two_col_related_docs_render( $attributes, $content ) {
  $markup = '';
  $markup .= '<aside class="col-md-4 documents" aria-label="Related documents">';
    $markup .= '<h2>Documents</h2>';
    $markup .= '<ul>';
      $markup .= $content;
    $markup .= '</ul>';
  $markup .= '</aside>';
  
  return $markup;
}