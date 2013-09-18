<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

class CidrResponseContextFailed implements CidrResponseContext
{ use Milk;

    const API_NOT_RESPONDING = "API_NOT_RESPONDING";
    const API_REJECTED = "API_REJECTED";

    /** string machine readable type of failure */
    private $status;

    /** string human readable message detailing the reasons for failure */
    private $message;

}