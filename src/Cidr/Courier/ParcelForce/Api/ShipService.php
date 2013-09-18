<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce\Api;

use Cidr\Courier\ParcelForce\Api\BaseRequest;
use Cidr\Courier\ParcelForce\Api\BaseReply;
use Cidr\Courier\ParcelForce\Api\CreateShipmentRequest;
use Cidr\Courier\ParcelForce\Api\CreateShipmentReply;
use Cidr\Courier\ParcelForce\Api\PrintLabelRequest;
use Cidr\Courier\ParcelForce\Api\PrintLabelReply;
use Cidr\Courier\ParcelForce\Api\CreateManifestReply;
use Cidr\Courier\ParcelForce\Api\PrintManifestRequest;
use Cidr\Courier\ParcelForce\Api\PrintManifestReply;
use Cidr\Courier\ParcelForce\Api\ReturnShipmentRequest;
use Cidr\Courier\ParcelForce\Api\ReturnShipmentReply;
use Cidr\Courier\ParcelForce\Api\RequestedShipment;
use Cidr\Courier\ParcelForce\Api\ShipmentType;
use Cidr\Courier\ParcelForce\Api\Contact;
use Cidr\Courier\ParcelForce\Api\NotificationType;
use Cidr\Courier\ParcelForce\Api\Address;
use Cidr\Courier\ParcelForce\Api\Enhancement;
use Cidr\Courier\ParcelForce\Api\Returns;
use Cidr\Courier\ParcelForce\Api\InternationalInfo;
use Cidr\Courier\ParcelForce\Api\Parcels;
use Cidr\Courier\ParcelForce\Api\Parcel;
use Cidr\Courier\ParcelForce\Api\Weight;
use Cidr\Courier\ParcelForce\Api\ContentDetails;
use Cidr\Courier\ParcelForce\Api\ContentDetail;
use Cidr\Courier\ParcelForce\Api\CollectionInfo;
use Cidr\Courier\ParcelForce\Api\DateTimeRange;
use Cidr\Courier\ParcelForce\Api\ShipmentLabelData;
use Cidr\Courier\ParcelForce\Api\ParcelLabelData;
use Cidr\Courier\ParcelForce\Api\LabelData;
use Cidr\Courier\ParcelForce\Api\LabelItem;
use Cidr\Courier\ParcelForce\Api\Barcodes;
use Cidr\Courier\ParcelForce\Api\Barcode;
use Cidr\Courier\ParcelForce\Api\PrintType;
use Cidr\Courier\ParcelForce\Api\Document;
use Cidr\Courier\ParcelForce\Api\CompletedManifests;
use Cidr\Courier\ParcelForce\Api\CompletedManifestInfo;
use Cidr\Courier\ParcelForce\Api\ManifestShipments;
use Cidr\Courier\ParcelForce\Api\ManifestShipment;
use Cidr\Courier\ParcelForce\Api\CompletedShipmentInfo;
use Cidr\Courier\ParcelForce\Api\CompletedShipments;
use Cidr\Courier\ParcelForce\Api\CompletedShipment;
use Cidr\Courier\ParcelForce\Api\CompletedReturnInfo;
use Cidr\Courier\ParcelForce\Api\Authentication;
use Cidr\Courier\ParcelForce\Api\Alerts;
use Cidr\Courier\ParcelForce\Api\Alert;
use Cidr\Courier\ParcelForce\Api\AlertType;

use Cidr\Courier\ParcelForce\CreateShipmentReply;

/**
 * ShipService
 * @author WSDLInterpreter
 */
class ShipService extends \SoapClient {

	/**
	 * Default class map for wsdl=>php
	 * @access private
	 * @var array
	 */
	private static $classmap = array(
		"BaseRequest" => BaseRequest::class,
		"BaseReply" => BaseReply::class,
		"CreateShipmentRequest" => CreateShipmentRequest::class,
		"CreateShipmentReply" => CreateShipmentReply::class,
		"PrintLabelRequest" => PrintLabelRequest::class,
		"PrintLabelReply" => PrintLabelReply::class,
		"CreateManifestReply" => CreateManifestReply::class,
		"PrintManifestRequest" => PrintManifestRequest::class,
		"PrintManifestReply" => PrintManifestReply::class,
		"ReturnShipmentRequest" => ReturnShipmentRequest::class,
		"ReturnShipmentReply" => ReturnShipmentReply::class,
		"RequestedShipment" => RequestedShipment::class,
		"ShipmentType" => ShipmentType::class,
		"Contact" => Contact::class,
		"NotificationType" => NotificationType::class,
		"Address" => Address::class,
		"Enhancement" => Enhancement::class,
		"Returns" => Returns::class,
		"InternationalInfo" => InternationalInfo::class,
		"Parcels" => Parcels::class,
		"Parcel" => Parcel::class,
		"Weight" => Weight::class,
		"ContentDetails" => ContentDetails::class,
		"ContentDetail" => ContentDetail::class,
		"CollectionInfo" => CollectionInfo::class,
		"DateTimeRange" => DateTimeRange::class,
		"ShipmentLabelData" => ShipmentLabelData::class,
		"ParcelLabelData" => ParcelLabelData::class,
		"LabelData" => LabelData::class,
		"LabelItem" => LabelItem::class,
		"Barcodes" => Barcodes::class,
		"Barcode" => Barcode::class,
		"PrintType" => PrintType::class,
		"Document" => Document::class,
		"CompletedManifests" => CompletedManifests::class,
		"CompletedManifestInfo" => CompletedManifestInfo::class,
		"ManifestShipments" => ManifestShipments::class,
		"ManifestShipment" => ManifestShipment::class,
		"CompletedShipmentInfo" => CompletedShipmentInfo::class,
		"CompletedShipments" => CompletedShipments::class,
		"CompletedShipment" => CompletedShipment::class,
		"CompletedReturnInfo" => CompletedReturnInfo::class,
		"Authentication" => Authentication::class,
		"Alerts" => Alerts::class,
		"Alert" => Alert::class,
		"AlertType" => AlertType::class,
	);

