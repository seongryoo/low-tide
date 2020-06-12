<?php

/* Creating block category for GCP  ----------------------- */

function lowtide_block_category( $categories, $post ) {
  return array_merge(
    $categories,
    array(
      array(
        'slug' => 'lowtide-blocks',
        'title' => __( 'Global Change', 'lowtide-blocks' ),
        'icon' => 'dashicons-admin-site-alt',
      ),
    )
  );
}
add_filter( 'block_categories', 'lowtide_block_category', 10, 2 );


/* Loading GCP block assets (js file and administrative css) ----------------------- */

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


/* Block registration functions ----------------------- */

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
    'render_callback' => 'lowtide_width_container_render',
  );

  register_block_type( 'lowtide/width-container', $register_args );
}

add_action( 'init', 'lowtide_register_contained_width_block' );

function lowtide_register_card_block() {
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
    'render_callback' => 'lowtide_card_block_render',
  );

  register_block_type( 'lowtide/card', $register_args );

}

add_action( 'init', 'lowtide_register_card_block' );

function lowtide_register_group_block() {
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
    'render_callback' => 'lowtide_group_block_render',
  );

  register_block_type( 'lowtide/basic-group', $register_args );

}

add_action( 'init', 'lowtide_register_group_block' );

function lowtide_register_image_block() {
  if ( ! function_exists( 'register_block_type' ) ) {
    return;
  }
  
  $register_args = array(
    'attributes' => array(
      'image' => array(
        'type' => 'string',
        'source' => 'attribute',
        'selector' => 'img',
        'attribute' => 'src',
      ),
      'altText' => array(
        'type' => 'string',
        'source' => 'attribute',
        'selector' => 'a',
        'attribute' => 'aria-label',
      ),
      'title' => array(
        'type' => 'string',
      ),
      'description' => array(
        'type' => 'string',
      ),
      'linkUrl' => array(
        'type' => 'string',
        'source' => 'attribute',
        'selector' => 'a',
        'attribute' => 'href',
      ),
      'className' => array(
        'type' => 'string',
      ),
    ),
    'render_callback' => 'lowtide_image_block_render',
  );

  register_block_type( 'lowtide/image-block', $register_args );

}

add_action( 'init', 'lowtide_register_image_block' );


/* Render functions ----------------------- */

function lowtide_width_container_render( $attributes, $content ) {
  $markup = '';
  $markup .= '<div class="row justify-content-md-center">';
    $markup .= '<div class="col-md-9">';
      $markup .= $content;
    $markup .= '</div>';
  $markup .= '</div>';
  
  return $markup;
}


function lowtide_card_block_render( $attributes, $content ) {
  $markup = '';
  $markup .= '<div class="card">';
    $markup .= '<div class="card-body">';
      $markup .= $content;
    $markup .= '</div>';
  $markup .= '</div>';
  
  return $markup;
}

function lowtide_group_block_render( $attributes, $content ) {
  $markup = '';
  $markup .= '<div class="section ' . $attributes['className'] . '">';
    $markup .= '<div class="container">';
      $markup .= $content;
    $markup .= '</div>';
  $markup .= '</div>';
  
  return $markup;
}