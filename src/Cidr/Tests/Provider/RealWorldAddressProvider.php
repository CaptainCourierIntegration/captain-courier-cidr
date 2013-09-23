<?php
/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Tests\Provider;

use Cidr\Model\Address;

class RealWorldAddressProvider implements DataProvider
{
    private $csvFile;
    private $requiredHeaders;
    private $rqeuiredNumberAddresses;

    public function __construct()
    {
        $this->csvFile = "res/addresses2.csv";
        $this->requiredHeaders = [
            "Establishment Name" => "line1",
            "Street" => "line2",
            "Town" => "town",
            "County" => "county",
            "Postcode" => "postcode"
        ];
        $this->requiredNumberAddresses = 1000;
    }

    /** returns Address array */
    public function getData()
    {
        $rows = $this->loadCsvFile();
        $rows = $this->preprocess($rows);

        $addresses = array_map(\Cidr\func(Address::class), $rows);
        return $addresses;
    }

    private function preprocess(array $rows)
    {
        // attribute selection
        $prows = []; // processed rows
        foreach ($rows as $row) {
            $prow = ["countryCode" => "GB"];
            foreach ($row as $key => $value) {
                if (in_array($key, array_keys($this->requiredHeaders))) {
                    $prow[$this->requiredHeaders[$key]] = $value;
                }
            }
            $prows[] = $prow;
        }
        $rows = $prows;

        // merge line1, line2 into lines array
        $prows = [];
        foreach($rows as $row) {
            $prow = array_merge($row, ["lines" => [$row["line1"], $row["line2"]]]);
            unset($prow["line1"]);
            unset($prow["line2"]);
            $prows[] = $prow;
        }
        $rows = $prows;

        // subsample
        $prows = [];
        for ($i = 0; $i < $this->requiredNumberAddresses; $i++) {
            $prows[$i] = $rows[$i];
        }
        $rows = $prows;

        // set id field
        $prows = [];
        $numRows = count($rows);
        for ($i = 0; $i < $numRows; $i++) {
            $prows[] = array_merge($rows[$i], ["id" => $i]);
        }
        $rows = $prows;

        return $rows;        
    }


    private function loadCsvFile()
    {
        $handle = fopen($this->csvFile, "r");
        $headers = fgetcsv($handle);
        $line = null;
        $rows = [];
        while( ($line = fgetcsv($handle)) !== false)
        {
            $obj = [];
            $count = count($line);
            for ($i = 0; $i < $count; $i++) {
                $obj[trim($headers[$i])] = trim($line[$i]);
            }
            $rows[] = $obj;
        }
        fclose($handle);

        return $rows;
    }

}