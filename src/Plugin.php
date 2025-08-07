<?php

  namespace Andruxnet\EventManager;

  /**
   * Main plugin class handling WordPress initialization.
   *
   * Implements the Singleton pattern to ensure only one instance exists
   * throughout the WordPress request lifecycle.
   */
  class Plugin
  {
    /**
     * Singleton instance of the plugin class.
     *
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * Returns the singleton instance of the plugin.
     *
     * @return self
     */
    public static function getInstance(): self {
      if (self::$instance === null) {
        self::$instance = new self();
      }

      return self::$instance;
    }

    /**
     * Initializes plugin.
     *
     * @return void
     */
    public function init(): void {
      // init hooks
    }

  }