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
        $container->import('../config/services.yaml');
        $validatorServiceId = 'antronin_psw_composition.validator.constraints.psw_composition';

        if ($builder->hasDefinition($validatorServiceId)) {
            $definition = $builder->getDefinition($validatorServiceId);

            $definition->setArgument('$lengthEnabled', $config['length']['enabled']);
            $definition->setArgument('$minLength', $config['length']['min']);
            $definition->setArgument('$maxLength', $config['length']['max']);
            $definition->setArgument('$minUppercase', $config['contents']['uppercase']['min']);
            $definition->setArgument('$uppercaseEnabled', $config['contents']['uppercase']['enabled']);
            $definition->setArgument('$minLowercase', $config['contents']['lowercase']['min']);
            $definition->setArgument('$lowercaseEnabled', $config['contents']['lowercase']['enabled']);
            $definition->setArgument('$minNumber', $config['contents']['number']['min']);
            $definition->setArgument('$numberEnabled', $config['contents']['number']['enabled']);
            $definition->setArgument('$minSpecial', $config['contents']['special']['min']);
            $definition->setArgument('$specialEnabled', $config['contents']['special']['enabled']);
        }
    }

    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import('Config/definition.php');
    }
}
