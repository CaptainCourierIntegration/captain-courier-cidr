<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

class CidrRequestContextGetQuote
{ use Milk;

	/** @var string */
	private $collectionPostcode;

 	/** @var string */
	private $deliveryPostcode;

	/** @var string */
	private $weight;

}