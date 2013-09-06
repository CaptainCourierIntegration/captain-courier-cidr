<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr;

// TODO once TwoSugar has been re-written to be Milk with custom paramters, refactor this to use TwoSugar over Milk
class CidrRequestContextPrintLabel implements CidrRequestContext
{ use Milk;

    /** @var string */
    private $shipmentNumber;


}