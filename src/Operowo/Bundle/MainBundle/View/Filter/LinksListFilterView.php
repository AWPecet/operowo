<?php

namespace Operowo\Bundle\MainBundle\View\Filter;

use Operowo\Bundle\MainBundle\View\BaseTemplateView;
use Symfony\Component\Templating\EngineInterface;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * @DI\Service(id="operowo.view.links_list", scope="prototype")
 */
class LinksListFilterView extends BaseTemplateView
{
    private $choices;
    private $filterLabel;
    private $route;
    private $genericQueryParameters = array();
    private $selectedChoicesId = array();

    /**
     * @DI\InjectParams({
     *      "templating" = @DI\Inject("templating")
     * })
     */
    public function __construct(EngineInterface $templating)
    {
        parent::__construct($templating);
        $this->setTemplateName('OperowoMainBundle:Filters:links_list.html.twig');
    }

    public function buildTemplateVariables()
    {
        $variables = parent::buildTemplateVariables();

        $choices = array();
        foreach ($this->getChoices() as $key => $choice) {
            $choiceId = $choice->getId();
            $isSelected = in_array($choiceId, $this->getSelectedChoicesId());

            $queryParameters = $this->getGenericQueryParameters();
            if (!$isSelected) {
                $queryParameters = array_merge_recursive($queryParameters, array('provinces' => array($choiceId)));
            } else if(isset($queryParameters['provinces'])) {
                $foundKey = array_search($choiceId, $queryParameters['provinces']);
                if($foundKey !== false) {
                    unset($queryParameters['provinces'][$foundKey]);
                }
            }

            $choices[$key] = array(
                'subject' => $choice,
                'route' => $this->getRoute(),
                'selected' => $isSelected,
                'query_parameters' => $queryParameters
            );
        }

        $variables['choices'] = $choices;
        $variables['label'] = $this->getFilterLabel();

        return $variables;
    }

    public function setChoices($choices)
    {
        $this->choices = $choices;
        return $this;
    }

    public function getChoices()
    {
        return $this->choices;
    }

    public function setFilterLabel($filterLabel)
    {
        $this->filterLabel = $filterLabel;
        return $this;
    }

    public function getFilterLabel()
    {
        return $this->filterLabel;
    }

    public function setGenericQueryParameters($genericQueryParameters)
    {
        $this->genericQueryParameters = $genericQueryParameters;
        return $this;
    }

    public function getGenericQueryParameters()
    {
        return $this->genericQueryParameters;
    }

    public function setRoute($route)
    {
        $this->route = $route;
        return $this;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setSelectedChoicesId($selectedChoicesId)
    {
        $this->selectedChoicesId = $selectedChoicesId;
        return $this;
    }

    public function getSelectedChoicesId()
    {
        return $this->selectedChoicesId;
    }
}