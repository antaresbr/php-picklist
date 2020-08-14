<?php

use Antares\Picklist\Picklists;

if (!function_exists('ai_picklist_infos')) {
    /**
     * Get package infos.
     *
     * @return array
     */
    function ai_picklist_infos()
    {
        return json_decode(file_get_contents(ai_picklist_path('support/infos.json')));
    }
}

if (!function_exists('ai_picklist_path')) {
    /**
     * Return the path of the resource relative to the package
     *
     * @param string $resource
     * @return string
     */
    function ai_picklist_path($resource = null)
    {
        if (!empty($resource) and substr($resource, 0, 1) != DIRECTORY_SEPARATOR) {
            $resource = DIRECTORY_SEPARATOR . $resource;
        }
        return dirname(__DIR__) . $resource;
    }
}

if (!function_exists('picklists')) {
    /**
     * Picklists helper function.
     * Get specific picklist by passing its id or access the global picklists collection if no id is supplied.
     *
     * @param mixed $id
     * @return \Antares\Picklist\Picklists|\Antares\Picklist\Picklist
     */
    function picklists($id = null)
    {
        return empty($id) ? Picklists::getCollection() : Picklists::get($id);
    }
}
