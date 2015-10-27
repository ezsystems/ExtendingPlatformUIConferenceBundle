<?php

namespace EzSystems\ExtendingPlatformUIConferenceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ConfigurationProcessor;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Symfony\Component\Config\Resource\FileResource;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class EzSystemsExtendingPlatformUIConferenceExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        // EzSystemsExtendingPlatformUIConferenceBundle will process a siteaccess aware configuration
        $processor = new ConfigurationProcessor( $container, 'ez_systems_extending_platform_ui_conference' );
    }

    public function prepend( ContainerBuilder $container )
    {
        // make sure Assetic can handle the assets (mainly the CSS files)
        $container->prependExtensionConfig( 'assetic', array( 'bundles' => array( 'EzSystemsExtendingPlatformUIConferenceBundle' ) ) );

        // prepend the yui.yml and the css.yml from EzSystemsExtendingPlatformUIConferenceBundle
        // of course depending on your needs you can remove the handling of yui.yml or css.yml
        $this->prependYui( $container );
        $this->prependCss( $container );
    }

    private function prependYui( ContainerBuilder $container )
    {
        $container->setParameter(
            'extending_platformui.public_dir',
            'bundles/ezsystemsextendingplatformuiconference'
        );
        $yuiConfigFile = __DIR__ . '/../Resources/config/yui.yml';
        $config = Yaml::parse( file_get_contents( $yuiConfigFile ) );
        $container->prependExtensionConfig( 'ez_platformui', $config );
        $container->addResource( new FileResource( $yuiConfigFile ) );
    }

    private function prependCss( ContainerBuilder $container )
    {
        $container->setParameter(
            'extending_platformui.css_dir',
            'bundles/ezsystemsextendingplatformuiconference/css'
        );
        $cssConfigFile = __DIR__ . '/../Resources/config/css.yml';
        $config = Yaml::parse( file_get_contents( $cssConfigFile ) );
        $container->prependExtensionConfig( 'ez_platformui', $config );
        $container->addResource( new FileResource( $cssConfigFile ) );
    }
}
