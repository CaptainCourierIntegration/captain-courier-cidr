<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce;

use Cidr\CourierCapability;
use Cidr\Milk;
use Cidr\Model\Task;
use Cidr\CidrRequest;

class GetQuote implements CourierCapability
{ use Milk;

	private $courierName;

    /**
     * @return string task type, such as CREATE_CONSIGNMENT.
     */
    function getTask()
    {
    	return Task::GET_QUOTE;
    }

    /**
     * @return string name of courier, such as PARCEL_FORCE
     */
    function getCourier()
    {
    	return $this->courierName;
    }

    /**
     * @return CidrResponse
     */
    function submitCidrRequest(CidrRequest $request)
    {
    	die("not implemented SUCKER");
    }

    /**
     * @return mixed CidrValidationViolation[] or true/false
     */
    function validate(CidrRequest $request)
    {
    	return true;
    }
}