<?php
namespace Lancer\LanceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

class HomeController extends BaseController
{
    public function indexAction(Request $request)
    {
        $result = $this->check();
        if ($result !== true) {
            return $result;
        }
        return $response = $this->render('LancerLanceBundle:Default:home.html.twig', array(
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
                'user' => $this->getUserDataHeader($request->getSession()))
        );
    }
}