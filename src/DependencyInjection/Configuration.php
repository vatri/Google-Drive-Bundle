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
            ->scalarNode("redirect_after_login_url")
                ->defaultValue("/")
            ->end()
            ->scalarNode('cookie_access_token_key')
                ->defaultValue('vatri_google_drive_access_token')
            ->end()
            ->scalarNode('session.key.redirect_path_after_auth')
                ->defaultValue('vatri_google_drive.session_redirect_path_after_auth')
            ->end()
        ->end();
// dump($treeBuilder);
        return $treeBuilder;
    }
}