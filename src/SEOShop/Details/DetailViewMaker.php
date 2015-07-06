<?php


namespace Robin\Connect\SEOShop\Details;


use Robin\Api\Collections\DetailsView;
use Robin\Api\Collections\Invoices;
use Robin\Api\Collections\Products;
use Robin\Api\Collections\Shipments;
use Robin\Api\Models\Views\Details\DetailViewItem;
use Robin\Api\Models\Views\Details\Invoice;
use Robin\Api\Models\Views\Details\OrderDetails;
use Robin\Api\Models\Views\Details\Product;
use Robin\Api\Models\Views\Details\Shipment;
use Robin\Connect\SEOShop\Models\Order;

class DetailViewMaker
{
    /**
     * @param Order $seoOrder
     * @return DetailsView
     */
    public static function makeDetailViews(Order $seoOrder)
    {
        $detailsView = new DetailsView();

        $detailsView->push(self::createDetailsView($seoOrder));
        $detailsView->push(self::createProductsView($seoOrder));
        $detailsView->push(self::createShipmentsView($seoOrder));
        $detailsView->push(self::createInvoicesView($seoOrder));
        return $detailsView;
    }


    /**
     * @param Order $order
     * @return static
     */
    private static function createDetailsView(order $order)
    {
        $details = OrderDetails::make(
            $order->createdAt,
            $order->status,
            $order->paymentStatus,
            $order->shipmentStatus
        );

        return DetailViewItem::make("details", $details);
    }

    private static function getOrderedProducts(order $seoOrder)
    {
        $products = $seoOrder->orderProducts;
        $robinProducts = Products::make();
        foreach ($products as $product) {
            $robinProducts->push(Product::make($product->productTitle, $product->quantityOrdered, $product->priceIncl));
        }

        return $robinProducts;
    }

    /**
     * @param order $order
     * @return DetailViewItem
     */
    private static function createProductsView(order $order)
    {
        return DetailViewItem::make("columns", static::getOrderedProducts($order), "products");
    }

    private static function createShipmentsView(order $seoOrder)
    {
        $shipments = $seoOrder->shipments;
        $robinShipments = Shipments::make();
        foreach ($shipments as $shipment) {
            $robinShipments->push(Shipment::make($shipment->getEditUrl(), $shipment->status));
        }
        return DetailViewItem::make("rows", $robinShipments, "Shipments");
    }

    private static function createInvoicesView(order $seoOrder)
    {
        $invoices = $seoOrder->invoices;
        $robinInvoices = Invoices::make();
        foreach ($invoices as $invoice) {
            $robinInvoices->push(Invoice::make($invoice->getEditUrl(), $invoice->status, $invoice->priceIncl));
        }
        return DetailViewItem::make("rows", $robinInvoices, "invoices");
    }
}