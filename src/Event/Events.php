<?php

namespace Calendar\Pdf\Renderer\Event;

use Aeon\Calendar\Gregorian\DateTime;
use Carbon\CarbonInterface;

class Events implements \IteratorAggregate, \Countable
{
    /** @var Event[] */
    private array $events;

    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->events);
    }

    public function count(): int
    {
        return count($this->events);
    }

    public function __construct(array $events=[])
    {
        $this->setEvents($events);
    }

    public function setEvents(array $events): Events
    {
        $this->validateEvents($events);
        $this->events = $events;
        return $this;
    }

    public function addEvents(array $events): Events
    {
        $this->validateEvents($events);
        $this->events = array_merge($this->events, $events);
        return $this;
    }

    public function getEventsByRange(CarbonInterface $start, CarbonInterface  $end): array
    {
        return array_filter($this->events, function ($event) use ($start, $end) {
            return $event->isInRange($start, $end);
        });
    }

    protected function validateEvents(array $events): bool
    {
        foreach ($events as $key => $event) {
            if (get_class($event) !== Event::class) {
                throw new EventException(sprintf('Item #%d provided in construct is not of type Event', $key));
            }
        }
        return true;
    }
}
