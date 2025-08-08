<?php

  namespace Andruxnet\EventManager\Repositories;

  use Andruxnet\EventManager\Models\Event;
  use Andruxnet\EventManager\Contracts\EventRepositoryInterface;

  /**
   * Event repository class to connect the Event model to WordPress data.
   */
  class EventRepository implements EventRepositoryInterface
  {
    /**
     * @param int $id
     *
     * @return Event|null
     * @throws \Exception when event date is invalid
     *
     * @todo Validate and make eventDate required when saving event meta
     *    inside the Plugin class so that converting to DateTime won't
     *    ever throw an exception because of invalid event date meta.
     */
    public function findById(int $id): ?Event {
      $post = get_post($id);

      if (!$post || 'event' !== get_post_type($id)) {
        return null;
      }

      return new Event(
        id: $post->ID,
        title: $post->post_title,
        description: $post->post_content,
        eventDate: new \DateTime(get_post_meta($post->ID, '_event_date', true) ?: 'now'), // default to current datetime for now
        location: get_post_meta($post->ID, '_event_location', true) ?: '',
        capacity: (int) get_post_meta($post->ID, '_event_capacity', true)
      );
    }

    /**
     * @param int $limit
     *
     * @return array
     * @throws \Exception
     */
    public function findUpcoming(int $limit = 0): array {
      // find posts with an event date later than today's date
      $posts = get_posts([
        'post_type' => 'event',
        'post_status' => 'publish',
        'numberposts' => $limit,
        'meta_key' => '_event_date',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        'meta_query' => [
          [
            'key' => '_event_date',
            'value' => date('Y-m-d'),
            'compare' => '>',
            'type' => 'DATE'
          ]
        ],
      ]);

      // convert to Event instances
      return array_map(function($post) {
        return new Event(
          id: $post->ID,
          title: $post->post_title,
          description: $post->post_content,
          eventDate: new \DateTime(get_post_meta($post->ID, '_event_date', true) ?: 'now'),
          location: get_post_meta($post->ID, '_event_location', true) ?: '',
          capacity: (int) get_post_meta($post->ID, '_event_capacity', true)
        );
      }, $posts);
    }
  }