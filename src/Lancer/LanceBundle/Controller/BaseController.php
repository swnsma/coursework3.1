<?php
namespace Lancer\LanceBundle\Controller;

use Lancer\LanceBundle\Model\Acl;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class BaseController extends Controller
{
    public function check()
    {
        $request = $this->container->get('request');

        $session = $request->getSession();
        if (!$session->has('userId')) {
            header('Location: ' . $this->generateUrl('lance_authorization'));
            exit;
        }
        $userId = $session->get('userId');
        $route = $request->get('_route');
        if (!$session->has($route)) {
            $acl = new Acl();
            $session->set($route, $acl->check($route, $userId));
        }

        if (!$session->get($route)) {
            header('Location: ' . $this->generateUrl('lance_403'));
            exit;
        }
        return true;
    }

    public function getMessage(Session $session)
    {
        $message = null;
        if ($session->has('message')) {
            $message = $session->get('message');
            $session->remove('message');
        }

        return $message;
    }

    public function getUserDataHeader(Session $session)
    {
        if (!$session->has('userId')) {
            return null;
        }
        return ['name' => $session->get('userName'), 'secondName' => $session->get('userSName')];
    }
}