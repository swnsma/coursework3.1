<?php
namespace ServiceBundle\Controller;

use Lancer\LanceBundle\Controller\BaseController;
use ServiceBundle\Model\Service;
use Symfony\Component\HttpFoundation\Request;

class ServiceController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $service = new Service();
        $service->load($id);
        if ($service->getId()) {
            return $this->render('ServiceBundle:Default:service.html.twig', array(
                'service' => $service->getData(),
                'user' => $this->getUserDataHeader($request->getSession())));
        }

        return $this->generate404($request->getSession(), 'Service');
    }

    public function newAction(Request $request)
    {
        $this->check();
        return $this->render('ServiceBundle:Default:serviceedit.html.twig', array(
            'user' => $this->getUserDataHeader($request->getSession()),
            'template_type' => 'New'));
    }

    /**
     * Delete service.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $disease = new Service();

        $disease->load($id);
        if ($disease->getId()) {
            $disease->delete();
            $disease->save();
        }

        return $this->redirectToRoute('services_list');
    }

    /**
     * Save changed data.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(Request $request)
    {
        $this->check();
        $data = $request->request->all();
        $disease = new Service();
        if (empty($data['id'])) {
            unset($data['id']);
        } else {
            $disease->load($data['id']);
        }

        $disease->setData($data);
        $disease->save();
        return $this->redirectToRoute('service_homepage', array('id' => $disease->getId()));
    }

    /**
     * Render service edit view.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $service = new Service();
        $service->load($id);

        if ($service->getId()) {
            return $this->render('ServiceBundle:Default:serviceedit.html.twig', array(
                'service' => $service->getData(),
                'user' => $this->getUserDataHeader($request->getSession()),
                'template_type' => 'Edit'));
        }
        return $this->redirectToRoute('service_new');
    }
}