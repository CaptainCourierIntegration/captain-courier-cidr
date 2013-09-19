<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Courier\ParcelForce\Api;

if (!class_exists("BaseRequest")) {
/**
 * BaseRequest
 */
class BaseRequest {
    /**
     * @access public
     * @var Authentication
     */
    public $Authentication;
}}

if (!class_exists("BaseReply")) {
/**
 * BaseReply
 */
class BaseReply {
    /**
     * @access public
     * @var Alerts
     */
    public $Alerts;
}}

if (!class_exists("CreateShipmentRequest")) {
/**
 * CreateShipmentRequest
 */
class CreateShipmentRequest extends BaseRequest {
    /**
     * @access public
     * @var RequestedShipment
     */
    public $RequestedShipment;
}}

if (!class_exists("CreateShipmentReply")) {
/**
 * CreateShipmentReply
 */
class CreateShipmentReply extends BaseReply {
    /**
     * @access public
     * @var CompletedShipmentInfo
     */
    public $CompletedShipmentInfo;
}}

if (!class_exists("PrintLabelRequest")) {
/**
 * PrintLabelRequest
 */
class PrintLabelRequest extends BaseRequest {
    /**
     * @access public
     * @var string
     */
    public $ShipmentNumber;
    /**
     * @access public
     * @var string
     */
    public $PrintFormat;
    /**
     * @access public
     * @var string
     */
    public $BarcodeFormat;
    /**
     * @access public
     * @var nsPrintType
     */
    public $PrintType;
}}

if (!class_exists("PrintLabelReply")) {
/**
 * PrintLabelReply
 */
class PrintLabelReply extends BaseReply {
    /**
     * @access public
     * @var Document
     */
    public $Label;
    /**
     * @access public
     * @var ShipmentLabelData
     */
    public $LabelData;
    /**
     * @access public
     * @var string
     */
    public $PartnerCode;
}}

if (!class_exists("CreateManifestReply")) {
/**
 * CreateManifestReply
 */
class CreateManifestReply extends BaseReply {
    /**
     * @access public
     * @var CompletedManifests
     */
    public $CompletedManifests;
}}

if (!class_exists("PrintManifestRequest")) {
/**
 * PrintManifestRequest
 */
class PrintManifestRequest extends BaseRequest {
    /**
     * @access public
     * @var string
     */
    public $ManifestNumber;
    /**
     * @access public
     * @var string
     */
    public $PrintFormat;
}}

if (!class_exists("PrintManifestReply")) {
/**
 * PrintManifestReply
 */
class PrintManifestReply extends BaseReply {
    /**
     * @access public
     * @var Document
     */
    public $Manifest;
}}

if (!class_exists("ReturnShipmentRequest")) {
/**
 * ReturnShipmentRequest
 */
class ReturnShipmentRequest extends BaseRequest {
    /**
     * @access public
     * @var string
     */
    public $ShipmentNumber;
    /**
     * @access public
     * @var DateTimeRange
     */
    public $CollectionTime;
}}

if (!class_exists("ReturnShipmentReply")) {
/**
 * ReturnShipmentReply
 */
class ReturnShipmentReply extends BaseReply {
    /**
     * @access public
     * @var CompletedReturnInfo
     */
    public $CompletedShipmentInfo;
}}

if (!class_exists("RequestedShipment")) {
/**
 * RequestedShipment
 */
class RequestedShipment {
    /**
     * @access public
     * @var nsShipmentType
     */
    public $ShipmentType;
    /**
     * @access public
     * @var string
     */
    public $ContractNumber;
    /**
     * @access public
     * @var integer
     */
    public $RequestId;
    /**
     * @access public
     * @var string
     */
    public $ServiceCode;
    /**
     * @access public
     * @var date
     */
    public $ShippingDate;
    /**
     * @access public
     * @var string
     */
    public $JobReference;
    /**
     * @access public
     * @var Contact
     */
    public $RecipientContact;
    /**
     * @access public
     * @var Address
     */
    public $RecipientAddress;
    /**
     * @access public
     * @var integer
     */
    public $TotalNumberOfParcels;
    /**
     * @access public
     * @var nsWeight
     */
    public $TotalShipmentWeight;
    /**
     * @access public
     * @var Enhancement
     */
    public $Enhancement;
    /**
     * @access public
     * @var Returns
     */
    public $Returns;
    /**
     * @access public
     * @var CollectionInfo
     */
    public $CollectionInfo;
    /**
     * @access public
     * @var InternationalInfo
     */
    public $InternationalInfo;
    /**
     * @access public
     * @var string
     */
    public $ReferenceNumber1;
    /**
     * @access public
     * @var string
     */
    public $ReferenceNumber2;
    /**
     * @access public
     * @var string
     */
    public $ReferenceNumber3;
    /**
     * @access public
     * @var string
     */
    public $ReferenceNumber4;
    /**
     * @access public
     * @var string
     */
    public $ReferenceNumber5;
    /**
     * @access public
     * @var string
     */
    public $SpecialInstructions1;
    /**
     * @access public
     * @var string
     */
    public $SpecialInstructions2;
    /**
     * @access public
     * @var string
     */
    public $SpecialInstructions3;
    /**
     * @access public
     * @var string
     */
    public $SpecialInstructions4;
}}

