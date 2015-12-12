<?php

namespace DiseaseBundle\Controller;

use DiseaseBundle\Model\DiseaseCollection;
use Lancer\LanceBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DiseaseListController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();
        return $this->render('DiseaseBundle:Default:diseaselist.html.twig', array(
            'user' => $this->getUserDataHeader($request->getSession())));
    }

    public function loadAction()
    {
        $new =  new DiseaseCollection();
        $data['items'] = $new->getAllItemsData();
        return new Response(json_encode($data));
    }
}