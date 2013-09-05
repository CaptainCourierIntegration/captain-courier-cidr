<?php
namespace Cidr\Tests\Provider;


use Cidr\Model\Contact;

class ContactProvider
{

    /** returns Contact array */
    public function getData()
    {
        return array_map(
            function($data){ return new Contact($data);},
            require(__DIR__ . "/contactData.php")
        );
    }

} 