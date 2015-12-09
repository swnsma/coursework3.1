<?php
namespace Lancer\LanceBundle\Controller;
use Lancer\LanceBundle\Config\BigBrother;
use Lancer\LanceBundle\Model\Installer;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class InstallController extends BaseController
{
    public function indexAction()
    {

        $config = BigBrother::getConfig();
        if (empty($config)) {
            return $this->render('LancerLanceBundle:Default:install.html.twig', array(
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..')));
        }
        $this->check();
        $installer = new Installer();
        $installer->run();
        return $this->RedirectToRoute('home');
    }

    public function saveAction(Request $request)
    {
        try {
            BigBrother::updateConfig($request->request->all());
            BigBrother::saveConfig();
        } catch (Exception $e) {
        }
        return $this->RedirectToRoute('LancerLanceBundle:Install:index');
    }
}