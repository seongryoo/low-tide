<?php

function lowtide_register_event_block() {
  if ( ! function_exists( 'register_block_type' ) ) {
    return;
  }
  
  $register_args = array(
    'attributes' => array(
      
      'date' => array(
        'type' => 'string',
      ),
      'eventInfo' => array(
        'type' => 'string',
      ),
      'name' => array(
        'type' => 'string',
      ),
      'desc' => array(
        'type' => 'string',
      ),
      
    ),
    'render_callback' => 'lowtide_event_block_render',
  );

  register_block_type( 'lowtide/event-block', $register_args );

}

add_action( 'init', 'lowtide_register_event_block' );

/* Render functions ----------------------- */

function lowtide_event_block_render( $attributes, $content ) {
  $date = date_create( $attributes[ 'date' ] );
  $name = $attributes[ 'name' ];
  $desc = $attributes[ 'desc' ];
  $eventInfo = $attributes[ 'eventInfo' ];
  
  $date_day = date_format( $date, 'D' );
  $date_num = date_format( $date, 'j' );
  $date_month = date_format( $date, 'M' );
              
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
    $markup .= '<p class="event-time">' . $eventInfo . '</p>';
    $markup .=  $desc;
  $markup .= '</div>';
  
  $markup .= '</div>';
  
  
  return $markup;
}