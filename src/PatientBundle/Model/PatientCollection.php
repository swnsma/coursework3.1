<?php
namespace PatientBundle\Model;

use Lancer\LanceBundle\Model\AbstractCollection;

class PatientCollection extends AbstractCollection
{
    public function __construct()
    {
        parent::__construct('patient');
    }

    public function getAllItems()
    {
        if (empty($this->_items)) {
            $data = $this->getAllItemsData();
            foreach ($data as $item) {
                $this->_items[] = new Patient($item);
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