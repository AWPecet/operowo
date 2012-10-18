<?php

namespace Operowo\Bundle\MainBundle\View;

use Assert\Assertion;
use Operowo\Bundle\MainBundle\Entity\InstitutionsCriteria;
use Symfony\Component\HttpFoundation\Response;
use JMS\DiExtraBundle\Annotation as DI;
use Operowo\Bundle\MainBundle\View\Filter\LinksListFilterView;

/**
 * @DI\Service(id="operowo.view.institutions_list", scope="prototype")
 */
class InstitutionsListView extends BaseTemplateView
{
    private $institutions;
    private $criteria;
    private $distribution;
    private $institutionsCount;

    /**
     * @var PaginationView
     */
    private $paginationView;

    /**
     * @var LinksListFilterView
     */
    private $provinceFilterView;

    /**
     * @DI\InjectParams({
     *      "templating" = @DI\Inject("templating"),
     *      "pagination" = @DI\Inject("operowo.view.pagination"),
     *      "provinceFilterView" = @DI\Inject("operowo.view.links_list")
     * })
     *
     * @param $templating
     */
    public function __construct($templating, PaginationView $pagination, LinksListFilterView $provinceFilterView)
    {
        parent::__construct($templating);
        $this->paginationView = $pagination;
        $this->provinceFilterView = $provinceFilterView;

        $this->setTemplateName('OperowoMainBundle:Institutions/List:list.html.twig');
    }

    public function buildTemplateVariables()
    {
        Assertion::notNull($this->getInstitutions(), 'Institutions are not set');
        Assertion::notNull($this->getCriteria(), 'Criteria is not set');
        Assertion::notNull($this->getInstitutionsCount(), 'Institutions count is not set');

        $variables = array();

        $institutions = array();
        foreach ($this->getInstitutions() as $institution) {
            $institutions[] = array(
                'name' => $institution->getName(),
                'province' => $institution->getProvince()->getName()
            );
        }

        $choices = array();
        foreach ($this->getDistribution() as $provinceWithCount) {
            $choices[] = $provinceWithCount['province'];
        }

        $this->provinceFilterView
            ->setChoices($choices)
            ->setFilterLabel('provinces')
            ->setRoute('operowo_institutions_list')
            ->setGenericQueryParameters($this->getCriteria()->toArray())
            ->setSelectedChoicesId($this->getCriteria()->getFilterOnProvinceId())
        ;

        $this->paginationView->bind(array(
            'criteria' => $this->getCriteria()/*$this->getOption('criteria')*/,
            'target' => $institutions,
            'total_count' => $this->getInstitutionsCount(),
            'route' => 'operowo_institutions_list'
        ));

        $variables['institutions'] = $institutions;
        $variables['pagination_view'] = $this->paginationView;
        $variables['filters_view'] = $this->provinceFilterView;

        return $variables;
    }

    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
        return $this;
    }

    public function getCriteria()
    {
        return $this->criteria;
    }

    public function setDistribution($distribution)
    {
        $this->distribution = $distribution;
        return $this;
    }

    public function getDistribution()
    {
        return $this->distribution;
    }

    public function setInstitutions($institutions)
    {
        $this->institutions = $institutions;
        return $this;
    }

    public function getInstitutions()
    {
        return $this->institutions;
    }

    public function setInstitutionsCount($institutionsCount)
    {
        $this->institutionsCount = $institutionsCount;
        return $this;
    }

    public function getInstitutionsCount()
    {
        return $this->institutionsCount;
    }
}