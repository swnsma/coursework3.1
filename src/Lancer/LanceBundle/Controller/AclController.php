<?php

namespace Lancer\LanceBundle\Controller;

use Lancer\LanceBundle\Model\Acl;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AclController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();

        return $this->render('LancerLanceBundle:Default:acl.html.twig', array(
            'user' => $this->getUserDataHeader($request->getSession())));
    }

    public function loadAction()
    {
        $data = array();
        $data['list'] = Acl::getAclList();
        $data['routes'] = Acl::getRoutes();
        return new Response(json_encode($data));
    }

    public function addRoleAction(Request $request)
    {
        $data = $request->request->all();
        $id = Acl::saveRole($data);

        return new Response(json_encode(array('id' => $id)));
    }

    public function saveAccessAction(Request $request)
    {
        $data = $request->request->all();
        Acl::saveAccess($data);

        return new Response();
    }
}