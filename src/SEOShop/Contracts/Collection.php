<?php


namespace Robin\Connect\SEOShop\Contracts;

/*
|--------------------------------------------------------------------------
| SEOShop Collection
|--------------------------------------------------------------------------
| Interface type to be able to check if an collection is an SEOShop Collection.
| When an fetched collection has only 1 item, but it's an SEOShop Collection it's
| still returned as the collection. When the fetched collection is a standard Collection
| Object, the first item is returned.
|
| This is because a SEOShop Collection can have extra methods to preform calculations
| on the whole collection, even when it's just one.
|
|
*/

interface Collection
{

}