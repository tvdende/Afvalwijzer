<?php
class Calendar
{
    public array $event = [];

    public function AddEvent(DateTime $date, string $summary): void
    {
        $event[] = new CalendarEvent($date, $summary);
    }
}
class CalendarEvent
{
    private DateTime $date;
    private string $summary;

    public function __construct(DateTime $date, string $summary)
    {
        $this->date = $date;
        $this->summary = $summary
    }

    public function __toString()
    { return "BEGIN:VEVENT\r\n" .
        "DTSTAMP:" . $this->date->format('Ymd\THis\Z') . "\r\n" .
        "UID:" . $this->date->format(DateTimeInterface::ATOM) . "@-afvalwijzer\r\n" .
        "DTSTART;VALUE=DATE:" . $this->date->format('Ymd') . "\r\n" .
        "DTEND;VALUE=DATE:" . $this->date->add(new DateInterval('P1D'))->format('Ymd') . "\r\n" .
        "SUMMARY:$this->summary\r\n" .
        "TRANSP:TRANSPARENT\r\n" .
        "X-MICROSOFT-CDO-BUSYSTATUS:FREE\r\n" .
        "END:VEVENT\r\n";
    }
}
