<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce\Api;

class ContentDetail
{
    /**
     * @access public
     * @var string
     */
    public $CountryOfManufacture;
    /**
     * @access public
     * @var string
     */
    public $Description;
    /**
     * @access public
     * @var nsWeight
     */
    public $UnitWeight;
    /**
     * @access public
     * @var positiveInteger
     */
    public $UnitQuantity;
    /**
     * @access public
     * @var double
     */
    public $UnitValue;
    /**
     * @access public
     * @var string
     */
    public $Currency;
    /**
     * @access public
     * @var string
     */
    public $TariffCode;
    /**
     * @access public
     * @var string
     */
    public $TariffDescription;
}
