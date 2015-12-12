<?php
namespace DiseaseBundle\Model;

use Lancer\LanceBundle\Model\AbstractModel;

class Disease extends AbstractModel
{
    public function __construct($data = null)
    {
        parent::__construct('disease', 'id');
        if (!is_null($data)) {
            $this->setData($data);
            $this->setOriginData($data);
        }
    }
}