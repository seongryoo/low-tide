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
    array( 'wp-blocks', 'wp-i18n', 'wp-editor', 'wp-date' ),
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

function lowtide_register_quote_block() {
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
    'render_callback' => 'lowtide_quote_block_render',
  );

  register_block_type( 'lowtide/quote-block', $register_args );

}

add_action( 'init', 'lowtide_register_quote_block' );


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


function lowtide_register_event_block() {
  if ( ! function_exists( 'register_block_type' ) ) {
    return;
  }
  
  $register_args = array(
    'attributes' => array(
      
      'date' => array(
        'type' => 'string',
      ),
      
    ),
    'render_callback' => 'lowtide_event_block_render',
  );

  register_block_type( 'lowtide/event-block', $register_args );

}

add_action( 'init', 'lowtide_register_event_block' );

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

function lowtide_quote_block_render( $attributes ) {
  $text = $attributes[ 'content' ];
  
  $markup = '';
  $markup .= '<blockquote class="card bio-quote ' . $attributes[ 'className' ] . '">';
    $markup .= '<div class="card-body">';
      $markup .= '<p class="quote-text">';
        $markup .= $text;
      $markup .= '</p>';
    $markup .= '</div>';
  $markup .= '</blockquote>';
  
  return $markup;
}

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

function lowtide_event_block_render( $attributes ) {
  $date = date_create( $attributes[ 'date' ] );
  $name = $attributes[ 'name' ];
  $desc = $attributes[ 'desc' ];
  $startTime = date_create($attributes[ 'startTime' ]);
  $endTime = date_create($attributes[ 'endTime' ]);
  
  $date_day = date_format( $date, 'D' );
  $date_num = date_format( $date, 'j' );
  $date_month = date_format( $date, 'M' );
  
  $time_start = date_format( $startTime, 'g:i A' );
  $time_end = date_format( $endTime, 'g:i A' );
  $time_zone = date_format( $startTime, 'e' );
              
  $markup = '';
  $markup .= '<div class="row events">';
  
  $markup .= '<div class="col-md-2 event-date">';
    $markup .= '<h5 class="date-box-prefix">' . $date_day . '</h5>';
    $markup .= '<div class="date-box">';
      $markup .= '<h5>' . $date_month . '</h5>';
      $markup .= '<h3>' . $date_num . '</h3>';
    $markup .= '</div>';
  $markup .= '</div>';
  
  $markup .= '<div class="col-md-9 event-body">';
    $markup .= '<h4 class="event-title">' . $name . '</h4>';
    $markup .= '<p class="event-time">' . $time_start . ' - ' . $time_end . ' ' . $time_zone . '</p>';
    $markup .= '<p class="event-description">' . $desc . '</p>';
  $markup .= '</div>';
  
  $markup .= '</div>';
  
  
  return $markup;
}