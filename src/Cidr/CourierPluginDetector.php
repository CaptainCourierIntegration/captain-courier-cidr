<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

use Cidr\Exception\FileNotFoundException;
use Cidr\Model\Task;
use Cidr\CourierPluginMetadata;
use Cidr\Exception\IllegalStateException;

class CourierPluginDetector
{ use Milk;

    private $courierFolder;
    private $namespace;
    private $validationPostfix;

    /** @var list of configuration files to look for */
    private $configurationFileNames;

    private $courierMetadataFactory;

    private $requireAllCouriersFeatureComplete;

    /**
     * TODO CLEAN UP
     * @return CourierIntegration[] detected
     */
    public function detectCouriers()
    {
        $couriers = [];
        foreach ($this->getCourierFolders() as $courierName) {

            $validationFileNames = [];
            foreach (Task::$Tasks as $task) {
                $fileName = "{$this->courierFolder}/{$courierName}/{$task}{$this->validationPostfix}";
                if($this->requireAllCouriersFeatureComplete and !file_exists($fileName)) {
                    throw new FileNotFoundException($fileName);
                }
                $validationFileNames[$task] = file_exists($fileName) ? $fileName : null;

            }

            $configurationResources = [];
            foreach($this->configurationFileNames as $canonicalConfigurationFileName) {
                d($canonicalConfigurationFileName);
                $configurationFileName = "{$this->courierFolder}/{$courierName}/{$canonicalConfigurationFileName}";

                if(!file_exists($configurationFileName)) {
                    continue;
                }

                if (\Cidr\endsWith($configurationFileName, ".php")) {
                    $resourceName = "{$this->namespace}\\{$courierName}\\" . explode (".", $canonicalConfigurationFileName)[0];
                    $resourceType = CourierPluginMetadata::RESOURCE_CLASS;
                } else if(\cidr\endsWith($configurationFileName, ".yml")) {
                    $resourceName = $configurationFileName;
                    $resourceType = CourierPluginMetadata::RESOURCE_YAML;
                } else {
                    throw new IllegalStateException(sprintf("unable to handle configuration file '%s', can handle the following file types: php, yml"));
                }
                $configurationResources[$resourceName] = $resourceType;
            }

            d($configurationResources);

            $couriers[] = $this->courierMetadataFactory->create(
                $courierName,
                $configurationResources,                    
                $validationFileNames
            );
        }

        return $couriers;

    }

    private function getCourierFolders()
    {
        $handle = opendir ($this->courierFolder);
        while (false !== ($entry = readdir ($handle))) {
            if ( !\Cidr\startsWith($entry, ".")
                 and is_dir ($this->courierFolder . "/" . $entry)) {
                $courierNames[] = $entry;
            }
        }
        return $courierNames;

    }

}