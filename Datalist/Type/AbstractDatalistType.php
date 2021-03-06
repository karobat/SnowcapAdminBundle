<?php

namespace Snowcap\AdminBundle\Datalist\Type;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Snowcap\AdminBundle\Datalist\DatalistBuilder;
use Snowcap\AdminBundle\Datalist\ViewContext;
use Snowcap\AdminBundle\Datalist\DatalistInterface;

abstract class AbstractDatalistType implements DatalistTypeInterface {
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => null,
                'layout' => 'grid',
                'filter_on_top' => false, //TODO: should be removed if confirmed that not used
                'filters_on_top' => false,
                'limit_per_page' => null,
                'range_limit' => 10,
                'search_placeholder' => null,
                'search_submit' => 'datalist.search.submit',
                'filter_submit' => 'datalist.filter.submit',
                'filter_reset' => 'datalist.filter.reset',
                'translation_domain' => 'messages'
            ))
            ->setOptional(array(
                'search'
            ));
    }

    /**
     * @param \Snowcap\AdminBundle\Datalist\DatalistBuilder $builder
     * @param array $options
     */
    public function buildDatalist(DatalistBuilder $builder, array $options)
    {

    }

    /**
     * @param \Snowcap\AdminBundle\Datalist\ViewContext $viewContext
     * @param \Snowcap\AdminBundle\Datalist\DatalistInterface $datalist
     * @param array $options
     */
    public function buildViewContext(ViewContext $viewContext, DatalistInterface $datalist, array $options)
    {
        $viewContext['datalist'] = $datalist;
        $viewContext['options'] = $options;
        $viewContext['translation_domain'] = $options['translation_domain'];
    }

}