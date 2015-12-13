<?php

namespace DiseaseBundle\Model;

use Lancer\LanceBundle\Model\AbstractCollection;

class DiseaseCollection extends AbstractCollection
{
    public function __construct()
    {
        parent::__construct('disease');
    }

    public function getAllItems()
    {
        if (empty($this->_items)) {
            $data = $this->getAllItemsData();
            foreach ($data as $item) {
                $this->_items[] = new Disease($item);
            }
        }

        return $this->_items;
    }

    public function getAllItemsData()
    {
        if (empty($this->_itemsData)) {
            $this->_load();
        }

        return $this->_itemsData;
    }


}