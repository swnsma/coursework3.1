<?php
namespace PatientBundle\Controller;

use Lancer\LanceBundle\Controller\BaseController;
use Lancer\LanceBundle\Model\Acl;
use PatientBundle\Model\PatientCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PatientListController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();
        return $this->render('PatientBundle:Default:patientlist.html.twig', array(
            'user' => $this->getUserDataHeader($request->getSession()),
            'new_action' => $this->check('patient_new', false)));
    }

    public function loadAction()
    {
        $new =  new PatientCollection();
        $data['items'] = $new->getAllItemsData();
        return new Response(json_encode($data));
    }
}