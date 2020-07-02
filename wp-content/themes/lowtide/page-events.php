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
          <h2>Events</h2>

          <div class="events upcoming">
            <h3>Upcoming events</h3>
            <?php render_upcoming_events(); ?>
          </div>

          <div class="events past">
            <h3>Past events</h3>
            <?php render_past_events(); ?>
          </div>

        </div>
      </div>
    </div>
    </div>
  </main>