<?php
namespace PatientBundle\Controller;

use Lancer\LanceBundle\Controller\BaseController;
use PatientBundle\Model\Patient;
use PatientDiseaseBundle\Model\PatientDiseaseCollection;
use Symfony\Component\HttpFoundation\Request;

class PatientController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $patient = new Patient();
        $patient->load($id);
        $diseases = new PatientDiseaseCollection();
        if($patient->getId()){
            return $this->render('PatientBundle:Default:patient.html.twig', array(
                'patient' => $patient->getData(),
                'user' => $this->getUserDataHeader($request->getSession()),
                'edit_action' => $this->check('patient_edit', false),
                'delete_action' => $this->check('patient_delete', false),
                'patient_disease_new' => $this->check('patient_disease_new', false),
                'diseases' => $diseases->getPatientItemsData($patient->getId(), true)
                ));
        }

        return $this->generate404($request->getSession(), 'Patient');
    }

    public function newAction(Request $request)
    {
        $this->check();
        return $this->render('PatientBundle:Default:patientedit.html.twig', array(
            'user' => $this->getUserDataHeader($request->getSession()),
            'template_type' => 'New'));
    }

    public function saveAction(Request $request)
    {
        $this->check();
        $data = $request->request->all();
        $disease = new Patient();
        if (empty($data['id'])) {
            unset($data['id']);
        } else {
            $disease->load($data['id']);
        }

        $disease->setData($data);
        $disease->save();
        return $this->redirectToRoute('patient_homepage', array('id' => $disease->getId()));
    }

    public function editAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $patient = new Patient();
        $patient->load($id);

        if ($patient->getId()) {
            return $this->render('PatientBundle:Default:patientedit.html.twig', array(
                'patient' => $patient->getData(),
                'user' => $this->getUserDataHeader($request->getSession()),
                'template_type' => 'Edit'));
        }
        return $this->redirectToRoute('patient_new');

    }

    public function deleteAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $disease = new Patient();

        $disease->load($id);
        if ($disease->getId()) {
            $disease->delete();
            $disease->save();
        }

        return $this->redirectToRoute('patients_list');
    }
}