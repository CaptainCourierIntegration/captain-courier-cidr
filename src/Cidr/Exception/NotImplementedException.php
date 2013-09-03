<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Exception;

class NotImplementedException extends BadMethodCallException
{

    public function __construct()
    {
        parent::__construct("method not implemented");
    }
}