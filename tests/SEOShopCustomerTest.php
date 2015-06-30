<?php

use Illuminate\Support\Collection;
use Robin\Support\Contracts\Retriever;
use Robin\Connect\SEOShop\Exceptions\EndpointNotCountableException;
use Robin\Connect\SEOShop\Exceptions\PropertyDoesNotExistsException;
use Robin\Connect\SEOShop\Models\Customer;
use Robin\Connect\SEOShop\Models\Order;
use Robin\Connect\SEOShop\Collections\Orders as OrderCollection;

class SEOShopCustomerTest extends TestCase
{

    public function testSeoShopResponseHasDefaultFields()
    {

        $customer = $this->getModel("customer");
        $client = new FakeClient();
        $seoShopCustomer = $this->getSeoShopCustomer($client, $customer);
        $this->assertEquals(6856292, $seoShopCustomer->id);
        $this->assertInternalType("object", $seoShopCustomer->invoices);
//        $this->assertInternalType("int", $seoShopCustomer->orders->total);
//        $this->assertEquals(99, $seoShopCustomer->orders->total);
    }

    public function testLoadSeoShopOrderFromAPI()
    {
        $client = $this->getSeoshop();
        $seoOrder = $client->orders(7846544);
        $this->assertInstanceOf(Order::class, $seoOrder);
    }

    public function testLoadCustomerFromAPI()
    {
        $client = $this->getSeoshop();
        $customer = $client->customers(6856292);
        $this->assertInstanceOf(Customer::class, $customer);
    }

    /**
     * @expectedException \Robin\Connect\SEOShop\Exceptions\PropertyDoesNotExistsException
     */
    public function testAccessingNotExistingPropertyResultsInException()
    {
        $model = $this->getModel("customer");
        $customer = new Customer(new FakeClient());
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

class FakeClient implements Retriever
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
        $order = new \Robin\Connect\SEOShop\Models\Order($this);
        $order->makeFromArray(["priceIncl" => 99]);
        return new OrderCollection(new Collection([$order]));
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
