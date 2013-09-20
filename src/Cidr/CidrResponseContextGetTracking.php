<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

class CidrResponseContextGetTracking implements CidrRequestContext
{ use Milk;

	/** @var array of \Cidr\Model\TrackingLogEntry */
	private $trackingLog;

}