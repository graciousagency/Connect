<?php

use Illuminate\Support\Collection;
use Mockery\Mock;
use Robin\Connect\SEOShop\Collections\Invoices;
use Robin\Connect\SEOShop\SEOShop;
use Robin\Support\Contracts\Retriever;
use Robin\Connect\SEOShop\Exceptions\EndpointNotCountableException;
use Robin\Connect\SEOShop\Exceptions\PropertyDoesNotExistsException;
use Robin\Connect\SEOShop\Models\Customer;
use Robin\Connect\SEOShop\Models\Order;
use Robin\Connect\SEOShop\Collections\Orders as OrderCollection;

class SEOShopCustomerTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    public function testSeoShopResponseHasDefaultFields()
    {

        $customer = $this->getModel("customer");
        $client = Mockery::mock(stdClass::class . ', ' . Retriever::class);
        $client->shouldReceive("retrieve")->once()->andReturn(new Invoices());

        $seoShopCustomer = $this->getSeoShopCustomer($client, $customer);
        $this->assertEquals(6856292, $seoShopCustomer->id);
        $this->assertInternalType("object", $seoShopCustomer->invoices);
    }

    public function testLoadSeoShopOrderFromAPI()
    {
        $api = Mockery::mock(WebshopappApiClient::class);
        $api->orders = Mockery::mock(WebshopappApiResourceOrders::class);

        $api->orders->shouldReceive('get')->once()->andReturn(
            [
                "order" => [
                    "id" => 2996143,
                    "createdAt" => "2015-04-21T11:16:04+02:00",
                    "updatedAt" => "2015-04-21T11:16:27+02:00",
                    "number" => "01884",
                    "status" => "processing_awaiting_shipment",
                    "customStatusId" => null,
                    "channel" => "main",
                    "remoteIp" => "127.0.0.1",
                    "userAgent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.90 Safari/537.36",
                    "referralId" => false,
                    "priceCost" => 0,
                    "priceExcl" => 51.27,
                    "priceIncl" => 52.5,
                    "weight" => 118,
                    "volume" => 0,
                    "colli" => 0,
                    "gender" => "male",
                    "birthDate" => "1991-01-01",
                    "nationalId" => "234567890",
                    "email" => "info@seoshop.com",
                    "firstname" => "Menno",
                    "middlename" => ""
                ]
            ]
        );

        $client = new SEOShop($api);
        $seoOrder = $client->orders(7846544);
        $this->assertInstanceOf(Order::class, $seoOrder);
    }

    public function testLoadCustomerFromAPI()
    {
        $api = Mockery::mock(WebshopappApiClient::class);
        $api->customers = Mockery::mock(WebshopappApiResourceCustomers::class);

        $api->customers->shouldReceive('get')->once()->andReturn(
            [
                "customer" => [
                    "id" => 530173,
                    "createdAt" => "2013-09-20T13:21:29+02:00",
                    "updatedAt" => null,
                    "lastOnlineAt" => "2013-09-20T13:21:29+02:00",
                    "isConfirmed" => true,
                    "remoteIp" => "127.0.0.1",
                    "userAgent" => "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_4) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.65 Safari/537.36",
                    "referralId" => false,
                    "gender" => false,
                    "birthDate" => false,
                    "nationalId" => "",
                    "email" => "info@seoshop.com",
                    "firstname" => "Jan",
                    "middlename" => "",
                    "lastname" => "Janssen"
                ]
            ]
        );

        $client = new SEOShop($api);
        $customer = $client->customers(6856292);
        $this->assertInstanceOf(Customer::class, $customer);
    }

    /**
     * @expectedException \Robin\Connect\SEOShop\Exceptions\PropertyDoesNotExistsException
     */
    public function testAccessingNotExistingPropertyResultsInException()
    {
        $model = $this->getModel("customer");
        $client = Mockery::mock(Retriever::class);
        $customer = new Customer($client);
        $customer->makeFromJson($model);

        $customer->foo;
    }

    /**
     * @expectedException \Robin\Connect\SEOShop\Exceptions\EndpointNotCountableException
     */
    public function testCountingUncountablePropertyResultsInException()
    {
        $client = $this->getSeoshop();
        $client->count("foo");
    }
}

