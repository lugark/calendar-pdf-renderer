<?php

namespace Calendar\Pdf\Renderer\Tests\Event;

use Aeon\Calendar\Gregorian\DateTime;
use Calendar\Pdf\Renderer\Event\Event;
use Calendar\Pdf\Renderer\Event\Types;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    public function testEventCreation()
    {
        $start = new \DateTime('now');
        $end = new \DateTime('now');
        $text = 'Test';
        $additionalInfo = [];
        $eventType = Types::EVENT_TYPE_CUSTOM;

        $eventTest = new Event($eventType);
        $eventTest->setText($text);
        $eventTest->setEventPeriod($start, $end);
        $eventTest->setAdditionalInformation($additionalInfo);

        $this->assertEquals($eventType, $eventTest->getType());
        $this->assertEquals($text, $eventTest->getText());
        $this->assertEquals($additionalInfo, $eventTest->getAdditionalInformation());
        $this->assertEquals(DateTime::fromDateTime($start), $eventTest->getStart());
        $this->assertEquals(DateTime::fromDateTime($end), $eventTest->getEnd());
    }

    public function getEventData()
    {
        return [
            'Within' => [
                new \DateTime('2017-01-01 10:00:00'),
                new \DateTime('2017-01-02 10:00:00'),
                DateTime::fromString('2016-12-31 10:00:00'),
                DateTime::fromString('2017-01-03 10:00:00'),
                true
            ],
            'Outside' => [
                new \DateTime('2017-01-01 10:00:00'),
                new \DateTime('2017-01-02 10:00:00'),
                DateTime::fromString('2017-01-04 10:00:00'),
                DateTime::fromString('2017-01-05 10:00:00'),
                false
            ],
            'EndIn' => [
                new \DateTime('2017-01-01 10:00:00'),
                new \DateTime('2017-01-02 10:00:00'),
                DateTime::fromString('2017-01-02 07:00:00'),
                DateTime::fromString('2017-01-05 10:00:00'),
                false
            ],
            'StartIn' => [
                new \DateTime('2017-01-01 10:00:00'),
                new \DateTime('2017-01-02 10:00:00'),
                DateTime::fromString('2017-01-01 07:00:00'),
                DateTime::fromString('2017-01-02 07:00:00'),
                false
            ],
            'NoEnd' => [
                new \DateTime('2017-01-01 10:00:00'),
                null,
                DateTime::fromString('2017-01-01 07:00:00'),
                DateTime::fromString('2017-01-02 07:00:00'),
                true
            ],
        ];
    }

    /**
     * @dataProvider getEventData
     */
    public function testInRange($start, $end, $testStart, $testEnd, bool $isInRange)
    {
        $eventTest = new Event(Types::EVENT_TYPE_CUSTOM);
        $this->assertFalse($eventTest->isInRange($testStart, $testEnd));
        $eventTest->setEventPeriod($start, $end);

        $this->assertEquals($isInRange, $eventTest->isInRange($testStart, $testEnd));
    }
}
