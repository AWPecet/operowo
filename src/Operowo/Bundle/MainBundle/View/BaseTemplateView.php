<?php

namespace Operowo\Bundle\MainBundle\View;

use Operowo\Bundle\MainBundle\Entity\InstitutionsCriteria;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Templating\EngineInterface;

class BaseTemplateView extends BaseView
{
    /**
     * @var \Symfony\Component\Templating\EngineInterface
     */
    private $templating;

    private $templateName;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function buildTemplateVariables()
    {
        return array();
    }

    public function toString()
    {
        return $this->templating->render($this->getTemplateName(), $this->buildTemplateVariables());
    }

    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
    }

    public function getTemplateName()
    {
        return $this->templateName;
    }

    protected function getTemplating()
    {
        return $this->templating;
    }
}