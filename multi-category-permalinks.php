<?php
/**
 * Multi Category Permalinks
 *
 * @link              https://markmarzeotti.com/
 * @since             1.0.0
 * @package           Multi_Category_Permalinks
 *
 * @wordpress-plugin
 * Plugin Name:       Multi Category Permalinks
 * Plugin URI:        https://markmarzeotti.com/
 * Description:       Allow multiple categories in the permalink structure.
 * Version:           1.0.0
 * Author:            Mark Marzeotti
 * Author URI:        https://markmarzeotti.com/
 * Text Domain:       multi-category-permalinks
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Flush the rewrite rules after activation or deactivation.
 */
function mcp_flush_rewrite_rules() {
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'mcp_flush_rewrite_rules' );
register_deactivation_hook( __FILE__, 'mcp_flush_rewrite_rules' );

/**
 * Use URL Structure taxonomy in post link.
 *
 * Use `/%postname%/` when selecting a custom permalink on the permalink settings page.
 * This function is not looking for anything to replace.
 *
 * @param string         $permalink The post's permalink.
 * @param WP_Post_Object $post      The post in question.
 * @param boolean        $leavename Whether to keep the post name.
 */
function mcp_modify_post_link( $permalink, $post ) {
    $home_url     = get_home_url();
    // Get the list of terms (categories) attached to this post.
    $categories   = get_the_terms( $post->ID, 'category' );
    // Pluck each term's ID into its own array.
    $category_ids = array_column( $categories, 'term_id' );
    // Get terms in order of heirarchy, but limited to the terms attached to this post.
    $categories   = get_terms( 
        array( 
            'taxonomy' => 'category', 
            'orderby' => 'parent', 
            'include' => $category_ids 
        ) 
    );
    $url_base     = '';
    $last_term    = null;
    if ( ! empty( $categories ) ) {
        $url_base = '/';
        foreach ( $categories as $key => $term ) {
            // This has to be the first term in the list or a child of the previous term to be appended.
            if ( null === $last_term || $term->parent === $last_term ) {
                $url_base .= 0 === $key ? $term->slug : '/' . $term->slug;
                $last_term = $term->term_id;
            }
        }
    }
    $permalink = $home_url . $url_base . '/' . $post->post_name . '/';
    return $permalink;
}
add_filter( 'post_link', 'mcp_modify_post_link', 10, 2 );

/**
 * Add a rewrite rule allowing multiple prefixes in URL structure.
 */
function mcp_add_rewrite_rule() {
    add_rewrite_rule( '(.+?)/[a-z-]*?([^/]+)/?$', 'index.php?taxonomy=category&term=$matches[1]&name=$matches[2]', 'bottom' );
}
add_action( 'init', 'mcp_add_rewrite_rule' );