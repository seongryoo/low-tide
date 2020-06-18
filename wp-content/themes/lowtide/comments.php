<?php


?>
<?php if ( ! empty( $_SERVER[ 'SCRIPT_FILENAME' ] ) && 'comments.php' == basename( $_SERVER[ 'SCRIPT_FILENAME' ] ) ) : ?>
  <?php die('You can not access this page directly!'); ?>
<?php endif; ?>

<?php if ( ! empty( $post->post_password ) ) : ?>
  <?php if ( $_COOKIE[ 'wp-postpass_' . COOKIEHASH ] != $post->post_password ) : ?>
    <?php die('You need a password to view this page!'); ?>
  <?php endif; ?>
<?php endif; ?>

<div id="comments" class="comments-wrapper">
  <?php if ( have_comments() ) : ?>
    <h2 class="comment-title">
      <?php if ( get_comments_number() == 1 ) : ?>
        1 comment
      <?php else : ?>
        <?php echo get_comments_number() ?> comments
      <?php endif ?>
    </h2>

    <?php
      $args = array(
        'post_id' => get_the_ID(),
        'status'  => 'approve',
        'order'   => 'ASC',
      );
  
      $comments = get_comments( $args );

      foreach ( $comments as $comment ) :
        $id = get_comment_ID();
        $author = get_comment_author();
        $src = get_avatar_url( $comment->user_id );
        $text = get_comment_text();
        $date = get_comment_date( 'U' );
        $authorURL = get_userdata( $comment->user_id )->user_url;
        $readableDate = human_time_diff( $date, current_time( 'timestamp' ) ) . ' ago';
      
        $markup = '';
        $markup .= '<div class="comment card">';                
          /* Bio block */
          $markup .= '<div class="bio-block">';

            $markup .= '<img class="blog-post-author-image" src="' . $src . '" alt="' . $author . '">';

            $markup .= '<div class="bio-block-info">';
              $markup .= '<a class="author-link" href="' . $authorURL . '" aria-label="Visit ' . $author . '\'s profile"><p class="blog-post-author">' . $author . '</p></a>';
              $markup .= '<p class="blog-post-date">' . $readableDate . '</p>';
            $markup .= '</div>';

          $markup .= '</div>';
      
          /* Comment contents */
          
          $markup .= '<p>' . $text . '</p>';
      
        $markup .= '</div>';
      
        echo $markup;
      endforeach;
    ?>
  <?php else : ?>
    <h2 class="comment-title">Be the first to comment!</h2>
  <?php endif ?>
  <?php
  $comments_args = array(
        // Change the title of send button 
        'label_submit' => 'Submit comment',
        // Change the title of the reply section
        'title_reply' => '',
        // Remove "Text or HTML to be displayed after the set of comment fields".
        'comment_notes_after' => '',
        // Redefine your own textarea (the comment body).
        'comment_field' => '<p class="comment-form-comment"><label for="comment" class="visually-hidden">' . 'Type a response and then tab to the submit button.' . '</label><br /><textarea id="comment" name="comment" aria-required="true" placeholder="Start typing your response..."></textarea></p>',
);
comment_form( $comments_args ); ?>
</div>