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
          <?php

            if ( have_posts() ) {
              while ( have_posts() ) {
                the_post(); ?>

                <h1><?php the_title(); ?></h1>

                <?php the_content(); ?>

                <?php
              }
            }

          ?>
        </div>
      </div>
    </div><!--/.container-->
  </div><!--/.section-->
</main>

<?php get_footer(); ?>