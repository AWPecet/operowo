<?php

namespace Operowo\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use FOS\UserBundle\Controller\SecurityController as BaseSecurityController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends BaseSecurityController
{
    /**
     * @Template()
     * @Cache(maxage="3600", smaxage="3600", public="true")
     */
    public function navbarFormAction()
    {
        $request = $this->container->get('request');
        $session = $request->getSession();
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);
        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        return array(
            'last_username' => $lastUsername,
            'csrf_token' => $csrfToken
        );
    }

    public function loginAction()
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new RedirectResponse($this->container->get('router')->generate('homepage'), 302);
        }

        return parent::loginAction();
    }
}