<?php

// Register Events post type
function lowtide_register_events() {
  $labels = array(
    'name'                    => 'Events',
    'singular_name'           => 'Event',
    'menu_name'               => 'Events',
    'name_admin_bar'          => 'Event',
    'add_new'                 => 'Add New',
    'add_new_item'            => 'Add New Event',
    'new_item'                => 'New Event',
    'edit_item'               => 'Edit Event',
    'view_item'               => 'View Event',
    'all_items'               => 'All Events',
    'search_items'            => 'Search Events',
    'not_found'               => 'No events found.',
    'not_found_in_trash'      => 'No events found in Trash.',
    'archives'                => 'Event archives',
    'filter_items_list'       => 'Filter events list',
    'items_list_navigation'   => 'Events list navigation',
    'items_list'              => 'Events list',
  );

  $args = array(
    'labels'                  => $labels,
    'public'                  => true,
    'menu_icon'               => 'dashicons-calendar-alt',
    'show_in_rest'            => true,
    'publicly_queryable'      => false,
  );

  register_post_type( 'post_event', $args );

  $supports = array(
    'custom-fields',
  );
  add_post_type_support( 'post_event', $supports );
}

add_action( 'init', 'lowtide_register_events' );

//Register custom meta
function lowtide_register_events_meta() {
  $args = array(
    'show_in_rest'            => true,
    'single'                  => true,
    'type'                    => 'string',
  );

  register_post_meta( 'post_event', 'post_event_meta_name', $args );
  register_post_meta( 'post_event', 'post_event_meta_date', $args );
  register_post_meta( 'post_event', 'post_event_meta_time', $args );
  register_post_meta( 'post_event', 'post_event_meta_loc', $args );
  register_post_meta( 'post_event', 'post_event_meta_desc', $args );
}

add_action( 'init', 'lowtide_register_events_meta');

//Register block template
function lowtide_register_block_template() {
  $event_object = get_post_type_object( 'post_event' );
  $event_object->template = array(
    array( 'lowtide/event-data'),
  );
  $event_object->template_lock = 'all';
}

add_action( 'init', 'lowtide_register_block_template' );