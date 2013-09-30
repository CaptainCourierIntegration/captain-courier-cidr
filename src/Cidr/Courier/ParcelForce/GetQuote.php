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
use Bond\Gearman\ServerStatus;

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
        $context = $request->getRequestContext();

        $serverStatus = new ServerStatus();
        d($serverStatus->getStatus());
        if (!$serverStatus->isAlive()) {
            throw new \Exception("NO GEARMAN SERVER!!!");
        }

        $client = new \GearmanClient();
        $client->addServer();

        $output = json_decode($client->doNormal(
            "node.scrappy.ParcelForce.getQuotes",
            json_encode([
                "collectionPostcode" => $context->getCollectionAddress()->getPostcode(),
                "deliveryPostcode" => $context->getDeliveryAddress()->getPostcode(),
                "weight" => array_sum(array_map(function($p){return $p->getWeight();}, $context->getParcels()))
            ])
        ));

        $quotes = [];
        foreach ($output as $quote) {
            $quotes[] = new Quote([
                "courierName" => $this->courierName,
                "serviceName" => $quote->service,
                "deliveryEstimate" => $quote->delivery,
                "price" => $quote->price,
                "includesVat" => $quote->vatIncluded,
                "compensation" => $quote->compensation
            ]);
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