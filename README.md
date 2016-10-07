# WP Exporter

WP Exporter exports WordPress metrics to [Prometheus](https://prometheus.io/).

## Requirements

This requires WordPress with an object cache configured (Redis, Memcached, APC, etc). Using it without will cause all metrics to be stored in the database, significantly reducing performance.

Compatible with PHP 5.4+ - If you plan on using it with a lower version of PHP you will need to replace the use of `$_SERVER['REQUEST_TIME_FLOAT']`.

## Installation

Add WP_Exporter.php to `/wp-content/mu-plugins/`.