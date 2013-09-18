<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce\Api;

class ParcelLabelData
{
    /**
     * @access public
     * @var string
     */
    public $ParcelNumber;
    /**
     * @access public
     * @var string
     */
    public $ShipmentNumber;
    /**
     * @access public
     * @var LabelData
     */
    public $LabelData;
    /**
     * @access public
     * @var Barcodes
     */
    public $Barcodes;
}
