<?php
/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Tests\Provider\Tests;

use Cidr\Tests\Provider\ParcelProvider;
use Cidr\Model\Parcel;

class ParcelProviderTest extends \PHPUnit_Framework_Testcase
{
    private $parcelProvider;

    public function setup()
    {
        $this->parcelProvider = new ParcelProvider();
    }

    public function provideParcel()
    {
        $this->setup();
        return array_map(
            function($parcel){return [$parcel];},
            $this->parcelProvider->getData()
        );
    }


    public function testGetDataReturnsArray()
    {
        $parcels = $this->parcelProvider->getData();
        $this->assertTrue( is_array($parcels));
    }

    /** @dataProvider provideParcel */
    public function testEveryElementOfGetDataIsParcel($parcel)
    {
        $this->assertInstanceOf(Parcel::class, $parcel);
    }

    /** @dataProvider provideParcel */
    public function testEveryParcelHasAllRequiredFields($parcel)
    {
        $this->assertArrayHasKey("id", $parcel->core());
        $this->assertArrayHasKey("width", $parcel->core());
        $this->assertArrayHasKey("height", $parcel->core());
        $this->assertArrayHasKey("length", $parcel->core());
        $this->assertArrayHasKey("weight", $parcel->core());
        $this->assertArrayHasKey("value", $parcel->core());
        $this->assertArrayHasKey("description", $parcel->core());


    }





} 