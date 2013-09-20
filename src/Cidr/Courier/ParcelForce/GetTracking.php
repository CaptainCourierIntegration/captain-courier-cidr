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
use Cidr\CidrResponse;
use Cidr\CidrResponseContextGetTracking;
use Cidr\Model\TrackingLogEntry;

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
        \Cidr\AssertArgument($request, "request is null");

    	$shipmentNumber = $request->getRequestContext()->getShipmentNumber();
        \Cidr\AssertArgument($shipmentNumber, "shipmentNumber is null");

        $client = new \GearmanClient();
        $client->addServer();
        $output = json_decode($client->doNormal(
            "node.scrappy.ParcelForce.getTracking",
            '{"shipmentNumber": "' . $shipmentNumber . '"}'
        ));

        $log = [];
        foreach($output as $entry) {
            $log[] = new TrackingLogEntry(
                $entry->date,
                $entry->time,
                $entry->location,
                $entry->trackingEvent
            );
        }

        return new CidrResponse(
            $request,
            $this,
            CidrResponse::STATUS_SUCCESS,
            new CidrResponseContextGetTracking($log)
        );

    }

    function validate(CidrRequest $request)
    {
    	return true;
    }

}