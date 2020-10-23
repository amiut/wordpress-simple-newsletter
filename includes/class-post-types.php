<?php
/**
 * PDFGEN Post Types Class class
 * Post types, Taxonomies, meta boxes, post columns are registered here
 *
 * @package DW_NLITE
 * @since   1.0
 */

namespace DW_NLITE;

defined('ABSPATH') || exit;

/**
 * Post Types class
 */
class Post_Types{

    public static function init() {
        add_action('init', [__CLASS__, 'register_post_types']);
        add_action('init', [__CLASS__, 'register_taxonomies']);

        // Register Metaboxes
        add_action('add_meta_boxes', [__CLASS__, 'metaboxes']);
    }

    public static function register_post_types() {
        // PDF Generated Documents        
    }

    /**
     * Register Metaboxes
     */
    public static function metaboxes() {
       
    }

    public static function move_metaboxes() {
      
    }

    public static function save_pdf_template($post_id, $post) {
        
    }

    public static function register_taxonomies() {

    }

}
