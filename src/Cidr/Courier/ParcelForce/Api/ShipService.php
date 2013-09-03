<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Courier\ParcelForce\Api;

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
		"BaseRequest" => "Cidr\\Courier\\ParcelForce\\Api\\BaseRequest",
		"BaseReply" => "Cidr\\Courier\\ParcelForce\\Api\\BaseReply",
		"CreateShipmentRequest" => "Cidr\\Courier\\ParcelForce\\Api\\CreateShipmentRequest",
		"CreateShipmentReply" => "Cidr\\Courier\\ParcelForce\\Api\\CreateShipmentReply",
		"PrintLabelRequest" => "Cidr\\Courier\\ParcelForce\\Api\\PrintLabelRequest",
		"PrintLabelReply" => "Cidr\\Courier\\ParcelForce\\Api\\PrintLabelReply",
		"CreateManifestReply" => "Cidr\\Courier\\ParcelForce\\Api\\CreateManifestReply",
		"PrintManifestRequest" => "Cidr\\Courier\\ParcelForce\\Api\\PrintManifestRequest",
		"PrintManifestReply" => "Cidr\\Courier\\ParcelForce\\Api\\PrintManifestReply",
		"ReturnShipmentRequest" => "Cidr\\Courier\\ParcelForce\\Api\\ReturnShipmentRequest",
		"ReturnShipmentReply" => "Cidr\\Courier\\ParcelForce\\Api\\ReturnShipmentReply",
		"RequestedShipment" => "Cidr\\Courier\\ParcelForce\\Api\\RequestedShipment",
		"ShipmentType" => "Cidr\\Courier\\ParcelForce\\Api\\ShipmentType",
		"Contact" => "Cidr\\Courier\\ParcelForce\\Api\\Contact",
		"NotificationType" => "Cidr\\Courier\\ParcelForce\\Api\\NotificationType",
		"Address" => "Cidr\\Courier\\ParcelForce\\Api\\Address",
		"Enhancement" => "Cidr\\Courier\\ParcelForce\\Api\\Enhancement",
		"Returns" => "Cidr\\Courier\\ParcelForce\\Api\\Returns",
		"InternationalInfo" => "Cidr\\Courier\\ParcelForce\\Api\\InternationalInfo",
		"Parcels" => "Cidr\\Courier\\ParcelForce\\Api\\Parcels",
		"Parcel" => "Cidr\\Courier\\ParcelForce\\Api\\Parcel",
		"Weight" => "Cidr\\Courier\\ParcelForce\\Api\\Weight",
		"ContentDetails" => "Cidr\\Courier\\ParcelForce\\Api\\ContentDetails",
		"ContentDetail" => "Cidr\\Courier\\ParcelForce\\Api\\ContentDetail",
		"CollectionInfo" => "Cidr\\Courier\\ParcelForce\\Api\\CollectionInfo",
		"DateTimeRange" => "Cidr\\Courier\\ParcelForce\\Api\\DateTimeRange",
		"ShipmentLabelData" => "Cidr\\Courier\\ParcelForce\\Api\\ShipmentLabelData",
		"ParcelLabelData" => "Cidr\\Courier\\ParcelForce\\Api\\ParcelLabelData",
		"LabelData" => "Cidr\\Courier\\ParcelForce\\Api\\LabelData",
		"LabelItem" => "Cidr\\Courier\\ParcelForce\\Api\\LabelItem",
		"Barcodes" => "Cidr\\Courier\\ParcelForce\\Api\\Barcodes",
		"Barcode" => "Cidr\\Courier\\ParcelForce\\Api\\Barcode",
		"PrintType" => "Cidr\\Courier\\ParcelForce\\Api\\PrintType",
		"Document" => "Cidr\\Courier\\ParcelForce\\Api\\Document",
		"CompletedManifests" => "Cidr\\Courier\\ParcelForce\\Api\\CompletedManifests",
		"CompletedManifestInfo" => "Cidr\\Courier\\ParcelForce\\Api\\CompletedManifestInfo",
		"ManifestShipments" => "Cidr\\Courier\\ParcelForce\\Api\\ManifestShipments",
		"ManifestShipment" => "Cidr\\Courier\\ParcelForce\\Api\\ManifestShipment",
		"CompletedShipmentInfo" => "Cidr\\Courier\\ParcelForce\\Api\\CompletedShipmentInfo",
		"CompletedShipments" => "Cidr\\Courier\\ParcelForce\\Api\\CompletedShipments",
		"CompletedShipment" => "Cidr\\Courier\\ParcelForce\\Api\\CompletedShipment",
		"CompletedReturnInfo" => "Cidr\\Courier\\ParcelForce\\Api\\CompletedReturnInfo",
		"Authentication" => "Cidr\\Courier\\ParcelForce\\Api\\Authentication",
		"Alerts" => "Cidr\\Courier\\ParcelForce\\Api\\Alerts",
		"Alert" => "Cidr\\Courier\\ParcelForce\\Api\\Alert",
		"AlertType" => "Cidr\\Courier\\ParcelForce\\Api\\AlertType",
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
