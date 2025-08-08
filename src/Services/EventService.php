<?php

  namespace Andruxnet\EventManager\Services;

    use Andruxnet\EventManager\Models\Event;
  use Andruxnet\EventManager\Contracts\EventRepositoryInterface;

  /**
   * Event Service class to handle business logic for events.
   */
  class EventService
  {
    /**
     * @param EventRepositoryInterface $eventRespository Repository for event data access
     */
    public function __construct(
      private readonly EventRepositoryInterface $eventRespository
    ) {}

    /**
     * @throws \Exception
     */
    public function getEventForDisplay(int $id): ?Event {
      return $this->eventRespository->findById($id);
    }

    /**
     * @param int $limit
     *
     * @return array
     */
    public function getUpcomingEventsForDisplay(int $limit): array {
      return $this->eventRespository->findUpcoming($limit);
    }
  }