<?php

namespace DiseaseBundle\Controller;

use DiseaseBundle\Model\Disease;
use Lancer\LanceBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class DiseaseController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $disease = new Disease();
        $disease->load($id);
        if ($disease->getId()) {
           return $this->render('DiseaseBundle:Default:disease.html.twig', array(
               'disease' => $disease->getData(),
               'user' => $this->getUserDataHeader($request->getSession())));
        }
        return $this->generate404($request->getSession(), 'Disease');
    }

    public function editAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $disease = new Disease();
        $disease->load($id);

        if ($disease->getId()) {
            return $this->render('DiseaseBundle:Default:diseaseedit.html.twig', array(
                'disease' => $disease->getData(),
                'user' => $this->getUserDataHeader($request->getSession()),
                'template_type' => 'Edit'));
        }
        return $this->redirectToRoute('disease_new');
    }

    public function newAction(Request $request)
    {
        $this->check();
        return $this->render('DiseaseBundle:Default:diseaseedit.html.twig', array(
            'user' => $this->getUserDataHeader($request->getSession()),
            'template_type' => 'New'));
    }

    public function deleteAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $disease = new Disease();

        $disease->load($id);
        if($disease->getId()){
            $disease->delete();
            $disease->save();
        }

        return $this->redirectToRoute('disease_list');
    }
    public function saveAction(Request $request)
    {
        $this->check();
        $data = $request->request->all();

        if (empty($data['id'])) {
            unset($data['id']);
        }
        $disease = new Disease();
        $disease->setData($data);
        $disease->save();
        return $this->redirectToRoute('disease_homepage', array('id' => $disease->getId()));
    }
}