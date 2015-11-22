<?php
namespace Lancer\LanceBundle\Controller;
use Lancer\LanceBundle\Config\BigBrother;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class InstallController extends Controller
{
    public function indexAction()
    {
        $config = BigBrother::getConfig();
        if (!empty($config)) {
            return $this->render('LancerLanceBundle:Default:install.html.php', array(
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..')));
        }
    }
}