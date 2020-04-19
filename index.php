<?php
error_reporting(E_ALL);
require_once('Postcode.php');
require_once('Caching.php');
require_once('ICal.php');
require_once('AfvalwijzerScraper.php');

// header('Content-Type: text/calendar');
// header('Content-Disposition: attachment; filename="afvalkalender.ical"');
$postcodeInput = $_GET['postcode'];
$huisnummer = $_GET['huisnummer'];
$taal = isset($_GET['taal']) ?  $_GET['taal'] : 'nl';
$format = isset($_GET['format']) ?  $_GET['format'] : 'ical';

// Validate postcode
$postcode = new Postcode($postcodeInput);

$array = array();

// Create a new cache with a expire time of one day
$cache = new Cache(1440);
$cacheFileName = $postcode->GetPostcode() . '.cache';

if ($cache->Exists($cacheFileName) && !$cache->IsExpired($cacheFileName)) {
    $array = $cache->GetFromCache($cacheFileName);
} else {
    $afvalwijzerScraper = new AfvalwijzerScraper($taal, $postcode, $huisnummer);
    $array = $afvalwijzerScraper->GetData();

    $cache->AddToCache($cacheFileName, $array);
}

switch ($format) {
    case 'ical':
        $calendar = new Calendar();
        foreach ($array as $item) {
            $date = new DateTime($item[1]->date);
            $calendar->AddEvent($date, $item[0]);
        }
        print $calendar->__toString();
        break;
}
