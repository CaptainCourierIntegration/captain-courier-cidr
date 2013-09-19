<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce\Api;

class Address
{
    /**
     * @access public
     * @var string
     */
    public $AddressLine1;
    /**
     * @access public
     * @var string
     */
    public $AddressLine2;
    /**
     * @access public
     * @var string
     */
    public $AddressLine3;
    /**
     * @access public
     * @var string
     */
    public $Town;
    /**
     * @access public
     * @var string
     */
    public $Postcode;
    /**
     * @access public
     * @var string
     */
    public $Country;
}
