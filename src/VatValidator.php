<?php
/**
 * This class is used to validate European VAT registration numbers.
 *
 * @author Serge Kukharev <serge.kukharev@gmail.com>
 *
 * @see http://en.wikipedia.org/wiki/VAT_identification_number
 * @see http://goo.gl/3vXBY1
 */

// TODO add special validation for some countries.

/**
 * Class VATValidator
 */
class VatValidator
{

    /** @var  string VAT number. */
    protected $_vat;
    /** @var  string VAT country. */
    protected $_country;

    /** @var array RegEx patterns for each country. */
    static protected $_countryPatterns = array(
        'AT' => '/^(AT){0,1}U[0-9]{8}$/i', // Austria
        'BE' => '/^(BE){0,1}[0,1]{1}[0-9]{9}$/i', // Belgium
        'BG' => '/^(BG){0,1}[0-9]{9,10}$/i', // Bulgaria
        'CY' => '/^(CY){0,1}[0-9]{8}[A-Z]$/i', // Cyprus
        'CZ' => '/^(CZ){0,1}[0-9]{8,10}$/i', // Czech Republic
        'DE' => '/^(DE){0,1}[0-9]{9}$/i', // Germany
        'DK' => '/^(DK){0,1}([0-9]{2}[\ ]{0,1}){3}[0-9]{2}$/i',
        // Denmark
        'EE' => '/^(EE){0,1}[0-9]{9}$/i', // Estonia
        'ES' => '/^(ES){0,1}([0-9A-Z][0-9]{7}[0-9A-Z])$/i', // Spain
        'GR' => '/^(EL){0,1}[0-9]{9}$/i', // Greece
        'EL' => '/^(EL){0,1}[0-9]{9}$/i', // Greece
        'FR' => '/^(FR){0,1}[0-9A-Z]{2}[\ ]{0,1}[0-9]{9}$/i', // France
        'FI' => '/^(FI){0,1}[0-9]{8}$/i', // Finland
        'HU' => '/^(HU){0,1}[0-9]{8}$/i', // Hungary
        'IE' => "/^((IE){0,1}[0-9][0-9A-Z\+\*][0-9]{5}[A-Z])
            |((IE){0,1}[0-9]{7}[A-Z]{1,2})$/i",
        // Ireland
        'IT' => '/^(IT){0,1}[0-9]{11}$/i', // Italy
        'LV' => '/^(LV){0,1}[0-9]{11}$/i', // Latvia
        'LT' => '/^(LT){0,1}([0-9]{9}|[0-9]{12})$/i', // Lithuania
        'LU' => '/^(LU){0,1}[0-9]{8}$/i', // Luxembourg
        'MT' => '/^(MT){0,1}[0-9]{8}$/i', // Malta
        'NL' => '/^(NL){0,1}[0-9]{9}B[0-9]{2}$/i', // Netherlands
        'PL' => '/^(PL){0,1}[0-9]{10}$/i', // Poland
        'PT' => '/^(PT){0,1}[0-9]{9}$/i', // Portugal
        'RO' => '/^(RO){0,1}[0-9]{2,10}$/i', // Romania
        'SK' => '/^(SK){0,1}[0-9]{10}$/i', // Slovakia
        'SI' => '/^(SI){0,1}[0-9]{8}$/i', // Slovenia
        'SE' => '/^(SE){0,1}[0-9]{10}(01)$/i', // Sweden
        // (?#...) group is used to allow multiline pattern.
        'UK' => "/^((GB){0,1}[1-9][0-9]{2}[\ ]{0,1}[0-9]{4}[\ ]{0,1}[0-9]{2})(?#
                )|((GB){0,1}[1-9][0-9]{2}[\ ]{0,1}[0-9]{4}[\ ](?#
                ){0,1}[0-9]{2}[\ ]{0,1}[0-9]{3})(?#
                )|((GB){0,1}(GD)[0-4][0-9][0-9])(?#
                )|((GB){0,1}(HA)[5-9][0-9][0-9])$/i", // United Kingdom
        'GB' => "/^((GB){0,1}[1-9][0-9]{2}[\ ]{0,1}[0-9]{4}[\ ]{0,1}[0-9]{2})(?#
                )|((GB){0,1}[1-9][0-9]{2}[\ ]{0,1}[0-9]{4}[\ ](?#
                ){0,1}[0-9]{2}[\ ]{0,1}[0-9]{3})(?#
                )|((GB){0,1}(GD)[0-4][0-9][0-9])(?#
                )|((GB){0,1}(HA)[5-9][0-9][0-9])$/i", // United Kingdom
        );

    /** @var array Relation of ISO3 to ISO2 code. */
    static protected $_countryISO = array(
        'AUT' => 'AU', 'BEL' => 'BE', 'BGR' => 'BG', 'CYP' => 'CY',
        'CZE' => 'CZ', 'DEU' => 'DE', 'DNK' => 'DK', 'EST' => 'EE',
        'ESP' => 'ES', 'GRC' => 'GR', 'FRA' => 'FR', 'FIN' => 'FI',
        'HUN' => 'HU', 'IRL' => 'IE', 'ITA' => 'IT', 'LVA' => 'LV',
        'LTU' => 'LT', 'LUX' => 'LU', 'MLT' => 'MT', 'NLD' => 'NL',
        'POL' => 'PL', 'PRT' => 'PT', 'ROU' => 'RO', 'SVK' => 'SK',
        'SVN' => 'SI', 'SWE' => 'SE', 'GBR' => 'UK'
    );
    /** @var array Relation of country name to ISO2 code. */
    static protected $_countryName = array(
        'austria'          => 'AU', 'belgium' => 'BE',
        'bulgaria'         => 'BG', 'cyprus' => 'CY',
        'czech republic'   => 'CZ', 'germany' => 'DE',
        'denmark'          => 'DK', 'estonia' => 'EE', 'spain' => 'ES',
        'greece'           => 'GR', 'france' => 'FR', 'finland' => 'FI',
        'hungary'          => 'HU', 'Ireland' => 'IE', 'italy' => 'IT',
        'latvia'           => 'LV', 'lithuania' => 'LT',
        'luxembourg'       => 'LU', 'malta' => 'MT',
        'netherlands'      => 'NL', 'poland' => 'PL',
        'portugal'         => 'PT', 'romania' => 'RO',
        'slovakia'         => 'SK', 'slovenia' => 'SI',
        'sweden'           => 'SE', 'united kingdom' => 'UK',
        'great britain'    => 'GB', 'england' => 'UK',
        'scotland'         => 'UK', 'northern ireland' => 'UK',
        'wales'            => 'UK'
    );

    /**
     * Class constructor.
     *
     * @param string $vat
     * @param string $country
     */
    public function __construct($vat, $country = '')
    {
        if (!empty($country)) {
            $this->setCountry($country);
            $this->setVatNumber($vat);
        } else {
            $this->setVatNumber($vat, true);
        }
    }

    /**
     * Shows if current vat number is valid or not.
     * Also returns false if country was invalid.
     *
     * @return bool
     */
    public function isValid()
    {
        if (!array_key_exists($this->_country, self::$_countryPatterns)) {
            return false;
        }

        // Just making sure that we always get boolean, not 1 || 0 || false.
        return preg_match(self::$_countryPatterns[$this->_country], $this->_vat)
            ? true : false;
    }

    /**
     * Setter for $this->country.
     *
     * @param string $country VAT country.
     *
     * @return $this
     */
    public function setCountry($country)
    {
        if (strlen($country) === 2
            && array_key_exists(
                strtoupper($country), self::$_countryPatterns
            )
        ) {
            $this->_country = strtoupper($country);
        } elseif (strlen($country) === 3
            && array_key_exists(
                strtoupper($country), self::$_countryISO
            )
        ) {
            // Get ISO2 code from ISO3
            $this->_country = self::$_countryISO[strtoupper($country)];
        } elseif (array_key_exists(strtolower($country), self::$_countryName)) {
            $this->_country = self::$_countryName[strtolower($country)];
        } else {
            $this->_country = null;
        }

        return $this;
    }

    /**
     * Setter for $this->vat.
     *
     * @param string  $vat             VAT number
     * @param boolean $doUpdateCountry Shows if we need to guess VAT country
     *                                 from VAT number.
     *
     * @return $this
     */

    public function setVatNumber($vat, $doUpdateCountry = false)
    {
        $this->_vat = $vat;

        if ($doUpdateCountry) {
            $this->_country = $this->getCountryFromVat($vat);
        }

        return $this;
    }

    /**
     * Getter for $this->vat.
     *
     * @return string
     */
    public function getVat()
    {
        return $this->_vat;
    }

    public function getCountry()
    {
        return $this->_country;
    }

    /**
     * Checks if VAT number is valid.
     *
     * @param string $vat
     * @param string $country
     *
     * @return boolean
     */
    public static function validate($vat, $country)
    {
        $validator = new VatValidator($vat, $country);

        return $validator->isValid();
    }

    /**
     * Tries to get country from VAT number.
     *
     * @param string $vat VAT number
     *
     * @return null|string
     */
    protected function getCountryFromVat($vat)
    {
        $country = strtoupper(substr($vat, 0, 2));

        return array_key_exists($country, self::$_countryPatterns) ? $country
            : null;
    }
} 