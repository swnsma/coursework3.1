<?php
namespace PatientBundle\Model;

use Lancer\LanceBundle\Model\AbstractModel;

class Patient extends AbstractModel
{
    public function __construct($data = null)
    {
        parent::__construct('patient', 'id');
        if (!is_null($data)) {
            $this->setData($data);
            $this->setOriginData($data);
        }
    }
}