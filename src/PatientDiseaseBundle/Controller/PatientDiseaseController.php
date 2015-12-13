<?php
namespace PatientDiseaseBundle\Controller;
use DiseaseBundle\Model\DiseaseCollection;
use Lancer\LanceBundle\Controller\BaseController;
use PatientBundle\Model\Patient;
use PatientBundle\Model\PatientCollection;
use PatientDiseaseBundle\Model\PatientDisease;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Model\UserCollection;

class PatientDiseaseController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $data = new PatientDisease();
        $data->load($id);
        if ($data->getId()) {
            $data->fullLoad();
            return $this->render('PatientDiseaseBundle:Default:patientdisease.html.twig', array(
                'data' => $data->getData(),
                'user' => $this->getUserDataHeader($request->getSession()),
                'edit_action' => $this->check('patient_disease_edit', false),
                'delete_action' => $this->check('patient_disease_delete', false)
            ));
        }
        return $this->generate404($request->getSession(), 'Patient with current disease');
    }

    public function editAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $patient = new PatientDisease();
        $patient->load($id);

        if (!$patient->getId()) {
            return $this->redirectToRoute('patient_new');
        }

        $patient->fullLoad();
        $diseases = new DiseaseCollection();
        $users = new UserCollection();

        $data = array();
        $data['item'] = $patient->getData();
        $data['diseases'] = $diseases->getAllItemsData();
        $data['users'] = $users->getAllItemsData();

        return $this->render('PatientDiseaseBundle:Default:patientdiseaseedit.html.twig', array(
            'data' => $data,
            'user' => $this->getUserDataHeader($request->getSession()),
            'template_type' => 'Edit'));


    }

    public function newAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $diseases = new DiseaseCollection();
        $users = new UserCollection();
        $patient = new Patient();
        $patient->load($id);
        $data = array();
        $data['diseases'] = $diseases->getAllItemsData();
        $data['users'] = $users->getAllItemsData();
        if ($patient->getId()){
            $data['patients'] = $patient->getData();
        } else {
            $patient = new PatientCollection();
            $data['patients'] = $patient->getAllItemsData();
        }

        return $this->render('PatientDiseaseBundle:Default:patientdiseaseedit.html.twig', array(
            'data' => $data,
            'user' => $this->getUserDataHeader($request->getSession()),
            'template_type' => 'New'));
    }

    public function saveAction(Request $request)
    {
        $this->check();
        $data = $request->request->all();
        $patient = new PatientDisease();
        $patient->setData($data);
        $patient->save();
        return $this->redirectToRoute('patient_disease_homepage', ['id'=>$patient->getId()]);
    }

    public function deleteAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $patient = new PatientDisease();
        $patient->load($id);
        $p = $patient->getPatientId();
        if ($patient->getId()) {
            $patient->delete();
            $patient->save();
        }
        return $this->redirectToRoute('patient_homepage', ['id'=>$p->getId()]);
    }
}