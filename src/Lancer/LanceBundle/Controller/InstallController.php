<?php
namespace Lancer\LanceBundle\Controller;

use Lancer\LanceBundle\Config\BigBrother;
use Lancer\LanceBundle\Model\DbConnection;
use Lancer\LanceBundle\Model\Installer;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class InstallController extends BaseController
{
    public function indexAction(Request $request)
    {
        $config = null;
        try{
        $config = BigBrother::getConfig();
        } catch(\Exception $e) {
        }
        if (empty($config)) {
            return $this->render('LancerLanceBundle:Default:install.html.twig', array(
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
                'message' => $this->getMessage($request->getSession())
            ));
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
            DbConnection::getInstance()->getConnection();
            BigBrother::saveConfig();
            $installer = new Installer();
            $installer->run();
        } catch (Exception $e) {
            $request->getSession()->set('message', 'Can\'t connect to database. Please try again.');
        }
        return $this->RedirectToRoute('lancer_lance_install');
    }
}