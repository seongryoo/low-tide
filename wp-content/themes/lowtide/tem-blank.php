<?php

/*
Template Name: Two Column Page
*/

?>

<?php get_header(); ?>
<div class="post section">
  <div class="container">
    <div class="row <?php 
      global $post;
      $post_slug = $post->post_name;
      echo $post_slug;
    ?>">
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
</div>


<?php get_footer(); ?>