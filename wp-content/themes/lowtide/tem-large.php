<?php

/*
Template Name: Single Column, Variable Width Sections
*/

?>

<?php get_header(); ?>

<main id="content" class="<?php 
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
</main>

<?php get_footer(); ?>