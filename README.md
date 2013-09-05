Cidr: Courier Integration Done Right!
=====================================



Cidr's goals are simpe, make working with couriers easy.

Cidr will be able to:
 - provide the cheapest quotes from couriers
 - create/view/delete/track shipment requests with minimum customization for a particular courier
 - get shipping labels 


Current features:
 - create shipments with couriers ParcelForce and P4D
 
setup
=====
create a file res/credentials.yml in the project directory.
It will look something like this:

````
ParcelForce:
    username: <insert here>
    password: <insert here>
P4D:
    username: <insert here>
    apiKey: <insert here>
````

Where you would replace <code>\<insert here\></code> with your username/password/apiKey.
If you don't need one of the couriers, don't add it to the credentials.yml file. For example if you only needed
ParcelForce then the credentials.yml file would look like:

````
ParcelForce:
    username: <insert here>
    password: <insert here>
````

Whilsts testing Cidr it is recommended to use test credentials instead of production credentials. 
P4D provide test credentials without signing up, just goto http://www.p4d.co.uk/v2/api.

testing
=======

Cidr uses Travis for testing, available <a href="https://travis-ci.org/CaptainCourierIntegration/captain-courier-cidr">here</a>.
Cidr can be tested locally by running:
````
phpunit --configuration=phpunit.xml src/
````


usage
=====

An example below for creating a shipment with ParcelForce:

````
use Cidr\Cidr;
use Cidr\CidrResponse;


// creates a new Cidr object
// the cidr object provides capabilities, which are objects that can only 
// perform 1 task for 1 courier such as creating a consignment for ParcelForce.
$cidr = new Cidr();
$capability = $cidr->getCapability("ParcelForce", "CreateConsignment");

// submits the request to the courier[s] and returns a Cidr\CidrResponse object.
// response has a context properties which is different depending on the status of the response.
$response = $capability->submitCidrRequest($request);

// checks the request was successful, and prints out the assigned consignment number.
// if the request failed, it prints out the response context to get an idea why it has failed.
if (CidrResponse::STATUS_SUCCESS === $response->getStatus()) {
    // gets the consignment number assigned for the created shipment
    $consignmentNumber = $response->getResponseContext();
    print "consignment number is '${consignmentNumber}'\n";
    exit(0);
} else {
  print "request failed\n";
  print_r($response->getResponseContext());
  exit(1);
}



````
