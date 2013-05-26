<?php

namespace Fe\ShrtFileBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class FeShrtFileExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        if (!isset($config['amazon_s3']['aws_key'])) {
            throw new \InvalidArgumentException('The option "fe_shrt_file.amazon_s3.aws_key" must be set.');
        }

        if (!isset($config['amazon_s3']['aws_secret_key'])) {
            throw new \InvalidArgumentException('The option "fe_shrt_file.amazon_s3.aws_secret_key" must be set.');
        }

        if (!isset($config['amazon_s3']['base_url'])) {
            throw new \InvalidArgumentException('The option "fe_shrt_file.amazon_s3.base_url" must be set.');
        }

        $container->setParameter('fe_shrt_file.amazon_s3.aws_key', $config['amazon_s3']['aws_key']);
        $container->setParameter('fe_shrt_file.amazon_s3.aws_secret_key', $config['amazon_s3']['aws_secret_key']);
        $container->setParameter('fe_shrt_file.amazon_s3.base_url', $config['amazon_s3']['base_url']);
    }
}
