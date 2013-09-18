<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce\Api;

class RequestedShipment
{
    /**
     * @access public
     * @var nsShipmentType
     */
    public $ShipmentType;
    /**
     * @access public
     * @var string
     */
    public $ContractNumber;
    /**
     * @access public
     * @var integer
     */
    public $RequestId;
    /**
     * @access public
     * @var string
     */
    public $ServiceCode;
    /**
     * @access public
     * @var date
     */
    public $ShippingDate;
    /**
     * @access public
     * @var string
     */
    public $JobReference;
    /**
     * @access public
     * @var Contact
     */
    public $RecipientContact;
    /**
     * @access public
     * @var Address
     */
    public $RecipientAddress;
    /**
     * @access public
     * @var integer
     */
    public $TotalNumberOfParcels;
    /**
     * @access public
     * @var nsWeight
     */
    public $TotalShipmentWeight;
    /**
     * @access public
     * @var Enhancement
     */
    public $Enhancement;
    /**
     * @access public
     * @var Returns
     */
    public $Returns;
    /**
     * @access public
     * @var CollectionInfo
     */
    public $CollectionInfo;
    /**
     * @access public
     * @var InternationalInfo
     */
    public $InternationalInfo;
    /**
     * @access public
     * @var string
     */
    public $ReferenceNumber1;
    /**
     * @access public
     * @var string
     */
    public $ReferenceNumber2;
    /**
     * @access public
     * @var string
     */
    public $ReferenceNumber3;
    /**
     * @access public
     * @var string
     */
    public $ReferenceNumber4;
    /**
     * @access public
     * @var string
     */
    public $ReferenceNumber5;
    /**
     * @access public
     * @var string
     */
    public $SpecialInstructions1;
    /**
     * @access public
     * @var string
     */
    public $SpecialInstructions2;
    /**
     * @access public
     * @var string
     */
    public $SpecialInstructions3;
    /**
     * @access public
     * @var string
     */
    public $SpecialInstructions4;
}