if (!class_exists("ShipmentType")) {
/**
 * ShipmentType
 */
class ShipmentType {
}}

if (!class_exists("Contact")) {
/**
 * Contact
 */
class Contact {
    /**
     * @access public
     * @var string
     */
    public $BusinessName;
    /**
     * @access public
     * @var string
     */
    public $ContactName;
    /**
     * @access public
     * @var string
     */
    public $EmailAddress;
    /**
     * @access public
     * @var string
     */
    public $Telephone;
    /**
     * @access public
     * @var string
     */
    public $Fax;
    /**
     * @access public
     * @var nsNotificationType
     */
    public $NotificationType;
}}

if (!class_exists("NotificationType")) {
/**
 * NotificationType
 */
class NotificationType {
}}

if (!class_exists("Address")) {
/**
 * Address
 */
class Address {
    /**
     * @access public
     * @var string
     */
    public $AddressLine1;
    /**
     * @access public
     * @var string
     */
    public $AddressLine2;
    /**
     * @access public
     * @var string
     */
    public $AddressLine3;
    /**
     * @access public
     * @var string
     */
    public $Town;
    /**
     * @access public
     * @var string
     */
    public $Postcode;
    /**
     * @access public
     * @var string
     */
    public $Country;
}}

if (!class_exists("Enhancement")) {
/**
 * Enhancement
 */
class Enhancement {
    /**
     * @access public
     * @var string
     */
    public $EnhancedCompensation;
    /**
     * @access public
     * @var boolean
     */
    public $SaturdayDeliveryRequired;
}}

if (!class_exists("Returns")) {
/**
 * Returns
 */
class Returns {
    /**
     * @access public
     * @var string
     */
    public $ReturnsEmail;
    /**
     * @access public
     * @var string
     */
    public $EmailMessage;
    /**
     * @access public
     * @var boolean
     */
    public $EmailLabel;
}}

if (!class_exists("InternationalInfo")) {
/**
 * InternationalInfo
 */
class InternationalInfo {
    /**
     * @access public
     * @var Parcels
     */
    public $Parcels;
    /**
     * @access public
     * @var string
     */
    public $ShipperExporterVatNo;
    /**
     * @access public
     * @var string
     */
    public $RecipientImporterVatNo;
    /**
     * @access public
     * @var boolean
     */
    public $DocumentsOnly;
    /**
     * @access public
     * @var string
     */
    public $DocumentsDescription;
}}

if (!class_exists("Parcels")) {
/**
 * Parcels
 */
class Parcels {
    /**
     * @access public
     * @var Parcel[]
     */
    public $Parcel;
}}

if (!class_exists("Parcel")) {
/**
 * Parcel
 */
class Parcel {
    /**
     * @access public
     * @var nsWeight
     */
    public $Weight;
    /**
     * @access public
     * @var positiveInteger
     */
    public $Length;
    /**
     * @access public
     * @var positiveInteger
     */
    public $Height;
    /**
     * @access public
     * @var positiveInteger
     */
    public $Width;
    /**
     * @access public
     * @var string
     */
    public $PurposeOfShipment;
    /**
     * @access public
     * @var string
     */
    public $InvoiceNumber;
    /**
     * @access public
     * @var string
     */
    public $ExportLicenseNumber;
    /**
     * @access public
     * @var string
     */
    public $CertificateNumber;
    /**
     * @access public
     * @var ContentDetails
     */
    public $ContentDetails;
}}

if (!class_exists("Weight")) {
/**
 * Weight
 */
class Weight {
}}

if (!class_exists("ContentDetails")) {
/**
 * ContentDetails
 */
class ContentDetails {
    /**
     * @access public
     * @var ContentDetail[]
     */
    public $ContentDetail;
}}

