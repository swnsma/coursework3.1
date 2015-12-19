<?php
namespace PatientServiceBundle\Model;

use Lancer\LanceBundle\Model\AbstractModel;
use Lancer\LanceBundle\Model\User;
use PatientBundle\Model\Patient;
use ServiceBundle\Model\Service;

class PatientService extends AbstractModel
{
    public function __construct($data = null)
    {
        parent::__construct('patient_service', 'id');
        if (!is_null($data)) {
            $this->setData($data);
            $this->setOriginData($data);
        }
    }

    public function fullLoad() {
        if ($this->getUserId()) {
            $user = new User();
            $user->load($this->getUserId());
            $this->setUserName($user->getName() . ' ' . $user->getSecondName());
        }

        if ($this->getPatientId()) {
            $patient = new Patient();
            $patient->load($this->getPatientId());
            $this->setPatientName($patient->getName() . ' ' . $patient->getSecondName());
        }

        if($this->getServiceId()) {
            $service  = new Service();
            $service->load($this->getServiceId());
            $this->setTitle($service->getTitle());
            $this->setPrice($service->getPrice());
        }
    }
}