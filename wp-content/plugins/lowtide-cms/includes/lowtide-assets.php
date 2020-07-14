<?php


/* Loading GCP block assets (js file and administrative css) ----------------------- */

function lowtide_load_blocks() {
  
  $scripts = array(
    'backlink',
    'card',
    'contained-width',
    'event-data',
    'file-upload',
    'group',
    'link',
    'quote',
    'two-col-main',
    'two-col-related-docs',
    'news-link',
  );
  
  $deps = array(
    'wp-blocks',
    'wp-i18n',
    'wp-editor',
    'wp-date',
  );
  
  foreach ( $scripts as $tag ) {
    $script_name = 'lowtide-' . $tag;
    $url = plugin_dir_url( __FILE__ ) . '../assets/js/' . $tag . '.js';
    wp_enqueue_script( $script_name, $url, $deps );
  }
  
  wp_enqueue_style( 
    'lowtide-admin-style', 
    plugin_dir_url( __FILE__ ) . '../assets/css/admin.css'
  );
  wp_localize_script( 
    'lowtide-news-link', 
    'scriptData', 
    array(
      'pluginUrl' => plugin_dir_url( __FILE__ ) . '../',
    ) 
  );
}

add_action( 'enqueue_block_editor_assets', 'lowtide_load_blocks' );
