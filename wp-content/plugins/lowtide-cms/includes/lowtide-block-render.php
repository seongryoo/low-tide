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

include( plugin_dir_path( __FILE__ ) . '../block-render/backlink.php' );
include( plugin_dir_path( __FILE__ ) . '../block-render/card.php' );
include( plugin_dir_path( __FILE__ ) . '../block-render/contained-width.php' );
include( plugin_dir_path( __FILE__ ) . '../block-render/event.php' );
include( plugin_dir_path( __FILE__ ) . '../block-render/group.php' );
include( plugin_dir_path( __FILE__ ) . '../block-render/quote.php' );
include( plugin_dir_path( __FILE__ ) . '../block-render/two-col-main.php' );
include( plugin_dir_path( __FILE__ ) . '../block-render/two-col-related-docs.php' );
include( plugin_dir_path( __FILE__ ) . '../block-render/file-upload.php' );
include( plugin_dir_path( __FILE__ ) . '../block-render/link.php' );
include( plugin_dir_path( __FILE__ ) . '../block-render/news-link.php' );