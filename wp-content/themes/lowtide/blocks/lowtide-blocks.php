<?php

function lowtide_load_blocks() {
  wp_enqueue_script(
    'lowtide-blocks',
    get_template_directory_uri() . '/js/lowtide-blocks.js',
    array( 'wp-blocks', 'wp-i18n', 'wp-editor' ),
    true
  );
  
  scream( get_template_directory_uri() . '/js/lowtide-blocks.js' );
  
  scream( 'blocks loaded ');
}

add_action( 'enqueue_block_editor_assets', 'lowtide_load_blocks' );


function lowtide_register_contained_width_block() {
  
  // Only load if Gutenberg is available.

  if ( ! function_exists( 'register_block_type' ) ) {

    return;

  }


  register_block_type(
    'lowtide/contained-width',
    array(
      
      'attributes' => array(
        
        'content' => array(
          'type' => 'string',
        ),
        
        'className' => array(
          'type' => 'string',
        ),
        
      ),
      
      'render_callback' => 'lowtide_render_contained_width_block',
    )
  );

}

add_action( 'init', 'lowtide_register_contained_width_block' );

function lowtide_render_contained_width_block( $attributes ) {
  
  $html = $attributes[ 'content' ];
  
  return '<div class="width-contained">Goob' . $html . '</div>';
}