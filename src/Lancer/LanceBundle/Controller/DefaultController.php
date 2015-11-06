<?php

namespace Lancer\LanceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        $model = new \Lancer\LanceBundle\Model\MagicObject();
        $model->setValue('a');
        $a = $model->getValue();
        echo $a;
    }
}
