<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Model;

class Contact
{ use TwoSugar;

    private $coreProperties = [
        "businessName",
        "name",
        "email",
        "telephone"
    ];

}
