<?php
namespace Cidr\Tests\Provider;

use Cidr\Milk;
use Cidr\Model\Shipment;
use Cidr\Tests\Provider\Tests\ContactProviderTest;

class ShipmentProvider implements DataProvider
{ use Milk;

    private $addressProvider;
    private $contactProvider;
    private $parcelProvider;

    private $nextId = 0;

    protected static $propertiesNotManagedByMilk = ["nextId"];

    /** returns Shipment array */
    public function getData($size = 100)
    {
        assert(null !== $this->addressProvider);
        assert(null !== $this->contactProvider);
        assert(null !== $this->parcelProvider);

        assert($this->addressProvider instanceof AddressProvider);
        assert($this->contactProvider instanceof ContactProvider);
        assert($this->parcelProvider instanceof ParcelProvider);

        $addresses = $this->addressProvider->getData();
        $contacts = $this->contactProvider->getData();
        $parcels = $this->parcelProvider->getData();
        $shipments = $this->generateDataSet($size);

        $modelShipments = [];
        foreach ($shipments as $shipment) {
            $shipmentParcels = array_map(
                function()use($parcels) { return clone $this->pick($parcels); },
                range(1, intval($shipment["numberParcels"]))
            );

            $collectionAddressIndex = $this->picki($addresses);

            unset($shipment["numberParcels"]);
            $shipment["collectionAddress"] = $addresses[$collectionAddressIndex];
            $shipment["collectionContact"] = $this->pick($contacts);
            $shipment["collectionTime"] = $this->getCollectionDateTime();
            $shipment["deliveryAddress"] = $this->pickAvoid(
                $addresses,
                $collectionAddressIndex
            );
            $shipment["deliveryContact"] = $this->pick($contacts);
            $shipment["deliveryTime"] = $this->getDeliveryDateTime();
            $shipment["parcels"] = $shipmentParcels;

            $modelShipments[] = new Shipment($shipment);
        }

        return $modelShipments;

    }

    private function generateDataSet($n)
    {
        return array_map(
            function($_){return $this->generateRawShipmentArray();},
            range(1, $n)
        );
    }

    private function generateRawShipmentArray()
    {
        return [
            "id" => $this->nextId++,
            "contractNumber" => $this->generateContractNumber(),
            "serviceCode" => $this->generateServiceCode(),
            "numberParcels" => $this->generateNumberParcels()
        ];
    }

    private function generateContractNumber()
    {
        return strval(rand(100000000, 1000000000000));
    }

    private function generateServiceCode()
    {
        return strval(rand(0, 9999));
    }

    private function generateNumberParcels()
    {
        return rand(0, 50);
    }

    private function pickAvoid(array $objs, $avoidIndex)
    {
        assert( count($objs) > 1);
        assert(isset($objs[$avoidIndex]));

        while (true) {
            $i = $this->picki($objs);
            if($i !== $avoidIndex) {
                return $objs[$i];
            }
        }
    }

    private function pick(array $objs)
    {
        return $objs[$this->picki($objs)];
    }

    private function picki(array $objs)
    {
        assert(0 !== count($objs));

        $objs = array_values($objs);
        return rand(0, count($objs) - 1);
    }

    private function getCollectionDateTime()
    {
        $today = (new \DateTime("today", new \DateTimeZone("Europe/London")))->format(
            "D"
        );
        $day = ($today === "Tue") ? "Wed" : "Tue";
        $result = new \DateTime("next $day", new \DateTimeZone("Europe/London"));
        $result->setTime(12, 00, 00);
        return $result;
    }

    private function dateInc(&$date)
    {
        return $date->add(
            \DateInterval::createFromDateString("1 day")
        );
    }

    private function getDeliveryDateTime()
    {
        $collectionDate = $this->getCollectionDateTime();
        $this->dateInc($collectionDate);
        while (in_array($collectionDate->format("D"), ["Sat", "Sun", "Mon"])) {
            $collectionDate = $this->dateInc($collectionDate);
        }

        return $collectionDate;
    }

}