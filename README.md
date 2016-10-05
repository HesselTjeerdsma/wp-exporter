# WP Exporter

WP Exporter exports WordPress metrics to [Prometheus](https://prometheus.io/).

## Requirements

This requires WordPress with an object cache configured (Redis, Memcached, APC, etc). Using it without will cause all metrics to be stored in the database, significantly reducing performance.

## Installation

Add WP_Exporter.php to `/wp-content/mu-plugins/`.