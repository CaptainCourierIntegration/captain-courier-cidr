<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 02/09/2013
 * Time: 10:28
 */

namespace Cidr\Courier\ParcelForce\Tests;

use Cidr\Courier\ParcelForce\Api\CompletedShipment;
use Cidr\Courier\ParcelForce\Api\CompletedShipmentInfo;
use Cidr\Courier\ParcelForce\Api\CompletedShipments;
use Cidr\Courier\ParcelForce\Api\CreateShipmentReply;
use Cidr\Courier\ParcelForce\Api\CreateShipmentRequest;
use Cidr\Courier\ParcelForce\Api\Exception;
use Cidr\Courier\ParcelForce\Api\ShipService;
use Cidr\Milk;

class MockedShipService extends ShipService
{ use Milk;

    private $shipmentNumber = "MK0730971";

    public function createShipment(CreateShipmentRequest $request)
    {
        print "MOCKED CALLLED\n";
        die("no");

        // CompletedShipments
        $completedShipment = new CompletedShipment();
        $completedShipment->ShipmentNumber = $this->shipmentNumber;

        $completedShipments = new CompletedShipments();
        $completedShipments->CompletedShipment = $completedShipment;

        // CompletedShipmentInfo
        $completedShipmentInfo = new CompletedShipmentInfo();
        $completedShipmentInfo->Status = "ALLOCATED";
        $completedShipmentInfo->CompletedShipments = $completedShipments;
        $completedShipmentInfo->RequestedShipment = $request->RequestedShipment;

        $createShipmentReply = new CreateShipmentReply();
        $createShipmentReply->CompletedShipmentInfo = $completedShipmentInfo;

        return $createShipmentReply;
    }

} 