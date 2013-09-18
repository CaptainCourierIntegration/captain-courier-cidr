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

    private $courierName;
    private $configurationClass;

    /** associative array from task to file */
    private $validationFiles;

}