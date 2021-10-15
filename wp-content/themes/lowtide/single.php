<?php get_header(); ?>

<main id="content" class="<?php 
  global $post;
  $post_slug = $post->post_name;
  echo $post_slug;
?>">
  
  <div class="section">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-md-9">
          <?php

            if ( have_posts() ) {
              while ( have_posts() ) {
                the_post(); 
                
                $href = get_permalink();
                $title = get_the_title();

              /* Post title */
                
              $markup = '';

              $markup .= '<h1 class="post-title" aria-hidden="true">' . $title . '</h1>';

              echo $markup;

              ?>

                <?php the_content(); ?>

                <?php
              }
            }

          ?>
        </div><!--/.col-md-9-->
      </div><!--/.row-->
    </div><!--/.container-->
  </div><!--/.section-->
</main>

<?php get_footer(); ?>