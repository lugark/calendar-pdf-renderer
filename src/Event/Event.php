<?php

namespace Calendar\Pdf\Renderer\Event;

use Carbon\CarbonInterface;
use Carbon\CarbonPeriod;
use DateTime;

class Event
{
    private string $text;

    private string $type;

    /** @var array<mixed> */
    private array $additionalInformation;

    private CarbonPeriod $eventPriod;

    public function __construct($type=Types::EVENT_TYPE_CUSTOM)
    {
        $this->type = $type;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): Event
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

    public function isInRange(CarbonInterface $start, ?CarbonInterface $end): bool
    {
        if (empty($this->eventPriod)) {
            return false;
        }

        if (!empty($end)) {
            return $this->eventPriod->overlaps(CarbonPeriod::create($start, $end));
        } else {
            return $this->eventPriod->contains($start);
        }
    }

    public function getType()
    {
        return $this->type;
    }

    public function setEventPeriod(string $start, string $end=null): void
    {
        if (empty($end)) {
            $end = $start;
        }

        $this->eventPriod = CarbonPeriod::create($start, $end);
    }

    public function getStart(): CarbonInterface
    {
        return $this->eventPriod->getStartDate();
    }

    public function getEnd(): CarbonInterface
    {
        return $this->eventPriod->getEndDate();
    }

    /**
     * @param array<mixed> $data
     */
    public static function fromArray(array $data, string $eventType): Event
    {
        if (! array_key_exists('name', $data)) {
            throw new EventException('Data for event does not have a name!');
        }

        $event = new Event($eventType);
        $event->setText($data['name']);

        $startDate = isset($data['start']) ? $data['start'] : $data['date'];
        $endDate = $data['end'] ?? null;

        if ($startDate === null) {
            throw new EventException('No start date found for Event!');
        }
        
        $event->setEventPeriod($startDate, $endDate);
        return $event;
    }
}
