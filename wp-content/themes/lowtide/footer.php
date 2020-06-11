<footer role="contentinfo" class="footer">
  <nav role="navigation" class="container" aria-label="Important site links">
    <ul>
      
      <?php
          if ( has_nav_menu( 'footer' ) ) {
            wp_nav_menu(
              array(
                'container' => '',
                'items_wrap' => '%3$s',
                'theme_location' => 'footer',
              )
            );
          }
      ?>
      
    </ul>
  </nav>
</footer>

<?php wp_footer(); ?>

</body>

</html>