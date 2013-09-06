<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Courier\P4D;

use Cidr\CidrResponse;
use Cidr\CidrRequest;
use Cidr\CidrResponseContextFailed;
use Cidr\Exception\NotImplementedException;
use Cidr\Milk;
use Cidr\Status;
use Cidr\Model\Address;
use Cidr\Model\Consignment;
use Cidr\Model\ConsignmentStatus;
use Cidr\Model\Contact;
use Cidr\CourierCapability;
use Cidr\Courier\P4D\Api\P4DQuoteResponse;
use Cidr\Courier\P4D\Api\CourierQuote;
use Cidr\Model\Task;
use Cidr\CidrResponseContextCreateConsignment;

class PrintLabel implements CourierCapability
{ use Milk;

    private $apiUrl;
    private $courierName;

    public function getTask()
    {
        return Task::PRINT_LABEL;
    }

    public function getCourier()
    {
        return $this->courierName;
    }

    function validate (CidrRequest $request)
    {
        return true;
    }


    function submitCidrRequest (CidrRequest $request)
    {
        throw new NotImplementedException();
    }


}
