<?php

namespace Antares\Picklist;

use Exception;

class PicklistException extends Exception
{
    /**
     * Create a new exception for not defined data directory
     *
     * @param  mixed  $item
     * @return static
     */
    public static function forNotDefinedDataDir()
    {
        return new static('Picklist data directory not defined.');
    }

    /**
     * Create a new exception for not defined id
     *
     * @return static
     */
    public static function forNotDefinedId()
    {
        return new static("Picklist ID not defined.\n");
    }

    /**
     * Create a new exception for a not found id
     *
     * @param  string  $id
     * @return static
     */
    public static function forNotFoundId($id)
    {
        return new static("Picklist ID not found: '{$id}'\n");
    }

    /**
     * Create a new exception for a item that is not an array
     *
     * @param  mixed  $item
     * @return static
     */
    public static function forItemIsNotArray($item)
    {
        return new static("Picklist item is not array:\n" . print_r($item, true));
    }

    /**
     * Create a new exception for a item property not found
     *
     * @param  array  $item
     * @param  string  $property
     * @return static
     */
    public static function forItemPropertyNotFound($item, $property)
    {
        return new static("Property '{$property}' not found in picklist item:'\n" . print_r($item, true));
    }

    /**
     * Create a new exception for an already defined item key
     *
     * @param  string  $id
     * @param  string  $key
     * @return static
     */
    public static function forAlreadyDefinedItemKey($key, $id)
    {
        return new static("Item key '{$key}' already defined for picklist '{$id}'.\n");
    }

    /**
     * Create a new exception for an already defined item label
     *
     * @param  string  $id
     * @param  string  $label
     * @return static
     */
    public static function forAlreadyDefinedItemLabel($label, $id)
    {
        return new static("Item label '{$label}' already defined for picklist '{$id}.\n");
    }
}
