<?php
/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Tests\Provider;

use Cidr\Model\Address;

class AddressProvider implements DataProvider
{

    /** returns Address array */
    public function getData()
    {
        $addressArray = require(__DIR__ . "/addressData.php");
        $addresses = [];

        foreach ($addressArray as $data) {
            $data["line1"] = $data["number"] . " " . $data["line1"];
            unset($data["number"]);
            $lines = [];
            for ($i = 1; $i <= intval($data["lines"]); $i++) {
                $lines[] = $data["line{$i}"];
                unset($data["line{$i}"]);
            }
            $data["lines"] = $lines;
            $addresses[] = new Address($data);
        }
        return $addresses;
    }

}