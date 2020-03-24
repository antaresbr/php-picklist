<?php

use Antares\Picklist\Picklists;

if (!function_exists('picklists')) {
    function picklists($id = null)
    {
        return empty($id) ? Picklists::getCollection() : Picklists::get($id);
    }
}
