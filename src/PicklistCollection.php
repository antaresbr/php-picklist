<?php

namespace Antares\Picklist;

use Antares\Support\AssociativeCollection;

class PicklistCollection extends AssociativeCollection
{
    /**
     * Create a new instance of this object.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct(Picklist::class);
    }

    /**
     * Add a picklist
     *
     * @param  mixed $id
     * @param  Picklist $item
     * @return boolean
     */
    public function add($id, $item)
    {
        if (empty($id) and !empty($item) and ($item instanceof Picklist)) {
            $id = $item->getId();
        }
        if (empty($item)) {
            $item = new Picklist($id);
        }
        return parent::add($id, $item);
    }

    /**
     * Get a picklist
     *
     * @param  mixed $id
     * @return Picklist
     */
    public function get($id)
    {
        if (!$this->hasKey($id)) {
            $this->add($id, null);
        }
        return parent::getItem($id);
    }
}
