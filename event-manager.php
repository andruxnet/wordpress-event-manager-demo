<?php
/**
 * Plugin Name: WordPress Event Manager Demo Project
 * Plugin URI: https://github.com/andruxnet/wordpress-event-manager-demo
 * Description: A demo WordPress plugin project for event management.
 * Version: 1.0.0
 * Author: andrux
 * License: GPL v2 or later
 * Text Domain: wordpress-event-manager-demo
 */

  // prevent direct access
  if (!defined('ABSPATH')) {
    exit;
  }

  require_once __DIR__ . '/vendor/autoload.php';

  // define constants to be used throughout the plugin
  define('ANDRUXNET_PLUGIN_PATH', plugin_dir_path(__FILE__));

  // initialize plugin
  add_Action('plugins_loaded', function() {
    \Andruxnet\EventManager\Plugin::getInstance()->init();
  });