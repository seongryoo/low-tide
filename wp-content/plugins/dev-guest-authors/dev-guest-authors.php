<?php

/**
 * Plugin Name: Guest Author Sidebar (for developers)
 */

class GuestAuthors {
  private $slugin = 'dev-guest-authors';
  private $scripts = array(
    'guest-authors',
    'register-plugin',
  );
  private $styles = array(

  );
  function __construct() {
    // Load block editor assets
    add_action( 'enqueue_block_editor_assets', function() {
      $wp_deps = array(
        'wp-blocks',
        'wp-editor',
        'wp-edit-post',
      );
      foreach( $this->scripts as $script ) {
        $handle = $this->slugin . '-' . $script;
        $url = plugin_dir_url( __FILE__ ) . 'js/' . $script . '.js';
        wp_enqueue_script( $handle, $url, $wp_deps );
      }
      foreach( $this->styles as $style ) {
        $handle = $this->slugin . '-' . $style;
        $url = plugin_dir_url( __FILE__ ) . 'css/' . $style . '.css';
        wp_enqueue_style( $handle, $url );
      }
    } );

    // Make js scripts into modules
    add_filter( 'script_loader_tag', function( $tag, $handle, $src ) {
      foreach( $this->scripts as $script ) {
        $script_handle = $this->slugin . '-' . $script;
        if ($script_handle == $handle) {
          return '<script type="module" src="' . esc_url( $src ) . '"></script>';
        }
      }
      return $tag;
    }, 10, 3 );

    // Plugin behavior
    add_action( 'init', function() {
      register_post_meta( 'post', 'dev_guest_author_name', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
      ) );
      register_post_meta( 'post', 'dev_guest_author_img', array(
        'show_in_rest' => true,
        'single' => true,
        'type' => 'string',
      ) );
    } );
  }
}


$guestAuthors = new GuestAuthors();