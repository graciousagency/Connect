<?php


namespace Robin\Connect\SEOShop\Models;

use Carbon\Carbon;
use Illuminate\Support\Collection;


/**
 * Class Customer
 * @package App\SEOShop\Response
 *
 * @property int id
 * @property Carbon createdAt
 * @property Carbon updatedAt
 * @property boolean isConfirmed
 * @property string type
 * @property string lastOnlineAt
 * @property string remoteIp
 * @property string userAgent
 * @property boolean referralId
 * @property string gender
 * @property boolean birthDate
 * @property string nationalId
 * @property string email
 * @property string firstname
 * @property string middlename
 * @property string lastname
 * @property string phone
 * @property string mobile
 * @property boolean isCompany
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
 * @property object addressBillingCountry
 * @property boolean addressShippingCompany
 * @property string addressShippingName
 * @property string addressShippingStreet
 * @property string addressShippingStreet2
 * @property string addressShippingNumber
 * @property string addressShippingExtension
 * @property string addressShippingZipcode
 * @property string addressShippingCity
 * @property string addressShippingRegion
 * @property object addressShippingCountry
 * @property string memo
 * @property boolean doNotifyRegistered
 * @property boolean doNotifyConfirmed
 * @property boolean doNotifyPassword
 * @property Collection groups
 * @property Collection invoices
 * @property object language
 * @property \Robin\Connect\SEOShop\Collections\Orders orders
 * @property Collection reviews
 * @property Collection shipments
 * @property Collection tickets
 * @property Collection metafields
 * @property Collection login
 *
 * @method Customer makeFromJson
 * @method Customer makeFromArray
 * @method Customer makeFromObject
 */
class Customer extends Model
{

}