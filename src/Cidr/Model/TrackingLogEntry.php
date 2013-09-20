<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Cidr\Courier;

use Cidr\Milk;

class TrackingLogEntry 
{ use Milk;

	private $date;
	private $time;
	private $location;
	private $trackignEvent;

}