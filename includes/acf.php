<?php
/**
 *
 * acf.php
 * Advanced Custom Fields Code
 *
 **/




/**
 * Load in Google Maps API
 * @source: https://www.advancedcustomfields.com/resources/google-map/#google-map%20api
 **/
	function my_acf_init() {
		// GOOGLE_API_KEY is defined in wp-config.php
		//acf_update_setting( 'google_api_key',GOOGLE_API_KEY );
	}
	add_action('acf/init', 'my_acf_init');

/**
 * Reduce WP Admin Loading Time
 * @source: https://awesomeacf.com/snippets/speed-acf-backend-loading-time/
 **/
	add_filter('acf/settings/remove_wp_meta_box', '__return_true');	