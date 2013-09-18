<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce\Api;

class Contact
{
    /**
     * @access public
     * @var string
     */
    public $BusinessName;
    /**
     * @access public
     * @var string
     */
    public $ContactName;
    /**
     * @access public
     * @var string
     */
    public $EmailAddress;
    /**
     * @access public
     * @var string
     */
    public $Telephone;
    /**
     * @access public
     * @var string
     */
    public $Fax;
    /**
     * @access public
     * @var nsNotificationType
     */
    public $NotificationType;
}