if (!class_exists("ContentDetail")) {
/**
 * ContentDetail
 */
class ContentDetail {
    /**
     * @access public
     * @var string
     */
    public $CountryOfManufacture;
    /**
     * @access public
     * @var string
     */
    public $Description;
    /**
     * @access public
     * @var nsWeight
     */
    public $UnitWeight;
    /**
     * @access public
     * @var positiveInteger
     */
    public $UnitQuantity;
    /**
     * @access public
     * @var double
     */
    public $UnitValue;
    /**
     * @access public
     * @var string
     */
    public $Currency;
    /**
     * @access public
     * @var string
     */
    public $TariffCode;
    /**
     * @access public
     * @var string
     */
    public $TariffDescription;
}}

if (!class_exists("CollectionInfo")) {
/**
 * CollectionInfo
 */
class CollectionInfo {
    /**
     * @access public
     * @var Contact
     */
    public $CollectionContact;
    /**
     * @access public
     * @var Address
     */
    public $CollectionAddress;
    /**
     * @access public
     * @var DateTimeRange
     */
    public $CollectionTime;
}}

if (!class_exists("DateTimeRange")) {
/**
 * DateTimeRange
 */
class DateTimeRange {
    /**
     * @access public
     * @var dateTime
     */
    public $From;
    /**
     * @access public
     * @var dateTime
     */
    public $To;
}}

if (!class_exists("ShipmentLabelData")) {
/**
 * ShipmentLabelData
 */
class ShipmentLabelData {
    /**
     * @access public
     * @var ParcelLabelData[]
     */
    public $ParcelLabelData;
}}

if (!class_exists("ParcelLabelData")) {
/**
 * ParcelLabelData
 */
class ParcelLabelData {
    /**
     * @access public
     * @var string
     */
    public $ParcelNumber;
    /**
     * @access public
     * @var string
     */
    public $ShipmentNumber;
    /**
     * @access public
     * @var LabelData
     */
    public $LabelData;
    /**
     * @access public
     * @var Barcodes
     */
    public $Barcodes;
}}

if (!class_exists("LabelData")) {
/**
 * LabelData
 */
class LabelData {
    /**
     * @access public
     * @var LabelItem[]
     */
    public $Item;
}}

if (!class_exists("LabelItem")) {
/**
 * LabelItem
 */
class LabelItem {
    /**
     * @access public
     * @var string
     */
    public $Name;
    /**
     * @access public
     * @var string
     */
    public $Data;
}}

if (!class_exists("Barcodes")) {
/**
 * Barcodes
 */
class Barcodes {
    /**
     * @access public
     * @var Barcode[]
     */
    public $Barcode;
}}

if (!class_exists("Barcode")) {
/**
 * Barcode
 */
class Barcode {
    /**
     * @access public
     * @var string
     */
    public $Name;
    /**
     * @access public
     * @var base64Binary
     */
    public $Data;
}}

if (!class_exists("PrintType")) {
/**
 * PrintType
 */
class PrintType {
}}

if (!class_exists("Document")) {
/**
 * Document
 */
class Document {
    /**
     * @access public
     * @var base64Binary
     */
    public $Data;
}}

if (!class_exists("CompletedManifests")) {
/**
 * CompletedManifests
 */
class CompletedManifests {
    /**
     * @access public
     * @var CompletedManifestInfo[]
     */
    public $CompletedManifestInfo;
}}

if (!class_exists("CompletedManifestInfo")) {
/**
 * CompletedManifestInfo
 */
class CompletedManifestInfo {
    /**
     * @access public
     * @var string
     */
    public $ManifestNumber;
    /**
     * @access public
     * @var string
     */
    public $ManifestType;
    /**
     * @access public
     * @var integer
     */
    public $TotalShipmentCount;
    /**
     * @access public
     * @var ManifestShipments
     */
    public $ManifestShipments;
}}

if (!class_exists("ManifestShipments")) {
/**
 * ManifestShipments
 */
class ManifestShipments {
    /**
     * @access public
     * @var ManifestShipment[]
     */
    public $ManifestShipment;
}}

if (!class_exists("ManifestShipment")) {
/**
 * ManifestShipment
 */
class ManifestShipment {
    /**
     * @access public
     * @var string
     */
    public $ShipmentNumber;
    /**
     * @access public
     * @var string
     */
    public $ServiceCode;
}}

if (!class_exists("CompletedShipmentInfo")) {
/**
 * CompletedShipmentInfo
 */
class CompletedShipmentInfo {
    /**
     * @access public
     * @var string
     */
    public $LeadShipmentNumber;
    /**
     * @access public
     * @var date
     */
    public $DeliveryDate;
    /**
     * @access public
     * @var string
     */
    public $Status;
    /**
     * @access public
     * @var CompletedShipments
     */
    public $CompletedShipments;
    /**
     * @access public
     * @var RequestedShipment
     */
    public $RequestedShipment;
}}

