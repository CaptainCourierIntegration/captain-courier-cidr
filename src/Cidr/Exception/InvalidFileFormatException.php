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
 * Time: 11:46
 */

namespace Cidr\Exception;

use Exception;

class InvalidFileFormatException extends \Exception
{
    public function __construct($file, $issue)
    {
        parent::__construct("file '$file' doesn't have correct format: $issue");

    }

}