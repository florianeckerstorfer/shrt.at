<?php
/**
 * This file is part of shrt.at
 * (c) 2013 Florian Eckerstorfer
 */

namespace Fe\ShrtFileBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * FeShrtFileExtension
 *
 * @package    FeShrtFileBundle
 * @subpackage DependencyInjection
 * @author     Florian Eckerstorfer <florian@eckerstorfer.co>
 * @copyright  2013 Florian Eckerstorfer
 * @license    http://opensource.org/licenses/MIT The MIT License
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
