<?php

namespace Antares\Picklist;

use Antares\Foundation\Arr;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

class Picklist implements Countable, IteratorAggregate, JsonSerializable, Traversable
{
    /**
     * The full Id of this object
     *
     * @var string
     */
    protected $id;

    /**
     * The file name portion of the id
     *
     * @var string
     */
    protected $idFile;

    /**
     * The index portion of the id
     *
     * @var string
     */
    protected $idIndex;

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

        if (empty($id)) {
            throw PicklistException::forNotDefinedId();
        }

        if (is_null($data) and !empty($id)) {
            $data = require $this->getPicklistFile($id);

            if ($id != $this->idFile) {
                if (Arr::has($data, $this->idIndex)) {
                    $data = Arr::get($data, $this->idIndex);
                } else {
                    throw PicklistException::forNotFoundId($id);
                }
            }
        }

        $this->setData($data);
    }

    /**
     * Get picklist folder
     *
     * @return string
     */
    protected function getDataFolder()
    {
        $folder = defined('PICKLIST_DATA') ? constant('PICKLIST_DATA') : null;
        if (empty($folder)) {
            $folder = is_string(ai_foundation_env('PICKLIST_DATA')) ? ai_foundation_env('PICKLIST_DATA') : null;
        }
        if (empty($folder) and function_exists('config')) {
            $folder = is_string(ai_foundation_config('picklist.PICKLIST_DATA')) ? ai_foundation_config('picklist.PICKLIST_DATA') : null;
        }
        return $folder;
    }

    /**
     * Get picklist file name
     *
     * @param string $id
     * @return string
     */
    protected function getPicklistFile($id)
    {
        $this->idFile = '';
        $this->idIndex = '';

        $folder = $this->getDataFolder();

        $idFile = explode('.', $id);
        $idIndex = [];

        while (count($idFile) > 0) {
            $tempId = implode('.', $idFile);
            $file = rtrim($folder, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . str_replace('.', DIRECTORY_SEPARATOR, $tempId) . '.php';

            if (is_file($file)) {
                $this->idFile = $tempId;
                $this->idIndex = implode('.', array_reverse($idIndex));
                return $file;
            }
            array_push($idIndex, array_pop($idFile));
        }

        throw PicklistException::forNotFoundId($id);
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
     * @return int
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Get itarator
     *
     * @return ArrayItarator
     */
    public function getIterator(): ArrayIterator
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
    public function jsonSerialize(): array
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
     * Get an item based on key
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function getItem($key)
    {
        foreach ($this->data as $item) {
            if ($item['key'] == $key) {
                return $item;
            }
        }

        return null;
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
