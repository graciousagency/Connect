<?php


namespace Robin\Connect\SEOShop\Collections;

use Robin\Connect\SEOShop\Contracts\Collection;
use Robin\Connect\SEOShop\Models\Order as SEOShopOrder;
use Illuminate\Support\Collection as LaravelCollection;


class Orders extends LaravelCollection implements Collection
{

    /**
     * @var int|float
     */
    public $count;

    public $total;

    /**
     * @param $orders
     */
    public function __construct($orders)
    {
        parent::__construct($orders);

        $this->count = $this->count();
        $this->total = $this->totalSpent();
    }

    private function totalSpent()
    {
        return $this->sum(
            function (SEOShopOrder $order) {
                return $order->priceIncl;
            }
        );
    }

    public function totalFormatted()
    {
        return "€" . $this->total;
    }
}