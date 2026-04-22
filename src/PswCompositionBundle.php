<?php

/**
 * File: src\PswCompositionBundle.php
 * Author: Peter Nagy <peter@antronin.consulting>
 * -----
 */
declare(strict_types=1);

namespace Antronin\PswCompositionBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

class PswCompositionBundle extends AbstractBundle
{
    /**
     * @param array<string, mixed> $config
     * @param ContainerConfigurator $container
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/packages/psw_composition.yaml');
        $container->get('admin_psw_composition')
            ->arg('minLength', $config['psw_composition']['length']['min'])
            ->arg('maxLength', $config['psw_composition']['length']['max'])
            ->arg('minUppercase', $config['psw_composition']['content']['uppercase']['min'])
            ->arg('minLowercase', $config['psw_composition']['content']['lowercase']['min'])
            ->arg('minNumber', $config['psw_composition']['content']['number']['min'])
            ->arg('minSpecial', $config['psw_composition']['content']['special']['min'])
            ->arg('uppercasePattern', $config['psw_composition']['content']['uppercase']['pattern'])
            ->arg('lowercasePattern', $config['psw_composition']['content']['lowercase']['pattern'])
            ->arg('numberPattern', $config['psw_composition']['content']['number']['pattern'])
            ->arg('specialsPattern', $config['psw_composition']['content']['special']['pattern'])
        ;
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('Config/definition.php');
    }
}
