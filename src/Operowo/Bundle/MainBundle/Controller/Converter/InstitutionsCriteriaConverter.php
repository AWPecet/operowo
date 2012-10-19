<?php

namespace Operowo\Bundle\MainBundle\Controller\Converter;

use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationInterface;
use JMS\DiExtraBundle\Annotation as DI;
use Operowo\Bundle\MainBundle\Entity\InstitutionsCriteria;

/**
 * @DI\Service(id="operowo.converter.institutions_criteria")
 * @DI\Tag("request.param_converter")
 */
class InstitutionsCriteriaConverter implements ParamConverterInterface
{
    function apply(Request $request, ConfigurationInterface $configuration)
    {
        $currentPage = $request->attributes->get('page', 1);
        $selectedProvinces = $request->query->get('provinces', array());

        $criteria = new InstitutionsCriteria($currentPage);
        $criteria->filterByProvinceId($selectedProvinces);

        $request->attributes->set($configuration->getName(), $criteria);

        return $criteria;
    }

    function supports(ConfigurationInterface $configuration)
    {
        return $configuration->getClass() == 'Operowo\Bundle\MainBundle\Entity\InstitutionsCriteria';
    }
}