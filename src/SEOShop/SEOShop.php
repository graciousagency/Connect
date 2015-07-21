<?php namespace Robin\Connect\SEOShop;

use Illuminate\Support\Collection;
use Robin\Connect\SEOShop\Resource;
use Robin\Support\Contracts\Retriever;
use Robin\Connect\Logger;
use Robin\Api\Collections\Customers;
use Robin\Api\Models\Customer;
use Robin\Connect\SEOShop\Collections\Orders;
use Robin\Connect\SEOShop\Exceptions\EndpointNotCountableException;
use Robin\Connect\SEOShop\Models\Customer as SEOShopCustomer;
use Robin\Connect\SEOShop\Models\Order;

class SEOShop implements Retriever
{
    /**
     * @var \WebshopappApiClient
     */
    protected $api;
    /**
     * @var \Robin\Support\Contracts\Logger
     */
    private $logger;

    /**
     * @param \WebshopappApiClient $api
     */
    public function __construct(\WebshopappApiClient $api)
    {
        $this->api = $api;
    }

    /**
     * @param $resource
     * @param $name
     * @return Resource
     */
    public function retrieve($resource, $name = null)
    {

        if (is_object($resource) && is_string($name)) {
            $name = ucfirst($name);
            return (new Resource($resource, $this, $name))->get();
        }

        return $this->api->read($resource);
    }

    /**
     * @param null $id
     * @param array $options
     * @return Customers|\Robin\Connect\SEOShop\Models\Customer[]
     */
    public function customers($id = null, $options = [])
    {
        //reassign $id when it's an array
        if (is_array($id)) {
            list($id, $options) = [null, $id];
        }

        $customers = new Customers();
        if ($id == null) {
            foreach ($this->api->customers->get($id, $options) as $seoCustomer) {
                $customer = new SEOShopCustomer($this, $this->logger);
                $customers->push($customer->makeFromArray($seoCustomer));
            }
            return $customers;
        }

        $customer = $this->api->customers->get($id, $options);
        return (new SEOShopCustomer($this, $this->logger))->makeFromArray($customer);

    }

    /**
     * @param null $id
     * @param array $options
     * @return Orders|\Robin\Connect\SEOShop\Models\Order[]
     */
    public function orders($id = null, $options = [])
    {
        //reassign $id when it's an array
        if (is_array($id)) {
            list($id, $options) = [null, $id];
        }

        $orders = [];
        if ($id == null) {
            foreach ($this->api->orders->get($id, $options) as $seoOrder) {
                $orders[] = (new Order($this, $this->logger))->makeFromArray($seoOrder);
            }
            return new Orders($orders);
        }

        $seoOrder = $this->api->orders->get($id, $options);
        return (new Order($this, $this->logger))->makeFromArray($seoOrder);
    }

    /**
     * @param $endpoint
     * @return mixed
     * @throws EndpointNotCountableException
     */
    public function count($endpoint)
    {
        if (property_exists($this->api, $endpoint) && method_exists($this->api->{$endpoint}, "count")) {
            return $this->api->{$endpoint}->count();
        }

        throw new EndpointNotCountableException;
    }

    public function getNumRetrieved()
    {
        return $this->api->getApiCallsMade();
    }

    /**
     * @return \WebshopappApiClient
     */
    public function getApi()
    {
        return $this->api;
    }
}