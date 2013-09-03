<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Model;


class Parcel
{ use TwoSugar;

    private $coreProperties = [
        "id",
        "width",
        "height",
        "length",
        "weight",
        "value",
        "description"
    ];

}

