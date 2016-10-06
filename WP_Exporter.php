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
if ( ! defined( 'WP_CACHE' ) ) {
	//return;
}

// What do to?
if ( ! defined( 'WP_CACHE_KEY_SALT' ) ) {
	//return;
}

if ( ! class_exists( 'WP_Exporter' ) ) {

    class WP_Exporter {

        /**
         * Endpoint Regex
         *
         * @var string
         */
        private $endpoint_regex = '^metrics/?';
		
		/**
         * Endpoint URL
         *
         * @var string
         */
        private $endpoint_url = 'metrics';

	    /**
	     * Query Var
	     *
	     * @var string
	     */
	    private $query_var = 'wpe_metrics';

        /**
         * Execution Time
         *
         * @var float
         */
        private $execution_time = 0.0;

        /**
         * Metrics
         *
         * @var array
         */
        private $metrics = array(
            // General HTTP
            'http_request_duration_seconds',
            'http_request_size_bytes',
            'http_requests_total',

            // WordPress Stuff
            'wordpress_request_queries', // not sure about name
            'wordpress_scrape_duration_seconds',
        );

	    /**
	     * WP_Exporter constructor.
	     */
		public function __construct() {

			add_filter( 'query_vars', array( $this, 'add_query_vars' ), 0 );

			add_action( 'init', array( $this, 'rewrites_init' ) );

			add_action( 'template_redirect', array( $this, 'output_endpoint_data' ) );

            add_action( 'shutdown', array( $this, 'set_execution_time' ) );
			
		}

        /**
         * Gets the Endpoint Regex
         *
         * @return string
         */
        public function get_endpoint_regex() {

            return $this->endpoint_regex;

        }

        /**
         * Sets the Endpoint Regex
         *
         * @param $regex string
         * @return string
         */
        public function set_endpoint_regex( $regex ) {

            $this->endpoint_regex = $regex;

            return $this->endpoint_regex;

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
         * Sets the Endpoint URL
         *
         * @param $url string
         * @return string
         */
        public function set_endpoint_url( $url ) {

            $this->endpoint_url = $url;

            return $this->endpoint_url;

        }

        /**
         * Gets the Query Var
         *
         * @return string
         */
        public function get_query_var() {

            return $this->query_var;

        }

        /**
         * Sets the Query Var
         *
         * @param $var string
         * @return string
         */
        public function set_query_var( $var ) {

            $this->query_var = $var;

            return $this->query_var;

        }

        /**
         * Get Execution Time
         *
         * @return float
         */
        public function get_execution_time() {

            return $this->execution_time;

        }

        /**
         * Set Execution Time
         *
         * @return float
         */
        public function set_execution_time() {

            $this->execution_time = microtime( true ) - $_SERVER['REQUEST_TIME'];

            return $this->execution_time;

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
		public function rewrites_init() {
			
			add_rewrite_rule(
                $this->endpoint_regex,
				'index.php?' . $this->query_var . '=true',
				'top'
			);
				
		}

	    /**
	     * Add the query var for our metrics endpoint
	     *
	     * @param array $vars
	     * @return array
	     */
		public function add_query_vars( $vars ) {

			$vars[] = $this->query_var;

			return $vars;
				
		}

	    /**
	     * Output Metrics
	     *
	     * TODO: Dont want to use a template file, need to find an alternative method
	     *
	     * @return object
	     */
	    public function output_endpoint_data() {

		    global $wp_query;

		    $wpe_metrics = get_query_var( $this->query_var, false );

            if ( $wpe_metrics != true ) {
                return;
            }

		    status_header( 200 );

		    header( 'Content-Type: text/plain' );

            global $wpe_exporter;

            $output = '# Prometheus Exporter for WordPress' . PHP_EOL;

            $output .= '# HELP wp_http_request_duration_microseconds The HTTP request latencies in microseconds.' . PHP_EOL;
            $output .= '# TYPE wp_http_request_duration_microseconds summary' . PHP_EOL;
            $output .= 'Queries: ' . get_num_queries() . ' ' . PHP_EOL;
            $output .= 'Time: ' . (float) $wpe_exporter->set_execution_time() . ' ' . PHP_EOL;
            $output .= 'Time: ' . $wpe_exporter->get_execution_time() . ' ' . PHP_EOL;

            echo $output;

            die();

	    }

        private function wp_send_text( $response ) {

            @header( 'Content-Type: text/plain; charset=' . get_option( 'blog_charset' ) );
            echo wp_json_encode( $response ); // replace this

        }
	
	}

}
global $wpe_exporter;
$wpe_exporter = new WP_Exporter;