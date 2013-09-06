<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



function apply() {
    $funcs = func_get_args();
    $input = $funcs[count($funcs)-1];
    array_pop ($funcs); 

    while (count ($funcs) !== 0) {
        $f = array_pop ($funcs);
        $input = $f($input);
    }

    return $input;
}



function startsWith($haystack, $needle)
{
    return $needle === "" || strpos($haystack, $needle) === 0;
}
function endsWith($haystack, $needle)
{
    return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
}

function assertArgument($argument, $premise, $message="")
{
    if(!$premise) {
        throw new \Cidr\Exception\InvalidArgumentException($argument, $message);
    }
}

function propertyValue($object, $property)
{
    assert(is_object($object));
    assert(!is_null($property));

    $refclass = new \ReflectionClass($object);
    $loaderProp = $refclass->getProperty($property);
    $loaderProp->setAccessible(true);
    return $loaderProp->getValue($object);
}