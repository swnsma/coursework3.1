<?php

namespace ServiceBundle\Controller;

use Lancer\LanceBundle\Controller\BaseController;
use ServiceBundle\Model\ServiceCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ServiceListController extends BaseController
{
    /**
     * Render services list view.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $this->check();
        return $this->render('ServiceBundle:Default:servicelist.html.twig', array(
            'user' => $this->getUserDataHeader($request->getSession())));
    }

    /**
     * Load services collection and return as json.
     *
     * @return Response
     */
    public function loadAction()
    {
        $new = new ServiceCollection();
        $data['items'] = $new->getAllItemsData();
        return new Response(json_encode($data));
    }
}