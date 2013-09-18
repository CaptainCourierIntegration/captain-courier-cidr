<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce\Api;

class Parcel
{
    /**
     * @access public
     * @var nsWeight
     */
    public $Weight;
    /**
     * @access public
     * @var positiveInteger
     */
    public $Length;
    /**
     * @access public
     * @var positiveInteger
     */
    public $Height;
    /**
     * @access public
     * @var positiveInteger
     */
    public $Width;
    /**
     * @access public
     * @var string
     */
    public $PurposeOfShipment;
    /**
     * @access public
     * @var string
     */
    public $InvoiceNumber;
    /**
     * @access public
     * @var string
     */
    public $ExportLicenseNumber;
    /**
     * @access public
     * @var string
     */
    public $CertificateNumber;
    /**
     * @access public
     * @var ContentDetails
     */
    public $ContentDetails;
}
