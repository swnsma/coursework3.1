<?php

namespace Lancer\LanceBundle\Controller;

use Lancer\LanceBundle\Config\BigBrother;
use Lancer\LanceBundle\Model\DbConnection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $a = DbConnection::getConnection();
    }
}
