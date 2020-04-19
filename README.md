# Afvalwijzer
Een simpele php pagina die de Afvalwijzer site leeggehaald en alle ophaaldagen van een jaar ophaalt.
De data die opgehaald is word gecached op de server en 1 dag bewaard in een tekst file, van deze data wprd het resultaat gegenereerd die word weergegeven in de browser.

## Voorbeeld:
### Json
http://example.ext/?postcode=4707wg&huisnummer=9&format=json

Deze call zal de data in Json formaat terug geven, hieronder een voorbeeld van hoe de data er uit zal zien:
```json
[
    [
        "Papier en karton",
        {
            "date":"2020-01-02 22:21:24.000000",
            "timezone_type":3,
            "timezone":"Europe\/Amsterdam"
        }
    ],
    [
        "Groente Fruit en Tuinafval",
        {
            "date":"2020-01-07 22:21:24.000000",
            "timezone_type":3,
            "timezone":"Europe\/Amsterdam"
        }
    ],
    ....
]
```

### Ical
http://example.ext/?postcode=4707wg&huisnummer=9&format=ical

Deze call zal de data in ICal formaat terug geven, hieronder een voorbeeld van hoe de data er uit zal zien:
```ical
BEGIN:VCALENDAR
PRODID:TvdEnd-Afvalwijzer
VERSION:2.0
CALSCALE:GREGORIAN

BEGIN:VTIMEZONE
TZID:Europe/Berlin
TZURL:http://tzurl.org/zoneinfo-outlook/Europe/Berlin
X-LIC-LOCATION:Europe/Berlin

BEGIN:DAYLIGHT
TZOFFSETFROM:+0100
TZOFFSETTO:+0200
TZNAME:CEST
DTSTART:19700329T020000
RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU
END:DAYLIGHT

BEGIN:STANDARD
TZOFFSETFROM:+0200
TZOFFSETTO:+0100
TZNAME:CET
DTSTART:19701025T030000
RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU
END:STANDARD
END:VTIMEZONE

BEGIN:VEVENT
DTSTAMP:20200102T222124Z
UID:2020-01-02T22:21:24+01:00@-afvalwijzer
DTSTART;VALUE=DATE:20200102
DTEND;VALUE=DATE:20200103
SUMMARY:Papier en karton
TRANSP:TRANSPARENT
X-MICROSOFT-CDO-BUSYSTATUS:FREE
END:VEVENT

BEGIN:VEVENT
DTSTAMP:20200107T222124Z
UID:2020-01-07T22:21:24+01:00@-afvalwijzer
DTSTART;VALUE=DATE:20200107
DTEND;VALUE=DATE:20200108
SUMMARY:Groente Fruit en Tuinafval
TRANSP:TRANSPARENT
X-MICROSOFT-CDO-BUSYSTATUS:FREE
END:VEVENT

END:VCALENDAR
```