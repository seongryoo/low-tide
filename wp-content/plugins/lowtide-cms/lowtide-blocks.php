<?php

/* Creating block category for GCP  ----------------------- */

function lowtide_block_category( $categories, $post ) {
  return array_merge(
    $categories,
    array(
      array(
        'slug' => 'lowtide-blocks',
        'title' => __( 'Sea Level Sensors', 'lowtide-blocks' ),
        'icon' => 'dashicons-sos',
      ),
    )
  );
}
add_filter( 'block_categories', 'lowtide_block_category', 10, 2 );

include( plugin_dir_path(__FILE__) . 'blocks/backlink.php' );
include( plugin_dir_path(__FILE__) . 'blocks/card.php' );
include( plugin_dir_path(__FILE__) . 'blocks/contained-width.php' );
include( plugin_dir_path(__FILE__) . 'blocks/event.php' );
include( plugin_dir_path(__FILE__) . 'blocks/group.php' );
include( plugin_dir_path(__FILE__) . 'blocks/quote.php' );
include( plugin_dir_path(__FILE__) . 'blocks/two-col-main.php' );
include( plugin_dir_path(__FILE__) . 'blocks/two-col-related-docs.php' );
include( plugin_dir_path(__FILE__) . 'blocks/file-upload.php' );
include( plugin_dir_path(__FILE__) . 'blocks/link.php' );
include( plugin_dir_path(__FILE__) . 'blocks/news-link.php' );