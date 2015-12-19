<?php
namespace PatientServiceBundle\Controller;

use Lancer\LanceBundle\Controller\BaseController;
use PatientBundle\Model\Patient;
use PatientBundle\Model\PatientCollection;
use PatientServiceBundle\Model\PatientService;
use ServiceBundle\Model\ServiceCollection;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Model\UserCollection;

class PatientServiceController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $service = new PatientService();
        $service->load($id);

        if($service->getId()){
            $service->fullLoad();
            return $this->render('PatientServiceBundle:Default:patientservice.html.twig', array(
                'user' => $this->getUserDataHeader($request->getSession()),
                'data' => $service->getData(),
                'edit_action' => $this->check('patient_service_edit', false),
                'delete_action' => $this->check('patient_service_delete', false)
            ));
        }

        return $this->generate404($request->getSession(), 'Current service');
    }

    public function saveAction(Request $request)
    {
        $this->check();
        $data = $request->request->all();

        $service = new PatientService();
        $service->setData($data);
        $service->save();

        return $this->redirectToRoute('patient_service_homepage', array('id' => $service->getId()));
    }

    public function deleteAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $service = new PatientService();
        $service->load($id);
        $p = $service->getPatientId();
        if ($service->getId()) {
            $service->delete();
            $service->save();
        }

        return $this->redirectToRoute('patient_homepage', array('id' => $p));

    }

    public function editAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $patient = new PatientService();
        $patient->load($id);
        if (!$patient->getId()) {
            return $this->redirectToRoute('patient_service_new');
        }

        $patient->fullLoad();
        $services = new ServiceCollection();
        $users = new UserCollection();

        $data = array();
        $data['item'] = $patient->getData();
        $data['users'] = $users->getAllItemsData();
        $data['services'] = $services->getAllItemsData();

        return $this->render('PatientServiceBundle:Default:patientserviceedit.html.twig', array(
            'data' => $data,
            'user' => $this->getUserDataHeader($request->getSession()),
            'template_type' => 'Edit'));
    }

    public function newAction(Request $request)
    {
        $this->check();

        $id = $request->get('id');
        $services = new ServiceCollection();
        $users = new UserCollection();
        $patient = new Patient();
        $patient->load($id);
        $data = array();
        $data['services'] = $services->getAllItemsData();
        $data['users'] = $users->getAllItemsData();
        if ($patient->getId()){
            $data['patients'] = $patient->getData();
        } else {
            $patient = new PatientCollection();
            $data['patients'] = $patient->getAllItemsData();
        }

        return $this->render('PatientServiceBundle:Default:patientserviceedit.html.twig', array(
            'data' => $data,
            'user' => $this->getUserDataHeader($request->getSession()),
            'template_type' => 'New'));
    }
}