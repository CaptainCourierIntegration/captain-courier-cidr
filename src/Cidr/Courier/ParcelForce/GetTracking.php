<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace Cidr\Courier\ParcelForce;

use Cidr\Model\Task;
use Cidr\Milk;
use Cidr\CourierCapability;
use Cidr\CidrRequest;

class GetTracking implements CourierCapability
{ use Milk;

	private $courierName;

    function getTask()
    {
    	return Task::GET_TRACKING;
    }

    function getCourier()
    {
    	return $this->courierName;
    }

    function submitCidrRequest(CidrRequest $request)
    {
    	die("ALL GOOD\n");
    }

    function validate(CidrRequest $request)
    {
    	return true;
    }

}