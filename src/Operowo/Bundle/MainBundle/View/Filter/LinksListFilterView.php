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

        $filterName = $this->getOption('name');
        $choices = array();
        foreach ($this->getOption('choices') as $key => $choice) {
            $choiceId = $choice;
            if (!is_scalar($choice)) {
                $choiceId = $key;
            }
            $isSelected = in_array($choiceId, $this->getOption('chosen'));

            $queryParameters = $this->getOption('query_parameters');
            if (!$isSelected) {
                $queryParameters = array_merge_recursive($queryParameters, array($filterName => array($choiceId)));
            } else if(isset($queryParameters[$filterName])) {
                $foundKey = array_search($choiceId, $queryParameters[$filterName]);
                if($foundKey !== false) {
                    unset($queryParameters[$filterName][$foundKey]);
                }
            }

            $choices[] = array(
                'subject' => $choice,
                'choice_route_name' => $this->getOption('choice_route_name'),
                'selected' => $isSelected,
                'query_parameters' => $queryParameters
            );
        }

        $variables['choices'] = $choices;
        $variables['name'] = $this->getOption('name');
        $variables['label'] = $this->getOption('label') ? $this->getOption('label') : ucfirst($this->getOption('name'));

        return $variables;
    }

    protected function buildOptionsResolver()
    {
        $resolver = parent::buildOptionsResolver();

        $resolver->setRequired(array(
            'name',
            'choices',
            'chosen',
            'query_parameters',
            'choice_route_name'
        ));
        $resolver->setOptional(array(
            'label'
        ));

        return $resolver;
    }
}