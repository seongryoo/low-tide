<?php

function render_upcoming_events() {
  render_events_in_upcoming_order( true );
}
function render_past_events() {
  render_events_in_upcoming_order( false );
}

function render_events_in_upcoming_order( $upcoming ) {

  $args = array(
    'post_type'     => 'post_event',
    'meta_key'      => 'post_event_meta_date',
    'meta_value'    => date( 'Y-m-d') . 'T00:00:00',
    'meta_compare'  => $upcoming == true ? '>' : '<',
    'order'         => 'ASC',
  );
  $query = new WP_Query( $args );

  if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
      $query->the_post();
      $id = get_the_ID();

      $name = get_the_title( $id );
      $date = get_post_meta( $id, 'post_event_meta_date', true);
      $time = get_post_meta( $id, 'post_event_meta_time', true);
      $loc  = get_post_meta( $id, 'post_event_meta_loc',  true);
      $desc = get_post_meta( $id, 'post_event_meta_desc', true);

      $dateObj = date_create( $date );

      $date_day = date_format( $dateObj, 'D' );
      $date_num = date_format( $dateObj, 'j' );
      $date_mon = date_format( $dateObj, 'M' );

      $markup = '';
      $markup .= '<div class="row events">';

      $markup .= '<div class="col-md-2 event-date">';
      $markup .= '<h5 class="date-box-prefix">' . $date_day . '</h5>';
      $markup .= '<div class="date-box">';
      $markup .= '<h5>' . $date_mon . '</h5>';
      $markup .= '<h3>' . $date_num . '</h3>';
      $markup .= '</div>';
      $markup .= '</div>';

      $delimiter = $time != '' && $loc != '' ? ', ' : '';

      $markup .= '<div class="col-md-9 event-body">';
      $markup .= '<h4 class="event-title">' . $name . '</h4>';
      $markup .= '<p class="event-time">' . $time . $delimiter . $loc . '</p>';
      $markup .=  $desc;
      $markup .= '</div>';

      $markup .= '</div>';

      echo $markup;
    }
  }
}