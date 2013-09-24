<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\P4D\Api;

use Cidr\Milk;

 class CollectionDate
 { use Milk;

 	/** @var DateTime date of collection */
 	private $date;

 	/** @var string human readable message indicating when this collection date is no longer valid */
 	private $deadline;

 }