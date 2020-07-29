<?php

/*
Template Name: Schedule template
Template Post Type: post_sched
*/

?>

<?php get_header(); ?>

<main id="content" class="<?php 
  global $post;
  $post_slug = $post->post_name;
  echo $post_slug;
?>">
  
  <div class="section">
    <div class="container">
      <div class="row justify-content-md-center">
          <?php

            if ( have_posts() ) {
              while ( have_posts() ) {
                the_post(); 
                
                $href = get_permalink();
                $title = get_the_title();

              /* Post title */
                
              $markup = '';

              $markup .= '<h2 class="blog-post-title" aria-hidden="true">' . $title . '</h2>';

              echo $markup;

              ?>

                <?php the_content(); ?>

                <?php
              }
            }

          ?>
      </div><!--/.row-->
    </div><!--/.container-->
  </div><!--/.section-->
</main>

<?php get_footer(); ?>