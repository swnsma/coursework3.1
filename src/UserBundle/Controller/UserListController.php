<?php

namespace UserBundle\Controller;

use Lancer\LanceBundle\Controller\BaseController;
use Lancer\LanceBundle\Model\Acl;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserBundle\Model\UserCollection;

class UserListController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();
        return $this->render('UserBundle:Default:userlist.html.twig',
            array('user' => $this->getUserDataHeader($request->getSession())));
    }

    public function loadAction()
    {
        $new =  new UserCollection();
        $data['items'] = $new->getAllItemsData();
        $data['roles'] = Acl::getRoles();
        return new Response(json_encode($data));
    }


}