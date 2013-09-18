<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

// TODO once TwoSugar has been re-written to be Milk with custom paramters, refactor this to use TwoSugar over Milk
class CidrRequestContextCreateConsignment implements CidrRequestContext
{ use Milk;

    /** Address */
    private $collectionAddress;

    /** Contact */
    private $collectionContact;

    /** DateTime */
    private $collectionTime;

    /** Address */
    private $deliveryAddress;

    /** Contact */
    private $deliveryContact;

    /** DateTime */
    private $deliveryTime;

    /** Parcel[] */
    private $parcels;

    /** String */
    private $contractNumber;

    /** String */
    private $serviceCode;

    // TODO resolve how to handle this property
    /** CourierQuote */
    //private $courierQuote;

}