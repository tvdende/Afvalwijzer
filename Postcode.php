<?php
class Postcode
{
    private $postcode;

    public function __construct(string $postcode)
    {
        $postcode = preg_replace('/[^A-Z0-9]/', "", strtoupper($postcode));

        if ($this->check($postcode)) {
            $this->postcode = $postcode;
        } else {
            throw new Exception('Ingevoerde postcode is niet in een geldig formaat');
        }
    }

    public function GetPostcode(): string
    {
        return $this->postcode;
    }

    // Check functie met true of false return
    private function Check($postcode): bool
    {
        return preg_match("/^\b[1-9]\d{3}\s*[A-Z]{2}\b$/", $postcode) == 1 ? true : false;
    }
}
