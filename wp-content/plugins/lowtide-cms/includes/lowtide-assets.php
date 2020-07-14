<?php


/* Loading GCP block assets (js file and administrative css) ----------------------- */

function lowtide_load_blocks() {

  wp_enqueue_script(
    'lowtide-event-helpers',
    plugin_dir_url( __FILE__ ) . '../assets/js/event-helpers.js'
  );
  
  $scripts = array(
    'backlink',
    'card',
    'contained-width',
    'event',
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
    'lowtide-event-helpers',
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
