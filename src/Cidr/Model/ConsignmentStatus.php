<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Model;

class ConsignmentStatus
{
    const LOCAL = "LOCAL"; // Courier hasn't been notificated of the consignment
    const ALLOCATED = "ALLOCATED"; // courier has accepted the consignment
    const REJECTED = "REJECTED"; // courier has rejected the consignment

    private function __construct() {}

}
