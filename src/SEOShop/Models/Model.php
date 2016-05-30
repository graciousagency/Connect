<?php


namespace Robin\Connect\SEOShop\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Robin\Support\Contracts\Retriever;
use Robin\Connect\SEOShop\Exceptions\PropertyDoesNotExistsException;
use Robin\Connect\SEOShop\Contracts\SEOShopModel;

abstract class Model implements Jsonable, SEOShopModel {

    private $backendUrl = "https://seoshop.webshopapp.com/backoffice/";

    protected $data = [];

    /**
     * @var array|Collection
     */
    protected $fetched = [];

    /**
     * @var Resource|Resource
     */
    private $resource;
    /**
     * @var Retriever
     */
    private $client;


    /**
     * @param Retriever $client
     */
    public function __construct(Retriever $client) {
        $this->client = $client;
        $this->fetched = collect();
    }

    /**
     * @param string $model
     * @return Model
     */
    public function makeFromJson($model) {
        if (is_string($model)) {
            return $this->make(json_decode($model));
        }

        throw new \InvalidArgumentException('$model should be of type string, ' . gettype($model) . ' given');
    }

    /**
     * @param $model
     * @return Model
     */
    public function makeFromObject($model) {
        if (is_object($model)) {
            return $this->make($model);
        }

        throw new \InvalidArgumentException('$model should be of type Object, ' . gettype($model) . ' given');
    }

    /**
     * @param array $model
     * @return Model
     */
    public function makeFromArray(array $model) {

        $model = json_decode(json_encode($model));

        return $this->make($model);
    }

    /**
     * @param $key
     * @return Collection|Resource|string
     * @throws PropertyDoesNotExistsException
     */
    public function __get($key) {
        if (property_exists($this->data, $key)) {
            return $this->getValueFromKey($key);
        }

        if (method_exists($this, $key)) {
            $namedResource = $key;
            $key = call_user_func([$this, $namedResource], $this);
            return $this->getValueFromKey($key, $namedResource);
        }

        throw new PropertyDoesNotExistsException();
    }

    /**
     * @return mixed|null|string
     */
    public function getModelName() {
        $key = collect(explode('\\', get_class($this)))->last();
        return lcfirst($key);
    }

    public function __toString() {
        return $this->toJson();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0) {
        return json_encode($this->data, $options);
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray() {
        $collection = collect();
        foreach ($this->data as $key => $value) {
            $key = Str::snake($key);
            $collection->put($key, $value);
        }
        return $collection->toArray();
    }

    /**
     * @param \stdClass $object
     * @return $this
     */
    private function make(\stdClass $object) {
        $modelName = $this->getModelName();

        $this->data = $this->getModelData($object, $modelName);
        return $this;
    }

    /**
     * @param $key
     * @param string $namedResource
     * @return mixed
     */
    protected function getValueFromKey($key, $namedResource = "") {
        $value = $this->data->{$key};

        if ($resource = $this->isResource($value)) {
            $key = ($namedResource !== "") ? $namedResource : $key;
            return $this->getResource($key, $resource);
        }

        if (in_array($key, ['createdAt', 'updatedAt'])) {
            return Carbon::createFromFormat("Y-m-d\\TH:i:sP", $value);
        }

        return $value;
    }

    /**
     * @param $value
     * @return bool
     */
    private function isResource($value) {
        return (gettype($value) !== "string" && gettype($value) === "object" && property_exists($value, 'resource')) ? $value->resource : false;
    }

    /**
     * @param $key
     * @return bool
     */
    private function notFetched($key) {
        return !$this->fetched->has($key);
    }

    /**
     * @param $key
     * @param $resource
     * @return mixed
     */
    private function getResource($key, $resource) {
        if ($this->notFetched($key)) {
            $result = $this->client->retrieve($resource, $key);
            $this->fetched->put($key, $result);
        }

        return $this->fetched->get($key);
    }

    protected function createBackOfficeUrl($uri, $parameters = []) {
        $parameters = http_build_query($parameters, "&");

        $url = $this->backendUrl . $uri . '?' . $parameters;
        return $url;
    }

    /**
     * @param $model
     * @param $modelName
     */
    private function getModelData($model, $modelName) {
        return (
            property_exists($model, $modelName)
            && !$this->isResource($model->{$modelName})
            && is_object($model->{$modelName})
        ) ?
            $model->{$modelName} :
            $model;
    }
}