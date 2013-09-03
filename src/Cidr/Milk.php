<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr;

use Cidr\Exception\BadArgumentsException;
use Cidr\Exception\UnknownMethodException;

/**
 * Provide a common constructor injection help method
 */
trait Milk
{

    public static $MILK_MANAGE_CLASS_VARIABLES_ONLY = 1;
	/**
	 * Ignore properties
	 * @var array
	 */
	// private static $propertiesNotManagedByMilk = [];

	/**
	 * Milk options
	 * @var int See Milk class constants
	 */
	// private static $milkOptions = 0;

	/**
	 * @desc Constructor which set class properties 
	 */
	public function __construct()
	{

		$args = func_get_args();
		$reflClass = new \ReflectionClass(__CLASS__);

		# http://uk1.php.net/manual/en/class.reflectionproperty.php#reflectionproperty.constants.modifiers
		# Urgh, ~ \ReflectionProperty::IS_STATIC  doesn't work
		$properties = $reflClass->getProperties();

		# determine properties not managed by milk
		$propertiesNotManagedByMilk = isset(self::$propertiesNotManagedByMilk) ? self::$propertiesNotManagedByMilk : [];
		$milkOptions = isset(self::$milkOptions) ? self::$milkOptions : 0;

		foreach( $properties as $reflProperty ) {
			
			// static properties obviously can't be set
			if( $reflProperty->isStatic() ) {
				continue;
			}

			// Check milk options to see if we need to check this variables heiracy
			// is this property declared in this class
			if( ( $milkOptions & Milk::$MILK_MANAGE_CLASS_VARIABLES_ONLY) and __CLASS__ !== $reflProperty->getDeclaringClass()->getName() ) {
			   	continue;
			}

			// is this property in the stop list
			if( $propertiesNotManagedByMilk and in_array($reflProperty->getName(), $propertiesNotManagedByMilk) ) {
				continue;
			}

			// Do we want to throw a exception for not having enough args? Not sure. Either way we're done with this loop.
			if( !$args ) {
				$ex = new BadArgumentsException( 
					$args = func_get_args(), 
					$this, 
					__METHOD__, 
					sprintf( "Passed %s args. Expected %s.", count($args), count($properties) )
				 );
                throw $ex;

				break;
			}
			// set the property
			$reflProperty->setAccessible(true);
			$reflProperty->setValue( $this, array_shift($args) );
		}

		// anything left to do?
		if( $args ) {
			throw new BadArgumentsException(
				$args = func_get_args(), 
				$this, 
				__METHOD__, 
				sprintf( "Passed %s args. Expected %s.", count($args), count($properties) )
			 );
		}

	}

    public function __call( $method, array $args )
    {
    	// look like a getter
    	if( 0 === strpos($method, 'get') ) {
    		$propertyName = lcfirst( substr($method, 3) );
    		if( property_exists($this, $propertyName) ) {
    			return $this->$propertyName;
    		}
    	} elseif( 0 === strpos($method, 'set') ) {
    		$propertyName = lcfirst( substr($method, 3) );
    		if( property_exists($this, $propertyName) ) {
    			if( !$args ) {
    				throw new \Exception("Must pass at least one arg to a set{\$property}()");
    			}
    			$this->$propertyName = $args[0];
    			return $this;
    		}
    	}
    	// parent implements __call()?
    	if( is_callable('parent::__call') ) {
    		return parent::__call( $method, $args );
    	}
    	throw new UnknownMethodException( $args, $this, $method );
    }


}