if (!class_exists("CompletedShipments")) {
/**
 * CompletedShipments
 */
class CompletedShipments {
    /**
     * @access public
     * @var CompletedShipment[]
     */
    public $CompletedShipment;
}}

if (!class_exists("CompletedShipment")) {
/**
 * CompletedShipment
 */
class CompletedShipment {
    /**
     * @access public
     * @var string
     */
    public $ShipmentNumber;
    /**
     * @access public
     * @var string
     */
    public $PartnerNumber;
}}

if (!class_exists("CompletedReturnInfo")) {
/**
 * CompletedReturnInfo
 */
class CompletedReturnInfo {
    /**
     * @access public
     * @var string
     */
    public $Status;
    /**
     * @access public
     * @var string
     */
    public $ShipmentNumber;
    /**
     * @access public
     * @var DateTimeRange
     */
    public $CollectionTime;
}}

if (!class_exists("Authentication")) {
/**
 * Authentication
 */
class Authentication {
    /**
     * @access public
     * @var string
     */
    public $UserName;
    /**
     * @access public
     * @var string
     */
    public $Password;
}}

if (!class_exists("Alerts")) {
/**
 * Alerts
 */
class Alerts {
    /**
     * @access public
     * @var Alert[]
     */
    public $Alert;
}}

if (!class_exists("Alert")) {
/**
 * Alert
 */
class Alert {
    /**
     * @access public
     * @var integer
     */
    public $Code;
    /**
     * @access public
     * @var string
     */
    public $Message;
    /**
     * @access public
     * @var nsAlertType
     */
    public $Type;
}}

if (!class_exists("AlertType")) {
/**
 * AlertType
 */
class AlertType {
}}

if (!class_exists("ShipService")) {
/**
 * ShipService
 * @author WSDLInterpreter
 */
class ShipService extends SoapClient {
    /**
     * Default class map for wsdl=>php
     * @access private
     * @var array
     */
    private static $classmap = array(
        "BaseRequest" => "BaseRequest",
        "BaseReply" => "BaseReply",
        "CreateShipmentRequest" => "CreateShipmentRequest",
        "CreateShipmentReply" => "CreateShipmentReply",
        "PrintLabelRequest" => "PrintLabelRequest",
        "PrintLabelReply" => "PrintLabelReply",
        "CreateManifestReply" => "CreateManifestReply",
        "PrintManifestRequest" => "PrintManifestRequest",
        "PrintManifestReply" => "PrintManifestReply",
        "ReturnShipmentRequest" => "ReturnShipmentRequest",
        "ReturnShipmentReply" => "ReturnShipmentReply",
        "RequestedShipment" => "RequestedShipment",
        "ShipmentType" => "ShipmentType",
        "Contact" => "Contact",
        "NotificationType" => "NotificationType",
        "Address" => "Address",
        "Enhancement" => "Enhancement",
        "Returns" => "Returns",
        "InternationalInfo" => "InternationalInfo",
        "Parcels" => "Parcels",
        "Parcel" => "Parcel",
        "Weight" => "Weight",
        "ContentDetails" => "ContentDetails",
        "ContentDetail" => "ContentDetail",
        "CollectionInfo" => "CollectionInfo",
        "DateTimeRange" => "DateTimeRange",
        "ShipmentLabelData" => "ShipmentLabelData",
        "ParcelLabelData" => "ParcelLabelData",
        "LabelData" => "LabelData",
        "LabelItem" => "LabelItem",
        "Barcodes" => "Barcodes",
        "Barcode" => "Barcode",
        "PrintType" => "PrintType",
        "Document" => "Document",
        "CompletedManifests" => "CompletedManifests",
        "CompletedManifestInfo" => "CompletedManifestInfo",
        "ManifestShipments" => "ManifestShipments",
        "ManifestShipment" => "ManifestShipment",
        "CompletedShipmentInfo" => "CompletedShipmentInfo",
        "CompletedShipments" => "CompletedShipments",
        "CompletedShipment" => "CompletedShipment",
        "CompletedReturnInfo" => "CompletedReturnInfo",
        "Authentication" => "Authentication",
        "Alerts" => "Alerts",
        "Alert" => "Alert",
        "AlertType" => "AlertType",
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
            }
            $variables .= "(".$type.")";
        }
        if (!in_array($variables, $validParameters)) {
            throw new Exception("Invalid parameter types: ".str_replace(")(", ", ", $variables));
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

}}

?>
