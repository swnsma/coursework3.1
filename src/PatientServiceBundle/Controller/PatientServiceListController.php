<?php
namespace PatientServiceBundle\Controller;

use Lancer\LanceBundle\Controller\BaseController;
use PatientBundle\Model\Patient;
use PatientServiceBundle\Model\PatientServiceCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientServiceListController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $patient = new Patient();
        $patient->load($id);
        if($patient->getId()){
            return $this->render("PatientServiceBundle:Default:patientservicelist.html.twig", array(
                'user' => $this->getUserDataHeader($request->getSession()),
                'patient' => $patient->getData(),
                'new_action' => $this->check('patient_service_new', false)
            ));
        }
        return $this->generate404($request->getSession(), 'Patient\'s Services');
    }

    public function loadAction(Request $request)
    {
        $id  = $request->get('id');
        $active = $request->get('active')=="0"?false:true;
        $collection = new PatientServiceCollection();
        $data = array();
        $data['items'] = $collection->getPatientItemsData($id, $active);
        $data['total'] = count($data['items']);
        return new Response(json_encode($data));
    }

    public  function forUserLoadAction(Request $request)
    {
        $id  = $request->getSession()->get('userId');
        $active = $request->get('active')=="0"?false:true;
        $collection = new PatientServiceCollection();
        $data = array();
        $data['items'] = $collection->getUserItemsData($id, $active);
        $data['total'] = count($data['items']);
        return new Response(json_encode($data));
    }
}