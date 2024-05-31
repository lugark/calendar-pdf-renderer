<?php

namespace Calendar\Pdf\Renderer\Tests\Event;

use Calendar\Pdf\Renderer\Event\Event;
use Calendar\Pdf\Renderer\Event\Types;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testEventCreationOnlyDate()
    {
        $eventTest = Event::fromArray(['date' => '2024-05-05 10:00:00', 'name' => 'Test'], Types::EVENT_TYPE_CUSTOM);
        $eventTest->setAdditionalInformation(['someData']);

        $this->assertEquals(Types::EVENT_TYPE_CUSTOM, $eventTest->getType());
        $this->assertEquals('Test', $eventTest->getText());
        $this->assertEquals(['someData'], $eventTest->getAdditionalInformation());
        $this->assertEquals(Carbon::create('2024-05-05 10:00:00'), $eventTest->getStart());
        $this->assertEquals(Carbon::create('2024-05-05 10:00:00'), $eventTest->getEnd());
    }

    public function testEventCreationStartAndEnd()
    {
        $eventTest = Event::fromArray(['start' => '2024-05-05 10:00:00', 'end'=> '2024-05-06 10:00:00', 'name' => 'Test'], Types::EVENT_TYPE_CUSTOM);
        $eventTest->setAdditionalInformation(['someData']);

        $this->assertEquals(Types::EVENT_TYPE_CUSTOM, $eventTest->getType());
        $this->assertEquals('Test', $eventTest->getText());
        $this->assertEquals(['someData'], $eventTest->getAdditionalInformation());
        $this->assertEquals(Carbon::create('2024-05-05 10:00:00'), $eventTest->getStart());
        $this->assertEquals(Carbon::create('2024-05-06 10:00:00'), $eventTest->getEnd());
    }

    public static function getEventData(): array
    {
        return [
            'Within' => [
                '2017-01-01 10:00:00',
                '2017-01-02 10:00:00',
                Carbon::create('2016-12-31 10:00:00'),
                Carbon::create('2017-01-03 10:00:00'),
                true
            ],
            'Outside' => [
                '2017-01-01 10:00:00',
                '2017-01-02 10:00:00',
                Carbon::create('2017-01-04 10:00:00'),
                Carbon::create('2017-01-05 10:00:00'),
                false
            ],
            'StartIn' => [
                '2017-01-01 10:00:00',
                '2017-01-02 10:00:00',
                Carbon::create('2017-01-02 07:00:00'),
                Carbon::create('2017-01-05 10:00:00'),
                true
            ],
            'EndIn' => [
                '2017-01-01 10:00:00',
                '2017-01-02 10:00:00',
                Carbon::create('2017-01-01 07:00:00'),
                Carbon::create('2017-01-02 07:00:00'),
                true
            ],
            'NoEnd' => [
                '2017-01-01 10:00:00',
                null,
                Carbon::create('2017-01-01 07:00:00'),
                Carbon::create('2017-01-02 07:00:00'),
                true
            ],
        ];
    }


    #[DataProvider('getEventData')]
    public function testInRange($start, $end, $testStart, $testEnd, bool $isInRange)
    {
        $eventTest = Event::fromArray(['start' => $start, 'end' => $end, 'name' => 'Test'], Types::EVENT_TYPE_CUSTOM);
        self::assertEquals($isInRange, $eventTest->isInRange($testStart, $testEnd));
    }
}
