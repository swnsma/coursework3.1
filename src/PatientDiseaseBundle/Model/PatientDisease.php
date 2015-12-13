<?php
namespace PatientDiseaseBundle\Model;

use DiseaseBundle\Model\Disease;
use Lancer\LanceBundle\Model\AbstractModel;
use Lancer\LanceBundle\Model\User;
use PatientBundle\Model\Patient;

class PatientDisease extends AbstractModel
{
    public function __construct($data = null)
    {
        parent::__construct('patient_disease', 'id');

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

        if($this->getDiseaseId()) {
            $disease = new Disease();
            $disease->load($this->getDiseaseId());
            $this->setTitle($disease->getTitle());
        }
    }
}