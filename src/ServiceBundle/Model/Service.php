<?php

namespace ServiceBundle\Model;

use Lancer\LanceBundle\Model\AbstractModel;

class Service extends AbstractModel
{
    public function __construct($data = null)
    {
        parent::__construct('service', 'id');
        if (!is_null($data)) {
            $this->setData($data);
            $this->setOriginData($data);
        }
    }
}