<?php
require_once('Postcode.php');

class AfvalwijzerScraper
{
    private $taal;
    private $postcode;
    private $huisnummer;

    function __construct(string $taal, Postcode $postcode, int $huisnummer)
    {
        $this->taal = $taal;
        $this->postcode = $postcode;
        $this->huisnummer = $huisnummer;
    }

    public function GetData(): array
    {
        $ophaalDagen = [];
        $data = file_get_contents("https://mijnafvalwijzer.nl/$this->taal/" . $this->postcode->GetPostcode() . "/$this->huisnummer/");
        $domDocument = new DOMDocument();

        // Ignore the errors of the invalid html of the data source
        libxml_use_internal_errors(true);
        $domDocument->loadHTML($data);
        libxml_use_internal_errors(false);

        $finder = new DomXPath($domDocument);
        $classname = "ophaaldagen";
        $jaar = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]")->item(0);

        foreach ($jaar->childNodes as $maand) {
            foreach ($maand->childNodes as $colomn) {
                foreach ($colomn->childNodes as $dag) {
                    if ($dag->nodeName == 'table') {
                        $dagArray = explode(PHP_EOL, $dag->nodeValue);
                        if (array_key_exists(3, $dagArray) && array_key_exists(4, $dagArray)) {
                            $ophaalType  = $this->clean($dagArray[4]);
                            $ophaalDag  = $this->convertDate($dagArray[3]);
                            $ophaalDagen[] = [$ophaalType, $ophaalDag];
                        }
                    }
                }
            }
        }
        return $ophaalDagen;
    }

    private function convertDate(string $dateString): DateTime
    {
        $dateString = $this->clean($dateString);
        $dateString = str_replace(
            ['januari', 'februari', 'maart', 'april', 'mei', 'juni', 'juli', 'augustus', 'september', 'oktober', 'november', 'december', 'maandag', 'dinsdag', 'woensdag', 'donderdag', 'vrijdag', 'zaterdag', 'zondag'],
            ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            $dateString
        );
        return DateTime::createFromFormat('l d F', $dateString);
    }
    private  function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

        return  str_replace('-', ' ', preg_replace('/[^A-Za-z0-9\-]/', '', $string)); // Removes special chars.
    }
}
