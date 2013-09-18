<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Model\Tests;

use Cidr\Model\TwoSugar;

class ExampleModel implements \Countable
{
    use TwoSugar;
    private $coreProperties = ["name","age"];
}

class TwoSugarTest extends \PHPUnit_Framework_Testcase
{

    public $testInput = array(
        "name" => "Captain",
        "age" => null,
        "cape" => "flowing",
    );

    public function testConstructor()
    {
        $e = new ExampleModel();
        $this->assertSame( count($e), 0 );
    }

    public function testConstructorwithArray()
    {
        $input = array_flip( ['zero', 'one', 'two'] );

        $e = new ExampleModel($input);

        $this->assertSame( count($e), count($input) );

        foreach( $input as $key => $value ) {
            $this->assertSame( $e->$key, $value );
        }
    }

    public function testCore()
    {
        $e = new ExampleModel($this->testInput);

        $core = $e->core();
        $this->assertSame( $core['name'], "Captain" );
        $this->assertSame( $core['age'], null );
        $this->assertSame( count($core), 2 );
    }

    public function testCustom()
    {
        $e = new ExampleModel($this->testInput);

        $custom = $e->custom();
        $this->assertSame( array( 'cape' => 'flowing' ), $custom );
    }

    public function testAll()
    {
        $e = new ExampleModel($this->testInput);
        $this->assertSame( $e->all(), $this->testInput );
    }

    public function testCorePropertiesGet()
    {
        $e = new ExampleModel();
        $this->assertSame( $e->corePropertiesGet(), ["name", "age"] );
    }

    public function testSetterGetter()
    {
        $e = new ExampleModel();

        $e->one = 'one';
        $e->two = 'two';
        $e->three = 'three';

        $this->assertSame( count($e), 3 );

        $e->four = 'four';

        $this->assertSame( count($e), 4 );
        $this->assertSame( $e->one, 'one' );
    }

    public function testGetterBadPropertyException()
    {
        $e = new ExampleModel();

        $this->setExpectedException( "Cidr\\Model\\Exception\\BadPropertyException" );
        $e->propertyDoesNotExist;
    }

    public function test__isset__unest()
    {
        $e = new ExampleModel( $this->testInput );
        $c = count($e);

        $this->assertTrue( isset($e->name) );
        $this->assertFalse( isset($e->age) );
        $this->assertTrue( isset($e->cape) );
        $this->assertFalse( isset($e->propertyDoesNotExist) );

        unset( $e->cape );
        $this->assertSame( count($e), --$c );
        $this->assertFalse( isset($e->cape) );

        unset( $e->age );
        $this->assertSame( count($e), --$c );
        $this->assertFalse( isset($e->age) );
    }

    public function testGetterExistsForName()
    {
        $e = new ExampleModel();
        $e->getName();
    }

    public function testSetterExistsForName()
    {
        $e = new ExampleModel();
        $e->setName("helloworld");
        $this->assertEquals("helloworld", $e->getName());
    }

    public function testGetterReturnsSameValueAsProperty()
    {
        $e = new ExampleModel();
        $this->assertSame($e->getName(), $e->name);
    }

    public function testGetterForNotExistentPropertyReturnsNull()
    {
        $e = new ExampleModel();
        $this->assertNull($e->getSupperPower());
    }

    /**
     * @expectedException \Exception
     */
    public function testSetterWithoutValueThrowsException()
    {
        $e = new ExampleModel();
        $e->setName();
    }

}
