<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Exception;

class NotImplementedException extends IllegalStateException
{

	/**
 	 *
	 * takes either no args or 2 args which are: class, method
	 */
    public function __construct()
    {
        $args = func_get_args();
        $msg = "";
        if(1 === count($args)) {
            $msg = $args[0];
        } elseif(2 == count($args)) {
            $msg = $args[0] . "::" . $args[1];
		} 
        parent::__construct("method not implemented" . ($msg ? ": {$msg}" : ""));
    }
}