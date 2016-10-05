<?php
/**
 * Plugin Name: Prometheus Exporter
 * Plugin URI:  https://kebo.io/
 * Description: Collects metrics ready to be exported to Prometheus. https://prometheus.io/
 * Version:     1.0
 * Author:      Peter Booker
 * Author URI:  http://www.peterbooker.com
 * License:     GPLv2+
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Exit if no cache enabled
if ( ! defined( 'WP_CACHE_KEY_SALT' ) || ! defined( 'WP_REDIS_OBJECT_CACHE' ) ) {
	exit;
}

function wpex_rewrites_init() {
	
    add_rewrite_rule(
        'metrics/',
        'index.php?_exporter_controller',
        'top'
	);
		
}
add_action( 'init', 'wpex_rewrites_init' );

function query_vars() {
	
    array_push( $vars, '_exporter_controller' );
	return $vars;
		
}
add_action( 'query_vars', 'query_vars' );

function wpex_template_include( $template ) {
	
    $controller = get_query_var('_api_controller', null);
	if ( $controller ) {
		$template = __DIR__ . '/api/v1.php';
	}
	
	return $template;
		
}
add_action( 'template_include', 'wpex_template_include', 99 );

//add_filter( 'protected_title_format', array( $this, 'protected_title_format' ) );

if ( ! class_exists( 'WP_Exporter' ) ) {

    class WP_Exporter {
		
		/**
         * NewRelic API URL
         *
         * @var string
         */
        private $api_url = 'https://api.newrelic.com/v2/';
		
		public function __construct() {
			
			add_action( 'init', array( $this, 'rewrites_init' ) );
			
			add_action( 'query_vars', array( $this, 'add_query_vars' ) );
			
			add_action( 'template_include', array( $this, 'template_include' ), 99 );
			
		}
		
		/**
         * Gets the API URL
         *
         * @return string
         */
        public function get_api_url() {

            return $this->api_url;

        }
		
		/**
		 * Get a collection of metrics.
		 *
		 * @return object
		 */
		public function get_metrics() {
			
			// Get Metric Data
			
		}
		
		private function rewrites_init() {
			
			add_rewrite_rule(
				'metrics/',
				'index.php?_exporter_controller',
				'top'
			);
				
		}
		
		private function add_query_vars() {
			
			array_push( $vars, '_exporter_controller' );
			return $vars;
				
		}
		
		private function template_include( $template ) {
			
			$controller = get_query_var('_api_controller', null);
			if ( $controller ) {
				$template = __DIR__ . '/api/v1.php';
			}
			
			return $template;
				
		}
	
}