<?php

namespace Vatri\GoogleDriveBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('vatri_google_drive');

        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode("credentials_file")
                ->defaultValue('%kernel.project_dir%/config/google-drive-api-client_secrets.json')
            ->end()
        ->end();
// dump($treeBuilder);
        return $treeBuilder;
    }
}