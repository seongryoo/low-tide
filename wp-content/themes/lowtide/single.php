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
                the_post(); ?>

                <h2><?php the_title(); ?></h2>

                <?php the_content(); ?>

                <?php
              }
            }

          ?>
          
          <?php
            if ( comments_open() || get_comments_number() ) {
              comments_template();
            }
          
          ?>
        </div><!--/.col-md-9-->
      </div><!--/.row-->
    </div><!--/.container-->
  </div><!--/.section-->
</main>

<?php get_footer(); ?>