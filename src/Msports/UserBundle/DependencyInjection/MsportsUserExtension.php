<?php

namespace Msports\UserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class MsportsUserExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $this->bindParameters($container, 'msport_user', $config);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    /**
     * Binds the params from config.
     *
     * @param ContainerBuilder $container Containerbuilder
     * @param string           $name      Alias name
     * @param array            $config    Configuration Array
     */
    public function bindParameters(ContainerBuilder $container, $name, $config)
    {
        $container->setParameter('msport_user.firewall_templates', $config['firewall_templates']);
        $container->setParameter('msport_user.default_login_background_image', $config['default_login_background_image']);
        $container->setParameter('msport_user.default_login_background_color', $config['default_login_background_color']);
    }
}
