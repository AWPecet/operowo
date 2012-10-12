<?php

namespace Operowo\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ListInstitutionsController extends Controller
{
    /**
     * @Route("/instytucje", name="operowo_institutions_list")
     * @Route("/", name="homepage")
     * @Template("OperowoMainBundle:Institutions/List:list.html.twig")
     */
    public function getAction()
    {
        return array();
    }
}
