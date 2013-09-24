<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

use Cidr\Model\Address;
use Cidr\Model\Contact;

class CidrRequestContextGetQuote
{ use Milk;

	/** @var Address */
	private $collectionAddress;

	/** @var Contact */
	private $collectionContact;

 	/** @var Address */
	private $deliveryAddress;

	/** @var Contact */
	private $deliveryContact;

    /** Parcel[] */
    private $parcels;

}