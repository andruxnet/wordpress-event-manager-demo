<?php

  namespace Andruxnet\EventManager;

  use Andruxnet\EventManager\Repositories\EventRepository;
  use Andruxnet\EventManager\Services\EventService;

  /**
   * Main plugin class handling WordPress initialization.
   *
   * Implements the Singleton pattern to ensure only one instance exists
   * throughout the WordPress request lifecycle.
   */
  class Plugin
  {
    /**
     * Plugin text domain for internationalization.
     */
    public const TEXT_DOMAIN = 'andruxnet-event-manager';

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
      add_action('init', [$this, 'registerPostType']);
      add_action('add_meta_boxes', [$this, 'addMetaBoxes']);
      add_action('save_post_event', [$this, 'saveEventMeta'], 10, 3);

      // enqueues
      add_action('wp_enqueue_scripts', [$this, 'enqueuePublicAssets']);

      // shortcodes
      add_shortcode('em_event', [$this, 'renderEventShortcode']);
      add_shortcode('em_upcoming', [$this, 'renderUpcomingEventsShortcode']);
    }

    /**
     * Registers custom post type and related taxonomies.
     *
     * @return void
     */
    public function registerPostType(): void {
      // register the post type first
      register_post_type('event', [
        'labels' => [
          'name' => __('Events', self::TEXT_DOMAIN),
          'singular_name' => __('Event', self::TEXT_DOMAIN),
          'add_new' => __('Add New Event', self::TEXT_DOMAIN),
          'add_new_item' => __('Add New Event', self::TEXT_DOMAIN),
          'edit_item' => __('Edit Event', self::TEXT_DOMAIN),
          'new_item' => __('New Event', self::TEXT_DOMAIN),
          'view_item' => __('View Event', self::TEXT_DOMAIN),
          'search_items' => __('Search Events', self::TEXT_DOMAIN),
          'not_found' => __('No events found', self::TEXT_DOMAIN),
          'not_found_in_trash' => __('No events found in Trash', self::TEXT_DOMAIN),
        ],
        'description' => __('Create and manage events', self::TEXT_DOMAIN),
        'public' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'show_in_rest' => true,
        'supports' => ['title', 'editor', 'thumbnail'],
        'has_archive' => false,
        'rewrite' => false,
      ]);

      // then the taxonomy
      register_taxonomy('event_category', 'event', [
        'labels' => [
          'name' => __('Categories', self::TEXT_DOMAIN),
          'singular_name' => __('Category', self::TEXT_DOMAIN),
          'add_new_item' => __('Add New Category', self::TEXT_DOMAIN),
          'edit_item' => __('Edit Category', self::TEXT_DOMAIN),
        ],
        'hierarchical' => true,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_rest' => true,
        'rewrite' => ['slug' => 'event-category'],
      ]);
    }

    /**
     * Add meta boxes for the event post type.
     *
     * @return void
     */
    public function addMetaBoxes(): void {
      add_meta_box(
        'event_details',
        __('Event Details', self::TEXT_DOMAIN),
        [$this, 'renderEventDetailsMetaBox'],
        'event',
        'normal',
        'high'
      );
    }

    /**
     * Render the event details meta box.
     *
     * @param \WP_Post $post The post object.
     *
     * @return void
     */
    public function renderEventDetailsMetaBox(\WP_Post $post): void {
      // a nonce for security
      wp_nonce_field('event-meta-box', 'event-meta-nonce');

      // event data
      $date = get_post_meta($post->ID, '_event_date', true);
      $location = get_post_meta($post->ID, '_event_location', true);
      $capacity = get_post_meta($post->ID, '_event_capacity', true);

      // load data into the meta box template
      include ANDRUXNET_PLUGIN_PATH . 'templates/admin/event-meta-box.php';
    }

    /**
     * Save event meta when saving the event post.
     *
     * @param int      $post_id The post ID.
     * @param \WP_Post $post    The post object.
     * @param bool     $update  Whether this is an existing post being updated.
     *
     * @return void
     */
    public function saveEventMeta(int $post_id, \WP_Post $post, bool $update): void {
      // security first
      $is_nonce_set = isset($_POST['event-meta-nonce']);
      $is_nonce_valid = wp_verify_nonce($_POST['event-meta-nonce'], 'event-meta-box');
      if (!$is_nonce_set || !$is_nonce_valid) {
        return;
      }

      // save event meta to the database
      if (isset($_POST['event-date'])) {
        update_post_meta($post_id, '_event_date', sanitize_text_field($_POST['event-date']));
      }

      if (isset($_POST['event-location'])) {
        update_post_meta($post_id, '_event_location', sanitize_text_field($_POST['event-location']));
      }

      if (isset($_POST['event-capacity'])) {
        update_post_meta($post_id, '_event_capacity', absint($_POST['event-capacity']));
      }
    }

    /**
     * Properly enqueue public assets.
     *
     * @return void
     */
    public function enqueuePublicAssets(): void {
      // we need dashicons in the front for event list template
      wp_enqueue_style('dashicons');

      wp_enqueue_style(
        self::TEXT_DOMAIN . '-public',
        ANDRUXNET_PLUGIN_URL . 'assets/css/public.css',
        [],
        uniqid()
      );
    }

    /**
     * Shortcode to display a single event.
     *
     * @param mixed $attributes Shortcode attributes
     *
     * @return string
     */
    public function renderEventShortcode(mixed $attributes): string {
      $attributes = shortcode_atts(
        ['id' => 0], // defaults to id 0, which will render as "not found"
        $attributes ?? []
      );

      $event_id = (int) $attributes['id'];
      if ($event_id === 0) {
        return sprintf(
          '<p>%s</p>',
          __('Event not found.', self::TEXT_DOMAIN),
        );
      }

      try {
        $service = new EventService(new EventRepository());
        $event = $service->getEventForDisplay((int) $attributes['id']);

        // need to return the output instead of just echoing it
        ob_start();
        include ANDRUXNET_PLUGIN_PATH . 'templates/public/single-event.php';
        return ob_get_clean();

      } catch (\Exception $e) {
        error_log('Event shortcode error: ' . $e->getMessage());

        return sprintf(
          '<p>%s</p>',
          __('There was an error displaying this event.', self::TEXT_DOMAIN)
        );
      }
    }

    /**
     * Shortcode to display a list of upcoming events.
     *
     * @param mixed $attributes Shortcode attributes
     *
     * @return string
     */
    public function renderUpcomingEventsShortcode(mixed $attributes): string {
      $attributes = shortcode_atts(
        ['limit' => 10],
        $attributes ?? []
      );

      try {
        $service = new EventService(new EventRepository());
        $events = $service->getUpcomingEventsForDisplay((int) $attributes['limit']);

        // need to return the output instead of just echoing it
        ob_start();
        include ANDRUXNET_PLUGIN_PATH . 'templates/public/event-list.php';
        return ob_get_clean();

      } catch (\Exception $e) {
        error_log('Event shortcode error: ' . $e->getMessage());

        return sprintf(
          '<p>%s</p>',
          __('There was an error displaying this event.', self::TEXT_DOMAIN)
        );
      }
    }
  }