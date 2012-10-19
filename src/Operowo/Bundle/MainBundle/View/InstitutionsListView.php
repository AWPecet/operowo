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
        $institutions = array();
        foreach ($this->getOption('institutions')->getItems() as $institution) {
            $institutions[] = array(
                'name' => $institution->getName(),
                'province' => $institution->getProvince()->getName()
            );
        }

        $choices = array();
        foreach ($this->getOption('distribution_in_provinces') as $provinceWithCount) {
            $choices[$provinceWithCount['province']->getId()] = $provinceWithCount['province'];
        }

        $this->provinceFilterView->bind(array(
            'name' => 'provinces',
            'choices' => $choices,
            'chosen' => $this->getOption('criteria')->getFilterOnProvinceId(),
            'label' => 'provinces',
            'choice_route_name' => 'operowo_institutions_list',
            'query_parameters' => $this->getOption('criteria')->toArray()
        ));

        $this->paginationView->bind(array(
            'paginated_model' => $this->getOption('institutions'),
            'route' => 'operowo_institutions_list'
        ));


        $variables = array();
        $variables['institutions'] = $institutions;
        $variables['pagination_view'] = $this->paginationView;
        $variables['filters_view'] = $this->provinceFilterView;

        return $variables;
    }

    protected function buildOptionsResolver()
    {
        $resolver = parent::buildOptionsResolver();

        $resolver->setRequired(array(
            'criteria',
            'institutions',
            'distribution_in_provinces'
        ));
        $resolver->setAllowedTypes(array(
            'criteria' => 'Operowo\Bundle\MainBundle\Entity\InstitutionsCriteria',
            'institutions' => 'Operowo\Bundle\MainBundle\Model\PaginatedResult'
        ));

        return $resolver;
    }
}