<?php

  namespace Andruxnet\EventManager\Contracts;

  use Andruxnet\EventManager\Models\Event;

  /**
   * Contract for event data and operations.
   */
  interface EventRepositoryInterface
  {
    /**
     * Retrieve an event by its post ID.
     *
     * @param int $id The event post ID.
     *
     * @return Event|null
     */
    public function findById(int $id): ?Event;

  }
