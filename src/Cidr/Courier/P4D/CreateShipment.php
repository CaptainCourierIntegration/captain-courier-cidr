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
use Cidr\Milk;
use Cidr\Status;
use Cidr\Model\Address;
use Cidr\Model\Shipment;
use Cidr\Model\ShipmentStatus;
use Cidr\Model\Contact;
use Cidr\CourierCapability;
use Cidr\Courier\P4D\Api\P4DQuoteResponse;
use Cidr\Courier\P4D\Api\CourierQuote;
use Cidr\Model\Task;
use Cidr\CidrResponseContextCreateShipment;
use Curl\Curl;

class CreateShipment implements CourierCapability
{ use Milk;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * @var string
     */
    private $courierName;

    /**
     * @var Curl
     */
    private $curl;

    public function getTask()
    {
        return Task::CREATE_CONSIGNMENT;
    }

    public function getCourier()
    {
        return $this->courierName;
    }

    public function validate (CidrRequest $request)
    {
        return array();
    }

    public function submitCidrRequest (CidrRequest $request)
    {

        $quoteResponse = $this->getQuotes($request);
        $quote = $quoteResponse->getCheapestQuote();

        $shipmentResponse = $this->placeShipment($request, $quoteResponse, $quote);

        if ("Success" === $shipmentResponse->Status) {
            $responseContext = new CidrResponseContextCreateShipment (
                $shipmentResponse->OrderNumber
            );
            $status = CidrResponse::STATUS_SUCCESS;
        } else {
            $responseContext = new CidrResponseContextFailed(CidrResponseContextFailed::API_REJECTED, print_r($shipmentResponse, true));
            $status = CidrResponse::STATUS_FAILED;
        }

        $cidrResponse = new CidrResponse($request, $this, $status, $responseContext);
        return $cidrResponse;
    }

    private function placeShipment(CidrRequest $request, P4DQuoteResponse $response, CourierQuote $quote)
    {
        $requestContext = $request->getRequestContext();
        $fields = array(
            "ShipAction" => "PlaceBooking",
            "Username" => $request->getCourierCredentials()->username,
            "APIKey" => $request->getCourierCredentials()->apiKey,
            "QuoteID" => $response->quoteId,
            "ItemID" => $quote->optionId,
            "CollectionDate" => null,
            "InsuranceID" => null
        );

        return json_decode($this->curl->post($this->apiUrl, $fields));
    }

    /**
     * @param \Cidr\CidrRequest $request
     * @return P4DQuoteResponse
     */
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

    /**
       Picks the cheapest quote that is on the desired collection day
       @return CourierQuote
       @throws Exception if no collection dates meet collection window
    */
    private function pickQuote(CidrRequest $request, P4DQuoteResponse $quotes)
    {
        $format = "Y-m-d";
        $expectedCollectionDay = $request->getRequestContext()->collectionTime->format($format);
        $cheapestQuote = null;

        foreach($quotes->quotes as $quote) {
            $dates = map(function($date) use($format) { return $date["date"]->format($format); }, $quote->collectionDates);
            $fSame = function ($date) use($expectedCollectionDay) { return $date === $expectedCollectionDay; };
            if(!\Cidr\apply(partial(filter, $fSame), isEmpty,  $dates)) {
                if($cheapestQuote == null || $quote->totalPrice < $cheapestQuote->totalPrice) {
                    $cheapestQuote = $quote;
                }
            }
        }

        error("no quote satisfies collection window", $cheapestQuote == null);
    }

    private function parcelsToString($parcels)
    {
        return implode("|", array_map(function($parcel) {
            return "$parcel->weight,$parcel->width,$parcel->height,$parcel->length,$parcel->description";
        }, $parcels));
    }

}
