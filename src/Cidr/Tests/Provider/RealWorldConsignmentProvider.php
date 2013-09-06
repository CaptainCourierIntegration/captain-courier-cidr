<?php
/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Tests\Provider;


use Cidr\Model\Address;
use Cidr\Model\Consignment;
use Cidr\Model\Contact;
use Cidr\Model\Parcel;

class RealWorldConsignmentProvider implements DataProvider
{

    public function getData()
    {
        $getCollectionDateTime = function () {
            $today = (new \DateTime("today", new \DateTimeZone("Europe/London")))->format("D");
            $day = ($today === "Tue") ? "Wed" : "Tue";
            $result = new \DateTime("next $day", new \DateTimeZone("Europe/London"));
            $result->setTime(12, 00, 00);
            return $result;
        };

        $dateInc = function (&$date) {
            return $date->add (
                \DateInterval::createFromDateString("1 day")
            );
        };

        $getDeliveryDateTime = function () use ($getCollectionDateTime, $dateInc) {
            $collectionDate = $getCollectionDateTime();
            $dateInc($collectionDate);
            while(in_array($collectionDate->format("D") , ["Sat", "Sun", "Mon"])) {
                $collectionDate = $dateInc($collectionDate);
            }
            return $collectionDate;
        };


        $collectionAddress = new Address([
            "lines" => [
                "Belgrave House",
                "76 Buckingham Palace Rd"
            ],
            "town" => "London",
            "county" => "Greater London",
            "countryCode" => "GB",
            "postcode" => "SW1W 9TQ"
        ]);

        $collectionContact = new Contact([
            "businessName" => "Google Ltd",
            "name" => "Sergey Brin",
            "email" => "joseph@captaincourier.org", //"email" => "sergey@gmail.com",
            "telephone" => "02070313000"
        ]);

        $collectionTime = $getCollectionDateTime();

        $deliveryAddress = new Address([
            "lines" => [
                "Google UK Ltd",
                "Peter House",
                "Oxford Street"
            ],
            "town" => "Manchester",
            "county" => "Greater Manchester",
            "countryCode" => "GB",
            "postcode" => "M1 5AN"
        ]);
        $deliveryContact = new Contact([
            "businessName" => "Google Ltd",
            "name" => "Larry Page",
            "email" => "joseph@captaincourier.org", // "email" => "larry@gmail.com",
            "telephone" => "02070313000"
        ]);

        $deliveryTime = $getDeliveryDateTime();

        $contractNumber = "1234567890";
        $serviceCode = "1111";
        $parcels = [
            new Parcel([
                "width" => 10,
                "height" => 10,
                "length" => 10,
                "weight" => 3,
                "value" => 12.50,
                "description" => "movie"
            ]),
            new Parcel([
                "width" => 15,
                "height" => 15,
                "length" => 15,
                "weight" => 4,
                "value" => 300,
                "description" => "PS3"
            ])
        ];

        $consignment = new Consignment([
            "collectionAddress" => $collectionAddress,
            "collectionContact" => $collectionContact,
            "collectionTime" => $collectionTime,
            "deliveryAddress" => $deliveryAddress,
            "deliveryContact" => $deliveryContact,
            "deliveryTime" => $deliveryTime,
            "contractNumber" => $contractNumber,
            "serviceCode" => $serviceCode,
            "parcels" => $parcels
        ]);

        return [$consignment];

    }

} 
