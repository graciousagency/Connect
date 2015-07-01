<?php


namespace Robin\Connect\SEOShop\Models;

use Illuminate\Support\Collection;

/**
 * Class Order
 * @package Robin\Connect\SEOShop\Model
 *
 * @property string id
 * @property string createdAt
 * @property string updatedAt
 * @property string number
 * @property string status
 * @property string customStatusId
 * @property string channel
 * @property string remoteIp
 * @property string userAgent
 * @property string referralId
 * @property string priceCost
 * @property string priceExcl
 * @property string priceIncl
 * @property string weight
 * @property string volume
 * @property string colli
 * @property string gender
 * @property string birthDate
 * @property string nationalId
 * @property string email
 * @property string firstname
 * @property string middlename
 * @property string lastname
 * @property string phone
 * @property string mobile
 * @property string isCompany
 * @property string companyName
 * @property string companyCoCNumber
 * @property string companyVatNumber
 * @property string addressBillingName
 * @property string addressBillingStreet
 * @property string addressBillingStreet2
 * @property string addressBillingNumber
 * @property string addressBillingExtension
 * @property string addressBillingZipcode
 * @property string addressBillingCity
 * @property string addressBillingRegion
 * @property Object addressBillingCountry
 * @property string addressShippingCompany
 * @property string addressShippingName
 * @property string addressShippingStreet
 * @property string addressShippingStreet2
 * @property string addressShippingNumber
 * @property string addressShippingExtension
 * @property string addressShippingZipcode
 * @property string addressShippingCity
 * @property string addressShippingRegion
 * @property Object addressShippingCountry
 * @property string paymentId
 * @property string paymentStatus
 * @property string paymentIsPost
 * @property string paymentIsInvoiceExternal
 * @property string paymentTaxRate
 * @property string paymentBasePriceExcl
 * @property string paymentBasePriceIncl
 * @property string paymentPriceExcl
 * @property string paymentPriceIncl
 * @property string paymentTitle
 * @property Object paymentData
 * @property string shipmentId
 * @property string shipmentStatus
 * @property string shipmentIsCashOnDelivery
 * @property string shipmentIsPickup
 * @property string shipmentTaxRate
 * @property string shipmentBasePriceExcl
 * @property string shipmentBasePriceIncl
 * @property string shipmentPriceExcl
 * @property string shipmentPriceIncl
 * @property string shipmentDiscountExcl
 * @property string shipmentDiscountIncl
 * @property string shipmentTitle
 * @property Object shipmentData
 * @property string isDiscounted
 * @property string discountType
 * @property string discountAmount
 * @property string discountPercentage
 * @property string discountCouponCode
 * @property string isNewCustomer
 * @property string comment
 * @property string memo
 * @property string allowNotifications
 * @property string doNotifyNew
 * @property string doNotifyReminder
 * @property string doNotifyCancelled
 * @property Object language
 * @property \Robin\Connect\SEOShop\Models\Customer customer
 * @property \Robin\Connect\SEOShop\Models\Invoice[]|Collection invoices
 * @property \Robin\Connect\SEOShop\Models\Shipment[]|Collection shipments
 * @property \Robin\Connect\SEOShop\Models\OrderProduct[]|Collection $orderProducts
 * @property Collection metafields
 * @property Object quote
 * @property Collection events
 *
 * @method Order makeFromJson($order)
 * @method Order makeFromArray(array $order)
 * @method Order makeFromObject($order)
 */
class Order extends Model
{
    public function orderProducts(Order $model)
    {
        return "products";
    }

    public function getEditUrl()
    {
        return $this->createBackOfficeUrl("sales-orders/edit", ['id' => $this->id]);
    }
}