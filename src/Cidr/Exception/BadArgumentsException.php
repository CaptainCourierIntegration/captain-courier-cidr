<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Exception;

class BadArgumentsException extends \Exception
{

    public $args;
    public $obj;
    public $method;

    public $reflMethod;

    public function __construct( $args, $obj, $method, $message = '' )
    {
        $this->args = $args;
        $this->obj = $obj;
        $this->method = $method;

        $reflMethod = new \ReflectionMethod( $method );

        $this->message = sprintf(
            "%s->%s(). declared in %s. %s, arguments were:\n%s",
            get_class($obj),
            explode("::", $method)[1],
            $method,
            $message ? "{$message}" : '',
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