<?php

namespace Calendar\Pdf\RendererBundle\Event;

use Aeon\Calendar\Gregorian\DateTime;
use Aeon\Calendar\Gregorian\TimePeriod;

class Event
{
    private $text;

    /** @var string */
    private $type;

    /** @var array */
    private $additionalInformation;

    private TimePeriod $period;

    public function __construct($type=Types::EVENT_TYPE_CUSTOM)
    {
        $this->type = $type;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText($text): Event
    {
        $this->text = $text;
        return $this;
    }

    public function getAdditionalInformation(): array
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(array $info): Event
    {
        $this->additionalInformation = $info;
        return $this;
    }

    public function isInRange(DateTime $start, DateTime $end): bool
    {
        if (empty($this->period)) {
            return false;
        }

        return ($this->period->start()->isAfterOrEqual($start) && $this->period->end()->isBeforeOrEqual($end));
    }

    public function getType()
    {
        return $this->type;
    }

    public function setEventPeriod(\DateTime $start, \DateTime $end=null)
    {
        if (empty($end)) {
            $end = clone $start;
        }

        $this->period = new TimePeriod(DateTime::fromDateTime($start), DateTime::fromDateTime($end));
    }

    public function getStart(): DateTime
    {
        return $this->period->start();
    }

    public function getEnd(): DateTime
    {
        return $this->period->end();
    }
}
