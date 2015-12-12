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

    public function deleteAction(Request $request)
    {
        $this->check();
        $id = $request->get('id');
        $disease = new Service();

        $disease->load($id);
        if($disease->getId()){
            $disease->delete();
            $disease->save();
        }

        return $this->redirectToRoute('services_list');
    }

    public function saveAction(Request $request)
    {
        $this->check();
        $data = $request->request->all();

        if (empty($data['id'])) {
            unset($data['id']);
        }
        $disease = new Service();
        $disease->setData($data);
        $disease->save();
        return $this->redirectToRoute('service_homepage', array('id' => $disease->getId()));
    }

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