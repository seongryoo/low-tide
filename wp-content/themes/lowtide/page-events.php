<?php get_header(); ?>

<main id="content" class="<?php 
  if ( is_single() || is_page() ) {
    global $post;
    $post_slug = $post->post_name;
    echo $post_slug;
  }
?>">
  
  <div class="section">
    <div class="container">
      <div class="row justify-content-md-center">
        <div class="col-md-12">
          <h1>Events</h1>

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

  <?php get_footer(); ?>