<?php

use Robin\Support\Contracts\Retriever;
use Robin\Connect\SEOShop\Resource;
use Robin\Connect\SEOShop\Models\Customer;
use Robin\Connect\SEOShop\Models\Order;

class ResourceTest extends TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    public function testCreateModelFromUrl()
    {
        $client = Mockery::mock(stdClass::class . ", " . Retriever::class);
        $client->shouldReceive("retrieve")->once()->andReturn(['id' => 6856292, "priceIncl" => 50]);

        $resourceJson = json_decode(
            '{"id": 6856292,"url": "customers/6856292","link": "http://api.webshopapp.com/nl/customers/6856292.json"}'
        );

        $resource = new Resource($resourceJson, $client, "customer");

        /** @var Customer $model */
        $model = $resource->get();
        $this->assertEquals("6856292", $model->id);
    }

    public function testCreateCollectionFromUrl()
    {
        $client = Mockery::mock(stdClass::class . ", " . Retriever::class);
        $client->shouldReceive("retrieve")->once()->andReturn(['id' => 6856292, "priceIncl" => 50]);

        $resourceJson = json_decode(
            '{"id": false,"url": "orders?customer=6856292","link": "http://api.webshopapp.com/nl/orders.json?customer=6856292"}'
        );

        $resource = new Resource($resourceJson, $client, "orders");

        /** @var Order $model */
        $models = $resource->get();
        $this->assertInstanceOf(\Robin\Connect\SEOShop\Collections\Orders::class, $models);
    }

    public function testLoadsResourceWhenNothingIsEmbedded()
    {
        $client = $this->getSeoshop();
        $resourceJson = $this->getModel("resources/customer_resource");

        $resource = new Resource(json_decode($resourceJson), $client, "customer");
        $resource->get();

        $calls = $resource->retriever->getNumRetrieved();
        $this->assertTrue(($calls >= 1));
    }

    public function testLoadsResourceWhenDataIsEmbedded()
    {
        $client = $this->getSeoshop();
        $resourceJson = $this->getModel("resources/customer_resource_embedded");

        $resource = new Resource(json_decode($resourceJson), $client, "customer");
        $resource->get();

        $calls = $resource->retriever->getNumRetrieved();
        $this->assertEquals(0, $calls);
    }
}