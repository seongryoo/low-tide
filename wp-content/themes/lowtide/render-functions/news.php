<?php

function render_news_card( $id ) {
  $title = get_the_title( $id );
  $date = get_post_meta( $id, 'post_ext_news_meta_date', true );
  $link = get_post_meta( $id, 'post_ext_news_meta_link', true );

  $img = get_post_meta( $id, 'post_ext_news_meta_img', true );
  $img_alt = get_post_meta( $img, '_wp_attachment_image_alt', true );
  $img_src = wp_get_attachment_image_src( $img, 'full' )[0];

  $logo = get_post_meta( $id, 'post_ext_news_meta_logo', true );
  $logo_alt = get_post_meta( $logo, '_wp_attachment_image_alt', true );
  $logo_src = wp_get_attachment_image_src( $logo, 'full' )[0];

  $date_obj = date_create( $date );
  $date_year = date_format( $date_obj, 'Y' );
  $date_month = date_format( $date_obj, 'F' );
  $date_label = date_format( $date_obj, 'M j, Y');

  $markup = '';
  $markup .= '<div class="col-md-4">';
    $markup .= '<div class="card">';
      $markup .= '<a href="' . esc_url( $link ) . '" ';
          $markup .= 'aria-label="Read ' . $date_month . ' ' . $date_year . ' article \'' . $title . '\'">';
        $markup .= '<img class="card-img-top" src="' . esc_url( $img_src ) . '" alt="' . esc_attr( $img_alt ) . '">';

        $markup .= '<div class="card-body">';
          $markup .= '<img class="source" src="' . esc_url( $logo_src ) . '" alt="' . esc_attr( $logo_alt ) . '">';
          $markup .= '<h4 class="card-title">' . $title . '</h4>';
          $markup .= '<p class="sub">';
            $markup .= '<span class="date">';
              $markup .= $date_label;
            $markup .= '</span>';
          $markup .= '</p>';
        $markup .= '</div>'; // card-body

      $markup .= '</a>';
    $markup .= '</div>'; // card
  $markup .= '</div>'; // col-md-4

  echo $markup;
}

function render_news_cards() {
  $args = array(
    'post_type'     => 'post_ext_news',
    'meta_key'      => 'post_ext_news_meta_date',
    'orderby'       => 'meta_value',
    'order'         => 'DESC',
    'posts_per_page' => -1,
  );
  $query = new WP_Query( $args );

  if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
      $query->the_post();
      $id = get_the_ID();
      render_news_card( $id );
    }
  }
}