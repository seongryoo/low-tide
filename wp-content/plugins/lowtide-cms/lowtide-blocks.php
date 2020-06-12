<?php

/* Creating block category for GCP  ----------------------- */

function lowtide_block_category( $categories, $post ) {
  return array_merge(
    $categories,
    array(
      array(
        'slug' => 'lowtide-blocks',
        'title' => __( 'Global Change', 'lowtide-blocks' ),
        'icon' => 'dashicons-admin-site-alt',
      ),
    )
  );
}
add_filter( 'block_categories', 'lowtide_block_category', 10, 2 );

include( plugin_dir_path(__FILE__) . 'blocks/lowtide-blocks-backlink.php' );
include( plugin_dir_path(__FILE__) . 'blocks/lowtide-blocks-card.php' );
include( plugin_dir_path(__FILE__) . 'blocks/lowtide-blocks-contained-width.php' );
include( plugin_dir_path(__FILE__) . 'blocks/lowtide-blocks-event.php' );
include( plugin_dir_path(__FILE__) . 'blocks/lowtide-blocks-group.php' );
include( plugin_dir_path(__FILE__) . 'blocks/lowtide-blocks-quote.php' );