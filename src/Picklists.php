<?php

namespace Antares\Picklist;

class Picklists
{
    /**
     * PicklistCollection glocal instance
     *
     * @var [type]
     */
    private static $collection;

    /**
     * Get glocal picklist collection instance
     *
     * @return PickListCollection
     */
    public static function getCollection()
    {
        if (is_null(static::$collection)) {
            static::$collection = new PickListCollection();
        }

        return static::$collection;
    }

    /**
     * Add a picklist
     *
     * @param  mixed $id
     * @param  Picklist $item
     * @return boolean
     */
    public static function add($id, $item)
    {
        return static::getCollection()->add($id, $item);
    }

    /**
     * Get a picklist
     *
     * @param  mixed $id
     * @return Picklist
     */
    public static function get($id)
    {
        return static::getCollection()->get($id);
    }

    /**
     * Delete a picklist
     *
     * @param  mixed $id
     * @return boolean
     */
    public static function delete($id)
    {
        return static::getCollection()->delete($id);
    }

    /**
     * Delete a picklist, if it exists
     *
     * @param  mixed $id
     * @return boolean
     */
    public static function deleteIfExists($id)
    {
        return static::getCollection()->deleteIfExists($id);
    }
}