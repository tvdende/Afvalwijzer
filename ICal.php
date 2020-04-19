<?php
class Calendar
{
    public $events;

    public function AddEvent(DateTime $date, string $summary): void
    {
        $this->events[] = new CalendarEvent($date, $summary);
    }

    public function __toString()
    {
        $result = "BEGIN:VCALENDAR\r\nPRODID:TvdEnd-Afvalwijzer\r\nVERSION:2.0\r\nCALSCALE:GREGORIAN\r\nBEGIN:VTIMEZONE\r\nTZID:Europe/Berlin\r\nTZURL:http://tzurl.org/zoneinfo-outlook/Europe/Berlin\r\nX-LIC-LOCATION:Europe/Berlin\r\nBEGIN:DAYLIGHT\r\nTZOFFSETFROM:+0100\r\nTZOFFSETTO:+0200\r\nTZNAME:CEST\r\nDTSTART:19700329T020000\r\nRRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU\r\nEND:DAYLIGHT\r\nBEGIN:STANDARD\r\nTZOFFSETFROM:+0200\r\nTZOFFSETTO:+0100\r\nTZNAME:CET\r\nDTSTART:19701025T030000\r\nRRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU\r\nEND:STANDARD\r\nEND:VTIMEZONE\r\n";
        foreach ($this->events as $item) {
            $result = $result . $item->__toString();
        }
        return $result . "END:VCALENDAR\r\n";
    }
}

class CalendarEvent
{
    private $date;
    private $summary;

    public function __construct(DateTime $date, string $summary)
    {
        $this->date = $date;
        $this->summary = $summary;
    }

    public function __toString()
    {
        return "BEGIN:VEVENT\r\n" .
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
