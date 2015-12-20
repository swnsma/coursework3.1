<?php
namespace ReportBundle\Controller;

use Lancer\LanceBundle\Controller\BaseController;
use Symfony\Component\HttpFoundation\Request;
use ReportBundle\Model\Report;

class ReportController extends BaseController
{
    public function indexAction(Request $request)
    {
        $this->check();
        return $this->render('ReportBundle:Default:reports.html.twig', array(
            'user' => $this->getUserDataHeader($request->getSession()),
            'services' => $this->check('report_services', false),
            'diseases' => $this->check('report_diseases', false)
        ));
    }

    public function diseasesAction(Request $request)
    {
        $this->check();
        $data = Report::diseaseReport();

        return $this->render('ReportBundle:Default:diseases.html.twig', array(
            'user' => $this->getUserDataHeader($request->getSession()),
            'all' => $data['all'],
            'month' => $data['month']
        ));
    }

    public function servicesAction(Request $request)
    {
        $this->check();
        $data = Report::servicesReport();

        return $this->render('ReportBundle:Default:services.html.twig', array(
            'user' => $this->getUserDataHeader($request->getSession()),
            'profit' => $data['profit'],
            'services' => $data['services']
        ));
    }
}