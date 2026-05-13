<?php

/**
 * File: src\PswCompositionBundle.php
 * Author: Peter Nagy <peter@antronin.consulting>
 * -----
 */

declare(strict_types=1);

namespace AntroninConsulting\PswCompositionBundle;

use Symfony\Component\HttpKernel\Bundle\AbstractBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

class PswCompositionBundle extends AbstractBundle
{

    /**
     * @param array<String, mixed> $config
     * @param ContainerConfigurator $container
     * @param ContainerBuilder $builder
     * @return void
     */
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import(resource: '../config/services.yaml');
        $builder->setParameter(name: 'psw_composition', value: $config);
        $validatorServiceId = 'antronin_consulting_psw_composition.validator.constraints.psw_composition';

        if ($builder->hasDefinition(id: $validatorServiceId)) {
            $definition = $builder->getDefinition(id: $validatorServiceId);

            $definition->setArgument(key: '$lengthEnabled',    value: $config['length']['enabled']);
            $definition->setArgument(key: '$minLength',        value: $config['length']['min']);
            $definition->setArgument(key: '$maxLength',        value: $config['length']['max']);
            $definition->setArgument(key: '$minUppercase',     value: $config['contents']['uppercase']['min']);
            $definition->setArgument(key: '$uppercaseEnabled', value: $config['contents']['uppercase']['enabled']);
            $definition->setArgument(key: '$uppercasePattern', value: $config['contents']['uppercase']['pattern']);
            $definition->setArgument(key: '$minLowercase',     value: $config['contents']['lowercase']['min']);
            $definition->setArgument(key: '$lowercaseEnabled', value: $config['contents']['lowercase']['enabled']);
            $definition->setArgument(key: '$lowercasePattern', value: $config['contents']['lowercase']['pattern']);
            $definition->setArgument(key: '$minNumber',        value: $config['contents']['number']['min']);
            $definition->setArgument(key: '$numberEnabled',    value: $config['contents']['number']['enabled']);
            $definition->setArgument(key: '$numberPattern',    value: $config['contents']['number']['pattern']);
            $definition->setArgument(key: '$minSpecial',       value: $config['contents']['special']['min']);
            $definition->setArgument(key: '$specialEnabled',   value: $config['contents']['special']['enabled']);
            $definition->setArgument(key: '$specialPattern',   value: $config['contents']['special']['pattern']);
        }
    }

    /**
     * @param DefinitionConfigurator $definition
     * @return void
     */
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->import(resource: 'Config/definition.php');
    }
}
