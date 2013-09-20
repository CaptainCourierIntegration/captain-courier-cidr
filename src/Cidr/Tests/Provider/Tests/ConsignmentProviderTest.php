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
    private $consignmentProvider;

    public function setup()
    {
        $this->consignmentProvider = new ShipmentProvider(
            new AddressProvider(),
            new ContactProvider(),
            new ParcelProvider()
        );
    }

    public function provideShipment()
    {
        $this->setup();
        return array_map(
            function($consignment){return [$consignment];},
            $this->consignmentProvider->getData()
        );
    }

    public function provideShipmentAndCoreProperty()
    {
        $consignments = $this->provideShipment();

        $args = [];
        foreach ($consignments as $consignment) {
            $consignment = $consignment[0];
            foreach ($consignment->corePropertiesGet() as $property) {
                $args[] = [$consignment, $property];
            }
        }
        return $args;
    }

    public function testGetDataReturnsArray()
    {
        $consignments = $this->consignmentProvider->getData();
        $this->assertTrue( is_array($consignments));
    }

    /** @dataProvider provideShipment */
    public function testEveryElementOfGetDataIsShipment($consignment)
    {
        $this->assertInstanceOf(Shipment::class, $consignment);
    }

    /** @dataProvider provideShipmentAndCoreProperty */
    public function testEveryShipmentHasAllRequiredFields($consignment, $property)
    {
        $this->assertArrayHasKey($property, $consignment->core());
        $this->assertNotNull($consignment->$property);
    }

    /** @dataProvider provideShipment */
    public function testShipmentHasIdOfCorrectType($consignment)
    {
        $this->assertTrue(
            is_integer($consignment->getId()) or is_int($consignment->getId())
        );
    }

    /** @dataProvider provideShipment */
    public function testShipmentCollectionAddressIsAddress($consignment)
    {
        $this->assertInstanceOf(Address::class, $consignment->getcollectionAddress());
    }

    /** @dataProvider provideShipment */
    public function testShipmentCollectionContactIsContactObject($consignment)
    {
        $this->assertInstanceOf(Contact::class, $consignment->getCollectionContact());
    }

    /** @dataProvider provideShipment */
    public function testShipmentCollectionTimeIsDateTimeObject($consignment)
    {
        $this->assertInstanceOf(DateTime::class, $consignment->getCollectionTime());
    }

    /** @dataProvider provideShipment */
    public function testShipmentDeliveryAddressIsAddress($consignment)
    {
        $this->assertInstanceOf(Address::class, $consignment->getDeliveryAddress());
    }

    /** @dataProvider provideShipment */
    public function testShipmentDeliveryContactIsContactObject($consignment)
    {
        $this->assertInstanceOf(Contact::class, $consignment->getDeliveryContact());
    }

    /** @dataProvider provideShipment */
    public function testShipmentDeliveryTimeIsDateTimeObject($consignment)
    {
        $this->assertInstanceOf(DateTime::class, $consignment->getDeliveryTime());
    }

    /** @dataProvider provideShipment */
    public function testShipmentContractNumberIsWithinCorrectRange($consignment)
    {
        $this->assertTrue( is_string($consignment->getContractNumber()) );
        $this->assertGreaterThan(5, strlen($consignment->getContractNumber()));
        $this->assertLessThan(20, strlen($consignment->getContractNumber()));
    }

    /** @dataProvider provideShipment */
    public function testShipmentServiceCodeIsWithinValidRange($consignment)
    {
        $this->assertInternalType('string', $consignment->getServiceCode());
    }

    /** @dataProvider provideShipment */
    public function testShipmentParcels($consignment)
    {
        $this->assertInternalType('array', $consignment->getParcels());
        $this->assertGreaterThanOrEqual(1, count($consignment->getParcels()));
    }

}