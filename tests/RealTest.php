<?php


use Carbon\Carbon;
use Robin\Api\Collections\Customers;
use Robin\Api\Collections\DetailsView;
use Robin\Api\Collections\Orders;
use Robin\Api\Models\Customer;
use Robin\Api\Models\Order;
use Robin\Api\Models\Views\ListView;
use Robin\Api\Models\Views\Panel;
use Robin\Connect\SEOShop\Details\DetailViewMaker;

class RealTest extends TestCase
{


    public function testSendCustomersToRobin()
    {
//        $this->markTestSkipped("This test hits the network, turn it on when you need to fully test the whole package");

        $seoShop = $this->getSeoshop();
        $robin = $this->getRobin();

        $customers = $seoShop->customers(
            [
                'page' => 1,
                'limit' => 1
            ]
        );

        $robinCustomers = new Customers();

        foreach ($customers as $customer) {
            $date = Carbon::createFromFormat("Y-m-d\\TH:i:sP", $customer->createdAt);
            $robinCustomers->push(
                Customer::make(
                    $customer->email,
                    $date,
                    $customer->orders->count,
                    $customer->orders->totalSpendFormatted(),
                    Panel::make($customer->orders->count, $customer->orders->totalSpendFormatted())
                )
            );
        }

        $result = $robin->customers($robinCustomers);


        $this->assertEquals("201", $result->getStatusCode());
    }

    public function testSendOrdersToRobin()
    {
//        $this->markTestSkipped("This test hits the network, turn it on when you need to fully test the whole package");

        $seoShop = $this->getSeoshop();
        $robin = $this->getRobin();

        $orders = $seoShop->orders(['page' => 1, 'limit' => 1]);

        $robinOrders = new Orders();

        foreach ($orders as $order) {
            $createdAt = Carbon::createFromFormat("Y-m-d\\TH:i:sP", $order->createdAt);
            $listView = ListView::make($order->number, $createdAt, $order->status);
            $detailsView = DetailViewMaker::makeDetailViews($order);

            $robinOrders->push(
                Order::make(
                    $order->number,
                    $order->email,
                    $createdAt,
                    $order->priceIncl,
                    $order->getEditUrl(),
                    $listView,
                    $detailsView
                )
            );
        }

        $result = $robin->orders($robinOrders);

        $this->assertEquals(201, $result->getStatusCode());
    }
}