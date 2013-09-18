<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce\Api;

class PrintLabelRequest extends BaseRequest
{
    /**
     * @access public
     * @var string
     */
    public $ShipmentNumber;
    /**
     * @access public
     * @var string
     */
    public $PrintFormat;
    /**
     * @access public
     * @var string
     */
    public $BarcodeFormat;
    /**
     * @access public
     * @var nsPrintType
     */
    public $PrintType;
}
