<?php
namespace Cidr\Tests\Provider;


use Cidr\Model\Parcel;

class ParcelProvider implements DataProvider
{

    /** returns Parcel array */
    public function getData()
    {
        return array_map(
            function($data){ return new Parcel($data);},
            require(__DIR__ . "/parcelData.php")
        );
    }

} 