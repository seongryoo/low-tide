<?php


/* Loading GCP block assets (js file and administrative css) ----------------------- */

function lowtide_load_blocks() {
  
  wp_enqueue_script(
    'lowtide-blocks',
    plugins_url( 'js/lowtide-blocks.js', __FILE__),
    array( 'wp-blocks', 'wp-i18n', 'wp-editor', 'wp-date' ),
    true
  );
  
  $scripts = array(
    'backlink',
    'card',
    'contained-width',
    'event',
    'file-upload',
    'group',
    'link',
    'quote',
    'two-col-main',
    'two-col-related-docs',
  );
  
  $deps = array(
    'wp-blocks',
    'wp-i18n',
    'wp-editor',
    'wp-date',
  );
  
  foreach ( $scripts as $tag ) {
    $script_name = 'lowtide-' . $tag;
    $url = plugins_url( 'blocks/js/' . $tag . '.js', __FILE__ );
    
    wp_enqueue_script( $script_name, $url, $deps );
  }
  
  wp_enqueue_style( 'lowtide-admin-style', plugins_url( 'css/admin.css', __FILE__ ));
}

add_action( 'enqueue_block_editor_assets', 'lowtide_load_blocks' );
