<?php
/*
 * (c) Captain Courier Integration <captain@captaincourier.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cidr\Tests\Provider\Tests;

use Cidr\Tests\Provider\ContactProvider;
use Cidr\Model\Contact;

class ContactProviderTest extends \PHPUnit_Framework_Testcase
{
    private $contactProvider;

    public function setup()
    {
        $this->contactProvider = new ContactProvider();
    }

    public function provideContact()
    {
        $this->setup();
        return array_map(
            function($contact){return [$contact];},
            $this->contactProvider->getData()
        );
    }


    public function testGetDataReturnsArray()
    {
        $contacts = $this->contactProvider->getData();
        $this->assertTrue( is_array($contacts));
    }

    /** @dataProvider provideContact */
    public function testEveryElementOfGetDataIsContact($contact)
    {
        $this->assertInstanceOf(Contact::class, $contact);
    }

    /** @dataProvider provideContact */
    public function testEveryContactHasAllRequiredFields($contact)
    {
        $this->assertArrayHasKey("businessName", $contact->core());
        $this->assertArrayHasKey("name", $contact->core());
        $this->assertArrayHasKey("email", $contact->core());
        $this->assertArrayHasKey("telephone", $contact->core());


    }





} 