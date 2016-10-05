<?php
/**
 * Plugin Name: Prometheus Exporter
 * Description: Collects metrics ready to be exported to Prometheus. https://prometheus.io/
 * Version:     1.0
 * Author:      Peter Booker
 * Author URI:  https://www.peterbooker.com
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Exit if no cache enabled
if ( ! defined( 'WP_CACHE_KEY_SALT' ) || ! defined( 'WP_REDIS_OBJECT_CACHE' ) ) {
	return;
}

if ( ! class_exists( 'WP_Exporter' ) ) {

    class WP_Exporter {
		
		/**
         * Endpoint URL
         *
         * @var string
         */
        private $endpoint_url = 'metrics/';

	    /**
	     * Endpoint Controller
	     *
	     * @var string
	     */
	    private $endpoint_controller = '_exporter_controller';
		
		public function __construct() {
			
			add_action( 'init', array( $this, 'rewrites_init' ) );
			
			add_action( 'query_vars', array( $this, 'add_query_vars' ) );
			
			add_action( 'template_include', array( $this, 'template_include' ), 99 );
			
		}
		
		/**
         * Gets the Endpoint URL
         *
         * @return string
         */
        public function get_endpoint_url() {

            return $this->endpoint_url;

        }
		
		/**
		 * Get a collection of metrics.
		 *
		 * @return object
		 */
		public function get_metrics() {
			
			// Get Metric Data
			
		}

	    /**
	     * Add the metrics endpoint used by Prometheus
	     *
	     * @return nil
	     */
		private function rewrites_init() {
			
			add_rewrite_rule(
				$this->endpoint_url,
				'index.php?' . $this->endpoint_controller,
				'top'
			);
				
		}

	    /**
	     * Add the query var for our metrics endpoint
	     *
	     * @return array
	     */
		private function add_query_vars() {
			
			array_push( $vars, $this->endpoint_controller );

			return $vars;
				
		}

	    /**
	     * Redirect output
	     *
	     * TODO: Dont want to use a template file, need to find an alternative method
	     *
	     * @param object $template
	     * @return object
	     */
		private function template_include( $template ) {
			
			$controller = get_query_var('_api_controller', null);
			if ( $controller ) {
				$template = __DIR__ . '/path/file.php';
			}
			
			return $template;
				
		}
	
	}

}