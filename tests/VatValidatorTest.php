<?php

require_once('../src/VATValidator.php');

/**
 * Class VATValidatorTest
 */
class VatValidatorTest extends PHPUnit_Framework_TestCase {

    /**
     * @dataProvider validVatProvider
     */
    public function testValidVats($country, $vat) {
        $validator = new VatValidator($vat, $country);

        $this->assertTrue($validator->isValid(), 'Failed for ' . $validator->getVat() . ' and country ' . $validator->getCountry());
    }

    /**
     * @dataProvider invalidVatProvider
     */
    public function testInvalidVats($country, $vat){
        $validator = new VatValidator($vat, $country);

        $this->assertFalse($validator->isValid(), 'Failed for ' . $validator->getVat() . ' and country ' . $validator->getCountry());
    }

    public function validVatProvider() {

        return array(
            array('LU', 'LU12345678'),
            array('AT', 'ATU12345678'),
            array('BE', 'BE0123456789'),
            array('Belgium', 'BE1234567890'),
            array('BG', 'BG123456789'),
            array('BGR', 'BG1234567890'),
            array('CY', 'CY12345678A'),
            array('CZ', 'CZ12345678'),
            array('Czech Republic', 'CZ1234567890'),
            array('EE', 'EE123456789'),
            array('FI', 'FI12345678'),
            array('FR', 'FR12123456789'),
            array('DE', 'DE123456789'),
            array('GR', 'EL123456789'),
            array('EL', 'EL123456789'),
            array('HU', 'HU12345678'),
            array('IE', 'IE1234567A'),
            array('IE', 'IE1234567AW'),
            array('IT', 'IT12345678901'),
            array('LV', 'LV12345678901'),
            array('LT', 'LT123456789'),
            array('LT', 'LT123456789012'),
            array('MT', 'MT12345678'),
            array('NL', 'NL123456789B12'),
            array('PT', 'PT123456789'),
            array('RO', 'RO12'),
            array('RO', 'RO1234567890'),
            array('RO', 'RO123456'),
            array('SK', 'SK1234567890'),
            array('SI', 'SI12345678'),
            array('ES', 'ESA2345678A'),
            array('Spain', 'ESA23456789'),
            array('ESP', 'ES12345678A'),
            array('SE', 'SE123456789001'),
            array('GB', 'GB123456789'),
            array('UK', 'GB123456789012'),
            array('GB', 'GBGD001'),
            array('Great Britain', 'GBGD499'),
            array('Great Britain', 'GBHA500'),
            array('Great Britain', 'GBHA999')
        );
    }

    public function invalidVatProvider(){
        return array(
            array(null, 'asd'),
            array(true, -1),
            array(123, ''),
            array('11', 'LU123456789'),
            array('AT', 'AT123456789'),
            array('BE', 'BE234567891'),
            array('EE', 'EE123456'),
            array('NL', 'NL123456789A12'),
            array('NL', 'NL123456789012'),
            array('PT', 'PT123456789123456'),
            array('RO', 'RO123456789123456789'),
            array('RO', 'RO1'),
            array('ES', 'ES1A23456789'),
            array('SE', 'SE123456789012'),
            array('GB', 'GBGD500'),
            array('GB', 'GBHA123')
        );
    }

}
 