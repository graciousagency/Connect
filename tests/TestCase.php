<?php

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Robin\Connect\SEOShop\SEOShop;
use Robin\Connect\SEOShop\Models\Customer;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @return SEOShop
     */
    public function getSeoshop()
    {
        $key = env("SEOSHOP_API_KEY");
        $secret = env("SEOSHOP_API_SECRET");
        $language = env("SEOSHOP_API_LANGUAGE");

        $api = new \WebshopappApiClient("live", $key, $secret, $language);
        return new SEOShop($api);
    }

    public function getRobin()
    {
        $key = env('ROBIN_API_KEY');
        $secret = env('ROIBIN_API_SECRET');
        $url = env('ROBIN_API_URL');

        return new \Robin\Api\Client($key, $secret, $url);
    }

    protected function setUp()
    {
        $this->filesystem = new Filesystem(new Local(__DIR__));
    }

    public function getModel($model, $decode = false)
    {
        $content = $this->filesystem->read("/models/" . $model . ".json");
        return ($decode) ? json_decode($content, true) : $content;
    }

    /**
     * @param $client
     * @param $customer
     * @return Customer
     */
    public function getSeoShopCustomer($client, $customer)
    {
        return (new Customer($client))->makeFromJson($customer);
    }
}