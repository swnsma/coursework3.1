<?php

namespace Lancer\LanceBundle\Controller;

use Lancer\LanceBundle\Model\AbstractModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $a = new AbstractModel('test', 'id');
        $a->load(1);
        $a->setName('Valera');
        $a->delete();
        $a->save();
    }
}
