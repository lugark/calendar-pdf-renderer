<?php

namespace Calendar\Pdf\Renderer\Tests\Event;

use Calendar\Pdf\Renderer\Event\Event;
use Calendar\Pdf\Renderer\Event\EventException;
use Calendar\Pdf\Renderer\Event\Events;
use Calendar\Pdf\Renderer\Event\Types;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EventsTest extends TestCase
{
    /** @var Events */
    private Events $sut;

    public static function getEventsTestData(): array
    {
        $events = array();
        $events[] = Event::fromArray(['start' => '2017-01-01 10:00:00', 'end'=> '2017-01-02 10:00:00', 'name' => 'Test'], Types::EVENT_TYPE_CUSTOM);
        $events[] = Event::fromArray(['start' => '2017-01-02 10:00:00', 'end'=> '2017-01-03 10:00:00', 'name' => 'Test'], Types::EVENT_TYPE_CUSTOM);
        $events[] = Event::fromArray(['start' => '2017-01-03 10:00:00', 'end'=> '2017-01-04 10:00:00', 'name' => 'Test'], Types::EVENT_TYPE_CUSTOM);

        return [
           'allWithin'  => [
               $events,
               Carbon::create('2017-01-01 10:00:00'),
               Carbon::create('2017-01-10 10:00:00'),
               3
           ]
        ];
    }

    #[DataProvider('getEventsTestData')]
    public function testEvents($events, $calendarStart, $calenderEnd, $count)
    {
        $this->sut = new Events($events);
        self::assertSameSize($events, $this->sut);

        $this->sut->addEvents($events);
        self::assertEquals(count($events)*2, count($this->sut));

        $this->sut->setEvents($events);
        self::assertEquals(count($events), count($this->sut));

        $filteredEvents = $this->sut->getEventsByRange($calendarStart, $calenderEnd);
        self::assertEquals($count, count($filteredEvents));

        $iterationCount=0;
        foreach ($this->sut as $event) {
            $iterationCount++;
        }
        self::assertEquals(count($events), $iterationCount);
    }

    public function testWrongEventSet()
    {
        $this->expectException(EventException::class);
        $this->sut = new Events();
        $this->sut->setEvents([new EventException()]);
    }
}
