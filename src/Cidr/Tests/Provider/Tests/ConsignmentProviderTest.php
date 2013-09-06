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
use Cidr\Tests\Provider\ConsignmentProvider;
use Cidr\Model\Consignment;
use Cidr\Tests\Provider\ContactProvider;
use Cidr\Tests\Provider\ParcelProvider;
use \DateTime;

class ConsignmentProviderTest extends \PHPUnit_Framework_Testcase
{
    private $consignmentProvider;

    public function setup()
    {
        $this->consignmentProvider = new ConsignmentProvider(
            new AddressProvider(),
            new ContactProvider(),
            new ParcelProvider()
        );
    }

    public function provideConsignment()
    {
        $this->setup();
        return array_map(
            function($consignment){return [$consignment];},
            $this->consignmentProvider->getData()
        );
    }

    public function provideConsignmentAndCoreProperty()
    {
        $consignments = $this->provideConsignment();

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

    /** @dataProvider provideConsignment */
    public function testEveryElementOfGetDataIsConsignment($consignment)
    {
        $this->assertInstanceOf(Consignment::class, $consignment);
    }

    /** @dataProvider provideConsignmentAndCoreProperty */
    public function testEveryConsignmentHasAllRequiredFields($consignment, $property)
    {
        $this->assertArrayHasKey($property, $consignment->core());
        $this->assertNotNull($consignment->$property);
    }

    /** @dataProvider provideConsignment */
    public function testConsignmentHasIdOfCorrectType($consignment)
    {
        $this->assertTrue(
            is_integer($consignment->getId()) or is_int($consignment->getId())
        );
    }

    /** @dataProvider provideConsignment */
    public function testConsignmentCollectionAddressIsAddress($consignment)
    {
        $this->assertInstanceOf(Address::class, $consignment->getcollectionAddress());
    }

    /** @dataProvider provideConsignment */
    public function testConsignmentCollectionContactIsContactObject($consignment)
    {
        $this->assertInstanceOf(Contact::class, $consignment->getCollectionContact());
    }

    /** @dataProvider provideConsignment */
    public function testConsignmentCollectionTimeIsDateTimeObject($consignment)
    {
        $this->assertInstanceOf(DateTime::class, $consignment->getCollectionTime());
    }

    /** @dataProvider provideConsignment */
    public function testConsignmentDeliveryAddressIsAddress($consignment)
    {
        $this->assertInstanceOf(Address::class, $consignment->getDeliveryAddress());
    }

    /** @dataProvider provideConsignment */
    public function testConsignmentDeliveryContactIsContactObject($consignment)
    {
        $this->assertInstanceOf(Contact::class, $consignment->getDeliveryContact());
    }

    /** @dataProvider provideConsignment */
    public function testConsignmentDeliveryTimeIsDateTimeObject($consignment)
    {
        $this->assertInstanceOf(DateTime::class, $consignment->getDeliveryTime());
    }

    /** @dataProvider provideConsignment */
    public function testConsignmentContractNumberIsWithinCorrectRange($consignment)
    {
        $this->assertTrue( is_string($consignment->getContractNumber()) );
        $this->assertGreaterThan(5, strlen($consignment->getContractNumber()));
        $this->assertLessThan(20, strlen($consignment->getContractNumber()));
    }

    /** @dataProvider provideConsignment */
    public function testConsignmentServiceCodeIsWithinValidRange($consignment)
    {
        $this->assertInternalType('string', $consignment->getServiceCode());
    }

    /** @dataProvider provideConsignment */
    public function testConsignmentParcels($consignment)
    {
        $this->assertInternalType('array', $consignment->getParcels());
        $this->assertGreaterThanOrEqual(1, count($consignment->getParcels()));
    }

} 