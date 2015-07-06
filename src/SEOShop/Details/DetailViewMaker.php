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

        $detailsView->addDetails(self::createDetailsView($seoOrder));

        $detailsView->addColumns(self::createProductsView($seoOrder), "Products");

        $detailsView->addRows(self::createShipmentsView($seoOrder), "Shipments");
        $detailsView->addRows(self::createInvoicesView($seoOrder), "Invoices");

        return $detailsView;
    }


    /**
     * @param Order $order
     * @return static
     */
    private static function createDetailsView(order $order)
    {
        return OrderDetails::make(
            $order->createdAt,
            $order->status,
            $order->paymentStatus,
            $order->shipmentStatus
        );
    }

    /**
     * @param order $order
     * @return DetailViewItem
     */
    private static function createProductsView(order $order)
    {
        $products = $order->orderProducts;
        $robinProducts = Products::make();
        foreach ($products as $product) {
            $robinProducts->push(Product::make($product->productTitle, $product->quantityOrdered, $product->priceIncl));
        }

        return $robinProducts;
    }

    private static function createShipmentsView(order $seoOrder)
    {
        $shipments = $seoOrder->shipments;
        $robinShipments = Shipments::make();
        foreach ($shipments as $shipment) {
            $robinShipments->push(Shipment::make($shipment->getEditUrl(), $shipment->status));
        }
        return $robinShipments;
    }

    private static function createInvoicesView(order $seoOrder)
    {
        $invoices = $seoOrder->invoices;
        $robinInvoices = Invoices::make();
        foreach ($invoices as $invoice) {
            $robinInvoices->push(Invoice::make($invoice->getEditUrl(), $invoice->status, $invoice->priceIncl));
        }
        return $robinInvoices;
    }
}