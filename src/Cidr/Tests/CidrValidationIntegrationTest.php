<?php
/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Tests;

use Bond\Di\DiTestCase;
use Cidr\Cidr;
use Cidr\CidrRequest;
use Cidr\CidrRequestContextCreateShipment;
use Cidr\CidrRequestContextPrintLabel;
use Cidr\CidrRequestContextGetTracking;
use Cidr\CidrRequestContextGetQuote;
use Cidr\CidrResponse;
use Cidr\Model\Address;
use Cidr\Model\Shipment;
use Cidr\Model\Contact;
use Cidr\Model\Parcel;
use Cidr\Model\Task;

/**
 * Class CidrValidationIntegrationTest
 * @package Cidr\Tests
 *
 * @group integration 
 *
 * @resource Cidr\StandaloneConfiguration
 */
class CidrValidationIntegrationTest extends DiTestCase
{
    private static $requiredDefinitions = [
        "ALL" => [CidrRequest::class],
        Task::CREATE_CONSIGNMENT => [
            CidrRequestContextCreateShipment::class,
            Address::class,
            Shipment::class,
            Contact::class,
            Parcel::class
        ],
        Task::PRINT_LABEL => [CidrRequestContextPrintLabel::class],
        Task::GET_TRACKING => [ CidrRequestContextGetTracking::class ],
        Task::GET_QUOTE => [ CidrRequestContextGetQuote::class ]
    ];

    public function provideRequiredClass()
    {
        $container = $this->setup();

        // preprocess requiredDefinitions
        $allDefs = self::$requiredDefinitions["ALL"];
        unset(self::$requiredDefinitions["ALL"]);
        foreach(self::$requiredDefinitions as $task => $defs) {
            self::$requiredDefinitions[$task] = array_merge($defs, $allDefs);
        }

        $cidr = new Cidr();
        $courierNames = $cidr->getSupportedCouriers();
        $args = [];
        foreach ($courierNames as $courier) {
            foreach($cidr->getSupportedCapabilities($courier) as $task) {
                foreach(self::$requiredDefinitions[$task] as $class) {
                    $args[] = [$container->get("cidrValidator"), $courier, $task, $class];
                }
            }
        }
        return $args;
    }

//    public function provideDefinedClass()
//    {
//        $container = $this->setup();
//        $cidrValidator = $container->get("cidrValidator");
//
//
//        $cidr = new Cidr();
//        $courierNames = $cidr->getSupportedCouriers();
//        foreach ($courierNames as $courier) {
//            foreach($cidr->getSupportedCapabilities($courier) as $task) {
//
//                $validators = $cidrValidator->getValidators();
//                $this->assertTrue(isset($validators[$courier]));
//                $this->assertTrue(isset($validators[$courier][$task]));
//
//                $validator = $validators[$courier][$task];
//                $loader = \Cidr\propertyValue($validator->getMetadataFactory(), "loader");
//                $classes = \Cidr\propertyValue($loader, "classes");
//
//                print_r($classes);
//                foreach($classes as $class => $constraints) {
//                    yield [$container->get("cidrValidator"), $courier, $task, $class];
//                }
//            }
//        }
//    }

    /** @dataProvider provideRequiredClass */
    public function testCourierHasValidationInformationForClass(
        $cidrValidator,
        $courier,
        $task,
        $class
    )
    {
        $validators = $cidrValidator->getValidators();
        $this->assertTrue(isset($validators[$courier]));
        $this->assertTrue(isset($validators[$courier][$task]));

        $validator = $validators[$courier][$task];
        $metadata = $validator->getMetadataFactory()->getMetadataFor($class);

        $loader = \Cidr\propertyValue($validator->getMetadataFactory(), "loader");
        $classes = \Cidr\propertyValue($loader, "classes");

        $this->assertArrayHasKey($class, $classes, "need validation constraints for class '$class', for task '$task', for courier '$courier'" . print_r(array_keys($classes), true));
    }

//    /** @dataProvider provideDefinedClass */
//    public function testCourierDoesNotHaveValidationInformationForUnnecessaryClasses(
//        $cidrValidator,
//        $courier,
//        $task,
//        $definedClass
//    )
//    {
//        $this->assertArrayHasKey(
//            $definedClass,
//            array_flip(self::$requiredDefinitions[$task]),
//            "unexpected validation constraints defined for class '$definedClass' four courier '$courier' for task '$task'"
//        );
//    }
}