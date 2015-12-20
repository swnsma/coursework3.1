<?php
namespace PatientDiseaseBundle\Controller;
use Lancer\LanceBundle\Controller\BaseController;
use PatientBundle\Model\Patient;
use PatientDiseaseBundle\Model\PatientDiseaseCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientDiseaseListController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $patient = new Patient();
        $patient->load($id);
        if($patient->getId()){
            return $this->render("PatientDiseaseBundle:Default:patientdiseaselist.html.twig", array(
                'user' => $this->getUserDataHeader($request->getSession()),
                'patient' => $patient->getData(),
                'new_action' => $this->check('patient_disease_new', false)
            ));
        }
        return $this->generate404($request->getSession(), 'Patient\'s Diseases');
    }

    public function loadAction(Request $request)
    {
        $id  = $request->get('id');
        $active = $request->get('active')=="0"?true:false;
        $collection = new PatientDiseaseCollection();
        $data = array();
        $data['items'] = $collection->getPatientItemsData($id, $active);
        $data['total'] = count($data['items']);
        return new Response(json_encode($data));
    }

    public function forUserLoadAction(Request $request)
    {
        $id  = $request->getSession()->get('userId');
        $active = $request->get('active')=="0"?true:false;
        $collection = new PatientDiseaseCollection();
        $data = array();
        $data['items'] = $collection->getUserItemsData($id, $active);
        $data['total'] = count($data['items']);
        return new Response(json_encode($data));
    }
}