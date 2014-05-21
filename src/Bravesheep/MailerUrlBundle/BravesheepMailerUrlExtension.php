<?php

namespace Bravesheep\MailerUrlBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BravesheepMailerUrlExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $resolver = new MailerUrlResolver();
        foreach ($config['urls'] as $name => $url) {
            $target = $url['url'];
            $prefix = $url['prefix'];

            $params = $resolver->resolve($target);
            $container->setParameter("{$prefix}transport", $params['transport']);
            $container->setParameter("{$prefix}host", $params['host']);
            $container->setParameter("{$prefix}user", $params['user']);
            $container->setParameter("{$prefix}password", $params['password']);
            $container->setParameter("{$prefix}port", $params['port']);
            $container->setParameter("{$prefix}encryption", $params['encryption']);
            $container->setParameter("{$prefix}auth_mode", $params['auth_mode']);
        }
    }
}
