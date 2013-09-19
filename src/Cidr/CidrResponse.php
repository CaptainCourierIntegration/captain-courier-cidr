<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

class CidrResponse
{ use Milk;

    const STATUS_SUCCESS = 'SUCCESS';
    const STATUS_FAILED = 'FAILED';

    private $request;
    private $handler;

    /** string STATUS_SUCCESS or STATUS_FAILED */
    private $status;
    private $responseContext;

}
