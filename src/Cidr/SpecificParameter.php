<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr;

class SpecificParameter
{

    private $name;
    private $type; // string, int, float, char,
    private $description;
    private $optional;

    function __construct($name, $type="string", $description="", $optional=false) 
    {
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
        $this->optional = $optional;
    }

    function getName()
    {
        return $this->name;
    }

    function getType()
    {
        return $this->type;
    }

    function getDescription()
    {
        return $this->description;
    }

    function getOptional()
    {
        return $this->opional;
    }
    
}
