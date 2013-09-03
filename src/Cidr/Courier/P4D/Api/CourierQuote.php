<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Courier\P4D\Api;

class CourierQuote
{
    public $optionId;
    public $carrier;
    public $serviceName;
    public $serviceId;
    public $subjectToVat;
    public $subTotal;
    public $vatTotal;
    public $totalPrice;
    public $serviceIcon;
    public $eta;
    public $additionalEta;
    public $printer;
    public $printerText;
    public $collectionDates;

    function __construct($obj) 
    {
        $this->optionId = $obj->OptionID;
        $this->carrier = $obj->Carrier;
        $this->serviceName = $obj->ServiceName;
        $this->serviceId = $obj->ServiceID;
        $this->subjectToVat = $obj->SubjectToVat;
        $this->subTotal = $obj->SubTotal;
        $this->vatTotal = $obj->VatTotal;
        $this->totalPrice = $obj->TotalPrice;
        $this->serviceIcon = $obj->ServiceIcon;
        $this->eta = $obj->ETA;
        $this->additionalEta = $obj->AdditionalETA;
        $this->printer = $obj->Printer;
        $this->printerText = $obj->PrinterText;
        $this->collectionDates = array_map(function($cd){
            return array(
                "date" => new \DateTime($cd->date, new \DateTimeZone("Europe/London")),
                "cutoff" => $cd->cutoff
            );
        }, $obj->CollectionDates);
    }
}
