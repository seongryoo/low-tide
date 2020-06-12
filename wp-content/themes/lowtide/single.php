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
          <nav aria-label="Breadcrumbs" class="breadcrumbs">
            <p>
              <a href="/blog/" class="back-link">
                <span aria-hidden="true">❮ </span>
                Back to blog
              </a>
            </p>
          </nav>
          <?php

            if ( have_posts() ) {
              while ( have_posts() ) {
                the_post(); 
                
                $src = get_avatar_url( get_the_author_meta( 'ID' ), 32 ); 
                $href = get_permalink();
                $title = get_the_title();
                $author = get_the_author();
                $authorURL = esc_url( get_the_author_meta( 'url' ) );

              /* Post title */

              $markup .= '<h2 class="blog-post-title" aria-hidden="true">' . $title . '</h2>';
          
              /* Post author */
                $markup .= '<div class="bio-block">';

                  $markup .= '<img class="blog-post-author-image" src="' . $src . '" alt="' . get_the_author() . '">';

                  $markup .= '<div class="bio-block-info">';
                    $markup .= '<a class="author-link" href="' . $authorURL . '" aria-label="Visit ' . $author . '\'s profile"><p class="blog-post-author">' . get_the_author() . '</p></a>';
                    $markup .= '<p class="blog-post-date">' . get_the_date( 'M n, Y' ) . '</p>';
                  $markup .= '</div>';

                $markup .= '</div>';

              echo $markup;

              ?>

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