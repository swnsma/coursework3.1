<?php

namespace DiseaseBundle\Controller;

use DiseaseBundle\Model\DiseaseCollection;
use Lancer\LanceBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DiseaseListController extends BaseController
{
    /**
     * Render disease list view.
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $this->check();
        return $this->render('DiseaseBundle:Default:diseaselist.html.twig', array(
            'user' => $this->getUserDataHeader($request->getSession())));
    }

    /**
     * Load diseases data and return as json.
     *
     * @return Response
     */
    public function loadAction()
    {
        $new =  new DiseaseCollection();
        $data['items'] = $new->getAllItemsData();
        return new Response(json_encode($data));
    }
}