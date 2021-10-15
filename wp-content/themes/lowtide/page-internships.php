<?php get_header(); ?>

<main id="content" class="<?php 
  if ( is_single() || is_page() ) {
    global $post;
    $post_slug = $post->post_name;
    echo $post_slug;
  }
?>">
  
  <div class="section">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-md-9">
          <h1><?php the_title(); ?></h1>
          <?php

            the_content();?>

            <h2>Available positions</h2>

            <?php

            $args = array(
              'post_type' => 'post_job',
            );
            $the_query = new WP_Query( $args );

            if ( $the_query->have_posts() ) {
              while ( $the_query->have_posts() ) {
                $the_query->the_post();
                $id = get_the_id();
                
                $is_avail = get_post_meta( $id, 'post_job_meta_is_avail', true );
                $is_hidden = get_post_meta( $id, 'post_job_meta_is_hidden', true );
                $start = get_post_meta( $id, 'post_job_meta_start_date', true );
                $href = get_permalink();
                $title = get_the_title();
                
                if ( ! $is_hidden ) {

                  $markup = '<a class="job-opening" href="' . esc_url( $href ) . '" target="_blank">';
                    $markup .= '<h3>' . esc_html( $title ) . '</h3>';
                    $markup .= '<div class="is-avail">';
                      if ( $is_avail ) {
                        $markup .= 'Accepting applications';
                      } else {
                        $markup .= 'Applications closed';
                      }
                    $markup .= '</div>';

                    if ( $start != '' ) {
                      $markup .= '<div class="start-date">Job start date: ' . esc_html( $start ) . '</div>';
                    }
                  $markup .= '</a>';
                  
                  echo $markup;
                }
              } /* endwhile */
            } /* endif */
          ?>
        </div>
      </div>
    </div><!--/.container-->
  </div><!--/.section-->
</main>

<?php get_footer(); ?>