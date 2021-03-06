<?php

namespace BlackSheep\MusicLibraryBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @see http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class BlackSheepMusicLibraryExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('services_repositories.yml');
        $loader->load('services_lastfm.yml');
        $parameters = [
            'ffprobe_path',
            'ffmpeg_path',
            'bitrate',
            'binary_timeout',
            'threads_count',
            'last_fm_api_key',
            'last_fm_api_secret'
        ];
        foreach ($parameters as $attribute) {
            $container->setParameter('black_sheep_music_library.' . $attribute, $config[$attribute]);
        }
    }
}
