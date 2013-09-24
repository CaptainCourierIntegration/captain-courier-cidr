<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

class CidrResponseContextCreateShipment implements CidrResponseContext
{ use Milk;

	/** @var string unique identifier of shipment that is used by customer */
	private $shipmentId;

    /** @var string courier generated shipment number */
    private $shipmentNumber;

}