<?php

namespace Operowo\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;

class ListInstitutionsController extends Controller
{
	/** @DI\Inject("operowo.institutions_repository") */
    private $institutionsRepository;

	/** @DI\Inject("knp_paginator") */
    private $paginator;

    /**
     * @Route("/instytucje", name="operowo_institutions_list")
     * @Route("/", name="homepage")
     * @Template("OperowoMainBundle:Institutions/List:list.html.twig")
     */
    public function getAction()
    {
    	$query = $this->institutionsRepository->getQuery();
    	$paginator = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
		    $query,
		    $this->get('request')->query->get('page', 1)/*page number*/,
		    10/*limit per page*/
		);

        return array(
        	'institutions' => $pagination->getItems(),
        	'pagination' => $pagination
        );
    }
}
