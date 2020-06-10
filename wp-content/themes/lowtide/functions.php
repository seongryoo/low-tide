<?php

/* Block registration */
include( get_template_directory() . '/blocks/lowtide-blocks.php' );

/* A useful helper method */
function scream( $msg ) {
  
  echo( '<script>console.log("' . $msg . '")</script>' );
  
}

/* Echos wordpress post name into any spot */
function page_slug() {
  global $post;
  $class = '';
  
  
  if (isset ( $post ) ) {
    $class .= $post->post_name;
  }
  
  echo $class;
}

/* Renames default template for clarity */
add_filter('default_page_template_title', function() {
    return 'Single Column Page';
});

/* Applies additional custom styling to nav menu <a> elements */
function lowtide_menu_links( $html ) {
  
   return preg_replace( '/<a /', '<a class="navlink" tabindex="0" ', $html );
  
}

add_filter( 'wp_nav_menu','lowtide_menu_links' );


/* Basic theme registration */
function lowtide_theme_support() {
  
  add_theme_support( 'post-thumbnails' );
  set_post_thumbnail_size( 600, 400 );
  
  add_theme_support( 'custom-logo' );
  
  add_theme_support( 'title-tag' );
  
}

add_action( 'after_setup_theme', 'lowtide_theme_support' );


/* basic style loading */
function lowtide_register_styles() {
  
  $style_dir = get_template_directory_uri() . '/css';
  
  wp_enqueue_style( 'lowtide-style', $style_dir . '/main.css' );
  
}

add_action( 'wp_enqueue_scripts', 'lowtide_register_styles' );


function lowtide_register_scripts() {
  
  $script_dir = get_template_directory_uri() . '/js';
  
  wp_enqueue_script( 'lowtide-strudel', $script_dir . '/strudel.js' );
  
  $strudel_dep = array( 'lowtide-strudel' );
  wp_enqueue_script( 'lowtide-nav', $script_dir . '/nav.js', $strudel_dep );
  add_filter('default_page_template_title', function() {
    return __('My default template name', 'your_text_domain');
});
}

add_action( 'enqueue_block_editor_assets', 'lowtide_register_scripts' );

function lowtide_menus() {
  
  $locations = array(
    'primary'   => 'Primary Menu',
    'footer'    => 'Footer Links',
  );
  
  register_nav_menus( $locations );
}

add_action( 'init', 'lowtide_menus' );

function lowtide_get_custom_logo( $html ) {
  
  $logo_id = get_theme_mod( 'custom_logo' );
  
  if ( ! $logo_id ) {
    return $html;
  }
  
  $logo = wp_get_attachment_image_src( $logo_id, 'full' );
  
  return $html;

}

add_filter( 'get_custom_logo', 'lowtide_get_custom_logo' );

if ( ! function_exists( 'wp_body_open' ) ) {
  
  function wp_body_open() {
    do_action( 'wp_body_open' );
  }

}

function lowtide_read_more_tag( $html ) {

  $search_str = '/<a(.*)>(.*)<\/a>/iU';
  
  $replace_str = sprintf( '<div class="read-more-button-wrap"><a$1><span class="faux-button">$2</span> <span class="screen-reader-text">"%1$s"</span></a></div>', get_the_title( get_the_ID() ) );
  
  return preg_replace( $search_str, $replace_str, $html );
  
}

add_filter( 'the_content_more_link', 'lowtide_read_more_tag' );