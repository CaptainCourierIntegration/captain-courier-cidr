<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Model\Exception;

class BadPropertyException extends \Exception
{

    public $property;
    public $obj;

    public function __construct( $property, $obj = null, $message = '' )
    {
        $this->message = sprintf(
            "Property `%s` doesn't exist%s.%s",
            $property,
            is_object($obj) ? " on `".get_class($obj)."`" : '',
            $message ? " {$message}" : ''
        );
    }

}
