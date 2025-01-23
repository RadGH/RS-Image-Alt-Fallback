<?php
/*
Plugin Name: RS Image Alt Fallback
Description: When using the default Image block, if the block is missing alt text, it will load alt text from the media attachment instead.
Version: 1.0.0
Author: Radley Sustaire
Author URI: https://radleysustaire.com
*/


/**
 * When rendering the core image block, change the alt text attribute
 * @param string $block_content
 * @param array $block
 */
function rs_image_alt_use_fallback( $block_content, $block ) {
	if ( $block['blockName'] !== 'core/image' ) return $block_content;
	
	// Check if alt text was provided by the block.
	$search = ' alt="" ';
	if ( ! str_contains($block_content, $search) ) {
		return $block_content;
	}
	
	// Otherwise get alt text from the media library attachment.
	$attachment_id = $block['attrs']['id'] ?? '';
	$alt = get_post_meta($attachment_id, '_wp_attachment_image_alt', true );
	if ( $alt ) {
		$replace = ' alt="' . esc_attr($alt) . '" ';
		$block_content = str_replace( $search, $replace, $block_content );
	}
	
	return $block_content;
}
add_filter( 'render_block', 'rs_image_alt_use_fallback', 10, 2 );