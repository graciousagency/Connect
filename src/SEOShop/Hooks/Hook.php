<?php


namespace Robin\Connect\SEOShop\Hooks;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class Hook implements Arrayable, Jsonable
{

    public $isActive;

    public $itemGroup;

    public $itemAction;

    public $language;

    public $format;

    public $address;

    function __construct($address, $format, $isActive, $itemAction, $itemGroup, $language)
    {
        $this->address = $address;
        $this->format = $format;
        $this->isActive = $isActive;
        $this->itemAction = $itemAction;
        $this->itemGroup = $itemGroup;
        $this->language = $language;
    }


    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $arr = [];
        foreach ($this as $property => $value) {
            $arr[$property] = $value;
        }
        return $arr;
    }
}