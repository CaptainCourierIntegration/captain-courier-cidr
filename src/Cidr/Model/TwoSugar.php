<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Model;

use Cidr\Model\Exception\BadPropertyException;

trait TwoSugar
{

    /**
     * Numerically indexed array of core properties for this type
     * This is expected to be implemented by the 'using' class
     * @var array
 */
    # private $coreProperties = [];

    /**
     * All properties of model are stored here
     * @var
     */
    private $data = [];

    /**
     * @param array Associative array of model properties
     */
    public final function __construct( array $properties = array() )
    {
        $this->data = $properties;
    }

    /**
     * @desc Get all core properties and their values as an associative array
     * @return array
     */
    public function core()
    {
        // always return core
        $output = [];
        foreach( $this->coreProperties as $property ) {
            $output[$property] = isset($this->data[$property]) ? $this->data[$property] : null;
        }
        return $output;

        // DEPRICATED
        // return only what we have
        // return array_intersect_key( $this->data, array_flip( $this->coreProperties ) );
    }

    /**
     * @desc Get all custom properties and their values as an associative array
     * @return array
     */
    public function custom()
    {
        return array_diff_key(
            $this->data,
            array_flip( $this->coreProperties )
        );
    }

    /**
     * @return array All properties of this object
     */
    public function all()
    {
        // return only what we have
        return $this->data;
        // always return core
        return array_merge( array_flip( $this->coreProperties ), $this->data );
    }

    /**
     * @return array
     */
    public function corePropertiesGet()
    {
        return $this->coreProperties;
    }

    /**
     * @desc Sugar to make this object behave like a stdClass
     * @return mixed
     */
    public function __get( $key )
    {
        //print "TwoSugar>> " . get_class($this) . "->get($key)\n";

        if( !array_key_exists( $key, $this->data ) ) {
            // is this a core property?
            if( in_array($key, $this->coreProperties) ) {
                return null;
            } else {
                throw new BadPropertyException( $key, $this );
            }
        }
        return $this->data[$key];
    }

    public function __call( $method, array $args )
    {
        // look like a getter
        if( 0 === strpos($method, 'get') ) {
            $propertyName = lcfirst( substr($method, 3) );
            if (array_key_exists($propertyName, $this->data)) {
                return $this->data[$propertyName];
            }
        } elseif( 0 === strpos($method, 'set') ) {
            $propertyName = lcfirst( substr($method, 3) );
            if( !$args ) {
                throw new \Exception("Must pass at least one arg to a set{\$property}()");
            }
            $this->data[$propertyName] = $args[0];
            return $this;
        }
        // parent implements __call()?
        if( is_callable('parent::__call') ) {
            return parent::__call( $method, $args );
        }

    }

    /**
     * @param string property name
     * @param mixed value
     * @desc Set property on object
     */
    public function __set( $key, $value )
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string property name
     * @return bool
     */
    public function __isset( $key )
    {
        return isset( $this->data[$key] );
    }

    /**
     * @param string property name
     */
    public function __unset( $key )
    {
        unset( $this->data[$key] );
    }

    /**
     * @return string
     */
    public function jsonSerialize()
    {
        return json_encode($this->data);
    }

    /**
     * Countable interface
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

}
