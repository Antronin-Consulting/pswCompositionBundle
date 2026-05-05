<?php

declare(strict_types=1);
/**
 * File: \src\Config\definition.php
 * Author: Peter Nagy <peter@antronin.consulting>
 * -----
 */

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return static function (DefinitionConfigurator $definition): void {
    $definition->rootNode()->children()
        ->arrayNode(name: 'length')->canBeEnabled()->children()
        ->integerNode(name: 'min')->min(min: 0)->defaultValue(value: 4)->end()
        ->integerNode(name: 'max')->min(min: 0)->defaultValue(value: 100)->end()
        ->end()
        ->end()
        ->arrayNode(name: 'contents')->canBeEnabled()->children()
        ->arrayNode(name: 'uppercase')->canBeEnabled()->children()
        ->integerNode(name: 'min')->defaultValue(value: 1)->min(min: 0)->end()
        ->stringNode(name: 'pattern')->defaultValue(value: '[A-Z]')->cannotBeEmpty()->end()
        ->end()
        ->end()
        ->arrayNode(name: 'lowercase')->canBeEnabled()->children()
        ->integerNode(name: 'min')->defaultValue(value: 1)->min(min: 0)->end()
        ->stringNode(name: 'pattern')->defaultValue(value: '[a-z]')->cannotBeEmpty()->end()
        ->end()
        ->end()
        ->arrayNode(name: 'number')->canBeEnabled()->children()
        ->integerNode(name: 'min')->defaultValue(value: 1)->min(min: 0)->end()
        ->stringNode(name: 'pattern')->defaultValue(value: '[0-9]')->cannotBeEmpty()->end()
        ->end()
        ->end()
        ->arrayNode(name: 'special')->canBeEnabled()->children()
        ->integerNode(name: 'min')->defaultValue(value: 1)->min(min: 0)->end()
        ->stringNode(name: 'pattern')->defaultValue(value: '.$_-@&#<>=%!+*/')->cannotBeEmpty()->end()
        ->end()
        ->end()
        ->end()
        ->end()
        ->end();
};
