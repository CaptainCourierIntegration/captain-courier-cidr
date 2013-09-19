<?php
/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Exception;

use Exception;

class InvalidArgumentException extends \LogicException {

    public function __construct(
        $arg,
        $message
    ) {
        parent::__construct(
            "argumented passed is invalid because '${message}', invalid argument is '${arg}'"
        );

    }

}