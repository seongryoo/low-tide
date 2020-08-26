<?php

// Register External News post type
function lowtide_register_ext_news() {
  $labels = array(
    'name'                    => 'External News',
    'singular_name'           => 'Article',
    'menu_name'               => 'External News',
    'add_new'                 => 'Add New',
    'add_new_item'            => 'Add New Article',
    'new_item'                => 'New Article',
    'edit_item'               => 'Edit Article',
    'view_item'               => 'View Article',
    'all_items'               => 'All External News',
    'search_items'            => 'Search External News',
    'not_found'               => 'No articles found.',
    'not_found_in_trash'      => 'No articles found in Trash.',
    'archives'                => 'External News archives',
    'filter_items_list'       => 'Filter external articles list',
    'items_list_navigation'   => 'External articles list navigation',
    'items_list'              => 'External articles list',
  );

  $args = array(
    'labels'                  => $labels,
    'public'                  => true,
    'menu_icon'               => 'dashicons-id-alt',
    'show_in_rest'            => true,
    'publicly_queryable'      => false,
  );

  register_post_type( 'post_ext_news', $args );

  $supports = array(
    'custom-fields',
  );
  add_post_type_support( 'post_ext_news', $supports );
}

add_action( 'init', 'lowtide_register_ext_news' );

// Register custom meta
function lowtide_register_ext_news_meta() {
  $args = array(
    'show_in_rest'            => true,
    'single'                  => true,
    'type'                    => 'string',
  );

  $fileArgs = array(
    'show_in_rest'            => true,
    'single'                  => true,
    'type'                    => 'number',
  );

  register_post_meta( 'post_ext_news', 'post_ext_news_meta_img', $fileArgs );
  register_post_meta( 'post_ext_news', 'post_ext_news_meta_img_url', $args );
  register_post_meta( 'post_ext_news', 'post_ext_news_meta_logo', $fileArgs );
  register_post_meta( 'post_ext_news', 'post_ext_news_meta_logo_url', $args );
  register_post_meta( 'post_ext_news', 'post_ext_news_meta_link', $args );
  register_post_meta( 'post_ext_news', 'post_ext_news_meta_date', $args );
}

add_action( 'init', 'lowtide_register_ext_news_meta' );

// Register block template
function lowtide_register_ext_news_data_block_template() {
  $ext_news_object = get_post_type_object( 'post_ext_news' );
  $ext_news_object->template = array(
    array( 'lowtide/ext-news-data' ),
  );
  $ext_news_object->template_lock = 'all';
}

add_action( 'init', 'lowtide_register_ext_news_data_block_template' );

// Change title placeholder for external news
add_filter( 'enter_title_here', 'lowtide_ext_news_custom_enter_title' );
function lowtide_ext_news_custom_enter_title( $input ) {
  if ( 'post_ext_news' === get_post_type() ) {
    return 'Article title';
  }
  return $input;
}