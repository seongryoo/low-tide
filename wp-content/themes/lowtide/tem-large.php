<?php

///*
//Template Name: Large Width One Column
//*/

?>

<?php get_header(); ?>

<main id="content">
  <div class="section">
    <div class="container">
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
      <h2></h2>
    
    
    </div><!--/.container-->
  </div><!--/.section-->
</main>

<?php get_footer(); ?>