<?php

namespace Geekhub\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('geekhub_user');
        $rootNode
            ->children()
                ->arrayNode('image')
                    ->children()
                         ->scalarNode('upload_directory')
                            ->defaultValue('uploads/')
                            ->info('directory in web/ where to place images (avatars etc...)')
                            ->example('uploads/images/')
                         ->end()
                            ->scalarNode('default_avatar_path')
                            ->defaultValue('/../src/Geekhub/UserBundle/Resources/public/images/default_avatar.png')
                            ->info('path to the default avatar image (used when errors occurs while getting original image)')
                            ->example('/../src/Geekhub/UserBundle/Resources/public/images/default_avatar.png')
                         ->end()
                    ->end()
                ->end()
            ->end();
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        return $treeBuilder;
    }
}
