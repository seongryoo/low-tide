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
          <h2><?php the_title(); ?></h2>
          <?php

            $args = array(
              'post_type' => 'post',
            );
            $the_query = new WP_Query( $args );

            if ( $the_query->have_posts() ) {
              while ( $the_query->have_posts() ) {
                $the_query->the_post();
                
                $src = get_avatar_url( get_the_author_meta( 'ID' ), 32 ); 
                $href = get_permalink();
                $title = get_the_title();
                $author = get_the_author();
                
                $markup = '<div class="blog-post-block card">';
                
                /* Post author */
                  $markup .= '<div class="bio-block">';
                
                    $markup .= '<img class="blog-post-author-image" src="' . $src . '" alt="' . get_the_author() . '">';
                  
                    $markup .= '<div class="bio-block-info">';
                      $markup .= '<p class="blog-post-author">' . get_the_author() . '</p>';
                      $markup .= '<p class="blog-post-date">' . get_the_date( 'M n, Y' ) . '</p>';
                    $markup .= '</div>';
                
                  $markup .= '</div>';
                
                /* Post title */
                  $markup .= '<a href="' . $href . 
                              '" aria-label="' . $title . 'posted by ' . $author . 'on ' . get_the_date( 'F jS, Y' ) . '">';
                
                    $markup .= '<h3 class="blog-post-title" aria-hidden="true">' . get_the_title() . '</h3>';
                    
                    /* Post excerpt */
                    $markup .= '<p class="blog-post-excerpt"><span class="visually-hidden">Excerpt: </span>' . get_the_excerpt() . '</p>';
                
                  $markup .= '</a>';
                
                
                  
                
                
                
                /* Number of comments */
                  $markup .= '<a href="' . $href . '/#comments" aria-label="Read comments on ' . $title . '">';
                
                  if ( get_comments_number() == 1 ) {
                    $markup .= '<p class="blog-post-comments">' . get_comments_number() . ' comment</p>';
                  } else {
                    $markup .= '<p class="blog-post-comments">' . get_comments_number() . ' comments</p>';
                  }
                
                  $markup .= '</a>';
                /* End of wrapper div */
                $markup .= '</div>';
                
                echo $markup;
              }
            }
          ?>
        </div>
      </div>
    </div><!--/.container-->
  </div><!--/.section-->
</main>

<?php get_footer(); ?>