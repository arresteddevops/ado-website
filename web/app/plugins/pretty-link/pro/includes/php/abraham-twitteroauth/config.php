<?php
/**
 * @file
 * A single location to store configuration.
 */

define('CONSUMER_KEY', apply_filters('prlipro_twitter_consumer_key', 'hQrf6vxql3WPGpcerNGdA'));
define('CONSUMER_SECRET', apply_filters('prlipro_twitter_consumer_secret', 'C5kWChGhnJ016AecJfYmGpToXNsWZ60tR6d5gT7cag'));
define('OAUTH_CALLBACK', get_option('home') . '/prli-twitter-oauth/callback');
