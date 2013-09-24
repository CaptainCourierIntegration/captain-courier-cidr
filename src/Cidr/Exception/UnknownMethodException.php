<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Exception;

class UnknownMethodException extends \Exception
{

    public $args;
    public $obj;
    public $method;

    public $reflMethod;

    public function __construct( $args, $obj, $method )
    {
        $this->args = $args;
        $this->obj = $obj;
        $this->method = $method;

        $this->message = sprintf(
            "%s->%s(). declared in %s. args are: %s",
            get_class($obj),
            $method,
            // throws exceptions for __construct: explode("::", $method)[1],
            $method,
            print_r( 
                array_map(
                    function($arg){ 
                        if (is_object($arg)) {
                            return sprintf("<object %s>", get_class($arg));
                        } else if(is_array($arg)) {
                            return sprintf("<array length=%s>", count($arg));
                        } else {
                            return (string)$arg;
                        }
                    },
                    $args
                ),
                true
            )
        );
    }

}