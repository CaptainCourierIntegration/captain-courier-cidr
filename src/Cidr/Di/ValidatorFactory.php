<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */



namespace Cidr\Di;;
use Cidr\Milk;
use Bond\Di\Factory;

use Cidr\Validator\Loader\YamlFileLoader;
use Cidr\Validator\ClassMetadataFactory;
use Symfony\Component\Validator\ValidatorBuilder;


class ValidatorFactory implements Factory
{ use Milk;

    /** 
     *returns a Symfony Validator given a yml file
     * @arg string $ymlFileName name of yml file that defines constriants
     */
    public function create()
    {
        list($ymlFileName) = func_get_args();
        $yamlFileLoader = new YamlFileLoader($ymlFileName);
        $metadataFactory = new ClassMetadataFactory(
            $yamlFileLoader
        );
        $validatorBuilder = new ValidatorBuilder();
        $validatorBuilder->setMetadataFactory(
            $metadataFactory
        );
        return $validatorBuilder->getValidator();
    }

}