<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 29/08/2013
 * Time: 11:33
 */

namespace Cidr\Exception;


use Exception;

class CourierNotFoundException extends \LogicException
{
    public function __construct($courier, $context)
    {
        parent::__construct("whilst '$context' the courier '$courier' was not found");
    }


} 