	/**
	 * Constructor using wsdl location and options array
	 * @param string $wsdl WSDL location for this service
	 * @param array $options Options for the SoapClient
	 */
	public function __construct($wsdl="https://expresslink-test.parcelforce.net/ws/?wsdl", $options=array()) {
		foreach(self::$classmap as $wsdlClassName => $phpClassName) {
		    if(!isset($options['classmap'][$wsdlClassName])) {
		        $options['classmap'][$wsdlClassName] = $phpClassName;
		    }
		}
		parent::__construct($wsdl, $options);
	}

	/**
	 * Checks if an argument list matches against a valid argument type list
	 * @param array $arguments The argument list to check
	 * @param array $validParameters A list of valid argument types
	 * @return boolean true if arguments match against validParameters
	 * @throws Exception invalid function signature message
	 */
	public function _checkArguments($arguments, $validParameters) {
		$variables = "";
		foreach ($arguments as $arg) {
		    $type = gettype($arg);
		    if ($type == "object") {
		        $type = get_class($arg);
                $type = substr($type, strrpos($type, "\\")+1);
            }
            $variables .= "(".$type.")";
        }
        if (!in_array($variables, $validParameters)) {
            throw new \Exception("Invalid parameter types: ".str_replace(")(", ", ", $variables));
        }
        return true;
    }

    /**
     * Service Call: createShipment
     * Parameter options:
     * (CreateShipmentRequest) CreateShipmentRequest
     * @param mixed,... See function description for parameter options
     * @return CreateShipmentReply
     * @throws Exception invalid function signature message
     */
    public function createShipment($mixed = null) {
        $validParameters = array(
            "(CreateShipmentRequest)",
        );
        $args = func_get_args();
        $this->_checkArguments($args, $validParameters);

        return $this->__soapCall("createShipment", $args);
    }

    /**
     * Service Call: printLabel
     * Parameter options:
     * (PrintLabelRequest) PrintLabelRequest
     * @param mixed,... See function description for parameter options
     * @return PrintLabelReply
     * @throws Exception invalid function signature message
     */
    public function printLabel($mixed = null) {
        $validParameters = array(
            "(PrintLabelRequest)",
        );
        $args = func_get_args();
        $this->_checkArguments($args, $validParameters);
        return $this->__soapCall("printLabel", $args);
    }

    /**
     * Service Call: createManifest
     * Parameter options:
     * (BaseRequest) CreateManifestRequest
     * @param mixed,... See function description for parameter options
     * @return CreateManifestReply
     * @throws Exception invalid function signature message
     */
    public function createManifest($mixed = null) {
        $validParameters = array(
            "(BaseRequest)",
        );
        $args = func_get_args();
        $this->_checkArguments($args, $validParameters);
        return $this->__soapCall("createManifest", $args);
    }

    /**
     * Service Call: printManifest
     * Parameter options:
     * (PrintManifestRequest) PrintManifestRequest
     * @param mixed,... See function description for parameter options
     * @return PrintManifestReply
     * @throws Exception invalid function signature message
     */
    public function printManifest($mixed = null) {
        $validParameters = array(
            "(PrintManifestRequest)",
        );
        $args = func_get_args();
        $this->_checkArguments($args, $validParameters);
        return $this->__soapCall("printManifest", $args);
    }

    /**
     * Service Call: returnShipment
     * Parameter options:
     * (ReturnShipmentRequest) ReturnShipmentRequest
     * @param mixed,... See function description for parameter options
     * @return ReturnShipmentReply
     * @throws Exception invalid function signature message
     */
    public function returnShipment($mixed = null) {
        $validParameters = array(
            "(ReturnShipmentRequest)",
        );
        $args = func_get_args();
        $this->_checkArguments($args, $validParameters);
        return $this->__soapCall("returnShipment", $args);
    }

}

?>
