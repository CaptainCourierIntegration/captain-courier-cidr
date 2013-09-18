<?php
/**
 * Created by PhpStorm.
 * User: joseph
 * Date: 02/09/2013
 * Time: 15:09
 */

namespace Cidr\Exception;

class FileNotFoundException extends \Exception
{
    public function __construct($fileName)
    {
        parent::__construct("file '$fileName' not found in " . realpath($fileName));
    }
}