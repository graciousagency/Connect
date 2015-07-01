<?php namespace Robin\Connect\SEOShop;


use Robin\Connect\Logger;
use Robin\Connect\SEOShop\Contracts\Collection as SEOShopCollection;
use Robin\Connect\SEOShop\Exceptions\ModelCannotBeMadeFromPrimitiveException;
use Robin\Connect\SEOShop\Models\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Robin\Support\Contracts\Retriever;

/**
 * Class Resource
 * @package \App\SEOShop
 *
 */
class Resource
{

    public $id;

    public $url;

    public $link;
    /**
     * @var \Robin\Connect\SEOShop\Client
     */
    public $retriever;

    private $responseModel;
    private $responseCollection;

    /**
     * @param $resource
     * @param Retriever $retriever
     * @param $model
     */
    public function __construct($resource, Retriever $retriever, $model)
    {
        $this->url = $resource->url;
        $this->link = $resource->link;
        $this->id = $resource->id;
        $this->retriever = $retriever;
        $this->embedded = property_exists($resource, "embedded") ? $resource->embedded : false;
        $this->model = ucfirst($model);
        $this->setResponseModel("Robin\\Connect\\SEOShop\\Models\\" . Str::singular($this->model));
        $this->setResponseCollection("Robin\\Connect\\SEOShop\\Collections\\" . $this->model);
    }

    /**
     * @param string $model
     */
    public function setResponseModel($model)
    {
        $this->responseModel = (class_exists($model)) ? $model : false;
    }

    public function setResponseCollection($collection)
    {
        $this->responseCollection = (class_exists($collection)) ? $collection : false;
    }

    /**
     * @return Collection
     *
     * Retrieves the resource and maps it to an object
     */
    public function get()
    {
        $primitives = ($this->embedded)
            ? $this->embedded
            : $this->fetchJson();

        $models = $this->mapPrimitivesToModels($primitives);

        return ($this->canFetchFirstItem($models)) ? $models->first() : $models;
    }

    /**
     * @param $primitives
     * @return Collection
     */
    private function mapPrimitivesToModels($primitives)
    {
        $items = ($this->responseModel) ? $this->makeModels($primitives) : $primitives;

        return ($this->responseCollection) ? $this->makeCollection($items) : collect($items);
    }

    /**
     * @param $primitives
     * @return Collection
     */
    private function makeModels($primitives)
    {
        $wrapped = [];

        $modelReflection = new \ReflectionClass($this->responseModel);
        if (is_object($primitives)) {
            return [$this->makeModel($modelReflection, $primitives)];
        }

        foreach ($primitives as $primitive) {
            $wrapped[] = ($this->makeModel($modelReflection, $primitive));
        }
        return $wrapped;
    }

    /**
     * @param \ReflectionClass $modelReflection
     * @param $primitive
     * @return Model
     * @throws ModelCannotBeMadeFromPrimitiveException
     */
    private function makeModel(\ReflectionClass $modelReflection, $primitive)
    {
        /** @var Model $model */
        $model = $modelReflection->newInstance($this->retriever);

        if (is_string($primitive)) {
            return $model->makeFromJson($primitive);
        }

        if (is_object($primitive)) {
            return $model->makeFromObject($primitive);
        }

        if (is_array($primitive)) {
            return $model->makeFromArray($primitive);
        }

        throw new ModelCannotBeMadeFromPrimitiveException();
    }

    /**
     * @param $items
     * @return object
     */
    private function makeCollection($items)
    {
        $responseReflection = new \ReflectionClass($this->responseCollection);
        return $responseReflection->newInstance($items);
    }

    /**
     * @return string
     */
    private function fetchJson()
    {
        return json_decode(json_encode($this->retriever->retrieve($this->url)));
    }

    /**
     * @param $models
     * @return bool
     */
    private function canFetchFirstItem($models)
    {
        return $this->isOne($models) && $this->isNotSEOShopCollection($models) && $this->resourceExpectsPlural();;
    }

    /**
     * @param $models
     * @return bool
     */
    private function isOne(Collection $models)
    {
        return $models->count() === 1;
    }

    /**
     * @param $models
     * @return bool
     */
    private function isNotSEOShopCollection($models)
    {
        return !$models instanceof SEOShopCollection;
    }

    private function resourceExpectsPlural()
    {
        return Str::singular($this->model) === $this->model;
    }
}