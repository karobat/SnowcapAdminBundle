<?php

namespace Snowcap\AdminBundle\Datalist;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormFactory;

use Snowcap\AdminBundle\Datalist\Type\DatalistTypeInterface;
use Snowcap\AdminBundle\Datalist\Field\DatalistField;
use Snowcap\AdminBundle\Datalist\Field\DatalistFieldConfig;
use Snowcap\AdminBundle\Datalist\Filter\DatalistFilter;
use Snowcap\AdminBundle\Datalist\Filter\DatalistFilterConfig;

class DatalistBuilder extends DatalistConfig
{
    /**
     * @var array
     */
    private $fields = array();

    /**
     * @var array
     */
    private $filters = array();

    /**
     * @var DatalistFactory
     */
    private $factory;

    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @param string $name
     * @param DatalistFactory $factory
     * @param array $options
     */
    public function __construct($name, DatalistTypeInterface $type, array $options, DatalistFactory $factory, FormFactory $formFactory)
    {
        parent::__construct($name, $type, $options);

        $this->factory = $factory;
        $this->formFactory = $formFactory;
    }

    /**
     * @param string $field
     * @param string $type
     * @param array $options
     * @return DatalistBuilder
     */
    public function addField($field, $type = null, array $options = array())
    {
        $this->fields[$field] = array(
            'type' => $type,
            'options' => $options
        );

        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param string $filter
     * @param string $type
     * @param array $options
     * @return DatalistBuilder
     */
    public function addFilter($filter, $type = null, array $options = array())
    {
        $this->filters[$filter] = array(
            'type' => $type,
            'options' => $options
        );

        return $this;
    }

    /**
     * @return DatalistInterface
     */
    public function getDatalist()
    {
        $datalist = new Datalist($this->getDatalistConfig());

        // Add fields
        foreach ($this->fields as $fieldName => $fieldConfig) {
            $field = $this->createField($fieldName, $fieldConfig);
            $field->setDatalist($datalist);
            $datalist->addField($field);
        }

        // Add filters and filter form
        $filterFormBuilder = $this->formFactory->createNamedBuilder('');
        foreach ($this->filters as $filterName => $filterConfig) {
            $filter = $this->createFilter($filterName, $filterConfig);
            $filter->setDatalist($datalist);
            $filter->getType()->buildForm($filterFormBuilder, $filter, $filter->getOptions());
            $datalist->addFilter($filter);
        }
        $datalist->setFilterForm($filterFormBuilder->getForm());

        return $datalist;
    }

    /**
     * @param string $fieldName
     * @param array $fieldConfig
     * @return \Snowcap\AdminBundle\Datalist\Field\DatalistFieldInterface
     */
    private function createField($fieldName, array $fieldConfig)
    {
        $type = $this->factory->getFieldType($fieldConfig['type'] ? : 'text');

        // Handle field options
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array('label' => ucfirst($fieldName)));
        $type->setDefaultOptions($resolver);
        $resolvedOptions = $resolver->resolve($fieldConfig['options']);

        $config = new DatalistFieldConfig($fieldName, $type, $resolvedOptions);

        return new DatalistField($config);
    }

    /**
     * @param string $filterName
     * @param array $filterConfig
     * @return Filter\DatalistFilter
     */
    private function createFilter($filterName, array $filterConfig)
    {
        $type = $this->factory->getFilterType($filterConfig['type']);

        // Handle filter options
        $resolver = new OptionsResolver();
        $resolver->setDefaults(array('label' => ucfirst($filterName)));
        $type->setDefaultOptions($resolver);
        $resolvedOptions = $resolver->resolve($filterConfig['options']);

        $config = new DatalistFilterConfig($filterName, $type, $resolvedOptions);

        return new DatalistFilter($config);
    }

    /**
     * @return DatalistBuilder
     */
    private function getDatalistConfig()
    {
        $config = clone $this;

        return $config;
    }
}