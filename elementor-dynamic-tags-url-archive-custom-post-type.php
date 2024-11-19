<?php
/**
 * Plugin Name:       Elementor dynamic tags url archive custom post type
 * Description:       This Wordpress plugin will let you chose from all url archive from custom post type in the current system and will return the value to Elementor.
 * Version:           1.0.0
 * Requires at least: 6.0
 * Requires PHP:      8.0
 * Author:            24hwww
 * Author URI:        https://github.com/24hwww
 * License:           Public domain
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'elementor/dynamic_tags/register', 'register_dynamic_tag_url_archive_fn' );
function register_dynamic_tag_url_archive_fn( $dynamic_tags_manager ) {
    $file_dynamic_tag_url_archive = __DIR__ . '/dynamic-tags/dynamic-tag-url_archive.php';
    if(file_exists($file_dynamic_tag_url_archive)){
	    require_once( $file_dynamic_tag_url_archive );
	    if (class_exists('Elementor_Dynamic_Tag_Url_Archive')) {
	        $dynamic_tags_manager->register( new \Elementor_Dynamic_Tag_Url_Archive );
	    }
    }
}


?>