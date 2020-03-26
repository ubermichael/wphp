<?php


namespace App\Services;


use App\Entity\Firm;
use App\Entity\Person;
use App\Entity\Title;

class CsvExporter {

    public function firmHeaders() {
        return ['Firm ID', 'Name', 'Street Address', 'City', 'Start Date', 'End Date'];
    }

    public function firmRow(Firm $firm) {
        return [
            $firm->getId(),
            $firm->getName(),
            $firm->getStreetAddress(),
            $firm->getCity() ? $firm->getCity()->getName() : '',
            preg_replace('/-00/', '', $firm->getStartDate()),
            preg_replace('/-00/', '', $firm->getEndDate()),
        ];
    }

    public function personHeaders() {
        return ['Person ID', 'Last Name', 'First Name', 'Gender', 'Birth Date', 'Birth City', 'Death Date', 'Death City'];
    }

    public function personRow(Person $person) {
        return [
            $person->getId(),
            $person->getLastName(),
            $person->getFirstName(),
            $person->getGender(),
            $person->getDob(),
            $person->getCityOfBirth() ? $person->getCityOfBirth()->getName() : '',
            $person->getDod(),
            $person->getCityOfDeath() ? $person->getCityOfBirth()->getName() : '',
        ];
    }

    public function titleHeaders() {
        return [
            'Title Id',
            'Title',
            'Signed Author',
            'Pseudonym',
            'Imprint',
            'Self Published',
            'Printing City',
            'Printing Country',
            'Printing Lat',
            'Printing Long',
            'Date',
            'Format',
            'Length',
            'Width',
            'Edition',
            'Volumes',
            'Pagination',
            'Price_pound',
            'Price_shilling',
            'Price_pence',
            'Genre',
            'Shelf mark',
        ];
    }

    public function titleRow(Title $title) {
        return [
            $title->getId(),
            $title->getTitle(),
            $title->getSignedAuthor(),
            $title->getPseudonym(),
            $title->getImprint(),
            $title->getSelfPublished() ? 'yes' : 'no',
            ($title->getLocationOfPrinting() ? $title->getLocationOfPrinting()->getName() : ''),
            ($title->getLocationOfPrinting() ? $title->getLocationOfPrinting()->getCountry() : ''),
            ($title->getLocationOfPrinting() ? $title->getLocationOfPrinting()->getLatitude() : ''),
            ($title->getLocationOfPrinting() ? $title->getLocationOfPrinting()->getLongitude() : ''),
            $title->getPubDate(),
            ($title->getFormat() ? $title->getFormat()->getName() : ''),
            $title->getSizeL(),
            $title->getSizeW(),
            $title->getEdition(),
            $title->getVolumes(),
            $title->getPagination(),
            $title->getPricePound(),
            $title->getPriceShilling(),
            $title->getPricePence(),
            ($title->getGenre() ? $title->getGenre()->getName() : ''),
            $title->getShelfmark(),
        ];
    }

}
