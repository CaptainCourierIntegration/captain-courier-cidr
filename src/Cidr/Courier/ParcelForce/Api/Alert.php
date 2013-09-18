<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce\Api;

class Alert
{
    /**
     * @access public
     * @var integer
     */
    public $Code;
    /**
     * @access public
     * @var string
     */
    public $Message;
    /**
     * @access public
     * @var nsAlertType
     */
    public $Type;
}
