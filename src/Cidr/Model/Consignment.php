<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Model;

use Cidr\Model\Address;
use Cidr\Model\Contact;
use Cidr\Model\Parcel;

class Consignment
{   use TwoSugar;

    private $coreProperties = [
        "id",
        "collectionAddress",
        "collectionContact",
        "collectionTime",
        "deliveryAddress",
        "deliveryContact",
        "deliveryTime",
        "contractNumber",
        "serviceCode",
        "parcels"
    ];

    function parcelAdd(Parcel $parcel) {
        $this->parcels[] = $parcel;
    }

}

