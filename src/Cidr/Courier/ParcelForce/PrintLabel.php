<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Courier\ParcelForce;

use Bond\Di\Factory;
use Cidr\CidrRequest;
use Cidr\CidrRequestContextPrintLabel;
use Cidr\CidrResponse;
use Cidr\CidrResponseContextCreateConsignment;
use Cidr\CidrResponseContextFailed;
use Cidr\CidrResponseContextPrintLabel;
use Cidr\Courier\ParcelForce\Api\PrintLabelRequest;
use Cidr\Status;
use Cidr\Milk;
use Cidr\Model\Address;
use Cidr\Model\Consignment;
use Cidr\Model\ConsignmentStatus;
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

class PrintLabel implements CourierCapability
{ use Milk;

    const PRINT_FORMAT = "PDF";
    const BARCODE_FORMAT = "PNG";
    const PRINT_TYPE = "ALL_PARCELS";
    
    private $shipServiceFactory;
    private $courierName;

    function getTask()
    {
        return Task::PRINT_LABEL;
    }

    public function getCourier()
    {
        assert(null != $this->courierName);

        return $this->courierName;
    }

    function submitCidrRequest(CidrRequest $request)
    {
        assert(null != $this->shipServiceFactory);
        assert($this->shipServiceFactory instanceOf Factory);
        assert(null !== $request);
        assert($request->getRequestContext() instanceof CidrRequestContextPrintLabel);


        $requestContext = $request->getRequestContext();
        $printLabelRequest = new PrintLabelRequest();
        $printLabelRequest->ShipmentNumber = $requestContext->getShipmentNumber();
        $printLabelRequest->PrintFormat = self::PRINT_FORMAT;
        $printLabelRequest->BarcodeFormat = self::BARCODE_FORMAT;
        $printLabelRequest->PrintType = self::PRINT_TYPE;


        $authentication = new Authentication();
        $authentication->UserName = $request->getCourierCredentials()["username"];
        $authentication->Password = $request->getCourierCredentials()["password"];

        $service = $this->shipServiceFactory->create();
        $printLabelReply = $service->createShipment($printLabelRequest);

        $cidrResponseContext = new CidrResponseContextPrintLabel(
            $printLabelReply
        );

        $cidrResponse = new CidrResponse(
            $request,
            $this,
            CidrResponse::STATUS_SUCCESS,
            $cidrResponseContext
        );

        return $cidrResponse;

//
//        if(
//            $createShipmentReply->Alerts == null
//            or $createShipmentReply->Alerts->Alert == null
//            or count($createShipmentReply->Alerts->Alert) == 0
//        ) {
//            $cidrResponseStatus = CidrResponse::STATUS_FAILED;
//
//            $consignmentNumber = $createShipmentReply
//                ->CompletedShipmentInfo
//                ->CompletedShipments
//                ->CompletedShipment
//                ->ShipmentNumber;
//
//            if ($createShipmentReply->CompletedShipmentInfo->Status === "ALLOCATED") {
//                $responseContext = new CidrResponseContextCreateConsignment(
//                    $consignmentNumber
//                );
//                $cidrResponseStatus = CidrResponse::STATUS_SUCCESS;
//            } else {
//                $responseContext = new CidrResponseContextFailed(
//                    CidrResponseContextFailed::API_REJECTED,
//                    "hello world, some developer needs to put a useful message here"
//                );
//            }
//        } else {
//            $alerts = $createShipmentReply->Alerts->Alert;
//            if(!is_array($alerts)) {
//                $alerts = array($alerts);
//            }
//            $msg = implode (
//                ", ",
//                array_map (
//                    function ($a) { return $a->Message;},
//                    $alerts)
//            );
//            $responseContext = new CidrResponseContextFailed(
//                null, $msg
//            );
//            $cidrResponseStatus = CidrResponse::STATUS_FAILED;
//        }
//        return new CidrResponse(
//            $request,
//            $this,
//            $cidrResponseStatus,
//            $responseContext
//        );
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
