<?php

namespace Vatri\GoogleDriveBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
/**
 * Bundle extension for Vatri\GoogleDriveBundle
 **/
class VatriGoogleDriveExtension extends Extension
{
	
	public function load(array $configs, ContainerBuilder $container)
    {
		// $configuration = new Configuration();

	 //    $config = $this->processConfiguration($configuration, $configs);

    	$loader = new YamlFileLoader(
	        $container,
	        new FileLocator(__DIR__.'/../Resources/config')
    	);

    	$loader->load('vatri_google_drive.yaml');

    }
}