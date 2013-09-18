<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\P4D\Api;

class P4DQuoteResponse
{
    public $shipAction;
    public $status;
    public $requestDate;
    public $username;
    public $quoteId;
    public $quoteExpires;
    public $numberParcels;
    public $deliveryCountry;
    public $quotes;

    function __construct($obj)
    {
        $this->shipAction = $obj->ShipAction;
        $this->status = $obj->Status;
        $this->requestDate = $obj->RequestDate;
        $this->username = $obj->Username;
        $this->quoteId = $obj->QuoteID;
        $this->quoteExpires = $obj->QuoteExpires;
        $this->numberParcels = $obj->NoParcels;
        $this->deliveryCountry = $obj->DeliveryCountry;
        $this->quotes = array_map(function($quote){return new CourierQuote($quote);}, $obj->ServiceOptions);
    }

    function getCheapestQuote()
    {
        return array_reduce($this->quotes, function($q1, $q2) {
            return $q1->totalPrice>$q2->totalPrice ? $q2 : $q1;
        }, $this->quotes[0]);
    }

}
