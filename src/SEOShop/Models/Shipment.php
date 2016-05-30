<?php


namespace Robin\Connect\SEOShop\Models;

use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Class Shipment
 * @package Robin\Connect\SEOShop\Models
 *
 * @property int id
 * @property Carbon createdAt
 * @property Carbon updatedAt
 * @property string number
 * @property string status
 * @property string trackingCode
 * @property string doNotifyShipped
 * @property string doNotifyTrackingCode
 * @property Object customer
 * @property Object order
 * @property Collection products
 * @property Collection metafields
 * @property Collection events
 */
class Shipment extends Model {

    /**
     * @param int $orderID
     * @return string
     */
    public function getEditUrl($orderID) {
//        $url = $this->createBackOfficeUrl('sales-order/edit', ['tab' => 'shipments', 'id' => $this->id]);
        $url = $this->createBackOfficeUrl('sales-orders/edit', ['tab' => 'shipments', 'id' => $orderID]);

        return "<a href='" . $url . "' target='_blank'>" . $this->getModelName() . "</a>";
    }
}