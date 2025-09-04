<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_vmmapicon
 *
 * @copyright   Copyright (C) 2025 Villaester Moderne Medien GmbH
 * @license     GNU General Public License version 2 or later
 * @author      VMM Development Team
 * @link        https://villaester.de
 * @version     1.0.0
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use Joomla\CMS\Extension\Service\Provider\FieldFactory;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Villaester\Component\Vmmapicon\Administrator\Extension\VmmapiconComponent;

/**
 * The service provider for the VMM API Connector component
 *
 * @since  1.0.0
 */
return new class implements ServiceProviderInterface
{
    /**
     * Registers the service provider with a DI container.
     *
     * @param   Container  $container  The DI container.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function register(Container $container)
    {
        $container->registerServiceProvider(new MVCFactory('\\Villaester\\Component\\Vmmapicon'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\Villaester\\Component\\Vmmapicon'));
        $container->registerServiceProvider(new RouterFactory('\\Villaester\\Component\\Vmmapicon'));
        $container->registerServiceProvider(new FieldFactory('\\Villaester\\Component\\Vmmapicon'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new VmmapiconComponent($container->get(ComponentDispatcherFactory::class));

                $component->setMVCFactory($container->get(MVCFactoryInterface::class));

                return $component;
            }
        );
    }
};
