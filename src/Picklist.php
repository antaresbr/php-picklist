<?php

namespace Antares\Picklist;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;
use JsonSerializable;

class Picklist implements Countable, IteratorAggregate, JsonSerializable, Traversable
{
    /**
     * The Id of this object
     *
     * @var array
     */
    protected $id;

    /**
     * The array with the items
     *
     * @var array
     */
    protected $data = null;

    /**
     * Create a new instance of this object.
     *
     * @param  string  $id
     * @param  array  $data
     * @return void
     */
    public function __construct(String $id, array $data = null)
    {
        $this->id = $id;
        
        if (empty($this->id)) {
            throw PicklistException::forNotDefinedId();
        }
        
        if (is_null($data) and !empty($id)) {
            $picklistFile = rtrim(PICKLIST_DATA, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . "{$id}.php";
            if (is_file($picklistFile)) {
                $data = require "{$picklistFile}";
            } else {
                throw PicklistException::forNotFoundId($id);
            }
        }

        $this->setData($data);
    }

    /**
     * Check item property existence.
     *
     * @param  array  $item
     * @param  string  $property
     * @return void
     */
    protected function checkItemProperty($item, $property)
    {
        if (!array_key_exists($property, $item)) {
            throw PicklistException::forItemPropertyNotFound($item, $property);
        }
    }

    /**
     * Set up data property
     *
     * @param  array  $data
     * @return boolean
     */
    protected function setData($data)
    {
        $this->clear();

        if (!is_array($data)) {
            $data = [];
        }

        foreach ($data as $idx => $item) {
            if (!is_array($item)) {
                throw PicklistException::forItemIsNotArray($item);
            }

            $this->checkItemProperty($item, 'key');
            $this->checkItemProperty($item, 'label');

            if ($this->hasKey($item['key'])) {
                throw PicklistException::forAlreadyDefinedItemKey($item['key'], $this->id);
            }
            if ($this->getKey($item['label']) !== false) {
                throw PicklistException::forAlreadyDefinedItemLabel($item['label'], $this->id);
            }

            array_push($this->data, $item);
        }
    }

    /**
     * Clear data property
     *
     * @return void
     */
    public function clear()
    {
        $this->data = [];
    }

    /**
     * Get items count
     *
     * @return integer
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Get itarator
     *
     * @return ArrayItarator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Get items data itself
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Get items data itself for serialization
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * Check if this picklist is empty
     *
     * @return string
     */
    public function isEmpty()
    {
        return empty($this->data);
    }

    /**
     * Get this picklist data
     *
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get this picklist id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Check is a key exists
     *
     * @param  mixed  $key
     * @return boolean
     */
    public function hasKey($key)
    {
        foreach ($this->data as $item) {
            if ($item['key'] == $key) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get item key from value of given field. Default search field is 'label'.
     *
     * @param  mixed  $search
     * @param  string  $field
     * @return mixed
     */
    public function getKey($search, $field = 'label')
    {
        $type = gettype($search);

        foreach ($this->data as $item) {
            if ($field = 'label' or $type == 'string') {
                if (mb_strtolower($search) == mb_strtolower($item[$field])) {
                    return $item['key'];
                }
            } else {
                if ($search == $item[$field]) {
                    return $item['key'];
                }
            }
        }

        return false;
    }

    /**
     * Get item label from key
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function getLabel($key)
    {
        foreach ($this->data as $item) {
            if ($item['key'] == $key) {
                return $item['label'];
            }
        }

        return false;
    }

    /**
     * Get min key value
     *
     * @return mixed
     */
    public function getMinKey()
    {
        $value = null;
        foreach ($this->data as $item) {
            if ($value === null or $item['key'] < $value) {
                $value = $item['key'];
            }
        }
        return $value;
    }

    /**
     * Get max key value
     *
     * @return mixed
     */
    public function getMaxKey()
    {
        $value = null;
        foreach ($this->data as $item) {
            if ($value === null or $item['key'] > $value) {
                $value = $item['key'];
            }
        }
        return $value;
    }
}