<?php
/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Tests\Provider\Tests;

use Cidr\Tests\Provider\AddressProvider;
use Cidr\Tests\Provider\RealWorldAddressProvider;
use Cidr\Model\Address;

class AddressProviderTest extends \PHPUnit_Framework_Testcase
{
    private $addressProvider;

    public function setup()
    {
       // $this->addressProvider = new AddressProvider();
        $this->addressProvider = new RealWorldAddressProvider();
    }

    public function provideAddress()
    {
        $this->setup();
        return array_map(
            function($address){return [$address];},
            $this->addressProvider->getData()
        );
    }

    public function testGetDataReturnsArray()
    {
        $addresses = $this->addressProvider->getData();
        $this->assertTrue( is_array($addresses));
    }

    /** @dataProvider provideAddress */
    public function testEveryElementOfGetDataIsAddress($address)
    {
        $this->assertInstanceOf(Address::class, $address);
    }

    /** @dataProvider provideAddress */
    public function testEveryAddressHasAtLeastOneLine($address)
    {
        $this->assertNotNull($address->getLines());
        $this->assertGreaterThanOrEqual(1, $address->getLines());
    }

    /** @dataProvider provideAddress */
    public function testEveryAddressHasATown($address) 
    {
        $this->assertNotNull($address->getTown());
    }

    /** @dataProvider provideAddress */
    public function testEveryAddressHasACounty($address)
    {
        $this->assertNotNull($address->getCounty());
    }

    /** @dataProvider provideAddress */
    public function testAddressHasPostcode($address)
    {
        $this->assertNotNull($address->getPostcode());
    }

}