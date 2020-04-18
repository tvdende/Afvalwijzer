<?php
header('Content-Type: text/calendar');
header('Content-Disposition: attachment; filename="afvalkalender.ical"');

$data = file_get_contents('https://mijnafvalwijzer.nl/nl/4707wg/4/');
$domDocument = new DOMDocument();
$domDocument->loadHTML($data);
$jaar = $domDocument->getElementById('jaar-2020');

$array = array();
if (file_exists('testcall.test')) {
    $array = json_decode(file_get_contents('testcall.test'), true);
} else {
    foreach ($jaar->childNodes as $maand) {
        foreach ($maand->childNodes as $colomn) {
            foreach ($colomn->childNodes as $dag) {
                if ($dag->nodeName == 'table') {
                    $dagArray = explode(PHP_EOL, $dag->nodeValue);
                    if (array_key_exists(3, $dagArray) && array_key_exists(4, $dagArray)) {
                        $ophaalType  = clean($dagArray[4]);
                        $ophaalDag  = convertDate($dagArray[3]);
                        $array[] = [$ophaalType, $ophaalDag];
                    }
                }
            }
        }
    }
    file_put_contents('testcall.test', json_encode($array));
}
createCalendar($array);

function convertDate(string $dateString): DateTime
{
    $dateString = clean($dateString);
    $dateString = str_replace(
        ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag'],
        ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
        $dateString
    );
    return DateTime::createFromFormat('l d F', $dateString);
}
function clean($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return  str_replace('-', ' ', preg_replace('/[^A-Za-z0-9\-]/', '', $string)); // Removes special chars.
}
function createCalendar($array)
{
    print "BEGIN:VCALENDAR\r\nPRODID:TvdEnd-Afvalwijzer\r\nVERSION:2.0\r\nCALSCALE:GREGORIAN\r\nBEGIN:VTIMEZONE\r\nTZID:Europe/Berlin\r\nTZURL:http://tzurl.org/zoneinfo-outlook/Europe/Berlin\r\nX-LIC-LOCATION:Europe/Berlin\r\nBEGIN:DAYLIGHT\r\nTZOFFSETFROM:+0100\r\nTZOFFSETTO:+0200\r\nTZNAME:CEST\r\nDTSTART:19700329T020000\r\nRRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU\r\nEND:DAYLIGHT\r\nBEGIN:STANDARD\r\nTZOFFSETFROM:+0200\r\nTZOFFSETTO:+0100\r\nTZNAME:CET\r\nDTSTART:19701025T030000\r\nRRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU\r\nEND:STANDARD\r\nEND:VTIMEZONE\r\n";
    foreach ($array as $item) {
        $date = new DateTime($item[1]['date']);
        print createCalendarEvent($date, $item[0]);
    }
    print "END:VCALENDAR\r\n";
}


function createCalendarEvent(DateTime $date, string $summary): string
{
    return "BEGIN:VEVENT\r\n" .
        "DTSTAMP:" . $date->format('Ymd\THis\Z') . "\r\n" .
        "UID:" . $date->format(DateTimeInterface::ATOM) . "@-afvalwijzer\r\n" .
        "DTSTART;VALUE=DATE:" . $date->format('Ymd') . "\r\n" .
        "DTEND;VALUE=DATE:" . $date->add(new DateInterval('P1D'))->format('Ymd') . "\r\n" .
        "SUMMARY:$summary\r\n" .
        "TRANSP:TRANSPARENT\r\n" .
        "X-MICROSOFT-CDO-BUSYSTATUS:FREE\r\n" .
        "END:VEVENT\r\n";
}
