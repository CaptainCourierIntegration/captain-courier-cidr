<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Model;


class Quote
{ use TwoSugar;

	private $coreProperties = [
		/** @var integer identifier of quote exposed to customer */
		"id",

		/** @var integer identifier of shipment this quote is tied to */
		"shipmentId",

		/** @var string name of courier */
		"courierName",

		/** @var string name of service human readable */
		"serviceName",

		/** @var string human readable message describing how long it takes to be delivered */
		"deliveryEstimate",

		/** @var string price of quote, in format ##.## where # is a digit in the range 0-9. can have an arbitrary number of significant digits. */
		"price",

		/** @var bool true if VAT is included in the quote price, false otherwise. */
		"includesVat"
	];

}