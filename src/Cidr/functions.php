<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr {

    function apply() {
        $funcs = func_get_args();
        $input = $funcs[count($funcs)-1];
        if(!is_array($input)) {
            $input = [$input];
        }
        array_pop ($funcs);

        while (count ($funcs) !== 0) {
            $f = array_pop ($funcs);

            $f = is_string($f) && class_exists($f) ? func($f) : $f;
            $input = [call_user_func_array($f, $input)];
        }

        return $input[0];
    }


    /**
     * returns an object's method as a function that works just like the method
     * this also works, if no method is passed in, and returns a function that makes classes
     * @param $obj
     * @param $method
     * @return callable
     */
    function func($obj, $method = null) {

        if(is_string($obj) and class_exists($obj) and is_null($method)) {
            return function() use($obj, $method) {
                $refl = new \ReflectionClass($obj);
                return $refl->newInstanceArgs(func_get_args());
            };
        } else {
            assert(is_object($obj));
            assert(!is_null($method));
            return function() use($obj, $method) {
                return call_user_func_array([$obj, $method], func_get_args());
            };

        }
    }



    function startsWith($haystack, $needle)
    {
        return $needle === "" || strpos($haystack, $needle) === 0;
    }
    function endsWith($haystack, $needle)
    {
        return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
    }

    /**
     * if no premise is supplied, the not null assertion will be applied
     * @param $argument
     * @param $premise
     * @param string $message
     * @throws Cidr\Exception\InvalidArgumentException
     */
    function assertArgument($argument, $premise, $message="")
    {
        if (count(func_get_args()) === 1) {
            $premise = !is_null($argument);
            $message = "argument can't be null";
        }

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

}