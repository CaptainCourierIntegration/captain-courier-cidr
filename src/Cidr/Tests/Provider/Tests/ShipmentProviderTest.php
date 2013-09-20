<?php
/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Tests\Provider\Tests;

use Cidr\Model\Address;
use Cidr\Model\Contact;
use Cidr\Tests\Provider\AddressProvider;
use Cidr\Tests\Provider\ShipmentProvider;
use Cidr\Model\Shipment;
use Cidr\Tests\Provider\ContactProvider;
use Cidr\Tests\Provider\ParcelProvider;
use \DateTime;

class ShipmentProviderTest extends \PHPUnit_Framework_Testcase
{
    private $shipmentProvider;

    public function setup()
    {
        $this->shipmentProvider = new ShipmentProvider(
            new AddressProvider(),
            new ContactProvider(),
            new ParcelProvider()
        );
    }

    public function provideShipment()
    {
        $this->setup();
        return array_map(
            function($shipment){return [$shipment];},
            $this->shipmentProvider->getData()
        );
    }

    public function provideShipmentAndCoreProperty()
    {
        $shipments = $this->provideShipment();

        $args = [];
        foreach ($shipments as $shipment) {
            $shipment = $shipment[0];
            foreach ($shipment->corePropertiesGet() as $property) {
                $args[] = [$shipment, $property];
            }
        }
        return $args;
    }

    public function testGetDataReturnsArray()
    {
        $shipments = $this->shipmentProvider->getData();
        $this->assertTrue( is_array($shipments));
    }

    /** @dataProvider provideShipment */
    public function testEveryElementOfGetDataIsShipment($shipment)
    {
        $this->assertInstanceOf(Shipment::class, $shipment);
    }

    /** @dataProvider provideShipmentAndCoreProperty */
    public function testEveryShipmentHasAllRequiredFields($shipment, $property)
    {
        $this->assertArrayHasKey($property, $shipment->core());
        $this->assertNotNull($shipment->$property);
    }

    /** @dataProvider provideShipment */
    public function testShipmentHasIdOfCorrectType($shipment)
    {
        $this->assertTrue(
            is_integer($shipment->getId()) or is_int($shipment->getId())
        );
    }

    /** @dataProvider provideShipment */
    public function testShipmentCollectionAddressIsAddress($shipment)
    {
        $this->assertInstanceOf(Address::class, $shipment->getcollectionAddress());
    }

    /** @dataProvider provideShipment */
    public function testShipmentCollectionContactIsContactObject($shipment)
    {
        $this->assertInstanceOf(Contact::class, $shipment->getCollectionContact());
    }

    /** @dataProvider provideShipment */
    public function testShipmentCollectionTimeIsDateTimeObject($shipment)
    {
        $this->assertInstanceOf(DateTime::class, $shipment->getCollectionTime());
    }

    /** @dataProvider provideShipment */
    public function testShipmentDeliveryAddressIsAddress($shipment)
    {
        $this->assertInstanceOf(Address::class, $shipment->getDeliveryAddress());
    }

    /** @dataProvider provideShipment */
    public function testShipmentDeliveryContactIsContactObject($shipment)
    {
        $this->assertInstanceOf(Contact::class, $shipment->getDeliveryContact());
    }

    /** @dataProvider provideShipment */
    public function testShipmentDeliveryTimeIsDateTimeObject($shipment)
    {
        $this->assertInstanceOf(DateTime::class, $shipment->getDeliveryTime());
    }

    /** @dataProvider provideShipment */
    public function testShipmentContractNumberIsWithinCorrectRange($shipment)
    {
        $this->assertTrue( is_string($shipment->getContractNumber()) );
        $this->assertGreaterThan(5, strlen($shipment->getContractNumber()));
        $this->assertLessThan(20, strlen($shipment->getContractNumber()));
    }

    /** @dataProvider provideShipment */
    public function testShipmentServiceCodeIsWithinValidRange($shipment)
    {
        $this->assertInternalType('string', $shipment->getServiceCode());
    }

    /** @dataProvider provideShipment */
    public function testShipmentParcels($shipment)
    {
        $this->assertInternalType('array', $shipment->getParcels());
        $this->assertGreaterThanOrEqual(1, count($shipment->getParcels()));
    }

}