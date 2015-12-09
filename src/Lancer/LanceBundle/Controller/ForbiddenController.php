<?php
namespace Lancer\LanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ForbiddenController extends BaseController
{
    public function indexAction(Request $request)
    {
        $response = $this->render( 'LancerLanceBundle:Default:forbidden.html.twig', array(
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..'),
                'user' => $this->getUserDataHeader($request->getSession()))
        );

        $response->setStatusCode(403);
        return $response;
    }
}