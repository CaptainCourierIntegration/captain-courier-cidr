<?php

/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr;

class CourierPluginMetadata
{ use Milk;

	const RESOURCE_CLASS = "class";
	const RESOURCE_YAML = "yaml";

	/** @var string name of courier */
    private $courierName;

    /** @var array associative array from fileName/resource to its type which is one of: RESOURCE_CLASS, RESOURCE_YAML */
    private $configurationResources;

    /** associative array from task to file */
    private $validationFiles;

}