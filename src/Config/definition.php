<?php

declare(strict_types=1);
/**
 * File: \src\Config\definition.php
 * Author: Peter Nagy <peter@antronin.consulting>
 * -----
 */

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;

return static function (DefinitionConfigurator $definition): void {
    $definition->rootNode()
        ->children()
        ->arrayNode('length')
        ->canBeEnabled()
        ->children()
        ->integerNode('min')->min(0)->defaultValue(4)->end()
        ->integerNode('max')->min(0)->defaultValue(100)->end()
        ->end()
        ->end()
        ->arrayNode('contents', 'content')
        ->canBeEnabled()
        ->children()
        ->arrayNode('uppercase')
        ->canBeEnabled()
        ->children()
        ->integerNode('min')->defaultValue(1)->min(0)->end()
        ->stringNode('pattern')->defaultValue('[A-Z]')->cannotBeEmpty()->end()
        ->end()
        ->end()
        ->arrayNode('lowercase')
        ->canBeEnabled()
        ->children()
        ->integerNode('min')->defaultValue(1)->min(0)->end()
        ->stringNode('pattern')->defaultValue('[a-z]')->cannotBeEmpty()->end()
        ->end()
        ->end()
        ->arrayNode('number')
        ->canBeEnabled()
        ->children()
        ->integerNode('min')->defaultValue(1)->min(0)->end()
        ->stringNode('pattern')->defaultValue('[0-9]')->cannotBeEmpty()->end()
        ->end()
        ->end()
        ->arrayNode('special')
        ->canBeEnabled()
        ->children()
        ->integerNode('min')->defaultValue(1)->min(0)->end()
        ->stringNode('pattern')->defaultValue('.$_-@&#<>=%!+*/')->cannotBeEmpty()->end()
        ->end()
        ->end()
        ->end()
        ->end()
        ->end();
};
