<?php


namespace Robin\Connect\SEOShop\Models;

use Illuminate\Support\Collection;

/**
 * Class Invoice
 * @package Robin\Connect\SEOShop\Models
 *
 * @property string id
 * @property string createdAt
 * @property string updatedAt
 * @property string number
 * @property string status
 * @property string isVatShifted
 * @property string priceExcl
 * @property string priceIncl
 * @property string doNotifyNew
 * @property string doNotifyPaid
 * @property string invoice
 * @property string isCreditNote
 * @property string creditNote
 * @property \Robin\Connect\SEOShop\Models\Order order
 * @property Customer customer
 * @property Collection items
 * @property Collection metafields
 * @property Collection events
 */
class Invoice extends Model
{
    public function getEditUrl()
    {
        $url = $this->createBackOfficeUrl('sales-orders/edit', ['tab' => 'invoices', 'id' => $this->id]);
        return "<a href='" . $url . "'>" . $this->getModelName() . "</a>";
    }
}