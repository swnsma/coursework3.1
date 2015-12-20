<?php
namespace Lancer\LanceBundle\Controller;

use Lancer\LanceBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();

        $user = new User();
        $user->load($request->getSession()->get('userId'));

        if ($user->getId()) {
           return $this->render('LancerLanceBundle:Default:myaccount.html.twig', array(
               'user' => $this->getUserDataHeader($request->getSession()),
               'account' => $user->getData(),
               'rejections' => $this->check('lance_reject_save', false),
               'install' => $this->check('lancer_lance_install', false),
               'acl' => $this->check('lance_acl', false)
           ));
        }

        return $this->generate404($request->getSession(), 'User');
    }

    public function loadmeAction(Request $request)
    {
        $user = new User();
        $user->load($request->getSession()->get('userId'));

        return new Response(json_encode($user->getData()));
    }

    public function saveAction(Request $request)
    {
        $user = new User();
        $user->load($request->getSession()->get('userId'));
        $data = $request->request->all();
        $user->setName($data['name']);
        $user->setSecondName($data['second_name']);
        $user->setPhoto($data['photo']);
        $user->setEmail($data['email']);
        $user->save();

        return new Response();
    }

    public function routeAction(Request $request)
    {
        $id = $request->getSession()->get('userId');
        $user = new User();
        $user->load($id);

        if (!$user->getPatientId()) {
            $user->transfer();
        }

        return $this->redirectToRoute('patient_homepage', array('id' => $user->getPatientId()));
    }
}