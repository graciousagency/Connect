<?php


use Robin\Support\Contracts\Retriever;
use Robin\Connect\SEOShop\Models\Order;

class SEOShopOrderTest extends TestCase
{

    public function testCreateOrderFromJson()
    {
        $order = $this->getModel("order");

        $client = Mockery::mock(Retriever::class);

        $seoShopOrder = (new Order($client))->makeFromJson($order);

        $this->assertEquals(7846544, $seoShopOrder->id);
        $this->assertInstanceOf("Robin\\Connect\\SEOShop\\Models\\Order", $seoShopOrder);
    }
}