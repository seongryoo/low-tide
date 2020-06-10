<?php get_header(); ?>

<main id="content">
  <div class="section <?php page_slug(); ?>">
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
        </div>
      </div>
    </div><!--/.container-->
  </div><!--/.section-->
</main>

<?php get_footer(); ?>