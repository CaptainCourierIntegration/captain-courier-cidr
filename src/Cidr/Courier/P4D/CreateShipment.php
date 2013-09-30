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
use Cidr\IdGenerator;

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

    /**
     * @var GetQuote
     */
    private $getQuoteCapability;

    /** @var IdGenerator */
    private $idGenerator;

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

    private function convertCreateShipmentRequestToGetQuotesrequest(CidrRequest $request)
    {
        $getQutotesRequest = new CidrRequest(
            new CidrRequestContextGetQuote(
                $request->getRequestContext()->getCollectionAddress(),
                $request->getRequestContext()->getCollectionContact(),
                $request->getRequestContext()->getDeliveryAddress(),
                $request->getRequestContext()->getDeliveryContact(),
                $request->getRequestContext()->getParcels()
            ),
            Task::GET_QUOTE,
            $request->getCourierCredentials()
        );
        throw new \Exception("not implemented");
    }


    /**
     * Picks the cheapest quote out of all the quotes
     * assumes all quotes are within the collection/delivery window
     * @param array $quotes where a quote is a Cidr\Courier\P4D\Api\CourierQuote
     * @return CourierQuote
     */
    private function pickQuote(array $quotes)
    {
        \Cidr\assertArgument(0 !== count($quotes), "requires at least one quote");
        $sortedQuotes = [];
        foreach($quotes->quotes as $quote) {
            $sortedQuotes[$quote->totalPrice] = $quote;
        }
        ksort($sortedQuotes);
        return array_shift(array_values($sortedQuotes));
    }


}
