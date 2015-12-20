<?php
namespace Lancer\LanceBundle\Controller;

use Lancer\LanceBundle\Model\User;
use Symfony\Component\HttpFoundation\Request;

class AuthorizationController extends BaseController
{
    /**
     * Open login form.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        if (!$request->getSession()->has('userId')) {
            return $this->render('LancerLanceBundle:Default:login.html.twig', array(
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
                'message' => $this->getMessage($request->getSession())));
        }
        return $this->redirectToRoute('home');
    }

    /**
     * Open new account form.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newaccountAction(Request $request)
    {
        $session = $request->getSession();
        if (!$session->has('userId')) {

            return $this->render('LancerLanceBundle:Default:newaccount.html.twig', array(
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
                'message' => $this->getMessage($request->getSession())));
        }
        return $this->redirectToRoute('home');
    }

    /**
     * Create account for new user.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registrationAction(Request $request)
    {
        $data = $request->request->all();
        $data['password'] = md5($data['password']);
        $user = new User();
        $user->load($data['email'], 'email');
        if (!$user->getId()) {
            $user->setData($data);
            $user->setRoleId('2');
            $user->save();

        } else {
            $request->getSession()->set('message', 'User with current email already exists');
            return $this->redirectToRoute('lance_newaccount');
        }
        return $this->redirectToRoute('lance_authorization');
    }

    /**
     * Try user to log in.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction(Request $request)
    {
        $data = $request->request->all();
        $user = new User();
        $user->load($data['email'], 'email');
        if (!empty($user->getId()) && $user->getPassword() == md5($data['pass'])) {
            $request->getSession()->set('userId', $user->getId());
            $request->getSession()->set('userName', $user->getName());
            $request->getSession()->set('userSName', $user->getSecondName());
            $request->getSession()->set('userRole', $user->getRoleId());
            return $this->redirectToRoute('home');

        }
        $request->getSession()->set('message', 'User with current login and password not found.');
        return $this->redirectToRoute('lance_authorization');
    }

    /**
     * Send 'remind password' email.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remindAction(Request $request)
    {
        if (!$request->getSession()->has('userId')) {
            $data = $request->request->all();
            $user = new User();
            $user->load($data['email'], 'email');
            if ($user->getId()) {
                $message = \Swift_Message::newInstance()
                    ->setSubject('Forget Password')
                    ->setFrom('swnsma@gmail.com')
                    ->setTo($user->getEmail())
                    ->setBody(
                        $this->renderView(
                            'LancerLanceBundle:Emails:forgetpassword.html.twig',
                            array('name' => $user->getName(), 'hash' => $user->getSecretHash())
                        ),
                        'text/html'
                    );
                $this->get('mailer')->send($message);
                $request->getSession()->set('message', 'Email with instruction has been send.');
            }
        }
        return $this->redirectToRoute('lance_authorization');
    }

    /**
     * Generate 'reset password' view, if came with correct hash.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function resetAction(Request $request)
    {
        $hash = $request->get('hash');
        $user = new User();
        $user->loadByHash($hash);
        if ($user->getId()) {
            return $this->render('LancerLanceBundle:Default:reset.html.twig', array(
                'message' => $this->getMessage($request->getSession()),
                'hash' => $hash
            ));
        }

        return $this->redirectToRoute('home');
    }

    /**
     * Save reset password.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveResetAction(Request $request)
    {
        $data = $request->request->all();
        $user = new User();
        $user->loadByHash($data['hash']);
        if ($user->getId() && $data['password']) {
            $user->setPassword(md5($data['password']));
            $user->save();
        }
        return $this->redirectToRoute('lance_authorization');
    }

    /**
     * Open 'forget password' view.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function forgetAction(Request $request)
    {
        if (!$request->getSession()->has('userId')) {
            return $this->render('LancerLanceBundle:Default:forgetpassword.html.twig', array(
                'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..'),
                'message' => $this->getMessage($request->getSession())));
        }
        return $this->redirectToRoute('home');
    }

    /**
     * Logout current user.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function logoutAction(Request $request)
    {
        $request->getSession()->clear();
        return $this->redirectToRoute('lance_authorization');
    }
}