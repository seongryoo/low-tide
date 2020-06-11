<?php


?>

<div id="comments" class="comments-wrapper">
  <?php if ( have_comments() ) : ?>
    <h2>
      <?php if ( get_comments_number() == 1 ) : ?>
        1 comment
      <?php else : ?>
        <?php echo get_comments_number() ?> comments
      <?php endif ?>
    </h2>

    <?php
      $args = array(
        'post_id' => get_the_ID(),
      );
  
      $comments = get_comments( $args );

      foreach ( $comments as $comment ) :
        echo $comment->comment_author . '<br />' . $comment->comment_content;
      endforeach;
    ?>
  <?php else : ?>
    <h2>Be the first to comment!</h2>
  <?php endif ?>
  
  <?php comment_form() ?>
</div>