<?php

/**
 * File: src\AntroninPswCompositionBundle.php
 * Author: Peter Nagy <peter@antronin.consulting>
 * -----
 */
declare(strict_types=1);

namespace Antronin\PswCompositionBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

class AntroninPswCompositionBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('../config/psw_composition.yaml');
    }

    /**
     * @param array<string, mixed> $config
     * @param ContainerConfigurator $container
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->services()
          ->get('antronin.psw_composition')
          ->arg(0, $config['twitter']['client_id'])
          ->arg(1, $config['twitter']['client_secret'])
        ;
    }
}
