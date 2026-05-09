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
        ->arrayNode(name: 'length')->canBeEnabled()->info('Password length settings')->children()
        ->integerNode(name: 'min')->info('Minimum password length')->min(min: 0)->defaultValue(value: 4)->end()
        ->integerNode(name: 'max')->info('Maximum password length')->min(min: 0)->defaultValue(value: 100)->end()
        ->end()
        ->end()
        ->arrayNode(name: 'contents')->canBeEnabled()->info('What the password should contain')->children()
        ->arrayNode(name: 'uppercase')->canBeEnabled()->children()
        ->integerNode(name: 'min')->info('Minimum count of uppercase letters')->defaultValue(value: 1)->min(min: 0)->end()
        ->stringNode(name: 'pattern')->info('Characters (as regular expression) which are considered uppercase characters')->defaultValue(value: '[A-Z]')->cannotBeEmpty()->end()
        ->end()
        ->end()
        ->arrayNode(name: 'lowercase')->canBeEnabled()->children()
        ->integerNode(name: 'min')->info('Minimum count of lowercase letters')->defaultValue(value: 1)->min(min: 0)->end()
        ->stringNode(name: 'pattern')->info('Characters (as regular expression) which are considered lowercase characters')->defaultValue(value: '[a-z]')->cannotBeEmpty()->end()
        ->end()
        ->end()
        ->arrayNode(name: 'number')->canBeEnabled()->children()
        ->integerNode(name: 'min')->info('Minimum count of number characters')->defaultValue(value: 1)->min(min: 0)->end()
        ->stringNode(name: 'pattern')->info('Characters (as regular expression) which are considered numbers')->defaultValue(value: '[0-9]')->cannotBeEmpty()->end()
        ->end()
        ->end()
        ->arrayNode(name: 'special')->canBeEnabled()->children()
        ->integerNode(name: 'min')->info('Minimum count of special characters')->defaultValue(value: 1)->min(min: 0)->end()
        ->stringNode(name: 'pattern')->info('Special characters allowed in the password')->defaultValue(value: '.$_-@&#<>=%!+*/')->cannotBeEmpty()->end()
        ->end()
        ->end()
        ->end()
        ->end()
        ->end();
};
