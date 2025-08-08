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
     * Retrieve an Event object using its post ID.
     *
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
  }