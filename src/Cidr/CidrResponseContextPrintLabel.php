<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr;

class CidrResponseContextPrintLabel implements CidrResponseContext
{ use Milk;

    /** @var string label encoded as base64 */
    private $pdf;

}