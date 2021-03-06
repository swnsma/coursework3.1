<?php

namespace ServiceBundle\Model;

use Lancer\LanceBundle\Model\AbstractCollection;
use Symfony\Component\EventDispatcher\Tests\Service;

class ServiceCollection extends AbstractCollection
{
    public function __construct()
    {
        parent::__construct('service');
    }

    public function getAllItems()
    {
        if (empty($this->_items)) {
            $data = $this->getAllItemsData();
            foreach ($data as $item) {
                $this->_items[] = new Service($item);
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