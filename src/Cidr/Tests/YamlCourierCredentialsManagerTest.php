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
 * Time: 12:07
 */

namespace Cidr\Tests;

use Bond\Di\DiTestCase;
use Cidr\CourierCredentialsManagerInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class YamlCourierCredentialsManagerTest
 * @package Cidr\Tests
 *
 * @resource Cidr\StandaloneConfiguration
 * @resource __CLASS__
 * @service yamlCourierCredentialsManagerTest
 */
class YamlCourierCredentialsManagerTest extends DiTestCase
{
    public $credentialsManager;

    public function __invoke($configurator, $container)
    {
        $container->setParameter("credentialsFile", __DIR__ . "/credentials.yml");
        $configurator->add(
            "yamlCourierCredentialsManagerTest",
            self::class
        )
            ->setProperty(
                "credentialsManager",
                new Reference("yamlCourierCredentialsManager")
            );

        $container->setParameter(
            "credentials",
            [
                "ParcelForce" => [
                    "username" => "pfusername",
                    "password" => "pfpassword"
                ],
                "P4D" => [
                    "username" => "p4dusername",
                    "apiKey" => "p4dapiKey"
                ]
            ]
        );
    }

    public function provideCourierNames()
    {
        return [ ["ParcelForce"], ["P4D"]];
    }

    public function provideCourierCredentialFields()
    {
        $container = $this->setup();
        $expectedCredentials = $container->getParameter("credentials");
        $fields = [];
        foreach ($this->provideCourierNames() as List($courier)) {
            foreach ($expectedCredentials[$courier] as $key => $value) {
                $fields[] = [$courier, $key, $value];
            }
        }
        return $fields;
    }

    public function provideValidRequiredCouriers()
    {
        return [
            [],
            ["ParcelForce"],
            ["P4D"],
            ["ParcelForce", "P4D"],
            ["P4D", "ParcelForce"]
        ];
    }

    public function provideInvalidRequiredCouriers()
    {
        return [
            [null],
            [""],
            ["PF"],
            ["ParcelForce", "Unknown"],
            ["P4D", "Unknown"],
            ["Unknown", "ParcelForce"],
            ["", "ParcelForce"],
            ["ParcelForce", "P4D", "Unknown"]
        ];
    }

    public function testInitDoesNotThrowException()
    {
        $this->credentialsManager->init();
    }

    public function testInitReturnsItself()
    {
        $this->assertSame(
            $this->credentialsManager,
            $this->credentialsManager->init()
        );
    }

    /**
     * @dataProvider provideValidRequiredCouriers
     */
    public function testInitDoesNotThrowExceptionForValidRequiredCouriers()
    {
        $this->credentialsManager->init(func_get_args());
    }

    /**
     * @dataProvider provideInvalidRequiredCouriers
     * @expectedException Cidr\Exception\CourierNotFoundException
     */
    public function testInitThrowsCourierNotFoundExceptionForInvalidRequiredCouriers()
    {
        $this->credentialsManager->init(func_get_args(), "ok");
    }

    public function testYamlCourierCredentialsManagerIsInstanceOfCourierCredentialsManagerInterface()
    {
        $this->assertInstanceOf(
            CourierCredentialsManagerInterface::class,
            $this->credentialsManager
        );
    }


    public function testGetCouriersDoesNotThrowExceptionIfInitCalled()
    {
        $this->credentialsManager->init();
        $this->credentialsManager->getCouriers();
    }

    /** @dataProvider provideCourierNames */
    public function testGetCouriersReturnsAllCouriersSpecifiedInFile($courier)
    {
        $this->credentialsManager->init();
        $this->assertArrayHasKey(
            $courier,
            array_flip($this->credentialsManager->getCouriers())
        );
    }

    /** @expectedException Cidr\Exception\CourierNotFoundException */
    public function testGetCredentialsThrowsCourierNotFoundExceptionIfInitCalled()
    {
        $this->credentialsManager->init();
        $this->credentialsManager->getCredentials("__no_courier__");
    }

    /** @dataProvider provideCourierNames */
    public function testGetCredentialsDoesNotThrowExceptionForValidCourier($courier)
    {
        $this->credentialsManager->init();
        $this->credentialsManager->getCredentials($courier);
    }

    /** @dataProvider provideCourierCredentialFields */
    public function testGetCredentialsReturnsCorrectValuesForCourier(
        $courier,
        $credential,
        $value
    )
    {
        $this->credentialsManager->init();

        $this->objectHasAttribute(
            $credential,
            $this->credentialsManager->getCredentials($courier)
        );
        $this->assertEquals(
            $value,
            $this->credentialsManager->getCredentials($courier)->$credential
        );
    }

}