<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

use Cidr\Model\Address;

class CidrRequestContextGetQuote
{ use Milk;

	/** @var Address */
	private $collectionAddress;

 	/** @var Address */
	private $deliveryAddress;

	/** @var string in the format /\d+\.\d{2}/ */
	private $weight;

}