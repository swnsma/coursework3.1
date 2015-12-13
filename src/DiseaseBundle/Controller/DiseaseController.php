<?php

namespace DiseaseBundle\Controller;

use DiseaseBundle\Model\Disease;
use Lancer\LanceBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;

class DiseaseController extends BaseController
{
    /**
     * Render disease view.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $disease = new Disease();
        $disease->load($id);
        if ($disease->getId()) {
            return $this->render('DiseaseBundle:Default:disease.html.twig', array(
                'disease' => $disease->getData(),
                'user' => $this->getUserDataHeader($request->getSession()),
                'edit_action' => $this->check('disease_edit', false),
                'delete_action' => $this->check('disease_delete', false)));
        }
        return $this->generate404($request->getSession(), 'Disease');
    }

    /**
     * Render disease edit view.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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

    /**
     * Render disease edit view with no params.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $this->check();
        return $this->render('DiseaseBundle:Default:diseaseedit.html.twig', array(
            'user' => $this->getUserDataHeader($request->getSession()),
            'template_type' => 'New'));
    }

    /**
     * Remove disease.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $disease = new Disease();

        $disease->load($id);
        if ($disease->getId()) {
            $disease->delete();
            $disease->save();
        }

        return $this->redirectToRoute('disease_list');
    }

    /**
     * Save data.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(Request $request)
    {
        $this->check();
        $data = $request->request->all();
        $disease = new Disease();
        if (empty($data['id'])) {
            unset($data['id']);
        } else {
            $disease->load($data['id']);
        }

        $disease->setData($data);
        $disease->save();
        return $this->redirectToRoute('disease_homepage', array('id' => $disease->getId()));
    }
}