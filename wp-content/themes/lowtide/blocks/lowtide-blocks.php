<?php

function lowtide_load_blocks() {
  wp_enqueue_script(
    'lowtide-blocks',
    get_template_directory_uri() . '/js/lowtide-blocks.js',
    array( 'wp-blocks', 'wp-i18n', 'wp-editor' ),
    true
  );
  wp_enqueue_style( 'lowtide-admin-style', get_template_directory_uri() . '/css/admin.css' );
}

add_action( 'enqueue_block_editor_assets', 'lowtide_load_blocks' );


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

    'render_callback' => 'lowtide_render_contained_width_block',
  
  );

  register_block_type( 'lowtide/width-container', $register_args );

}

add_action( 'init', 'lowtide_register_contained_width_block' );
