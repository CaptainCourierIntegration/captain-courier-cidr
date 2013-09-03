<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Tests;

use Cidr\Milk;

class basic
{
	use Milk;
	public static $staticPropertyToIgnore = '';
	private $a;
	private $b;
	private $c;
}

class basicPropertiesNotManagedByMilk
{
	use Milk;
	private static $propertiesNotManagedByMilk = ['a'];
	private $a;
	private $b;
}

trait abc
{
	private $a;
	private $b;
	private $c;
}

class basicWithTrait
{
	Use abc;
	Use Milk;
}

class line
{
	protected $x;
	private $myPrivateVar;
}
class square extends line
{
	protected $y;
}
class cube extends square
{
	use Milk;
	protected $z;
}

class cubeOnly extends line
{
	use Milk;
    //todo review this line, use to be milkOptions = MILK_MANAGE_CLASS_VARIABLES_ONLY
	private static $milkOptions = 1;
	protected $z;
}

class MilkTest extends \PHPUnit_Framework_Testcase
{

	public function testMilk()
	{
		$basic = new basic('a','b','c');
		$this->assertSame( 'a', $basic->getA() );

		$basic->setA('aa');
		$this->assertSame( 'aa', $basic->getA() );
	}

	public function testBasicWithTrait()
	{
		$basicWithTrait = new basicWithTrait('a','b','c');
		$this->assertSame( 'a', $basicWithTrait->getA() );
		$this->assertSame( 'c', $basicWithTrait->getC() );

		$basicWithTrait->setA('aa');
		$this->assertSame( 'aa', $basicWithTrait->getA() );
	}

	public function testBasicPropertiesNotManagedByMilk()
	{
		$basic = new basicPropertiesNotManagedByMilk('b');
		$this->assertSame( null, $basic->getA() );
		$this->assertSame( 'b', $basic->getB() );
	}

	public function testlineBoxSquareCube()
	{
		// these'll throw exceptions if something isn't right
		$cube = new cube('z','y','x');
		$cubeonly = new cubeOnly('z');
	}

	public function testMilkTooFewBadNumberOfArguments()
	{
		$this->setExpectedException('Cidr\\Exception\\BadArgumentsException');
		$basic = new basic();
	}

	public function testMilkTooManyBadNumberOfArguments()
	{
		$this->setExpectedException('Cidr\\Exception\\BadArgumentsException');
		$basic = new basic('a','b','c','d');
	}

}