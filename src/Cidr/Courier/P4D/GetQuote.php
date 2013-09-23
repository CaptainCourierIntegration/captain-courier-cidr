<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\P4D;

use Cidr\Model\Task;
use Cidr\CidrRequest;
use Cidr\Exception\NotImplementedException;
use Cidr\CourierCapability;
use Cidr\Milk;

class GetQuote implements CourierCapability
{ use Milk;

    /**
     * @var string
     */
    private $courierName;

    /** @inheritdoc */
    public function getTask()
    {
    	return Task::GET_QUOTE;
    }	

    public function getCourier()
    {
    	return $this->courierName;
    }

    public function validate(CidrRequest $request)
    {
    	return true;
    }

    public function submitCidrRequest(CidrRequest $request) 
    {

    }


    private function getQuotes(CidrRequest $request)
    {
        $requestContext = $request->getRequestContext();

        $collectionLines = $requestContext->getCollectionAddress()->getLines();
        $deliveryLines = $requestContext->getDeliveryAddress()->getLines();
        $fields = array(
            "ShipAction" => "GetQuote",
            "Username" => $request->getCourierCredentials()->username,
            "APIKey" => $request->getCourierCredentials()->apiKey,
            "CollectionName" => $requestContext->getCollectionContact()->getName(),
            "CollectionCompany" => $requestContext->getCollectionContact()->getBusinessName(),
            "CollectionAddress1" => $collectionLines[0],
            "CollectionAddress2" => count($collectionLines) >= 2 ? $collectionLines[1] : "",
            "CollectionAddress3" => count($collectionLines) >= 3 ? $collectionLines[2] : "",
            "CollectionTown" => $requestContext->getCollectionAddress()->getTown(),
            "CollectionCounty" => $requestContext->getCollectionAddress()->getCounty(),
            "CollectionPostcode" => $requestContext->getCollectionAddress()->getPostcode(),
            "CollectionTelephone" => $requestContext->getCollectionContact()->getTelephone(),
            "CollectionCountry" => $requestContext->getCollectionAddress()->getCountryCode(),
            "DeliveryName" => $requestContext->getDeliveryContact()->getName(),
            "DeliveryCompany" => $requestContext->getDeliveryContact()->getBusinessName(),
            "DeliveryAddress1" => $deliveryLines[0],
            "DeliveryAddress2" => count($deliveryLines) >= 2? $deliveryLines[1] : "",
            "DeliveryAddress3" => count($deliveryLines) >= 3? $deliveryLines[2] : "",
            "DeliveryTown" => $requestContext->getDeliveryAddress()->getTown(),
            "DeliveryCounty" => $requestContext->getDeliveryAddress()->getCounty(),
            "DeliveryPostcode" => $requestContext->getDeliveryAddress()->getPostcode(),
            "DeliveryCountry" =>  $requestContext->getDeliveryAddress()->getCountryCode(),
            "DeliveryTelephone" => $requestContext->getDeliveryContact()->getTelephone(),
            "BundleData" => $this->parcelsToString($requestContext->getParcels()),
            "ParcelContents" => implode(", ", array_map(function($p){return $p->description;}, $requestContext->getParcels())),
            "EstimatedValue" => array_sum(array_map(function($p){return $p->value;}, $requestContext->getParcels()))
        );
                
        $jsonResponse = $this->curl->post($this->apiUrl, $fields);
        $objResponse = json_decode($jsonResponse);
        $response = new P4DQuoteResponse($objResponse);
        return $response;
        //return \Cidr\apply(P4DQuoteResponse::class, '\json_decode', \Cidr\func( $this->curl, "post"), [$this->apiUrl, $fields]);
    }

}