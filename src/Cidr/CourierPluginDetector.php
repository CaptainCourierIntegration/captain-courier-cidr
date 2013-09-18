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

class CourierPluginDetector
{ use Milk;

    private $courierFolder;
    private $namespace;
    private $validationPostfix;
    private $configurationFileName;

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

            $configurationFileName = "{$this->courierFolder}/{$courierName}/{$this->configurationFileName}";
            if (!file_exists($configurationFileName)) {
                throw new FileNotFoundException($configurationFileName);
            }

            $couriers[] = $this->courierMetadataFactory->create (
                $courierName,
                "{$this->namespace}\\{$courierName}\\" . explode (".", $this->configurationFileName)[0],
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