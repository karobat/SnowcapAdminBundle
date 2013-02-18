<?php

namespace Snowcap\AdminBundle\Datalist\Action\Type;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Snowcap\AdminBundle\Routing\Helper\ContentRoutingHelper;

use Snowcap\AdminBundle\Datalist\Action\DatalistActionInterface;

class ContentAdminActionType extends AbstractActionType {
    /**
     * @var \Snowcap\AdminBundle\Routing\Helper\ContentRoutingHelper
     */
    private $routingHelper;

    /**
     * @param \Symfony\Component\Routing\RouterInterface $router
     */
    public function __construct(ContentRoutingHelper $routingHelper)
    {
        $this->routingHelper = $routingHelper;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver
            ->setDefaults(array('params' => array('id' => 'id')))
            ->setRequired(array('admin', 'action'))
            ->setAllowedTypes(array(
                'params' => 'array',
                'admin' => 'Snowcap\AdminBundle\Admin\ContentAdmin',
                'action' => 'string'
            ));
    }

    public function getUrl(DatalistActionInterface $action, $item, array $options = array())
    {
        $parameters = array();
        $accessor = PropertyAccess::getPropertyAccessor();
        foreach($options['params'] as $paramName => $paramPath) {
            $paramValue = $accessor->getValue($item, $paramPath);
            $parameters[$paramName] = $paramValue;
        }

        return $this->routingHelper->generateUrl($options['admin'], $options['action'], $parameters);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'content_admin';
    }

    /**
     * @return string
     */
    public function getBlockName()
    {
        return 'simple';
    }
}