<?php


use Robin\Support\Contracts\Retriever;
use Robin\Connect\SEOShop\Models\Order;

class SEOShopOrderTest extends TestCase
{

    public function testCreateOrderFromJson()
    {
        $order = $this->getModel("order");

        $client = new OrderClient();

        $seoShopOrder = (new Order($client))->makeFromJson($order);

        $this->assertEquals(7846544, $seoShopOrder->id);
        $this->assertInstanceOf("Robin\\Connect\\SEOShop\\Models\\Order", $seoShopOrder);
    }
}

class OrderClient implements Retriever
{

    public function customers()
    {
        // TODO: Implement customers() method.
    }

    public function orders()
    {
        // TODO: Implement orders() method.
    }

    public function retrieve($resource, $name = null)
    {
        // TODO: Implement retrieve() method.
    }

    public function getNumRetrieved()
    {
        // TODO: Implement getNumRetrieved() method.
    }

    public function count($endpoint)
    {
        // TODO: Implement count() method.
    }
}