<?php

namespace Operowo\Bundle\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;
use Operowo\Bundle\MainBundle\Entity\InstitutionsCriteria;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ListInstitutionsController extends Controller
{
    /**
     * @DI\Inject("operowo.institutions_repository")
     * @var \Operowo\Bundle\MainBundle\Entity\InstitutionsRepository
     */
    private $institutionsRepository;

    /**
     * @DI\Inject("operowo.view.institutions_list")
     * @var Operowo\Bundle\MainBundle\View\InstitutionsListView
     */
    private $view;

    /**
     * @Route("/instytucje/{page}", name="operowo_institutions_list", requirements={"page" = "\d+"}, defaults={"page" = "1"})
     * @Route("/", name="homepage")
     * @Template("OperowoMainBundle:Institutions/List:list.html.twig")
     * @ParamConverter("criteria", class="Operowo\Bundle\MainBundle\Entity\InstitutionsCriteria")
     */
    public function getAction(InstitutionsCriteria $criteria)
    {
        $institutionsCount = 0;
    	$paginatedInstitutions = $this->institutionsRepository->findByCriteria($criteria, $institutionsCount);
        $distribution = $this->institutionsRepository->getDistributionInProvinces();

        $this->view->bind(array(
            'institutions' => $paginatedInstitutions,
            'criteria' => $criteria,
            'distribution_in_provinces' => $distribution
        ));

        return $this->view->toResponse();
    }
}
