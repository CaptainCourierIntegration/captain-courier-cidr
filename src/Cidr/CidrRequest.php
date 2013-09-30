<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

class CidrRequest
{ use Milk;

    private $requestContext; // sublcass of CidrRequestContext
    private $task; // type of request, ie CreateShipment, Quote
    private $courierCredentials = []; // associative array

    /**
     *
     * Their are 4 states of a request, Created(s), Quoted(q), Aborted(a), and Confirmed(c).
     * The regex for state transitions is: s(a|q+c?a) which can be broken down into 3 states:
     * sa
     * sq+a
     * sq+ca
     * hence if current request is s then history is guarenteed to be empty, if a then previous request could be either: s, q or c but not a or epsilon
     * @var array of CidrResponse with head being oldest request, tail being the most recent request
     */
    private $history = []; 
}