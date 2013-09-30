<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce;

use Cidr\CidrRequest;
use Cidr\CidrResponse;
use Cidr\CidrResponseContextCreateShipment;
use Cidr\CidrResponseContextFailed;
use Cidr\Status;
use Cidr\Milk;
use Cidr\Model\Address;
use Cidr\Model\Shipment;
use Cidr\Model\ShipmentStatus;
use Cidr\Model\Contact;
use Cidr\CourierCapability;

use Cidr\Courier\ParcelForce\Api\Address as PFAddress;
use Cidr\Courier\ParcelForce\Api\Authentication;
use Cidr\Courier\ParcelForce\Api\CollectionInfo;
use Cidr\Courier\ParcelForce\Api\Contact as PFContact;
use Cidr\Courier\ParcelForce\Api\CreateShipmentRequest;
use Cidr\Courier\ParcelForce\Api\CreateShipmentReply;
use Cidr\Courier\ParcelForce\Api\DateTimeRange;
use Cidr\Courier\ParcelForce\Api\RequestedShipment;
use Cidr\Courier\ParcelForce\Api\ShipService;

use Cidr\Model\Task;

class CreateShipment implements CourierCapability
{ use Milk;

    const NOTIFICATION_TYPE = "EMAIL"; // OR SMS

    private $shipServiceFactory;
    private $courierName;
    private $idGenerator;

    function getTask()
    {
        return Task::CREATE_CONSIGNMENT;
    }

    public function getCourier()
    {
        return $this->courierName;
    }

    function submitCidrRequest(CidrRequest $request)
    {
        $requestContext = $request->getRequestContext(); // Shipment

        $collectionInfo = new CollectionInfo();
        $collectionInfo->CollectionContact = $this->contactToPFContact(
            $requestContext->getCollectionContact()
        );
        $collectionInfo->CollectionAddress = $this->addressToPFAddress (
            $requestContext->getCollectionAddress()
        );
        $collectionInfo->CollectionTime = $this->getDateTimeRange (
            $requestContext->getCollectionTime()->format("Y-m-d\\T00:00:00")
        ); // "2013-07-24T00:00:00"

        $requestedShipment = new RequestedShipment();
        $requestedShipment->ShipmentType = "DELIVERY";
        $requestedShipment->ContractNumber = "P421324"; // TODO UPDATE
        $requestedShipment->requestedId = "0"; // TODO UPDATE
        $requestedShipment->ServiceCode = "S10"; // TODO UPDATE
        $requestedShipment->ShippingDate = $requestContext->getDeliveryTime()->format("Y-m-d"); //"2013-07-24";
        $requestedShipment->JobReference = ""; // TODO UPDATE
        $requestedShipment->RecipientContact = $this->contactToPFContact (
            $requestContext->getDeliveryContact()
        );
        $requestedShipment->RecipientAddress = $this->addressToPFAddress (
            $requestContext->getDeliveryAddress()
        );
        $requestedShipment->TotalNumberOfParcels =
            (string) count( $requestContext->getParcels() );
        $requestedShipment->CollectionInfo = $collectionInfo;

        $authentication = new Authentication();
        $authentication->UserName = $request->getCourierCredentials()->username;
        $authentication->Password = $request->getCourierCredentials()->password;

        $createShipmentRequest = new CreateShipmentRequest();
        $createShipmentRequest->Authentication = $authentication;
        $createShipmentRequest->RequestedShipment = $requestedShipment;

        $service = $this->shipServiceFactory->create();
        $createShipmentReply = $service->createShipment($createShipmentRequest);

        if(
            $createShipmentReply->Alerts == null
            or $createShipmentReply->Alerts->Alert == null
            or count($createShipmentReply->Alerts->Alert) == 0
        ) {
            $cidrResponseStatus = CidrResponse::STATUS_FAILED;

            $shipmentNumber = $createShipmentReply
                ->CompletedShipmentInfo
                ->CompletedShipments
                ->CompletedShipment
                ->ShipmentNumber;

            if ($createShipmentReply->CompletedShipmentInfo->Status === "ALLOCATED") {
                $responseContext = new CidrResponseContextCreateShipment(
                    $this->idGenerator->nextId(),
                    $shipmentNumber
                );
                $cidrResponseStatus = CidrResponse::STATUS_SUCCESS;
            } else {
                $responseContext = new CidrResponseContextFailed(
                    CidrResponseContextFailed::API_REJECTED,
                    "hello world, some developer needs to put a useful message here"
                );
            }
        } else {
            $alerts = $createShipmentReply->Alerts->Alert;
            if(!is_array($alerts)) {
                $alerts = array($alerts);
            }
            $msg = implode (
                ", ",
                array_map (
                    function ($a) { return $a->Message;},
                    $alerts)
            );
            $responseContext = new CidrResponseContextFailed(
                null, $msg
            );
            $cidrResponseStatus = CidrResponse::STATUS_FAILED;
        }
        return new CidrResponse(
            $request,
            $this,
            $cidrResponseStatus,
            $responseContext
        );
    }

    public function validate(CidrRequest $request)
    {
        return true;
    }

    private function getDateTimeRange($from, $to=null) {
        $date = new DateTimeRange();
        $date->From = $from;
        $date->To = $to===null? $from : $to;
        return $date;
    }

     private function addressToPFAddress(Address $address)
     {
         $pfAddress = new PFAddress();
         list(
             $pfAddress->AddressLine1,
             $pfAddress->AddressLine2,
             $pfAddress->AddressLine3
         ) = array_pad ($address->getLines(), 3, "");

         $pfAddress->Town = $address->town;
         $pfAddress->Postcode = $address->postcode;
         $pfAddress->Country = $address->countryCode;
         return $pfAddress;
     }

     private function contactToPFContact(Contact $contact)
     {
         $pfContact = new PFContact();
         $pfContact->BusinessName =
             ($contact->businessName==="" or $contact->businessName==null)
             ? "home"
             : $contact->businessName;
         $pfContact->ContactName = $contact->name;
         $pfContact->EmailAddress = $contact->email;
         $pfContact->Telephone = $contact->telephone;
         $pfContact->NotificationType = self::NOTIFICATION_TYPE;

          return $pfContact;
     }
}
