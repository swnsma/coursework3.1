<?php

namespace UserBundle\Controller;

use Lancer\LanceBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Lancer\LanceBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseController
{
    public function editRoleAction()
    {
        $this->check();
        return $this->render('UserBundle:Default:usereditrole.html.twig');
    }

    public function saveRoleAction(Request $request)
    {
        $data = $request->request->all();
        if ($request->getSession()->get('userId')) {
            $user = new User();
            $user->load($data['user']);
            $user->setRoleId($data['role']);
            $user->save();
        }
        return new Response();
    }
}