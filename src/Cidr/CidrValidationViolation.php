<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

/**
 * wrapper for symfony's ConstraintViolation class
 */
class CidrValidationViolation
{ use Milk;

    /** path to field that is violating a constraint */
    private $path;

    /** string */
    private $message;

    /** value that violated constraint */
    private $value;

}