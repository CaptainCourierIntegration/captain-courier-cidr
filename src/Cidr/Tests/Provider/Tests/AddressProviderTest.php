<?php
/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Tests\Provider\Tests;

use Cidr\Tests\Provider\AddressProvider;
use Cidr\Model\Address;

class AddressProviderTest extends \PHPUnit_Framework_Testcase
{
    private $addressProvider;

    public function setup()
    {
        $this->addressProvider = new AddressProvider();
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
    public function testEveryAddressHasAllRequiredFields($address)
    {
        $rawAddresses = require(__DIR__ . "/../addressData.php");
        $rawAddress = array_values(array_filter(
            $rawAddresses,
            function($a)use($address){return $a["id"] === $address->getId();}
        ))[0];

        $this->assertEquals($rawAddress["id"], $address->getId());
        $this->assertEquals($rawAddress["number"] . " " . $rawAddress["line1"], $address->getLines()[0]);
        $this->assertEquals(intval($rawAddress["lines"]), count($address->getLines()));
        $this->assertEquals($rawAddress["town"], $address->getTown());
        $this->assertEquals($rawAddress["county"], $address->getCounty());
        $this->assertEquals($rawAddress["countryCode"], $address->getCountryCode());
        $this->assertEquals($rawAddress["postcode"], $address->getPostcode());

    }





} 