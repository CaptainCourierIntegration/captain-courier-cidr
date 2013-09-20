<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Model;

use Cidr\Milk;

class Quote
{ use Milk;

	/** @var string name of service human readable */
	private $serviceName;

	/** @var string human readable message describing how long it takes to be delivered */
	private $deliveryEstimate;

	/** @var string price of quote, in format ##.## where # is a digit in the range 0-9. can have an arbitrary number of significant digits. */
	private $price;

	/** @var bool true if VAT is included in the quote price, false otherwise. */
	private $includesVat;

	/** @var string human readable price indicating how much compensation can be provided by courier if damaged/lost, etc */
	private $compensation;

}