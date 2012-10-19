<?php

namespace Operowo\Bundle\MainBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Mopa\Bundle\BootstrapBundle\Navbar\AbstractNavbarMenuBuilder;
use Symfony\Component\Translation\TranslatorInterface;

class NavbarMenuBuilder extends AbstractNavbarMenuBuilder
{
    protected $securityContext;
    protected $isLoggedIn;
    protected $translator;

    public function __construct(FactoryInterface $factory, SecurityContextInterface $securityContext, TranslatorInterface $translator)
    {
        parent::__construct($factory);

        $this->securityContext = $securityContext;
        $this->translator = $translator;
        //$this->isLoggedIn = $this->securityContext->isGranted('IS_AUTHENTICATED_FULLY');
    }

    public function createMainMenu(Request $request)
    {
        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav');

        $menu->addChild($this->translator->trans('institutions'), array('route' => 'operowo_institutions_list'));

        return $menu;
    }
}