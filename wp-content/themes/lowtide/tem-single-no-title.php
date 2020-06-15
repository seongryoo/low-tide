<?php

/*
Template Name: Single Column, Custom Title
*/

?>


<?php get_header(); ?>

<main id="content" class="<?php 
  global $post;
  $post_slug = $post->post_name;
  echo $post_slug;
?>">
  
  <div class="section bio">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-md-9">
          <?php

            if ( have_posts() ) {
              while ( have_posts() ) {
                the_post(); ?>

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