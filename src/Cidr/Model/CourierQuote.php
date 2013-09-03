<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Model;

class CourierQuote
{ use TwoSugar;

    private $coreProperties = [
        /** string name of courier who gave quote */
        "courierName",

        /** double price quoted for a shipment */
        "price",

        /** DateTime quote was given */
        "dateCreated"
    ];






}