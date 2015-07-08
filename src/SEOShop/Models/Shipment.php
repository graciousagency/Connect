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
class Shipment extends Model
{
    public function getEditUrl()
    {
        $url = $this->createBackOfficeUrl('sales-order/edit', ['tab' => 'shipments', 'id' => $this->id]);
        return "<a href='" . $url . "'>" . $this->getModelName() . "</a>";
    }
}