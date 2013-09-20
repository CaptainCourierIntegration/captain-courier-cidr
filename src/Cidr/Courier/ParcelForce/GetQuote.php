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
use Cidr\Model\Quote;
use Cidr\CidrResponse;
use Cidr\CidrResponseContextGetQuote;

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
    function submitCidrRequest(CidrRequest $request = null)
    {
        $context = $request->getRequestContext();
        $client = new \GearmanClient();
        $client->addServer();
        $output = json_decode($client->doNormal(
            "node.scrappy.ParcelForce.getQuotes",
            json_encode([
                "collectionPostcode" => $context->getCollectionPostcode(),
                "deliveryPostcode" => $context->getDeliveryPostcode(),
                "weight" => $context->getWeight()
            ])
        ));

        $quotes = [];
        foreach ($output as $quote) {
            $quotes[] = new Quote(
                $quote->service,
                $quote->delivery,
                $quote->price,
                $quote->vatIncluded,
                $quote->compensation
            );
        }

        return new CidrResponse(
            $request,
            $this,
            CidrResponse::STATUS_SUCCESS,
            new CidrResponseContextGetQuote($quotes)
        );
    }

    /**
     * @return mixed CidrValidationViolation[] or true/false
     */
    function validate(CidrRequest $request)
    {
    	return true;
    }
}