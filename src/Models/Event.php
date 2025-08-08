<?php

  namespace Andruxnet\EventManager\Models;

  /**
   * Event model representing an event post record.
   */
  class Event
  {
    /**
     * Create a new Event instance.
     *
     * @param int       $id           Event post ID
     * @param string    $title        Event title
     * @param string    $description  Event description
     * @param \DateTime $eventDate    Event date and time
     * @param string    $location     Event location
     * @param int       $capacity     Maximum attendees to the event (0 = unlimited)
     */
    public function __construct(
      public readonly int $id,
      public readonly string $title,
      public readonly string $description,
      public readonly \DateTime $eventDate,
      public readonly string $location,
      public readonly int $capacity
    ) {}

    /**
     * Return Event instance as array.
     *
     * @return array
     */
    public function toArray(): array {
      return [
        'id' => $this->id,
        'title' => $this->title,
        'description' => $this->description,
        'event_date' => $this->eventDate,
        'location' => $this->location,
        'capacity' => $this->capacity,
      ];
    }

  }